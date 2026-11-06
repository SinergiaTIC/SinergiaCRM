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

use SuiteCRM\PDF\PDFFileNameResolver;

/**
 * Utils functions for the AOS_PDF_Templates module to be used from views, controllers and logic hooks
 */
class AOS_PDF_TemplatesUtils
{
    /**
     * Builds the dropdown that inserts the {{variables}} accepted by the file name
     * pattern (stic_file_name_c) in that field.
     *
     * The variables of every module are given to the browser as JSON and only the ones of the
     * module selected in the "type" field are rendered, so switching module is immediate and the
     * page is not loaded with options that are not going to be used.
     *
     * @param SugarView $view The view calling the selector (it provides the bean being edited)
     * @return string HTML of the selector and its script
     */
    public static function getFileNameSelector($view)
    {
        global $app_list_strings;

        // The list of types is built by AOS_PDF_Templates::loadTabModules(). Its
        // per-user access filtering is tracked as a core issue; here it is filtered again through
        // isModuleAccessible() in getFileNameVariables()
        $type = !empty($view->bean->type)
            ? (string)$view->bean->type
            : (string)key($app_list_strings['pdf_template_type_dom'] ?? array());
        $variables = json_encode(self::getFileNameVariables(), JSON_UNESCAPED_UNICODE);

        // The placeholder of the dropdown falls back to a neutral text when the
        // translation is not available in the language of the user
        $placeholder = json_encode(
            self::getFileNameString('LBL_STIC_FILE_NAME_SELECTOR_PLACEHOLDER', '--'),
            JSON_UNESCAPED_UNICODE
        );
        $type = json_encode($type, JSON_UNESCAPED_UNICODE);

        // The customCode of a panel may be rendered more than once by the template handler, so the
        // script avoids "{{" sequences and receives its data from PHP variables instead of literals.
        // The dropdown comes before the script, otherwise it would not exist yet on the first call.
        // It is a plain select, excluded from the shared sortable/searchable widget
        // (data-stic-search="false"), so opening it never depends on that JavaScript.
        return <<<HTML
<select id="file_name_field_selector" data-stic-search="false" data-stic-sort="false"
    onchange="sticFileNameInsertVariable();"></select>
<script type="text/javascript">
// Insert the {{variable}} tokens in the stic_file_name_c field
var sticFileNameVariables = $variables;
var sticFileNamePlaceholder = $placeholder;
var sticFileNameType = $type;

function sticFileNameFillSelector(moduleName){
    var selector = document.getElementById('file_name_field_selector');
    if(!selector){
        return;
    }
    var modules = sticFileNameVariables || {};
    var groups = modules[moduleName] || null;
    while(selector.firstChild){
        selector.removeChild(selector.firstChild);
    }
    var placeholderOption = document.createElement('option');
    placeholderOption.value = '';
    placeholderOption.text = sticFileNamePlaceholder;
    selector.appendChild(placeholderOption);
    if(groups){
        for(var group in groups){
            if(!Object.prototype.hasOwnProperty.call(groups, group)){
                continue;
            }
            var options = groups[group] || [];
            if(!options.length){
                continue;
            }
            var optgroup = document.createElement('optgroup');
            optgroup.label = group;
            for(var i = 0; i < options.length; i++){
                var option = document.createElement('option');
                option.value = options[i][0];
                option.text = options[i][1];
                optgroup.appendChild(option);
            }
            selector.appendChild(optgroup);
        }
    }
}

function sticFileNameInsertVariable(){
    var selector = document.getElementById('file_name_field_selector');
    var input = document.getElementById('stic_file_name_c');
    if(!selector || !input || !selector.value){
        return;
    }
    var token = selector.value;
    var start = input.selectionStart === null ? input.value.length : input.selectionStart;
    var end = input.selectionEnd === null ? start : input.selectionEnd;
    input.value = input.value.substring(0, start) + token + input.value.substring(end);
    // The placeholder is restored so the same variable can be inserted again
    selector.value = '';
    input.focus();
    try{
        input.setSelectionRange(start + token.length, start + token.length);
    }catch(e){
    }
}

// The variables depend on the module selected in the "type" field, which is rendered after this
// script, so the listener is attached once the whole page is loaded
(function(){
    var bind = function(){
        var typeField = document.getElementById('type');
        if(typeField){
            typeField.addEventListener('change', function(){
                sticFileNameFillSelector(this.value);
            });
        }
    };
    if(document.readyState === 'complete'){
        bind();
    }else{
        window.addEventListener('load', bind);
    }
})();

sticFileNameFillSelector(sticFileNameType);
</script>
HTML;
    }

    /**
     * Variables of the file name pattern available for each module that can have a
     * PDF template, grouped as: fields of the record, fields of the related records and data of
     * the generation itself.
     *
     * The filter is stricter than the one applied by \SuiteCRM\PDF\PDFFileNameResolver, so every
     * variable offered here is not only resolvable but also useful in a file name: no id or link
     * fields, no fields without textual value, none hidden from the layouts and, in the case of
     * the relations, only those storing the id of the related record. One-to-one `link` fields
     * such as `assigned_user_link` are the exception to the link rule: they are offered as the
     * relation group of the module they point to, exactly where the resolver resolves them.
     *
     * @return array module name => group label => list of [token, label] options.
     */
    public static function getFileNameVariables()
    {
        global $app_list_strings, $beanList;

        $variables = array();

        // Only the modules the current user has access to are offered, so role
        // restrictions and security groups are honoured (administrators always get every module)
        foreach (array_keys($app_list_strings['pdf_template_type_dom'] ?? array()) as $moduleName) {
            if (empty($beanList[$moduleName]) || !self::isModuleAccessible($moduleName)) {
                continue;
            }

            $module = new $beanList[$moduleName]();
            if (empty($module->field_defs)) {
                continue;
            }

            $groups = array();
            // Generation data first.
            $groups[self::getFileNameString('LBL_STIC_FILE_NAME_GROUP_CONTEXT', 'Generation data')] =
                self::getFileNameContextVariables();

            $fields = array();
            $relations = array();

            foreach ($module->field_defs as $name => $fieldDef) {
                // Relations are offered in their own group, so they are not repeated
                // here. The `link` type is included because one-to-one `link` fields such as
                // `assigned_user_link` are offered as the relation group of their module as well.
                if (in_array($fieldDef['type'] ?? '', array('relate', 'link'), true) || !self::isFileNameField($fieldDef)) {
                    continue;
                }
                $fields[] = array(
                    '{{' . $name . '}}',
                    self::getFileNameFieldLabel($fieldDef, $name, $module->module_dir),
                );
            }

            // The aliases of the resolver (nombre, apellidos, nif) are offered as
            // well when the module has the field they point to
            foreach (PDFFileNameResolver::FIELD_ALIASES as $alias => $targetField) {
                if (isset($module->field_defs[$alias]) || !isset($module->field_defs[$targetField])) {
                    continue;
                }
                if (!self::isFileNameField($module->field_defs[$targetField])) {
                    continue;
                }
                $fields[] = array(
                    '{{' . $alias . '}}',
                    self::getFileNameFieldLabel($module->field_defs[$targetField], $alias, $module->module_dir),
                );
            }

            $groups[self::getFileNameString('LBL_STIC_FILE_NAME_GROUP_RECORD', 'Record fields')] =
                self::sortFileNameOptions($fields);

            $relationGroupKeys = array();

            foreach ($module->field_defs as $name => $fieldDef) {
                $relatedModule = self::getFileNameRelatedModule($fieldDef);
                if ($relatedModule === null || !self::isFileNameField($fieldDef, true)) {
                    continue;
                }

                // A group of the records of a related module is only offered when the
                // current user has access to that module
                if (!self::isModuleAccessible($fieldDef['module'] ?? '')) {
                    continue;
                }

                // A module may hold two fields pointing to the very same relation,
                // e.g. an `assigned_user_name` relate and the `assigned_user_link` one-to-one link,
                // so one single group per target record is built
                $relationKey = ($fieldDef['module'] ?? '') . '|' . ($fieldDef['id_name'] ?? '');
                if (isset($relationGroupKeys[$relationKey])) {
                    continue;
                }
                $relationGroupKeys[$relationKey] = true;

                $options = array(
                    array(
                        '{{' . $name . '}}',
                        self::getFileNameString('LBL_STIC_FILE_NAME_RELATED_NAME', 'Related record name') . ' (' . $name . ')',
                    ),
                );

                foreach ($relatedModule->field_defs as $relatedName => $relatedDef) {
                    if (!self::isFileNameField($relatedDef)) {
                        continue;
                    }
                    $options[] = array(
                        '{{' . $name . '.' . $relatedName . '}}',
                        self::getFileNameFieldLabel($relatedDef, $relatedName, $relatedModule->module_dir),
                    );
                }

                if (count($options) < 2) {
                    continue;
                }

                $groupLabel = rtrim(translate($fieldDef['vname'] ?? '', $module->module_dir), ': ')
                    . ' · ' . $name . ' (' . $relatedModule->module_dir . ')';

                $relations[$groupLabel] = $options;
            }

            $groups = array_merge($groups, array_map(array(__CLASS__, 'sortFileNameOptions'), $relations));

            $variables[$moduleName] = $groups;
        }

        return $variables;
    }

    /**
     * Sorts the [token, label] options of a group by their label, keeping the
     * technical name of the field as the tiebreaker.
     *
     * The dropdown is a plain select, so the order in which PHP builds the groups is the order
     * the user reads in the list. The collator of the user language puts the accents in their
     * place, with a plain comparison when the intl extension is not available.
     *
     * @param array $options
     * @return array
     */
    public static function sortFileNameOptions(array $options)
    {
        if (count($options) < 2) {
            return $options;
        }

        $collator = null;
        if (class_exists('\\Collator')) {
            $collator = new \Collator(\Locale::getDefault());
            $collator->setAttribute(\Collator::NUMERIC_COLLATION, \Collator::ON);
        }

        usort(
            $options,
            function (array $a, array $b) use ($collator) {
                $result = $collator ? $collator->compare($a[1], $b[1]) : strnatcasecmp($a[1], $b[1]);

                return $result !== 0 ? $result : strcmp($a[0], $b[0]);
            }
        );

        return $options;
    }

    /**
     * Variables filled with the data of the generation instead of the record ones.
     *
     * @return array list of [token, label] options.
     */
    public static function getFileNameContextVariables()
    {
        $variables = array();
        $names = array('record_id', 'template_name', 'now_date', 'now_datetime');
        foreach ($names as $name) {
            $label = self::getFileNameString('LBL_STIC_FILE_NAME_VAR_' . strtoupper($name), $name);
            $variables[] = array('{{' . $name . '}}', $label);
        }

        return $variables;
    }

    /**
     * Translation of one of the strings of the selector. When the translation is
     * not available in the language of the user, a neutral text is used instead of showing the
     * key of the string.
     *
     * @param string $key
     * @param string $fallback
     * @return string
     */
    public static function getFileNameString($key, $fallback)
    {
        global $mod_strings;

        $value = trim((string)($mod_strings[$key] ?? ''));

        return $value !== '' ? $value : $fallback;
    }

    /**
     * Whether the current user can access a module. Queries its access control lists, so role
     * restrictions and security group configuration are honoured; administrators always have
     * access. Modules that do not implement access control are treated as accessible.
     *
     * @param string $moduleName
     * @return bool
     */
    public static function isModuleAccessible($moduleName)
    {
        if (!is_string($moduleName) || $moduleName === '') {
            return false;
        }

        if (!ACLController::moduleSupportsACL($moduleName)) {
            return true;
        }

        return ACLController::checkAccess($moduleName, 'view', true);
    }

    /**
     * Whether a field definition can become part of a file name. The resolver would
     * read any of them, but the selector only offers the ones that are really usable: no id, no
     * link, no image, signature or file, and no field hidden from the layouts.
     *
     * Relation groups accept one-to-one `link` fields such as `assigned_user_link`, so when
     * $forRelation is true a plain `link` field is a usable field and only the rest of the
     * exclusions are applied.
     *
     * @param mixed $fieldDef
     * @param bool $forRelation whether the check belongs to the fields of a related record group,
     *                          where usable `link` fields are accepted.
     * @return bool
     */
    public static function isFileNameField($fieldDef, $forRelation = false)
    {
        if (!is_array($fieldDef)) {
            return false;
        }

        if (isset($fieldDef['dbType']) && strtolower($fieldDef['dbType']) === 'id') {
            return false;
        }

        $type = $fieldDef['type'] ?? '';

        if ($type === 'id' || ($type === 'link' && !$forRelation)) {
            return false;
        }

        // Images, signatures and files have no value usable as a file name
        if (in_array($type, array('image', 'Signature', 'file'), true)) {
            return false;
        }

        if (isset($fieldDef['reportable']) && !$fieldDef['reportable']) {
            return false;
        }

        return !isset($fieldDef['vname']) || $fieldDef['vname'] !== 'LBL_DELETED';
    }

    /**
     * Module of the record behind a relation field, but only when the relation can
     * really be followed: the resolver loads the related bean through the id stored in id_name.
     * One-to-one `link` fields such as `assigned_user_link` (which point to the Users module)
     * are accepted as well when they declare the module and the id field.
     *
     * @param mixed $fieldDef
     * @return object|null
     */
    public static function getFileNameRelatedModule($fieldDef)
    {
        global $beanList;

        static $relatedModules = array();

        if (!is_array($fieldDef)) {
            return null;
        }

        // Beside `relate` fields, one-to-one `link` fields such as
        // `assigned_user_link` also designate a relation the resolver can follow
        $type = $fieldDef['type'] ?? '';
        if (!in_array($type, array('relate', 'link'), true)) {
            return null;
        }

        // Many-to-one and many-to-many `link` fields hold several records, whose id is not stored
        // in a field of the record, so the resolver cannot follow them
        if ($type === 'link' && ($fieldDef['link_type'] ?? '') !== 'one') {
            return null;
        }

        if (($fieldDef['source'] ?? '') !== 'non-db' || empty($fieldDef['id_name'])) {
            return null;
        }

        $relatedModuleName = $fieldDef['module'] ?? '';
        if (empty($relatedModuleName) || empty($beanList[$relatedModuleName])) {
            return null;
        }

        if (!isset($relatedModules[$relatedModuleName])) {
            $relatedModule = new $beanList[$relatedModuleName]();
            $relatedModules[$relatedModuleName] = empty($relatedModule->field_defs) ? null : $relatedModule;
        }

        return $relatedModules[$relatedModuleName];
    }

    /**
     * Label of a variable: the translated name of the field followed by the name of
     * the field itself, so the {{variable}} to write can be identified without doubt.
     *
     * @param array $fieldDef
     * @param string $name
     * @param string $moduleName
     * @return string
     */
    public static function getFileNameFieldLabel($fieldDef, $name, $moduleName)
    {
        $vname = $fieldDef['vname'] ?? '';
        $label = trim(rtrim(translate($vname, $moduleName), ': '));

        if ($label === '' || $label === $vname) {
            $label = $name;
        }

        return $label . ' (' . $name . ')';
    }
}
