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

$layout_defs['Meetings']['subpanel_setup']['contacts']['override_subpanel_name'] = 'SticDefault';
$layout_defs['Meetings']['subpanel_setup']['leads']['override_subpanel_name'] = 'SticDefault';

// Subpanels default sorting
$layout_defs['Meetings']['subpanel_setup']['contacts']['sort_order'] = 'asc';
$layout_defs['Meetings']['subpanel_setup']['contacts']['sort_by'] = 'last_name, first_name';
$layout_defs['Meetings']['subpanel_setup']['leads']['sort_order'] = 'asc';
$layout_defs['Meetings']['subpanel_setup']['leads']['sort_by'] = 'last_name, first_name';
$layout_defs['Meetings']['subpanel_setup']['users']['sort_order'] = 'asc';
$layout_defs['Meetings']['subpanel_setup']['users']['sort_by'] = 'name';
$layout_defs['Meetings']['subpanel_setup']['history']['sort_order'] = 'desc';
$layout_defs['Meetings']['subpanel_setup']['history']['sort_by'] = 'date_modified';

// Documents subpanel
$layout_defs["Meetings"]["subpanel_setup"]['stic_meetings_documents'] = array(
    'order' => 50,
    'module' => 'Documents',
    'subpanel_name' => 'SticDefault',
    'sort_order' => 'asc',
    'sort_by' => 'id',
    'title_key' => 'LBL_STIC_MEETINGS_DOCUMENTS_FROM_DOCUMENTS_TITLE',
    'get_subpanel_data' => 'stic_meetings_documents',
    'top_buttons' => array(
        0 => array(
            'widget_class' => 'SubPanelTopButtonQuickCreate',
        ),
        1 => array(
            'widget_class' => 'SubPanelTopSelectButton',
            'mode' => 'MultiSelect',
        ),
    ),
);

// Tasks subpanel
$layout_defs["Meetings"]["subpanel_setup"]['meetings_activities_tasks'] = array(
    'order' => 60,
    'module' => 'Tasks',
    'subpanel_name' => 'ForActivities',
    'sort_order' => 'asc',
    'sort_by' => 'date_due',
    'title_key' => 'LBL_MEETINGS_ACTIVITIES_TASKS_FROM_TASKS_TITLE',
    'get_subpanel_data' => 'meetings_activities_tasks',
    'top_buttons' => array(
        0 => array(
            'widget_class' => 'SubPanelTopCreateTaskButton',
        ),
    ),
);
