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

// SinergiaCRM admin section
$mod_strings['LBL_SINERGIACRM_TAB_TITLE'] = 'SinergiaCRM';
$mod_strings['LBL_SINERGIACRM_TAB_DESCRIPTION'] = 'Admin settings for SinergiaCRM';

// SinergiaCRM admin section items (titles and short descriptions)
$mod_strings['LBL_STIC_VALIDATION_ACTIONS_LINK_TITLE'] = 'Validation actions';
$mod_strings['LBL_STIC_VALIDATION_ACTIONS_DESCRIPTION'] = 'Manage validation actions and link them to scheduled jobs.';

$mod_strings['LBL_STIC_VALIDATION_RESULTS_LINK_TITLE'] = 'Validation results';
$mod_strings['LBL_STIC_VALIDATION_RESULTS_DESCRIPTION'] = 'Manage and review the validation actions results.';

$mod_strings['LBL_STIC_CUSTOM_VIEWS_LINK_TITLE'] = 'Custom views';
$mod_strings['LBL_STIC_CUSTOM_VIEWS_DESCRIPTION'] = 'Module views conditional customization.';

$mod_strings['LBL_SINERGIACRM_TAB_STIC_SETTINGS_LINK_TITLE'] = 'Settings';
$mod_strings['LBL_SINERGIACRM_TAB_STIC_SETTINGS_DESCRIPTION'] = 'Settings management for SinergiaCRM.';

$mod_strings['LBL_STIC_TEST_DATA_LINK_TITLE'] = 'Test data';
$mod_strings['LBL_STIC_TEST_DATA_DESCRIPTION'] = 'Load or delete test data.';

$mod_strings['LBL_STIC_MANAGE_SDA_ACTIONS_LINK_TITLE'] = 'Sinergia Data Analytics';
$mod_strings['LBL_STIC_MANAGE_SDA_ACTIONS_DESCRIPTION'] = 'Rebuild the integration with Sinergia Data Analytics.';

// Test data
$mod_strings['LBL_STIC_TEST_DATA_NOTICE'] = "<strong>Important:</strong> Loaded sample records should not be used to store real data, since they can be deleted in the future.";
$mod_strings['LBL_STIC_TEST_DATA_INSERT_LINK_TITLE'] = 'Load test dataset';
$mod_strings['LBL_STIC_TEST_DATA_INSERT_DESCRIPTION'] = 'Load a sample dataset in order to help in SinergiaCRM learning process. This data might be freely deleted at any time.';
$mod_strings['LBL_STIC_TEST_DATA_INSERT_SUCCESS'] = 'Test dataset succesfully loaded.';
$mod_strings['LBL_STIC_TEST_DATA_INSERT_ERROR'] = 'Errors have occurred while loading the test dataset. Please review the <a target="_blank" href="index.php?action=LogView&module=Configurator&doaction=all&filter=action_insertSticData">log</a>.';
$mod_strings['LBL_STIC_TEST_DATA_REMOVE_LINK_TITLE'] = 'Delete test dataset';
$mod_strings['LBL_STIC_TEST_DATA_REMOVE_DESCRIPTION'] = 'Delete the sample dataset previously loaded.';
$mod_strings['LBL_STIC_TEST_DATA_REMOVE_SUCCESS'] = 'Test dataset succesfully deleted.';
$mod_strings['LBL_STIC_TEST_DATA_REMOVE_ERROR'] = 'Errors have occurred while deleting the test dataset. Please review the <a target="_blank" href="index.php?action=LogView&module=Configurator&doaction=all&filter=action_insertSticData">log</a>.';

// SinergiaDA
$mod_strings['LBL_STIC_RUN_SDA_ACTIONS_LINK_TITLE'] = 'Rebuild now';
$mod_strings['LBL_STIC_RUN_SDA_ACTIONS_DESCRIPTION'] = 'Rebuild and repair the views and other necessary elements for integration with Sinergia Data Analytics. Add new fields if needed.';
$mod_strings['LBL_STIC_GO_TO_SDA_LINK_TITLE'] = 'Go to Sinergia Data Analytics';
$mod_strings['LBL_STIC_RUN_SDA_SUCCESS_MSG'] = 'Rebuild of Sinergia Data Analytics has been successfully completed.';
$mod_strings['LBL_STIC_RUN_SDA_ERROR_MSG'] = 'The following errors have been found in the rebuild of Sinergia Data Analytics';

// Advanced main menu
$mod_strings['LBL_CONFIGURE_MAIN_MENU'] = 'Main menu';
$mod_strings['LBL_CONFIGURE_MAIN_MENU_DESC'] = 'Set main menu structure and content';
$mod_strings['LBL_STIC_MENU_CONFIGURE_TITLE'] = 'Main menu settings';
$mod_strings['LBL_STIC_MENU_ENABLED_NOT_INCLUDED'] = 'Enabled modules not included in the menu';
$mod_strings['LBL_STIC_MENU_ENABLED_INCLUDED'] = 'Menu Configuration';
$mod_strings['LBL_STIC_MENU_SAVE'] = 'Save and apply';
$mod_strings['LBL_STIC_MENU_RESTORE'] = 'Restore';
$mod_strings['LBL_STIC_MENU_RESTORE_CONFIRM'] = 'Restore the default SinergiaCRM menu?';
$mod_strings['LBL_STIC_MENU_INFO'] = 'To include a module in the main menu it must be <a href="index.php?module=Administration&action=ConfigureTabs" target="_blank">enabled</a>. If it already is, you can drag it from the excluded modules area (right) and drop it over the menu node where you want it to appear (left). To hide it, do the opposite. To resort the nodes, drag them to the desired position. With the right mouse button you can show the item context menu, which allows you to create new nodes (which can point to any URL), duplicate them, rename them or delete them.';
$mod_strings['LBL_STIC_MENU_ICONS'] = 'Show module icons';
$mod_strings['LBL_STIC_MENU_ALL'] = 'Show ALL option';
$mod_strings['LBL_STIC_MENU_CHANGE_TO_LEGACY'] = 'Switch to legacy menu';
$mod_strings['LBL_STIC_MENU_CHANGE_TO_LEGACY_CONFIRM'] = 'Do you want to switch to the legacy menu? This will restore the settings it had when the advanced menu was activated. You can re-enable the advanced menu later.';
$mod_strings['LBL_STIC_MENU_COMMAND_CREATE'] = 'Create';
$mod_strings['LBL_STIC_MENU_COMMAND_CREATE_DEFAULT'] = 'New node';
$mod_strings['LBL_STIC_MENU_COMMAND_RENAME'] = 'Rename';
$mod_strings['LBL_STIC_MENU_COMMAND_EDITURL'] = 'Edit URL';
$mod_strings['LBL_STIC_MENU_COMMAND_EDITURL_PROMPT'] = 'Enter the URL';
$mod_strings['LBL_STIC_MENU_COMMAND_EDITURL_PROMPT_VALIDATE'] = 'Please enter a valid URL';
$mod_strings['LBL_STIC_MENU_COMMAND_REMOVE'] = 'Remove';
$mod_strings['LBL_STIC_MENU_COMMAND_DUPLICATE'] = 'Duplicate';
$mod_strings['LBL_STIC_MENU_COMMAND_NEW_MAIN_NODE'] = 'New main node';
$mod_strings['LBL_STIC_MENU_COMMAND_EXPAND'] = 'Expand tree';
$mod_strings['LBL_STIC_MENU_COMMAND_COLLAPSE'] = 'Collapse tree';

// SuiteCRM modified strings
$mod_strings['LBL_CONFIGURE_GROUP_TABS_DESC'] = 'Configure how subpanels are grouped in the detail views';
$mod_strings['LBL_CONFIGURE_GROUP_TABS'] = 'Subpanel grouping';

// Other strings
$mod_strings['LBL_ADMIN_ACTIONS'] = 'Admin actions';
$mod_strings['ERR_SYS_GEN_PWD_TPL_NOT_SELECTED'] = 'Set the email template that will be sent when the system generates the password of a new user.';
