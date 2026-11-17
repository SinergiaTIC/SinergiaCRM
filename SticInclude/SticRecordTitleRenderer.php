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

require_once 'modules/EmailTemplates/EmailTemplate.php';

/**
 * Renders the DetailView header title from an EmailTemplates record of type
 * "header_title", using SuiteCRM's native template parser.
 */
class SticRecordTitleRenderer
{
    /** @var array Cached EmailTemplates beans by id. */
    protected static $templates = array();

    /**
     * Render the configured header title for a module/bean.
     *
     * @param string     $moduleName
     * @param SugarBean  $bean
     * @return string|null Parsed HTML, or null when no template is configured.
     */
    public static function render($moduleName, SugarBean $bean)
    {
        global $sugar_config;

        $mapping = isset($sugar_config['detailview_header_templates']) && is_array($sugar_config['detailview_header_templates'])
            ? $sugar_config['detailview_header_templates']
            : array();

        if (empty($mapping[$moduleName])) {
            return null;
        }

        $template = self::getTemplate($mapping[$moduleName]);
        if (!$template || empty($template->body_html)) {
            return null;
        }

        $html = html_entity_decode((string) $template->body_html, ENT_QUOTES, 'UTF-8');

        $parser = new EmailTemplate();
        // Use the native SuiteCRM parser with the bean already loaded in the view.
        return $parser->parse_template_bean($html, $moduleName, $bean);
    }

    /**
     * Logic hook (after_ui_frame) that injects the rendered title into the
     * DetailView header. Runs before the page is painted, so no flicker.
     *
     * @param string $event
     * @param SugarBean $bean
     * @param array $arguments
     */
    public static function renderHeaderTitleJs($event, $bean, $arguments)
    {
        if (($_REQUEST['action'] ?? '') !== 'DetailView') {
            return;
        }

        $module = $_REQUEST['module'] ?? '';
        $record = $_REQUEST['record'] ?? '';
        if ($module === '' || $record === '') {
            return;
        }

        $detailBean = BeanFactory::getBean($module, $record);
        if (!$detailBean) {
            return;
        }

        $html = self::render($module, $detailBean);
        if ($html === null) {
            return;
        }

        echo '<script type="text/javascript">(function(){'
            . 'var el=document.querySelector(".module-title-text");'
            . 'if(el){el.innerHTML=' . json_encode($html, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP) . ';}'
            . '})();</script>';
    }

    /**
     * Retrieve and validate an EmailTemplates bean of type header_title.
     *
     * @param string $templateId
     * @return EmailTemplate|null
     */
    protected static function getTemplate($templateId)
    {
        if (isset(self::$templates[$templateId])) {
            return self::$templates[$templateId];
        }

        $template = BeanFactory::getBean('EmailTemplates', $templateId);
        if (!$template || $template->deleted || $template->type !== 'header_title') {
            $template = null;
        }

        self::$templates[$templateId] = $template;
        return $template;
    }
}
