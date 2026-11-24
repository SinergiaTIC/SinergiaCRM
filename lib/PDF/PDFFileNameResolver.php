<?php
/**
 * This file is part of SinergiaCRM.
 * SinergiaCRM is a work developed by SinergiaTIC Association, based on SuiteCRM.
 * Copyright (C) 2013 - 2023 SinergiaTIC Association
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SinergiaTIC Association at email address info@sinergiacrm.org.
 */

namespace SuiteCRM\PDF;

use BeanFactory;
use LoggerManager;

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

/**
 * Class PDFFileNameResolver
 *
 * Builds the download name of a PDF generated from an AOS_PDF_Templates record out of the
 * pattern configured in the template `stic_file_name_c` field.
 *
 * The pattern accepts `{{placeholder}}` tokens which are interpolated with the data of the
 * record the document is being generated for:
 *
 *   Contrato_{{referencia}}_{{apellidos}}  ->  Contrato_REF12345_Perez.pdf
 *
 * Placeholders:
 *   - `{{field}}`                 any field of the record the document is generated for.
 *   - `{{relate_field.field}}`    any field of a record related to it, following `relate`
 *                                 fields and one-to-one `link` fields such as
 *                                 `assigned_user_link`, which point to the Users module.
 *                                 Nesting is allowed up to self::MAX_RELATION_DEPTH.
 *   - `{{relate_field}}`          the display name of a related record, taken from the `rname`
 *                                 of the field (see below).
 *   - `{{record_id}}`             id of the record the document is generated for.
 *   - `{{template_name}}`         name of the PDF template.
 *   - `{{now_date}}`              generation date, `Y-m-d`.
 *   - `{{now_datetime}}`          generation timestamp, `YmdHis`.
 *
 * The last four placeholders are only used when the record has no field with that very same
 * name, so a module field always wins over the reserved name. The same happens with the
 * aliases of self::FIELD_ALIASES: a field of the record named `nombre` is used as it is, and
 * the alias is only applied when the record has no field with that name.
 *
 * A placeholder that does not exist in the record is handled as an empty value, so the name is
 * still built with the rest of the variables and no consecutive separators are left behind.
 *
 * The resulting name is sanitized: characters rejected by the common file systems are
 * replaced, blank runs are collapsed into a single underscore (so empty values never leave
 * `Name__Surname` behind), the result is trimmed and truncated to fit the file system limits.
 *
 * An empty string is returned whenever the pattern cannot produce a usable name, either
 * because there is no pattern, because the pattern is left empty once interpolated or because
 * it holds a malformed placeholder, such as `{{a field with spaces}}`. Callers are expected to
 * fall back to their previous naming in that case, which keeps a misconfigured template from
 * producing badly named or colliding documents.
 *
 * @package SuiteCRM\PDF
 */
class PDFFileNameResolver
{
    /**
     * Matches `{{placeholder}}` tokens, allowing the dotted notation used for related fields.
     */
    public const PLACEHOLDER_REGEX = '/\{\{\s*([A-Za-z0-9_]+(?:\.[A-Za-z0-9_]+)*)\s*\}\}/';

    /**
     * Maximum length in bytes of the generated base name. The rest of the 255 bytes commonly
     * accepted by the file systems is reserved for the extension.
     */
    public const MAX_BASE_NAME_BYTES = 200;

    /**
     * Maximum number of `relate` hops followed when resolving a placeholder.
     */
    private const MAX_RELATION_DEPTH = 3;

    /**
     * Placeholders that do not exist as such in the records but that are resolved with a well
     * known field of the module, so the names of the feature (nombre, apellidos, nif) work with
     * the standard modules of the CRM.
     *
     * A field of the record with the very same name always wins over the alias.
     */
    public const FIELD_ALIASES = [
        'nombre' => 'first_name',
        'apellidos' => 'last_name',
        'nif' => 'stic_identification_number_c',
    ];

    /**
     * Base names that cannot be used on Windows, whatever the extension is.
     */
    private const WINDOWS_RESERVED_NAMES = [
        'CON', 'PRN', 'AUX', 'NUL',
        'COM1', 'COM2', 'COM3', 'COM4', 'COM5', 'COM6', 'COM7', 'COM8', 'COM9',
        'LPT1', 'LPT2', 'LPT3', 'LPT4', 'LPT5', 'LPT6', 'LPT7', 'LPT8', 'LPT9',
    ];

    /**
     * Field types whose value is not usable as plain text on a file name, either because it is
     * markup, a URL or binary content.
     */
    private const NON_TEXTUAL_FIELD_TYPES = ['image', 'Signature', 'file'];

    /**
     * Resolves the download name of a document generated from a PDF template.
     *
     * @param string|null $pattern value of the template `stic_file_name_c` field.
     * @param object|null $contextBean record the document is being generated for.
     * @param object|null $templateBean PDF template holding the pattern.
     * @param string $extension file extension, without the leading dot.
     * @return string the file name, or an empty string when the pattern cannot be used.
     */
    public static function resolve(
        $pattern,
        $contextBean = null,
        $templateBean = null,
        string $extension = 'pdf'
    ): string
    {
        if (!is_string($pattern) || trim($pattern) === '') {
            return '';
        }

        $unresolved = [];

        // The malformed placeholders of the pattern, such as `{{not a field}}`, are
        // detected before interpolating it, so a value of the record holding braces does not
        // discard a perfectly valid pattern
        $withoutPlaceholders = preg_replace(self::PLACEHOLDER_REGEX, '', $pattern) ?? $pattern;
        if (str_contains($withoutPlaceholders, '{{') || str_contains($withoutPlaceholders, '}}')) {
            self::log('discarded the file name pattern because it holds a malformed placeholder', $pattern, $contextBean);

            return '';
        }

        $interpolated = preg_replace_callback(
            self::PLACEHOLDER_REGEX,
            function (array $matches) use ($contextBean, $templateBean, &$unresolved) {
                $value = self::resolvePlaceholder($matches[1], $contextBean, $templateBean);

                if ($value === null) {
                    // The placeholder points to something that does not exist in the record, so it
                    // is handled as an empty value: the name is still built with the rest of the
                    // variables and no consecutive separators are left behind.
                    $unresolved[] = $matches[1];

                    return '';
                }

                return $value;
            },
            $pattern
        );

        if (!is_string($interpolated)) {
            self::log('discarded the file name pattern because it could not be interpolated', $pattern, $contextBean);

            return '';
        }

        if (!empty($unresolved)) {
            self::log(
                'some placeholders of the file name pattern do not exist in the record and are ' .
                'handled as empty values: ' . implode(', ', $unresolved),
                $pattern,
                $contextBean
            );
        }

        $baseName = self::sanitize($interpolated);
        if ($baseName === '') {
            self::log(
                'discarded the file name pattern because every placeholder resolved to an empty value',
                $pattern,
                $contextBean
            );

            return '';
        }

        $extension = self::sanitizeExtension($extension);
        if ($extension === '') {
            return $baseName;
        }

        // The pattern may already include the extension, so it is not repeated
        $suffix = '.' . $extension;
        if (strlen($baseName) > strlen($suffix) && strtolower(substr($baseName, -strlen($suffix))) === strtolower($suffix)) {
            $baseName = substr($baseName, 0, -strlen($suffix));
        }

        if ($baseName === '') {
            return '';
        }

        return $baseName . $suffix;
    }

    /**
     * Traces why a pattern was discarded, so the administrator can find out from the log why the
     * download kept its previous name instead of having to guess it.
     *
     * @param string $reason
     * @param string $pattern
     * @param object|null $contextBean
     */
    private static function log(string $reason, string $pattern, $contextBean): void
    {
        if (!class_exists(LoggerManager::class)) {
            return;
        }

        $module = is_object($contextBean) ? ($contextBean->module_dir ?? '') : '';
        $recordId = is_object($contextBean) ? ($contextBean->id ?? '') : '';

        LoggerManager::getLogger()->warn(sprintf(
            'PDFFileNameResolver: %s. Pattern "%s", record module "%s", record id "%s".',
            $reason,
            $pattern,
            (string)$module,
            (string)$recordId
        ));
    }

    /**
     * Turns a single `{{placeholder}}` token into its value, or into an empty string when the
     * value is known to be empty.
     *
     * @param string $token
     * @param object|null $contextBean
     * @param object|null $templateBean
     * @param int $depth current `relate` depth.
     * @return string|null the value, or null when the token cannot be resolved at all.
     */
    private static function resolvePlaceholder($token, $contextBean, $templateBean, int $depth = 0): ?string
    {
        $parts = explode('.', trim((string) $token));
        $name = array_shift($parts);

        if ($name === '') {
            return null;
        }

        if (!empty($parts)) {
            return self::resolveRelatedValue($contextBean, $name, $parts, $depth);
        }

        // A bare relation field resolves to the display name of the related record.
        if (self::isRelateField($contextBean, $name)) {
            $related = self::getRelatedBean($contextBean, $name);
            if ($related === null) {
                return '';
            }

            $fieldDef = self::getFieldDefs($contextBean)[$name] ?? [];

            return self::readFieldValue($related, self::getRelatedDisplayName($fieldDef)) ?? '';
        }

        $value = self::readFieldValue($contextBean, $name);
        if ($value !== null) {
            return $value;
        }

        // The name is not a field of the record, but it may be one of the aliases, which point
        // to a well known field of the module
        $alias = self::FIELD_ALIASES[$name] ?? null;
        if ($alias !== null) {
            $value = self::readFieldValue($contextBean, $alias);
            if ($value !== null) {
                return $value;
            }
        }

        return $depth === 0 ? self::resolveReservedValue($name, $contextBean, $templateBean) : null;
    }

    /**
     * Resolves a placeholder pointing to a field of a record related to the context one.
     *
     * @param object|null $contextBean
     * @param string $relateName name of the `relate` field.
     * @param string[] $path remaining tokens of the dotted notation.
     * @param int $depth
     * @return string|null the value, or null when the token cannot be resolved at all.
     */
    private static function resolveRelatedValue($contextBean, $relateName, array $path, int $depth): ?string
    {
        if ($depth >= self::MAX_RELATION_DEPTH) {
            return '';
        }

        if (!self::isRelateField($contextBean, $relateName)) {
            return null;
        }

        $related = self::getRelatedBean($contextBean, $relateName);
        if ($related === null) {
            return '';
        }

        $name = array_shift($path);

        if (!empty($path)) {
            return self::resolveRelatedValue($related, $name, $path, $depth + 1);
        }

        return self::readFieldValue($related, $name);
    }

    /**
     * Loads the record behind a `relate` field, if it is available.
     *
     * @param object|null $bean
     * @param string $relateName
     * @return object|null
     */
    private static function getRelatedBean($bean, $relateName)
    {
        if (!self::isRelateField($bean, $relateName)) {
            return null;
        }

        $fieldDef = self::getFieldDefs($bean)[$relateName];
        $module = $fieldDef['module'] ?? '';
        $idName = $fieldDef['id_name'] ?? '';

        if (!is_string($module) || $module === '' || !is_string($idName) || $idName === '') {
            return null;
        }

        $id = $bean->{$idName} ?? null;
        if (empty($id)) {
            return null;
        }

        $related = BeanFactory::getBean($module, $id);

        if (!is_object($related) || empty($related->field_defs)) {
            return null;
        }

        return $related;
    }

    /**
     * @param object|null $bean
     * @param string $name
     * @return bool
     */
    private static function isRelateField($bean, $name): bool
    {
        if (!is_object($bean)) {
            return false;
        }

        // Beside `relate` fields, one-to-one `link` fields such as
        // `assigned_user_link` also designate a relation that can be followed: they point to a
        // single record of a module whose id is stored in the field of self::id_name
        $fieldDef = self::getFieldDefs($bean)[$name] ?? null;

        return is_array($fieldDef) && in_array($fieldDef['type'] ?? '', ['relate', 'link'], true);
    }

    /**
     * Field of the related record holding its display name. Legacy `relate` fields resolved the
     * plain `name` of the record, which is kept as they are, while one-to-one `link` fields may
     * point to records with a different display field, such as the `user_name` of the Users
     * module, declared in the `rname` property of the field definition.
     *
     * @param array $fieldDef definition of the `relate` or `link` field.
     * @return string
     */
    private static function getRelatedDisplayName(array $fieldDef): string
    {
        return (($fieldDef['type'] ?? '') === 'link') ? ($fieldDef['rname'] ?? 'name') : 'name';
    }

    /**
     * Reads a field of a record as plain text suitable for a file name.
     *
     * @param object|null $bean
     * @param string $name
     * @return string|null the value, or null when the record has no such field.
     */
    private static function readFieldValue($bean, $name)
    {
        if (!is_object($bean) || $name === '') {
            return null;
        }

        $fieldDefs = self::getFieldDefs($bean);
        $isKnownField = array_key_exists($name, $fieldDefs);

        if (!$isKnownField && !isset($bean->{$name})) {
            return null;
        }

        $fieldDef = $isKnownField ? $fieldDefs[$name] : [];
        $type = is_array($fieldDef) ? ($fieldDef['type'] ?? '') : '';

        if (in_array($type, self::NON_TEXTUAL_FIELD_TYPES, true)) {
            return '';
        }

        $raw = $bean->{$name} ?? null;

        if (is_bool($raw)) {
            return $raw ? '1' : '0';
        }

        if ($raw === null || is_array($raw) || is_object($raw)) {
            return '';
        }

        $value = trim((string)$raw);
        if ($value === '') {
            return '';
        }

        // Markup coming from a rich text editor would be a valid but unreadable file name.
        if (in_array($type, ['wysiwyg', 'html', 'textarea'], true)) {
            $value = trim(strip_tags($value));
        }

        if ($value === '') {
            return '';
        }

        if (strpos($value, '&') !== false) {
            $value = html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }

        return trim($value);
    }

    /**
     * Resolves the placeholders provided by the generation context instead of by a record field.
     *
     * @param string $name
     * @param object|null $contextBean
     * @param object|null $templateBean
     * @return string|null the value, or null when the name is not a reserved placeholder.
     */
    private static function resolveReservedValue($name, $contextBean, $templateBean): ?string
    {
        switch ($name) {
            case 'record_id':
                $id = is_object($contextBean) ? ($contextBean->id ?? '') : '';
                return is_string($id) || is_numeric($id) ? (string)$id : '';

            case 'template_name':
                $templateName = is_object($templateBean) ? ($templateBean->name ?? '') : '';
                return is_string($templateName) || is_numeric($templateName)
                    ? trim((string)$templateName)
                    : '';

            case 'now_date':
                return date('Y-m-d');

            case 'now_datetime':
                return date('YmdHis');

            default:
                return null;
        }
    }

    /**
     * Removes from a string everything the common file systems would not accept.
     *
     * @param string $name
     * @return string
     */
    private static function sanitize(string $name): string
    {
        // Path separators, wildcards, reserved punctuation, braces and control characters.
        $name = preg_replace('/[\/\\\\:*?"<>|\{\}\x00-\x1F\x7F]/', '_', $name) ?? '';
        $name = preg_replace('/\s+/', ' ', $name) ?? '';

        // Spaces have always been turned into underscores by the PDF templates.
        $name = str_replace(' ', '_', $name);

        // Empty values must not leave consecutive separators or relative path fragments behind.
        $name = preg_replace('/[_\-]{2,}/', '_', $name) ?? '';
        $name = preg_replace('/\.{2,}/', '.', $name) ?? '';
        $name = trim($name, "._- \t\n\r\0\x0B");

        if ($name === '') {
            return '';
        }

        if (strlen($name) > self::MAX_BASE_NAME_BYTES) {
            $name = trim(mb_strcut($name, 0, self::MAX_BASE_NAME_BYTES, 'UTF-8'), "._-");
        }

        if ($name === '') {
            return '';
        }

        return self::escapeReservedName($name);
    }

    /**
     * @param string $name base name, extension included.
     * @return string
     */
    private static function escapeReservedName(string $name): string
    {
        $stem = strtoupper(explode('.', $name)[0]);

        return in_array($stem, self::WINDOWS_RESERVED_NAMES, true) ? '_' . $name : $name;
    }

    /**
     * @param string $extension
     * @return string
     */
    private static function sanitizeExtension(string $extension): string
    {
        return substr(preg_replace('/[^A-Za-z0-9]/', '', $extension) ?? '', 0, 10);
    }

    /**
     * @param object|null $bean
     * @return array
     */
    private static function getFieldDefs($bean): array
    {
        if (!is_object($bean) || empty($bean->field_defs) || !is_array($bean->field_defs)) {
            return [];
        }

        return $bean->field_defs;
    }
}
