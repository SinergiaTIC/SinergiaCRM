<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once 'modules/EmailTemplates/EmailTemplate.php';

class SticRecordTitleRenderer
{
    protected static $templates = array();

    public static function render($moduleName, SugarBean $bean)
    {
        global $sugar_config;
        $mapping = isset($sugar_config['detailview_header_templates']) && is_array($sugar_config['detailview_header_templates'])
            ? $sugar_config['detailview_header_templates'] : array();
        if (empty($mapping[$moduleName])) {
            return null;
        }
        $template = self::getTemplate($mapping[$moduleName]);
        if (!$template || empty($template->body_html)) {
            return null;
        }
        $html = html_entity_decode((string) $template->body_html, ENT_QUOTES, 'UTF-8');
        $parser = new EmailTemplate();
        $html = $parser->parse_template_bean($html, $moduleName, $bean);

        // Resolve the photo token after native parsing. The EmailTemplates parser
        // cannot resolve an image to a download URL, so handle it here.
        return self::resolvePhotoToken($html, $moduleName, $bean);
    }

    /**
     * Replace $stic_contact_photo with an <img> tag: the record's real photo when
     * present, otherwise a local fake-face avatar.
     */
    protected static function resolvePhotoToken($html, $moduleName, SugarBean $bean)
    {
        if (strpos($html, '$stic_contact_photo') === false) {
            return $html;
        }

        $photoUrl = self::getPhotoUrl($moduleName, $bean);
        $img = '<img src="' . htmlspecialchars($photoUrl, ENT_QUOTES, 'UTF-8') . '" '
            . 'alt="" style="width:72px;height:72px;border-radius:50%;object-fit:cover;'
            . 'flex:0 0 72px;vertical-align:middle;">';

        return str_replace('$stic_contact_photo', $img, $html);
    }

    /**
     * Build the URL for the record photo, falling back to the fake-face avatar.
     */
    protected static function getPhotoUrl($moduleName, SugarBean $bean)
    {
        global $sugar_config;

        $siteUrl = rtrim((string) ($sugar_config['site_url'] ?? ''), '/');
        $fake = $siteUrl . '/custom/themes/default/images/stic_fake_face.svg';

        $id = $bean->id ?? '';
        $photoName = $bean->photo ?? '';
        if ($id === '' || $photoName === '') {
            return $fake;
        }

        // Image fields store the upload file as upload://<id>_photo.
        $uploadPath = 'upload://' . $id . '_photo';
        if (!file_exists($uploadPath)) {
            return $fake;
        }

        return $siteUrl . '/index.php?entryPoint=download&id=' . rawurlencode($id . '_photo')
            . '&type=' . rawurlencode($moduleName) . '&t=' . rawurlencode((string) ($bean->date_modified ?? ''));
    }

    public function renderHeaderTitleJs($event, $arguments)
    {
        if (!empty($_REQUEST['to_pdf']) || !empty($_REQUEST['sugar_body_only'])) {
            return;
        }
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
        echo '<script type="text/javascript">(function(){var el=document.querySelector(".module-title-text");if(el){el.innerHTML='
            . json_encode($html, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP) . ';}})();</script>';
    }

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
