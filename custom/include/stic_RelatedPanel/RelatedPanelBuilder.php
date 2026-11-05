<?php
/**
 * This file is part of SinergiaCRM.
 * SinergiaCRM is a work developed by SinergiaTIC Association, based on SuiteCRM.
 * Copyright (C) 2013 - 2026 SinergiaTIC Association
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by
 * the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along
 * with this program; if not, see http://www.gnu.org/licenses or write to the
 * Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SinergiaTIC Association at email address info@sinergiacrm.org.
 */

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

// STIC-Custom 20261023 DEV - Related records panel
// Builds the payload of the right-hand "related records" panel for a record detail view.
// Configuration driven (SticRelatedPanelSettings):
//  - which modules / subpanels are shown (admin tree)
//  - per subpanel: title field, subtitle field and the date field used to place
//    its records in the chronology (defaults: name, assigned user, date_modified)
//  - ALL the records of every enabled relation are merged into ONE flat
//    chronology (newest first), with no grouping by subpanel
//  - the relation list (for the user filter) only contains sources with records
// Data access reuses the core subpanel machinery (SubPanelDefinitions +
// SugarBean::get_union_related_list) so relationship logic, collections and
// "function:" data sources behave exactly as in the bottom subpanels.
// See stic-ai-handbook: output/features/related-records-panel

require_once 'include/SubPanel/SubPanelDefinitions.php';
require_once 'custom/include/stic_RelatedPanel/RelatedPanelSettings.php';
require_once 'custom/include/stic_RelatedPanel/RelatedPanelSecurity.php';

class SticRelatedPanelBuilder
{
    /**
     * @var SticRelatedPanelSecurity
     */
    protected $security;

    /**
     * Fields that are SubPanelDetailViewLink widgets but point to a DIFFERENT record
     * (assigned user, parent account...) and therefore must not be used as card title.
     * @var string[]
     */
    protected $titleDenylist = array(
        'assigned_user_name',
        'parent_name',
        'account_name',
        'contact_name',
        'report_to_name',
        'member_name',
        'created_by_name',
        'modified_by_name',
    );

    /**
     * Date fields evaluated (in this order) to build the timeline entry date
     * when no explicit "right field" is configured.
     * @var string[]
     */
    protected $dateCandidates = array(
        'date_end',
        'date_start',
        'date_entered',
        'date_modified',
        'date_closed',
        'active_date',
    );

    /**
     * @param SticRelatedPanelSecurity|null $security
     */
    public function __construct($security = null)
    {
        $this->security = $security ? $security : new SticRelatedPanelSecurity();
    }

    /**
     * Builds the full panel payload for a record.
     *
     * @param SugarBean $bean Retrieved parent record
     * @return array{module:string, record:string, generated_at:string,
     *               enabled:bool, pinned:bool, timeline:array, relations:array}
     */
    public function build(SugarBean $bean)
    {
        $module = (string) $bean->module_dir;
        $enabled = SticRelatedPanelSettings::isEnabledForModule($module);
        $pinned = SticRelatedPanelSettings::isPinned();

        $timeline = array();
        $relations = array();
        if ($enabled) {
            $built = $this->buildTimeline($bean, $module);
            $timeline = $built['timeline'];
            $relations = $built['relations'];
        }

        return array(
            'module' => $module,
            'record' => (string) $bean->id,
            'generated_at' => date('c'),
            'enabled' => $enabled,
            'pinned' => $pinned,
            'timeline' => $timeline,
            'relations' => $relations,
        );
    }

    /**
     * Collects ALL the related records in ONE flat chronology (no grouping by
     * subpanel) plus the list of relation sources that can be filtered.
     *
     * @param SugarBean $bean
     * @param string $module
     * @return array{timeline:array, relations:array}
     */
    protected function buildTimeline(SugarBean $bean, $module)
    {
        $timeline = array();
        $relations = array();
        $max = SticRelatedPanelSettings::getMax();

        $definitions = new SubPanelDefinitions($bean);
        $tabs = $definitions->get_available_tabs();

        // Load relationship metadata (same requirement as SubPanelTiles::display()).
        if (empty($GLOBALS['relationships'])) {
            if (!class_exists('Relationship')) {
                require 'modules/Relationships/Relationship.php';
            }
            $rel = BeanFactory::newBean('Relationships');
            $rel->load_relationship_meta();
        }

        $adminOrder = 0;
        foreach ($tabs as $key) {
            $subConfig = SticRelatedPanelSettings::getSubpanelConfig($module, $key);
            if (empty($subConfig['enabled'])) {
                continue;
            }

            $panel = $definitions->load_subpanel($key);
            if ($panel === false) {
                continue;
            }

            // Skip orphaned subpanels (mirror of SubPanelTiles::display() checks).
            if (!isset($panel->_instance_properties['collection_list'])
                && isset($panel->_instance_properties['get_subpanel_data'])) {
                $dataSource = $panel->_instance_properties['get_subpanel_data'];
                if (stripos($dataSource, 'function:') === false
                    && !isset($bean->field_defs[$dataSource])) {
                    continue;
                }
            }
            if ($panel->isCollection() && $panel->load_sub_subpanels() === false) {
                continue;
            }

            $headerPanel = $panel->isCollection() ? $panel->get_header_panel_def() : $panel;
            $headerModule = $headerPanel ? (string) $headerPanel->get_module_name() : '';
            $relationTitle = $headerPanel ? trim((string) $headerPanel->get_title()) : '';
            if ($relationTitle === '' && $panel->isCollection()) {
                // Collections (e.g. Activities/History) keep their title in the
                // collection definition, not in the first sub-subpanel header.
                $titleKey = isset($panel->_instance_properties['title_key'])
                    ? (string) $panel->_instance_properties['title_key']
                    : '';
                if ($titleKey !== '') {
                    $relationTitle = (string) translate($titleKey, $module);
                }
            }
            if ($relationTitle === '') {
                $relationTitle = (string) $key;
            }
            $dateField = SticRelatedPanelSettings::getSubpanelDateField($subConfig);

            $records = array();
            $rowCount = 0;
            $hasMore = false;

            // Make sure the fields needed to merge every relation in the global
            // chronology are part of the SELECT. The union wrapper can only
            // ORDER BY (and expose) columns that the subqueries return.
            $restoreFields = $this->injectDateFields($panel, array($dateField, SticRelatedPanelSettings::DEFAULT_DATE_FIELD, 'date_entered'));

            try {
                $result = SugarBean::get_union_related_list(
                    $bean,
                    $this->getTimelineSortBy($panel, $dateField),
                    'desc',
                    '',
                    0,
                    -1,
                    max($max, 1),
                    0,
                    $panel
                );
                $rows = is_array($result) && isset($result['list']) ? $result['list'] : array();
                $rowCount = (int) (isset($result['row_count']) ? $result['row_count'] : 0);

                if (!empty($rows)) {
                    $rows = $this->security->filterVisibleRows($rows);
                    foreach ($rows as $row) {
                        $record = $this->mapRow($row, $panel, $subConfig);
                        $record['subpanel'] = (string) $key;
                        $record['relation'] = $relationTitle;
                        $records[] = $record;
                    }
                    $hasMore = $rowCount > count($rows);
                }
            } catch (Throwable $e) {
                $GLOBALS['log']->error('SticRelatedPanelBuilder: subpanel [' . $key . '] - ' . $e->getMessage());
            } finally {
                $this->restoreDateFields($restoreFields);
            }

            // Relations without records cannot contribute to the chronology nor
            // be filtered: they are not part of the payload.
            if (empty($records)) {
                continue;
            }

            $relations[] = array(
                'key' => (string) $key,
                'title' => $relationTitle,
                'module' => $headerModule,
                'icon' => $this->iconClass($headerModule),
                'count' => $rowCount,
                'has_more' => $hasMore,
                'date_field' => $dateField,
                'order' => $subConfig['order'] !== null ? (int) $subConfig['order'] : $adminOrder,
            );
            $adminOrder++;

            foreach ($records as $record) {
                $timeline[] = $record;
            }
        }

        // Admin (tree) order for the relation filter list
        usort($relations, function ($a, $b) {
            if ($a['order'] === $b['order']) {
                return 0;
            }
            return ($a['order'] < $b['order']) ? -1 : 1;
        });

        // ONE chronology: newest first, records without date at the bottom.
        usort($timeline, array($this, 'compareByDate'));

        return array('timeline' => $timeline, 'relations' => $relations);
    }

    /**
     * Maps one union row to a timeline entry using the subpanel configuration.
     *
     * @param mixed $row Union row bean (SugarBean or stdClass fallback)
     * @param aSubPanel $panel Subpanel the row belongs to
     * @param array $subConfig Effective subpanel configuration
     * @return array{id:string,module:string,icon:string,title:string,subtitle:string,
     *               date:string,date_label:string,date_field:string,date_ts:mixed,url:string}
     */
    protected function mapRow($row, $panel, array $subConfig)
    {
        $fields = array();
        if (is_object($row) && is_callable(array($row, 'get_list_view_data'))) {
            $fields = $row->get_list_view_data();
        }

        $rowPanel = $panel;
        if ($panel->isCollection() && isset($row->panel_name)
            && isset($panel->sub_subpanels[$row->panel_name])) {
            $rowPanel = $panel->sub_subpanels[$row->panel_name];
        }

        $module = isset($row->module_dir) ? (string) $row->module_dir : '';
        $id = isset($row->id) ? (string) $row->id : '';

        // --- Chronology date: configured field, then date_modified, then auto
        $dateField = SticRelatedPanelSettings::getSubpanelDateField($subConfig);
        $dateRaw = $this->resolveDate($row, $fields, $dateField);
        $dateLabel = $dateRaw !== null ? $this->formatFieldValue($row, $dateRaw, $dateField) : '';
        // Title and subtitle come straight from get_list_view_data(), which
        // HTML-escapes the values; the client renders them as text nodes, so
        // decode once here to avoid showing "&quot;&quot;" literally.
        $title = $this->textValue($this->resolveTitle($row, $rowPanel, $fields, $subConfig['title_field']));
        $subtitle = $this->textValue($this->resolveSubtitle($row, $fields, $subConfig['subtitle_field']));

        return array(
            'id' => $id,
            'module' => $module,
            'icon' => $this->iconClass($module),
            'title' => $title,
            'subtitle' => $subtitle,
            'date' => $dateRaw !== null ? (string) $dateRaw : '',
            'date_label' => $dateLabel,
            'date_field' => $dateField,
            'date_ts' => $this->sortKeyValue($dateRaw),
            'url' => $this->buildUrl($module, $id),
            'details' => $this->buildDetails($row, $rowPanel, $fields, $subConfig, $title, $subtitle, $dateLabel),
        );
    }

    /**
     * Extra list-view columns shown in the client tooltip of a timeline entry:
     * the rest of the subpanel columns that are not already the title,
     * subtitle or chronology date of the card.
     *
     * @param mixed $row
     * @param aSubPanel|null $rowPanel
     * @param array $fields get_list_view_data() values
     * @param array $subConfig
     * @param string $title
     * @param string $subtitle
     * @param string $dateLabel
     * @return array<int,array{label:string,value:string}>
     */
    protected function buildDetails($row, $rowPanel, array $fields, array $subConfig, $title, $subtitle, $dateLabel)
    {
        $details = array();
        if (!$rowPanel || !is_callable(array($rowPanel, 'get_list_fields'))) {
            return $details;
        }
        $instance = isset($rowPanel->template_instance) ? $rowPanel->template_instance : null;
        $module = (string) $rowPanel->get_module_name();
        $dateField = SticRelatedPanelSettings::getSubpanelDateField($subConfig);

        $skipNames = array(
            'id', 'deleted', 'module_dir', 'edit_button', 'remove_button', 'select_button',
            // Already represented by the automatic subtitle (assigned user)
            'assigned_user_name',
            // UNION helper columns that may not carry usage => query_only in
            // every subpanel definition
            'contact_id', 'contact_name_owner', 'contact_name_mod',
            'assigned_user_owner', 'assigned_user_mod', 'reply_to_status', 'file_url',
        );
        foreach (array('title_field', 'subtitle_field') as $configKey) {
            if (!empty($subConfig[$configKey])) {
                $skipNames[] = (string) $subConfig[$configKey];
            }
        }
        $skipNames[] = $dateField;
        if (empty($subConfig['date_field'])) {
            // The automatic chronology date is date_modified: do not repeat it.
            $skipNames[] = SticRelatedPanelSettings::DEFAULT_DATE_FIELD;
        }

        // Values already visible in the card (title / subtitle / date): never
        // repeat them as extra fields.
        $skipValues = array();
        foreach (array($title, $subtitle, $dateLabel) as $visibleValue) {
            $normalized = $this->normalizeDetailValue($visibleValue);
            if ($normalized !== '') {
                $skipValues[$normalized] = true;
            }
        }

        $seenFields = array();
        foreach ($rowPanel->get_list_fields() as $fieldName => $def) {
            $fieldName = (string) $fieldName;
            if ($fieldName === '' || isset($seenFields[$fieldName])) {
                continue;
            }
            $seenFields[$fieldName] = true;

            if (in_array($fieldName, $skipNames, true)) {
                continue;
            }
            // Column kept out of the subpanel display (union helper columns
            // such as contact_id / contact_name_owner use usage => query_only)
            if (!empty($def['query_only'])
                || (!empty($def['usage']) && (string) $def['usage'] === 'query_only')) {
                continue;
            }
            if (!empty($def['widget_class'])
                && (stripos((string) $def['widget_class'], 'Button') !== false
                    || stripos((string) $def['widget_class'], 'SubPanelIcon') !== false)) {
                continue;
            }

            $raw = $this->rawFieldValue($row, $fields, $fieldName);
            if ($raw === null) {
                continue;
            }
            $type = $this->fieldType($row, $instance, $fieldName, $def);
            if (in_array($type, array('link', 'image', 'file', 'wysiwyg'), true)) {
                continue;
            }
            $value = $this->normalizeDetailValue($this->formatFieldValue($row, $raw, $fieldName, $type));
            if ($value === '' || strpos($value, '0000-00-00') === 0) {
                continue;
            }
            if (isset($skipValues[$value])) {
                continue;
            }
            $skipValues[$value] = true; // no duplicate columns with the same value

            $details[] = array(
                'label' => $this->fieldLabel($fieldName, $def, $module, $instance, $row),
                'value' => $this->truncateDetailValue($value),
            );
            if (count($details) >= 8) {
                break;
            }
        }
        return $details;
    }

    /**
     * Type of a subpanel field (list definition first, then vardefs).
     *
     * @param mixed $row
     * @param SugarBean|null $instance
     * @param string $fieldName
     * @param array $def
     * @return string
     */
    protected function fieldType($row, $instance, $fieldName, array $def)
    {
        if (!empty($def['type'])) {
            return (string) $def['type'];
        }
        if ($instance && !empty($instance->field_defs[$fieldName]['type'])) {
            return (string) $instance->field_defs[$fieldName]['type'];
        }
        if (is_object($row) && !empty($row->field_defs[$fieldName]['type'])) {
            return (string) $row->field_defs[$fieldName]['type'];
        }
        return '';
    }

    /**
     * Translated label of a tooltip field, with vardefs fallback (list_fields
     * entries such as first_name / last_name have no vname).
     *
     * @param string $fieldName
     * @param array $def
     * @param string $module
     * @param SugarBean|null $instance
     * @param mixed $row
     * @return string
     */
    protected function fieldLabel($fieldName, array $def, $module, $instance, $row)
    {
        $vname = !empty($def['vname']) ? (string) $def['vname'] : '';
        // list_fields keys sometimes reference labels that are not shipped in
        // the module language file (e.g. LBL_LIST_DATE in the Tasks/Meetings
        // Activities subpanels): always fall back to the vardefs, whose label
        // is the one the detail view uses.
        $fallbacks = array();
        if ($instance && !empty($instance->field_defs[$fieldName]['vname'])) {
            $fallbacks[] = (string) $instance->field_defs[$fieldName]['vname'];
        }
        if (is_object($row) && !empty($row->field_defs[$fieldName]['vname'])) {
            $fallbacks[] = (string) $row->field_defs[$fieldName]['vname'];
        }
        foreach (array_merge(array($vname), $fallbacks) as $candidate) {
            if ($candidate === '') {
                continue;
            }
            $translated = translate($candidate, $module);
            if ($translated && $translated !== $candidate) {
                return $this->normalizeDetailValue(rtrim(trim((string) $translated), ':'));
            }
        }
        return $fieldName;
    }

    /**
     * Cleans a display value that will be rendered as text on the client card
     * (list view values arrive HTML-escaped): strips tags, decodes entities
     * and collapses whitespace. Same rules as detail (tooltip) values.
     *
     * @param mixed $value
     * @return string
     */
    protected function textValue($value)
    {
        return $this->normalizeDetailValue($value);
    }

    /**
     * Cleans a value for the tooltip: no HTML, decoded entities and single spaces.
     *
     * @param mixed $value
     * @return string
     */
    protected function normalizeDetailValue($value)
    {
        $value = (string) $value;
        if (strpos($value, '<') !== false) {
            $value = strip_tags($value);
        }
        $value = html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $value = preg_replace('/\s+/u', ' ', $value);
        return trim((string) $value);
    }

    /**
     * Caps a tooltip value to keep the popover readable.
     *
     * @param string $value
     * @return string
     */
    protected function truncateDetailValue($value)
    {
        $limit = 120;
        if (function_exists('mb_strlen') && function_exists('mb_substr')) {
            if (mb_strlen($value, 'UTF-8') > $limit) {
                return mb_substr($value, 0, $limit - 1, 'UTF-8') . '…';
            }
            return $value;
        }
        if (strlen($value) > $limit) {
            return substr($value, 0, $limit - 3) . '...';
        }
        return $value;
    }

    /**
     * Raw value used to place a row in the chronology.
     *
     * @param mixed $row
     * @param array $fields
     * @param string $dateField
     * @return string|null
     */
    protected function resolveDate($row, array $fields, $dateField)
    {
        $value = $this->rawFieldValue($row, $fields, $dateField);
        if (!$this->isUsableDate($value)) {
            $value = $this->rawFieldValue($row, $fields, SticRelatedPanelSettings::DEFAULT_DATE_FIELD);
        }
        if (!$this->isUsableDate($value)) {
            $value = $this->pickDate($row);
        }
        return $this->isUsableDate($value) ? (string) $value : null;
    }

    /**
     * Whether a raw value can be used as a chronology date.
     *
     * @param mixed $value
     * @return bool
     */
    protected function isUsableDate($value)
    {
        return $value !== null
            && $value !== ''
            && strpos((string) $value, '0000-00-00') === false;
    }

    /**
     * Resolves the card title: configured field with fallback to automatic detection.
     */
    protected function resolveTitle($row, $rowPanel, array $fields, $titleField)
    {
        if (!empty($titleField)) {
            $value = $this->rawFieldValue($row, $fields, $titleField);
            if ($value !== null && $value !== '') {
                return (string) $value;
            }
        }
        return $this->pickTitle($row, $rowPanel, $fields);
    }

    /**
     * Resolves the card subtitle: configured field with fallback to automatic logic.
     */
    protected function resolveSubtitle($row, array $fields, $subtitleField)
    {
        if (!empty($subtitleField)) {
            $value = $this->rawFieldValue($row, $fields, $subtitleField);
            if ($value !== null && $value !== '') {
                if ($subtitleField === 'assigned_user_name') {
                    return translate('LBL_RRP_ASSIGNED_TO') . ': ' . (string) $value;
                }
                // Enum values are already translated by get_list_view_data()
                return (string) $value;
            }
        }
        return $this->pickSubtitle($row, $fields);
    }

    /**
     * Raw value of a field from the row (list view data first, bean property as fallback).
     *
     * @param mixed $row
     * @param array $fields
     * @param string $fieldName
     * @return string|null
     */
    protected function rawFieldValue($row, array $fields, $fieldName)
    {
        $upper = strtoupper($fieldName);
        if (isset($fields[$upper]) && $fields[$upper] !== '') {
            return (string) $fields[$upper];
        }
        if (isset($row->$fieldName) && $row->$fieldName !== '') {
            return (string) $row->$fieldName;
        }
        return null;
    }

    /**
     * Formats a field value for display (dates/datetimes through the user format).
     *
     * @param mixed $row
     * @param mixed $value
     * @param string $fieldName
     * @param string|null $type Optional field type (vardefs fallback when provided)
     * @return string
     */
    protected function formatFieldValue($row, $value, $fieldName, $type = null)
    {
        if ($type === null && is_object($row) && isset($row->field_defs[$fieldName]['type'])) {
            $type = $row->field_defs[$fieldName]['type'];
        }
        if ($type === 'date') {
            $timedate = TimeDate::getInstance();
            return (string) $timedate->to_display_date($value, false);
        }
        if ($type === 'datetime' || $type === 'datetimecombo') {
            return $this->formatDate($value);
        }
        // Dates stored as strings in unknown fields: try the datetime format
        if (preg_match('/^\d{4}-\d{2}-\d{2}/', (string) $value)) {
            return $this->formatDate($value);
        }
        return (string) $value;
    }

    /**
     * Chooses the card title: the first SubPanelDetailViewLink field that points to the
     * row's own record, with sane fallbacks.
     */
    protected function pickTitle($row, $rowPanel, array $fields)
    {
        if ($rowPanel) {
            foreach ($rowPanel->get_list_fields() as $fieldName => $def) {
                if (empty($def['widget_class']) || $def['widget_class'] !== 'SubPanelDetailViewLink') {
                    continue;
                }
                $fieldName = (string) $fieldName;
                if (in_array($fieldName, $this->titleDenylist, true)
                    || !empty($def['target_module'])) {
                    continue;
                }
                if (!empty($def['module']) && $def['module'] !== (isset($row->module_dir) ? $row->module_dir : '')) {
                    continue;
                }
                $value = $this->rawFieldValue($row, $fields, $fieldName);
                if ($value !== null && $value !== '') {
                    return $value;
                }
            }
        }

        foreach (array('name', 'document_name', 'subject') as $prop) {
            $value = $this->rawFieldValue($row, $fields, $prop);
            if ($value !== null && $value !== '') {
                return $value;
            }
        }
        return isset($row->id) ? (string) $row->id : '';
    }

    /**
     * Chooses the card subtitle: assigned user, else status, else empty.
     */
    protected function pickSubtitle($row, array $fields)
    {
        if (!empty($fields['ASSIGNED_USER_NAME'])) {
            // Label already translated server-side to keep the client dumb.
            return translate('LBL_RRP_ASSIGNED_TO') . ': ' . (string) $fields['ASSIGNED_USER_NAME'];
        }
        if (!empty($fields['STATUS'])) {
            // get_list_view_data() already resolves enum values.
            return (string) $fields['STATUS'];
        }
        return '';
    }

    /**
     * First non-empty date candidate of the row (raw DB value, for ordering).
     */
    protected function pickDate($row)
    {
        foreach ($this->dateCandidates as $field) {
            if (isset($row->$field) && $row->$field !== '' && strpos((string) $row->$field, '0000-00-00') === false) {
                return (string) $row->$field;
            }
        }
        return null;
    }

    /**
     * Formats a raw DB datetime for display using the user format and timezone.
     */
    protected function formatDate($date)
    {
        if (empty($date)) {
            return '';
        }
        $timedate = TimeDate::getInstance();
        return (string) $timedate->to_display_date_time($date);
    }

    /**
     * Sortable value of the right field: numeric timestamp for dates, plain value otherwise.
     */
    protected function sortKeyValue($value)
    {
        if ($value === null || $value === '') {
            return null;
        }
        $value = (string) $value;
        if (preg_match('/^\d{4}-\d{2}-\d{2}/', $value)) {
            $ts = strtotime($value);
            return $ts === false ? null : $ts;
        }
        if (is_numeric($value)) {
            return $value + 0;
        }
        return $value;
    }

    /**
     * Chronology comparator: newest first, rows without date always last.
     * Public because it is used as a usort callback.
     *
     * @param array $a
     * @param array $b
     * @return int
     */
    public function compareByDate($a, $b)
    {
        $av = isset($a['date_ts']) ? $a['date_ts'] : null;
        $bv = isset($b['date_ts']) ? $b['date_ts'] : null;

        // Nulls always last regardless of direction
        if ($av === null && $bv === null) {
            return 0;
        }
        if ($av === null) {
            return 1;
        }
        if ($bv === null) {
            return -1;
        }
        if (is_int($av) && is_int($bv)) {
            return $this->normalizeCompare($av, $bv, true);
        }
        if (is_numeric($av) && is_numeric($bv)) {
            return $this->normalizeCompare($av, $bv, true);
        }
        return -strcasecmp((string) $av, (string) $bv) < 0 ? -1
            : (-strcasecmp((string) $av, (string) $bv) > 0 ? 1 : 0);
    }

    /**
     * Sign of (b - a): -1 when b > a (b is more recent), 1 when b < a,
     * 0 when equal. Comparisons must stay consistent (-1 / 0 / 1).
     *
     * @param mixed $a
     * @param mixed $b
     * @param bool $descending
     * @return int
     */
    protected function normalizeCompare($a, $b, $descending)
    {
        $an = (float) $a;
        $bn = (float) $b;
        if ($an === $bn) {
            return 0;
        }
        if ($an < $bn) {
            return $descending ? 1 : -1;
        }
        return $descending ? -1 : 1;
    }

    /**
     * Detail-view URL for the row record (ajaxified like subpanel links).
     */
    protected function buildUrl($module, $id)
    {
        if ($module === '' || $id === '') {
            return '';
        }
        return ajaxLink('index.php?module=' . $module . '&action=DetailView&record=' . urlencode($id));
    }

    /**
     * Icon CSS class for a module (suitepicon font, same pattern as the module menu).
     */
    protected function iconClass($module)
    {
        $module = strtolower((string) $module);
        if ($module === '') {
            return '';
        }
        return 'suitepicon-module-' . str_replace('_', '-', $module);
    }

    /**
     * Adds the given fields to the subpanel list fields so the UNION query
     * selects them (needed to merge all the relations in one chronology).
     * Returns the state required by restoreDateFields().
     *
     * @param aSubPanel $panel
     * @param array $fields
     * @return array
     */
    protected function injectDateFields($panel, array $fields)
    {
        $state = array();
        $fields = array_values(array_unique(array_filter(array_map('strval', $fields), 'strlen')));
        if (empty($fields)) {
            return $state;
        }

        $panels = $panel->isCollection() ? $panel->sub_subpanels : array($panel);
        foreach ($panels as $current) {
            if (!$current || !isset($current->panel_definition['list_fields'])) {
                continue;
            }
            $instance = isset($current->template_instance) ? $current->template_instance : null;
            if (!$instance || empty($instance->field_defs)) {
                continue;
            }
            foreach ($fields as $field) {
                if (!isset($instance->field_defs[$field])) {
                    continue;
                }
                $state[] = array(
                    'panel' => $current,
                    'field' => $field,
                    'exists' => array_key_exists($field, $current->panel_definition['list_fields']),
                    'value' => isset($current->panel_definition['list_fields'][$field])
                        ? $current->panel_definition['list_fields'][$field]
                        : null,
                );
                if (array_key_exists($field, $current->panel_definition['list_fields'])) {
                    continue;
                }
                $definition = $instance->field_defs[$field];
                if (!isset($definition['name'])) {
                    $definition['name'] = $field;
                }
                $current->panel_definition['list_fields'][$field] = $definition;
            }
        }
        return $state;
    }

    /**
     * Reverts the list field changes made by injectDateFields().
     *
     * @param array $state
     * @return void
     */
    protected function restoreDateFields(array $state)
    {
        foreach ($state as $entry) {
            $current = $entry['panel'];
            $field = $entry['field'];
            if ($entry['exists']) {
                $current->panel_definition['list_fields'][$field] = $entry['value'];
            } else {
                unset($current->panel_definition['list_fields'][$field]);
            }
        }
    }

    /**
     * Default sort field of a subpanel (same default as the core list view).
     */
    protected function getSortBy($panel)
    {
        return isset($panel->_instance_properties['sort_by'])
            ? (string) $panel->_instance_properties['sort_by']
            : 'id';
    }

    /**
     * Field declared by the related module(s) of a subpanel.
     *
     * @param aSubPanel $panel
     * @param string $field
     * @return bool
     */
    protected function panelDeclaresField($panel, $field)
    {
        if ($field === '') {
            return false;
        }
        $panels = $panel->isCollection() ? $panel->sub_subpanels : array($panel);
        foreach ($panels as $current) {
            $instance = ($current && isset($current->template_instance)) ? $current->template_instance : null;
            if ($instance && isset($instance->field_defs[$field])) {
                return true;
            }
        }
        return false;
    }

    /**
     * Field used to fetch the records that will be placed in the chronology.
     * The configured date field wins when the related module declares it;
     * otherwise date_modified, the subpanel default or the always present id.
     *
     * @param aSubPanel $panel
     * @param string $dateField
     * @return string
     */
    protected function getTimelineSortBy($panel, $dateField)
    {
        $candidates = array(
            (string) $dateField,
            SticRelatedPanelSettings::DEFAULT_DATE_FIELD,
            $this->getSortBy($panel),
            'id',
        );
        foreach ($candidates as $candidate) {
            if ($candidate !== '' && $this->panelDeclaresField($panel, $candidate)) {
                return $candidate;
            }
        }
        return 'id';
    }
}
