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
//  - per subpanel: title field, subtitle field, right field (defaults: auto)
//  - per subpanel: record sort field (title|subtitle|right) and direction
//  - subpanels without records are returned as empty sections (count 0) unless
//    "hide_empty" is enabled; they are always placed at the bottom
//  - section order: admin order, overridden by the user preference; empties last
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
     *               enabled:bool, pinned:bool, hide_empty:bool, sections:array}
     */
    public function build(SugarBean $bean)
    {
        $module = (string) $bean->module_dir;
        $enabled = SticRelatedPanelSettings::isEnabledForModule($module);
        $pinned = SticRelatedPanelSettings::isPinned();
        $hideEmpty = SticRelatedPanelSettings::hideEmpty();

        $sections = array();
        if ($enabled) {
            $sections = $this->buildSections($bean, $module, $hideEmpty);
            $sections = $this->orderSections($sections, $module);
        }

        return array(
            'module' => $module,
            'record' => (string) $bean->id,
            'generated_at' => date('c'),
            'enabled' => $enabled,
            'pinned' => $pinned,
            'hide_empty' => $hideEmpty,
            'sections' => $sections,
        );
    }

    /**
     * Collects the sections (one per ENABLED subpanel, empties included unless hidden).
     *
     * @param SugarBean $bean
     * @param string $module
     * @param bool $hideEmpty
     * @return array
     */
    protected function buildSections(SugarBean $bean, $module, $hideEmpty)
    {
        $sections = array();
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

        $order = 0;
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

            $records = array();
            $rowCount = 0;
            $hasMore = false;

            try {
                $result = SugarBean::get_union_related_list(
                    $bean,
                    $this->getSortBy($panel),
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
                        $records[] = $this->mapRow($row, $panel, $subConfig);
                    }
                    $records = $this->sortRecords($records, $subConfig);
                    $records = array_slice($records, 0, $max);
                    $hasMore = $rowCount > count($rows);
                }
            } catch (Throwable $e) {
                $GLOBALS['log']->error('SticRelatedPanelBuilder: subpanel [' . $key . '] - ' . $e->getMessage());
            }

            if (empty($records) && $hideEmpty) {
                continue;
            }

            $headerPanel = $panel->isCollection() ? $panel->get_header_panel_def() : $panel;
            $headerModule = $headerPanel ? (string) $headerPanel->get_module_name() : '';

            $sections[] = array(
                'key' => (string) $key,
                'title' => $headerPanel ? (string) $headerPanel->get_title() : (string) $key,
                'module' => $headerModule,
                'icon' => $this->iconClass($headerModule),
                'count' => $rowCount,
                'has_more' => $hasMore,
                'empty' => empty($records),
                'order' => $subConfig['order'] !== null ? (int) $subConfig['order'] : $order,
                'records' => $records,
            );
            $order++;
        }

        return $sections;
    }

    /**
     * Applies the section order: admin order, then the user preference, then
     * empty sections are moved to the bottom.
     *
     * @param array $sections
     * @param string $module
     * @return array
     */
    protected function orderSections(array $sections, $module)
    {
        // Admin order
        usort($sections, function ($a, $b) {
            if ($a['order'] === $b['order']) {
                return 0;
            }
            return ($a['order'] < $b['order']) ? -1 : 1;
        });

        // User preference order (only keys present in the sections)
        $userOrder = SticRelatedPanelSettings::getUserOrder($module);
        if (!empty($userOrder)) {
            $position = array();
            foreach ($userOrder as $index => $key) {
                $position[$key] = $index;
            }
            usort($sections, function ($a, $b) use ($position) {
                $pa = isset($position[$a['key']]) ? $position[$a['key']] : 9999;
                $pb = isset($position[$b['key']]) ? $position[$b['key']] : 9999;
                if ($pa === $pb) {
                    return 0;
                }
                return ($pa < $pb) ? -1 : 1;
            });
        }

        // Empty sections always at the bottom
        $filled = array();
        $empty = array();
        foreach ($sections as $section) {
            if (!empty($section['empty'])) {
                $empty[] = $section;
            } else {
                $filled[] = $section;
            }
        }

        return array_merge($filled, $empty);
    }

    /**
     * Maps one union row to a timeline card using the subpanel configuration.
     *
     * @param mixed $row Union row bean (SugarBean or stdClass fallback)
     * @param aSubPanel $panel Subpanel the row belongs to
     * @param array $subConfig Effective subpanel configuration
     * @return array{id:string,module:string,icon:string,title:string,subtitle:string,
     *               right:string,date:?string,right_label:string,url:string}
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

        // --- Right field (was "date"): configured field or automatic date
        $rightRaw = null;
        $rightLabel = '';
        if (!empty($subConfig['right_field'])) {
            $rightRaw = $this->rawFieldValue($row, $fields, $subConfig['right_field']);
            if ($rightRaw !== null && $rightRaw !== '') {
                $rightLabel = $this->formatFieldValue($row, $rightRaw, $subConfig['right_field']);
            }
        }
        $autoDate = $this->pickDate($row);
        if (($rightRaw === null || $rightRaw === '') && $autoDate !== null) {
            $rightRaw = $autoDate;
            $rightLabel = $this->formatDate($autoDate);
        }

        $record = array(
            'id' => $id,
            'module' => $module,
            'icon' => $this->iconClass($module),
            'title' => $this->resolveTitle($row, $rowPanel, $fields, $subConfig['title_field']),
            'subtitle' => $this->resolveSubtitle($row, $fields, $subConfig['subtitle_field']),
            'right' => $rightRaw !== null ? (string) $rightRaw : '',
            'right_label' => $rightLabel,
            'url' => $this->buildUrl($module, $id),
        );

        // Internal sort keys (stripped before returning by sortRecords)
        $record['_sort_title'] = mb_strtolower($record['title']);
        $record['_sort_subtitle'] = mb_strtolower($record['subtitle']);
        $record['_sort_right'] = $this->sortKeyValue($rightRaw);

        return $record;
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
     */
    protected function formatFieldValue($row, $value, $fieldName)
    {
        $type = null;
        if (is_object($row) && isset($row->field_defs[$fieldName]['type'])) {
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
     * Sorts the records of one section according to its configuration
     * and strips the internal sort keys.
     */
    protected function sortRecords(array $records, array $subConfig)
    {
        $field = 'right';
        if (in_array($subConfig['sort_field'], SticRelatedPanelSettings::SORT_FIELDS, true)) {
            $field = $subConfig['sort_field'];
        }
        $dir = ($subConfig['sort_dir'] === 'asc') ? 1 : -1;
        $key = '_sort_' . $field;

        usort($records, function ($a, $b) use ($key, $dir) {
            $av = $a[$key];
            $bv = $b[$key];
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
                return ($av === $bv) ? 0 : (($av < $bv) ? -$dir : $dir);
            }
            if (is_numeric($av) && is_numeric($bv)) {
                $cmp = ($av == $bv) ? 0 : (($av < $bv) ? -1 : 1);
                return $cmp * $dir;
            }
            $cmp = strcasecmp((string) $av, (string) $bv);
            return $cmp * $dir;
        });

        foreach ($records as $index => $record) {
            unset($records[$index]['_sort_title'], $records[$index]['_sort_subtitle'], $records[$index]['_sort_right']);
        }

        return $records;
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
     * Default sort field of a subpanel (same default as the core list view).
     */
    protected function getSortBy($panel)
    {
        return isset($panel->_instance_properties['sort_by'])
            ? (string) $panel->_instance_properties['sort_by']
            : 'id';
    }
}
