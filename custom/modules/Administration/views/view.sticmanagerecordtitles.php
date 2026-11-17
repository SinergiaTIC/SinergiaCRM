<?php
/**
 * This file is part of SinergiaCRM.
 * SinergiaCRM is a work developed by SinergiaTIC Association, based on SuiteCRM.
 * Copyright (C) 2013 - 2026 SinergiaTIC Association
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation.
 */

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class ViewSticManageRecordTitles extends SugarView
{
    protected function _getModuleTitleParams($browserTitle = false)
    {
        global $mod_strings;

        return array(
            "<a href='index.php?module=Administration&action=index'>" . $mod_strings['LBL_MODULE_NAME'] . "</a>",
            $mod_strings['LBL_STIC_RECORD_TITLES_LINK_TITLE'],
        );
    }

    public function preDisplay()
    {
        global $current_user;

        if (!is_admin($current_user)) {
            sugar_die('Unauthorized access to administration.');
        }
    }

    public function display()
    {
        global $mod_strings, $app_strings, $sugar_config, $app_list_strings, $beanList, $beanFiles;

        $this->ss->assign('MOD', $mod_strings);
        $this->ss->assign('APP', $app_strings);
        $this->ss->assign('title', $this->getModuleTitle(false));

        $mapping = isset($sugar_config['detailview_header_templates']) && is_array($sugar_config['detailview_header_templates'])
            ? $sugar_config['detailview_header_templates']
            : array();

        $modules = array();
        if (is_array($app_list_strings['moduleList'])) {
            foreach ($app_list_strings['moduleList'] as $moduleName => $moduleLabel) {
                if (empty($beanList[$moduleName]) || empty($beanFiles[$beanList[$moduleName]])) {
                    continue;
                }
                require_once $beanFiles[$beanList[$moduleName]];
                $bean = BeanFactory::newBean($moduleName);
                if (!$bean || !isset($bean->field_defs['name'])) {
                    continue;
                }
                $modules[$moduleName] = $moduleLabel;
            }
        }
        asort($modules);
        $this->ss->assign('MODULES', $modules);
        $this->ss->assign('MAPPING', $mapping);

        $templateBean = BeanFactory::newBean('EmailTemplates');
        $templates = $templateBean->get_full_list('name', "email_templates.type = 'header_title' AND email_templates.deleted = 0");
        $templateOptions = array();
        if (is_array($templates)) {
            foreach ($templates as $template) {
                $templateOptions[$template->id] = $template->name;
            }
        }
        $this->ss->assign('TEMPLATES', $templateOptions);

        $this->ss->display('custom/modules/Administration/templates/SticManageRecordTitles.tpl');
    }
}
