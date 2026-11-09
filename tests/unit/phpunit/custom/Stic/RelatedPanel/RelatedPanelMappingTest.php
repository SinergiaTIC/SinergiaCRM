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

// STIC-Custom 20261022 DEV - Related records panel
// Unit tests for the pure mapping logic of the related records panel
// (no database required: only pure methods are exercised via reflection).
// Run: vendor/bin/phpunit tests/unit/phpunit/custom/Stic/RelatedPanel/RelatedPanelMappingTest.php
// See stic-ai-handbook: output/features/related-records-panel

use PHPUnit\Framework\TestCase;

require_once 'SticInclude/SticRelatedPanel/RelatedPanelBuilder.php';

class RelatedPanelMappingTest extends TestCase
{
    /**
     * @var SticRelatedPanelBuilder
     */
    protected $builder;

    protected function setUp(): void
    {
        $this->builder = new SticRelatedPanelBuilder();
    }

    /**
     * Calls a protected method of the builder.
     */
    protected function call($method, array $args = array())
    {
        $reflection = new ReflectionMethod('SticRelatedPanelBuilder', $method);
        $reflection->setAccessible(true);
        return $reflection->invokeArgs($this->builder, $args);
    }

    /**
     * Minimal stand-in for a subpanel template instance (only field_defs is used).
     */
    protected function fakeInstance(array $fieldDefs)
    {
        $instance = new stdClass();
        $instance->field_defs = $fieldDefs;
        return $instance;
    }

    /**
     * Minimal stand-in for an aSubPanel: module name, list fields and instance.
     *
     * @param array $listFields get_list_fields() shape
     * @param array $fieldDefs template_instance->field_defs
     * @param string $module
     * @return object
     */
    protected function fakePanel(array $listFields, array $fieldDefs, $module = 'Contacts')
    {
        $panel = new class {
            public $template_instance;
            public $module = 'Contacts';
            public $listFields = array();

            public function get_module_name()
            {
                return $this->module;
            }

            public function get_list_fields()
            {
                return $this->listFields;
            }

            public function isCollection()
            {
                return false;
            }
        };
        $panel->module = $module;
        $panel->listFields = $listFields;
        $panel->template_instance = $this->fakeInstance($fieldDefs);
        return $panel;
    }

    public function testIconClass()
    {
        $this->assertSame('suitepicon-module-contacts', $this->call('iconClass', array('Contacts')));
        $this->assertSame(
            'suitepicon-module-stic-payments',
            $this->call('iconClass', array('stic_Payments'))
        );
        $this->assertSame('', $this->call('iconClass', array('')));
    }

    public function testPickDatePrefersFirstCandidate()
    {
        $row = new stdClass();
        $row->date_modified = '2026-10-01 10:00:00';
        $this->assertSame('2026-10-01 10:00:00', $this->call('pickDate', array($row)));

        $row->date_entered = '2026-10-05 10:00:00';
        $row->date_start = '2026-10-10 08:00:00';
        $this->assertSame('2026-10-10 08:00:00', $this->call('pickDate', array($row)));
    }

    public function testPickDateSkipsEmptyDates()
    {
        $row = new stdClass();
        $row->date_end = '';
        $row->date_entered = '0000-00-00 00:00:00';
        $row->date_modified = '2026-01-01 00:00:00';
        $this->assertSame('2026-01-01 00:00:00', $this->call('pickDate', array($row)));
    }

    public function testPickDateReturnsNullWithoutDates()
    {
        $row = new stdClass();
        $this->assertNull($this->call('pickDate', array($row)));
    }

    public function testPickSubtitlePrefersAssignedUser()
    {
        $row = new stdClass();
        $fields = array('ASSIGNED_USER_NAME' => 'Juan', 'STATUS' => 'Held');
        $subtitle = $this->call('pickSubtitle', array($row, $fields));
        $this->assertStringContainsString('Juan', $subtitle);
        $this->assertStringNotContainsString('Held', $subtitle);
    }

    public function testPickSubtitleFallsBackToStatus()
    {
        $row = new stdClass();
        $fields = array('STATUS' => 'Held');
        $this->assertSame('Held', $this->call('pickSubtitle', array($row, $fields)));
    }

    public function testPickSubtitleEmpty()
    {
        $row = new stdClass();
        $this->assertSame('', $this->call('pickSubtitle', array($row, array())));
    }

    public function testPickTitleFromNameField()
    {
        $row = new stdClass();
        $row->name = 'Ana Pérez';
        $row->module_dir = 'Contacts';
        $fields = array('NAME' => 'Ana Pérez');
        $this->assertSame('Ana Pérez', $this->call('pickTitle', array($row, null, $fields)));
    }

    public function testPickTitleFallsBackToId()
    {
        $row = new stdClass();
        $row->id = 'abc-123';
        $this->assertSame('abc-123', $this->call('pickTitle', array($row, null, array())));
    }

    public function testCompareByDateDescendingWithNullsLast()
    {
        $newer = array('date_ts' => strtotime('2026-10-20 12:00:00'));
        $older = array('date_ts' => strtotime('2026-10-18 12:00:00'));
        $noDate = array('date_ts' => null);

        $this->assertSame(-1, $this->call('compareByDate', array($newer, $older)));
        $this->assertSame(1, $this->call('compareByDate', array($older, $newer)));
        $this->assertSame(1, $this->call('compareByDate', array($noDate, $older)));
        $this->assertSame(-1, $this->call('compareByDate', array($older, $noDate)));
        $this->assertSame(0, $this->call('compareByDate', array($noDate, array('date_ts' => null))));
        // Equal timestamps are equal in the chronology
        $this->assertSame(0, $this->call('compareByDate', array($newer, array('date_ts' => $newer['date_ts']))));
    }

    public function testCompareByDateWithNonNumericValues()
    {
        // Values that could not be parsed as a date fall back to a reversed
        // lexicographic comparison (latest string first)
        $a = array('date_ts' => 'zzz');
        $b = array('date_ts' => 'aaa');
        $this->assertSame(-1, $this->call('compareByDate', array($a, $b)));
        $this->assertSame(1, $this->call('compareByDate', array($b, $a)));
    }

    public function testIsUsableDate()
    {
        $this->assertTrue($this->call('isUsableDate', array('2026-10-01 10:00:00')));
        $this->assertTrue($this->call('isUsableDate', array('2026-10-01')));
        $this->assertFalse($this->call('isUsableDate', array(null)));
        $this->assertFalse($this->call('isUsableDate', array('')));
        $this->assertFalse($this->call('isUsableDate', array('0000-00-00 00:00:00')));
    }

    public function testRawFieldValuePrefersListViewData()
    {
        $row = new stdClass();
        $row->name = 'bean-name';
        $fields = array('NAME' => 'list-view-name');
        $this->assertSame('list-view-name', $this->call('rawFieldValue', array($row, $fields, 'name')));
        // Bean property is used when the list view data is missing
        $this->assertSame('bean-name', $this->call('rawFieldValue', array($row, array(), 'name')));
        // Empty list view value does not shadow the bean property
        $this->assertSame('bean-name', $this->call('rawFieldValue', array($row, array('NAME' => ''), 'name')));
        $this->assertNull($this->call('rawFieldValue', array($row, array(), 'missing')));
    }

    public function testResolveTitleUsesConfiguredFieldWithFallback()
    {
        $row = new stdClass();
        $row->name = 'bean-name';
        $row->subject = 'Subject value';
        // Configured field wins when it has a value
        $this->assertSame(
            'Subject value',
            $this->call('resolveTitle', array($row, null, array(), 'subject'))
        );
        // Falls back to the automatic logic when empty
        $this->assertSame(
            'bean-name',
            $this->call('resolveTitle', array($row, null, array('NAME' => 'bean-name'), ''))
        );
    }

    public function testResolveSubtitleUsesConfiguredFieldWithFallback()
    {
        $row = new stdClass();
        // A configured plain field (already translated by get_list_view_data) is shown as-is
        $this->assertSame(
            'Held',
            $this->call('resolveSubtitle', array($row, array('STATUS' => 'Held'), 'status'))
        );
        // Falls back to the automatic logic when empty
        $row->status = 'HELD_TOKEN';
        $fallback = $this->call('resolveSubtitle', array($row, array(), 'nonexistent_field'));
        $this->assertSame('', $fallback);
        $fields = array('ASSIGNED_USER_NAME' => 'Juan');
        $subtitle = $this->call('resolveSubtitle', array($row, $fields, ''));
        $this->assertStringContainsString('Juan', $subtitle);
    }

    public function testBuildUrl()
    {
        // URL form depends on the ajaxUI configuration (ajaxLink() may return an
        // ajaxified hash URL or a plain DetailView URL), so accept both shapes.
        $url = $this->call('buildUrl', array('Contacts', 'abc-123'));
        $this->assertStringContainsString('Contacts', $url);
        $this->assertTrue(
            strpos($url, 'record=abc-123') !== false || strpos($url, 'record%3Dabc-123') !== false,
            'Detail URL must reference the record id: ' . $url
        );

        $this->assertSame('', $this->call('buildUrl', array('Contacts', '')));
        $this->assertSame('', $this->call('buildUrl', array('', 'abc-123')));
    }

    public function testNormalizeDetailValue()
    {
        $this->assertSame('Hello world', $this->call('normalizeDetailValue', array('<b>Hello</b>&nbsp; world')));
        $this->assertSame('multiple spaces', $this->call('normalizeDetailValue', array("  multiple   spaces  ")));
        $this->assertSame('Ana & Luis', $this->call('normalizeDetailValue', array('Ana &amp; Luis')));
        $this->assertSame('', $this->call('normalizeDetailValue', array('')));
        $this->assertSame('', $this->call('normalizeDetailValue', array(null)));
    }

    public function testTruncateDetailValue()
    {
        $short = 'short text';
        $this->assertSame($short, $this->call('truncateDetailValue', array($short)));

        $cut = $this->call('truncateDetailValue', array(str_repeat('a', 200)));
        $this->assertLessThanOrEqual(120, mb_strlen($cut, 'UTF-8'));
        $this->assertStringStartsWith(str_repeat('a', 50), $cut);
        $this->assertTrue(
            strpos($cut, '…') !== false || substr($cut, -3) === '...',
            'Long values must be visually truncated'
        );

        // Multi-byte values are never cut in the middle of a character
        $cut = $this->call('truncateDetailValue', array(str_repeat('ñ', 200)));
        $this->assertLessThanOrEqual(120, mb_strlen($cut, 'UTF-8'));
        $this->assertNotFalse(json_encode($cut));
    }

    public function testFormatFieldValueHonorsExplicitType()
    {
        $row = new stdClass();
        // Non-date type: value is returned untouched
        $this->assertSame('plain', $this->call('formatFieldValue', array($row, 'plain', 'unknown', 'varchar')));

        // 'date' and 'datetime' go through different formatting branches
        $asDate = $this->call('formatFieldValue', array($row, '2026-10-01 05:04:03', 'when', 'date'));
        $asDateTime = $this->call('formatFieldValue', array($row, '2026-10-01 05:04:03', 'when', 'datetime'));
        $this->assertNotSame($asDate, $asDateTime);

        // The explicit type wins over the bean vardefs
        $row->field_defs = array('when' => array('type' => 'varchar'));
        $this->assertSame(
            $asDate,
            $this->call('formatFieldValue', array($row, '2026-10-01 05:04:03', 'when', 'date'))
        );

        // Without an explicit type the bean vardefs are used
        $row->field_defs = array('when' => array('type' => 'date'));
        $this->assertSame(
            $asDate,
            $this->call('formatFieldValue', array($row, '2026-10-01 05:04:03', 'when'))
        );
    }

    public function testFieldTypeResolution()
    {
        $row = new stdClass();
        // The list definition has priority
        $this->assertSame(
            'enum',
            $this->call('fieldType', array($row, null, 'status', array('type' => 'enum')))
        );

        // Then the subpanel template instance vardefs
        $instance = $this->fakeInstance(array('status' => array('type' => 'varchar')));
        $this->assertSame(
            'varchar',
            $this->call('fieldType', array($row, $instance, 'status', array()))
        );

        // Finally the row bean vardefs
        $row->field_defs = array('status' => array('type' => 'text'));
        $this->assertSame('text', $this->call('fieldType', array($row, null, 'status', array())));
        $this->assertSame('', $this->call('fieldType', array($row, null, 'nope', array())));
    }

    public function testFieldLabelFallsBackToVardefs()
    {
        $row = new stdClass();
        // No vname anywhere: the raw field name is used
        $this->assertSame(
            'custom_x',
            $this->call('fieldLabel', array('custom_x', array(), 'Contacts', null, $row))
        );

        // list_fields often carry no vname (query_only first_name / last_name):
        // the vardefs are used so the label is not the raw field name.
        $instance = $this->fakeInstance(array('first_name' => array('vname' => 'LBL_FIRST_NAME')));
        $label = $this->call('fieldLabel', array('first_name', array(), 'Contacts', $instance, $row));
        $this->assertNotSame('first_name', $label);
        $this->assertNotSame('LBL_FIRST_NAME', $label);

        // The definition vname wins over the instance vname
        $instance = $this->fakeInstance(array('first_name' => array('vname' => 'LBL_LAST_NAME')));
        $label = $this->call(
            'fieldLabel',
            array('first_name', array('vname' => 'LBL_FIRST_NAME'), 'Contacts', $instance, $row)
        );
        $this->assertNotSame('LBL_FIRST_NAME', $label);
        $this->assertNotSame('LBL_LAST_NAME', $label);
    }

    public function testBuildDetailsKeepsOnlyExtraVisibleColumns()
    {
        $row = new stdClass();
        $row->id = 'r1';
        $row->name = 'Ana';
        $row->status = 'Active';
        $row->description = 'Line one';
        $row->date_modified = '2026-10-01 10:00:00';
        $row->assigned_user_name = 'Juan';
        $row->image_field = 'x.png';
        $row->query_only_field = 'secret';

        $panel = $this->fakePanel(
            array(
                'name' => array('vname' => 'LBL_NAME'),
                'status' => array('vname' => 'LBL_STATUS'),
                'description' => array('vname' => 'LBL_DESCRIPTION'),
                'date_modified' => array('vname' => 'LBL_DATE_MODIFIED'),
                'assigned_user_name' => array('vname' => 'LBL_ASSIGNED_TO_NAME'),
                'image_field' => array('vname' => 'LBL_IMAGE'),
                'query_only_field' => array('vname' => 'LBL_QUERY', 'query_only' => true),
                'edit_button' => array('widget_class' => 'SubPanelEditButton'),
            ),
            array(
                'name' => array('type' => 'name'),
                'status' => array('type' => 'enum'),
                'description' => array('type' => 'text'),
                'date_modified' => array('type' => 'datetime'),
                'assigned_user_name' => array('type' => 'relate'),
                'image_field' => array('type' => 'image'),
                'query_only_field' => array('type' => 'varchar'),
            )
        );

        $details = $this->call('buildDetails', array(
            $row,
            $panel,
            array(),
            array(),
            'Ana',        // title  -> not repeated
            'Juan',       // subtitle -> not repeated
            '10/01/2026', // date -> not repeated
        ));

        $values = array();
        foreach ($details as $detail) {
            $values[] = $detail['value'];
            $this->assertNotSame('', $detail['label'], 'Every tooltip row needs a label');
        }
        $this->assertSame(array('Active', 'Line one'), $values);
    }

    public function testBuildDetailsDropsDuplicatedValues()
    {
        $row = new stdClass();
        $row->status = 'Active';
        $row->description = 'Active';
        $panel = $this->fakePanel(
            array(
                'status' => array('vname' => 'LBL_STATUS'),
                'description' => array('vname' => 'LBL_DESCRIPTION'),
            ),
            array(
                'status' => array('type' => 'enum'),
                'description' => array('type' => 'text'),
            )
        );

        $details = $this->call('buildDetails', array($row, $panel, array(), array(), '', '', ''));
        $this->assertCount(1, $details);
        $this->assertSame('Active', $details[0]['value']);
    }

    public function testFieldLabelUntranslatedVnameFallsBackToVardefs()
    {
        $row = new stdClass();
        $row->field_defs = array('date_start' => array('vname' => 'LBL_START_DATE'));
        // The list_fields label is not shipped in the module language file
        // (LBL_LIST_DATE): the vardefs label is used before the raw name.
        $label = $this->call(
            'fieldLabel',
            array('date_start', array('vname' => 'LBL_LIST_DATE'), 'Tasks', null, $row)
        );
        $this->assertNotSame('date_start', $label);
        $this->assertNotSame('LBL_LIST_DATE', $label);
        $this->assertNotSame('LBL_START_DATE', $label);
    }

    public function testBuildDetailsSkipsUsageQueryOnlyColumns()
    {
        $row = new stdClass();
        $row->status = 'Planned';
        $row->contact_id = 'abc-123';
        // Union helper columns are declared with usage => query_only
        $panel = $this->fakePanel(
            array(
                'status' => array('vname' => 'LBL_STATUS'),
                'contact_id' => array('usage' => 'query_only'),
            ),
            array('status' => array('type' => 'enum'))
        );

        $details = $this->call('buildDetails', array($row, $panel, array(), array(), '', '', ''));
        $this->assertCount(1, $details);
        $this->assertSame('status', $details[0]['label'] === 'status' ? 'status' : $details[0]['label']);
        $this->assertSame('Planned', $details[0]['value']);
    }

    public function testBuildDetailsSkipsIconAndUnionHelperColumns()
    {
        $row = new stdClass();
        $row->status = 'Planned';
        $row->object_image = 'icon';
        $row->contact_id = 'abc';
        $row->contact_name_owner = '1';
        $row->assigned_user_owner = '1';
        $row->reply_to_status = 'sent';
        // SubPanelIcon widget (module image of every subpanel) and UNION helper
        // columns are never tooltip fields, even without usage => query_only
        $panel = $this->fakePanel(
            array(
                'status' => array('vname' => 'LBL_STATUS'),
                'object_image' => array('vname' => 'LBL_OBJECT_IMAGE', 'widget_class' => 'SubPanelIcon'),
                'contact_id' => array(),
                'contact_name_owner' => array(),
                'assigned_user_owner' => array(),
                'reply_to_status' => array(),
            ),
            array('status' => array('type' => 'enum'), 'object_image' => array('type' => 'image'))
        );

        $details = $this->call('buildDetails', array($row, $panel, array(), array(), '', '', ''));
        $this->assertCount(1, $details);
        $this->assertSame('Planned', $details[0]['value']);
    }

    public function testMapRowTitleDecodesEscapedValues()
    {
        // get_list_view_data() HTML-escapes values (e.g. a name containing
        // quotes); the client renders them as text nodes so they must be
        // decoded exactly once before reaching the DOM.
        $row = new stdClass();
        $row->id = 'p1';
        $row->module_dir = 'stic_Financial_Products';
        $row->name = '-  &quot;&quot;&quot;';
        $panel = $this->fakePanel(
            array('name' => array('vname' => 'LBL_NAME', 'widget_class' => 'SubPanelDetailViewLink')),
            array('name' => array('type' => 'name'))
        );
        $record = $this->call('mapRow', array(
            $row,
            $panel,
            array('title_field' => '', 'subtitle_field' => '', 'date_field' => ''),
        ));
        $this->assertSame('- """', $record['title']);

        $row->name = 'clean values stay as-is';
        $record = $this->call('mapRow', array(
            $row,
            $panel,
            array('title_field' => '', 'subtitle_field' => '', 'date_field' => ''),
        ));
        $this->assertSame('clean values stay as-is', $record['title']);
    }

    public function testBuildDetailsWithoutPanel()
    {
        $this->assertSame(
            array(),
            $this->call('buildDetails', array(new stdClass(), null, array(), array(), '', '', ''))
        );
    }
}
