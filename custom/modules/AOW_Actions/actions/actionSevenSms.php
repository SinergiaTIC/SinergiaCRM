<?php /** @noinspection PhpUnused */

require_once __DIR__ . '/../../../../modules/AOW_Actions/actions/actionBase.php';

class actionSevenSms extends actionBase {
    public function __construct($id = '') {
        parent::__construct($id);
    }

    public function loadJS() {
        return [];
    }

    public function edit_display($line, SugarBean $bean = null, $params = []) {

        // STIC-Custom 20240404 EPS
        $email_templates_arr = get_bean_select_array(true, 'EmailTemplate', 'name', '', 'name');

        $hidden = "style='visibility: hidden;'";
        if ($params['email_template'] != '') {
            $hidden = "";
        }

        global $sugar_config;
        $from = empty($params['from']) ? $sugar_config['seven_sender'] : $params['from'];
        // END STIC-Custom 20240404 EPS

        $foreignId = $params['foreign_id'] ?? '';
        $label = $params['label'] ?? '';
        $flash = isset($params['flash']) ? 'checked=checked' : '';

        return "<label for='seven_from'>" . translate('LBL_SEVENSMS_FROM', 'AOW_Actions') . "</label>
            <input maxlength='16' id='seven_from' name='aow_actions_param[" . $line . "][from]' placeholder='"
            // STIC-Custom 20240404 EPS
            // . translate('LBL_SEVENSMS_FROM', 'AOW_Actions') . "' value=' " . $params['from']
            . translate('LBL_SEVENSMS_FROM', 'AOW_Actions') . "' value='" . $from
            // END STIC-Custom 20240404 EPS
            . "'><br/>"
            . "<label for='seven_label'>" . translate('LBL_SEVENSMS_LABEL', 'AOW_Actions') . "</label>
                <input maxlength='100' id='seven_label' name='aow_actions_param[" . $line . "][label]' placeholder='"
            . translate('LBL_SEVENSMS_LABEL', 'AOW_Actions') . "' value=' " . $label
            . "'><br/>"
            . "<label for='seven_foreign_id'>" . translate('LBL_SEVENSMS_FOREIGN_ID', 'AOW_Actions') . "</label>
                <input maxlength='64' id='seven_foreign_id' name='aow_actions_param[" . $line . "][foreign_id]' placeholder='"
            . translate('LBL_SEVENSMS_FOREIGN_ID', 'AOW_Actions') . "' value=' " . $foreignId
            . "'><br/>"
            . "<label for='seven_flash'>" . translate('LBL_SEVENSMS_FLASH', 'AOW_Actions') . "</label>
                <input type='checkbox' id='seven_flash' name='aow_actions_param[" . $line . "][flash]' " . $flash
            . '><br/>'
            // STIC-Custom 20240404 EPS
            . "<label for='seven_template'>" . translate('LBL_EMAIL_TEMPLATE', 'AOW_Actions') . "</label> 
                <div>
                 <select name='aow_actions_param[" . $line . "][email_template]' id='aow_actions_param_email_template" . $line . "' onchange='show_edit_template_link(this," . $line . ");' >" . get_select_options_with_id($email_templates_arr, $params['email_template']) . "</select>"
                 . "<br> <a href='javascript:open_email_template_form(" . $line . ")' >".translate('LBL_CREATE_EMAIL_TEMPLATE', 'AOW_Actions')."</a>"
                 . "&nbsp;<span name='edit_template' id='aow_actions_edit_template_link" . $line . "' $hidden><a href='javascript:edit_email_template_form(".$line.")' >".translate('LBL_EDIT_EMAIL_TEMPLATE', 'AOW_Actions')."</a></span> <br>
                 </div>";
                //  . "<label for='seven_text'>" . translate('LBL_SEVENSMS_TEXT', 'AOW_Actions') . "<span class='required'>*</span></label>"
                // . "<textarea cols='110' id='seven_text' name='aow_actions_param[" . $line . "][text]' placeholder='"
                // . translate('LBL_SEVENSMS_TEXT', 'AOW_Actions') . "' rows='5'>" . $params['text']
                // . '</textarea>';
            // END STIC-Custom 20240404 EPS
    }

    protected function getPhoneFromParams(SugarBean $bean, array $params): ?string {
        switch ($bean->module_name) {
            case 'Accounts':
                /** @var Account $bean */

                return $bean->phone_alternate;
            case 'Contacts':
                /** @var Contact $bean */

                return $bean->phone_mobile;
            case 'Employees':
                /** @var Employee $bean */

                return $bean->phone_mobile;
            case 'Leads':
                /** @var Lead $bean */

                return $bean->phone_mobile;
            case 'Users':
                /** @var User $bean */

                return $bean->phone_mobile;
            default:
                return null;
        }
    }

    /**
     * Return true on success otherwise false.
     * Use actionSevenSms::getLastMessagesSuccess() and actionSevenSms::getLastMessagesFailed()
     * methods to get last SMS sending status
     * @param SugarBean $bean
     * @param array $params
     * @param bool $in_save
     * @return boolean
     */
    public function run_action(SugarBean $bean, $params = [], $in_save = false) {
        global $sugar_config;

        // STIC-Custom EPS 20240404
        $emailTemp = BeanFactory::newBean('EmailTemplates');
        $emailTemp->retrieve($params['email_template']);
        
        if ($emailTemp->id == '') {
            return false;
        }

        $this->parse_template($bean, $emailTemp);

        $text = $emailTemp->body;
        // END STIC-Custom



        $to = $this->getPhoneFromParams($bean, $params);
        if (!$to) return false;

        $apiKey = $sugar_config['seven_api_key'];
        if (!$apiKey) return false;

        // STIC-Custom EPS 20240404
        // $text = $params['text'];
        // END STIC-Custom EPS 20240404
        $from = $params['from'];
        $label = $params['label'];
        $foreign_id = $params['foreign_id'];
        $flash = 'yes' === $params['flash'];
        $json = compact('flash', 'foreign_id', 'from', 'label', 'text', 'to');

        $ch = curl_init('https://gateway.seven.io/api/sms');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($json));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Content-type: application/json',
            'X-Api-Key: ' . $apiKey,
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        $result = json_decode($result);
        curl_close($ch);

        // STIC-Custom EPS 20240404
        // return 100 === $result->success;
        return 100 == $result->success;
        // END STIC-Custom EPS 20240404
    }

    // STIC-Custom EPS 20240404
    public function parse_template(SugarBean $bean, &$template, $object_override = array())
    {
        global $sugar_config;

        require_once 'modules/AOW_Actions/actions/templateParser.php';

        $object_arr[$bean->module_dir] = $bean->id;

        foreach ($bean->field_defs as $bean_arr) {
            if ($bean_arr['type'] == 'relate') {
                if (isset($bean_arr['module']) &&  $bean_arr['module'] != '' && isset($bean_arr['id_name']) &&  $bean_arr['id_name'] != '' && $bean_arr['module'] != 'EmailAddress') {
                    $idName = $bean_arr['id_name'];
                    if (isset($bean->field_defs[$idName]) && $bean->field_defs[$idName]['source'] != 'non-db') {
                        if (!isset($object_arr[$bean_arr['module']])) {
                            $object_arr[$bean_arr['module']] = $bean->$idName;
                        }
                    }
                }
            } else {
                if ($bean_arr['type'] == 'link') {
                    if (!isset($bean_arr['module']) || $bean_arr['module'] == '') {
                        $bean_arr['module'] = getRelatedModule($bean->module_dir, $bean_arr['name']);
                    }
                    if (isset($bean_arr['module']) &&  $bean_arr['module'] != ''&& !isset($object_arr[$bean_arr['module']])&& $bean_arr['module'] != 'EmailAddress') {
                        $linkedBeans = $bean->get_linked_beans($bean_arr['name'], $bean_arr['module'], array(), 0, 1);
                        if ($linkedBeans) {
                            $linkedBean = $linkedBeans[0];
                            if (!isset($object_arr[$linkedBean->module_dir])) {
                                $object_arr[$linkedBean->module_dir] = $linkedBean->id;
                            }
                        }
                    }
                }
            }
        }

        $object_arr['Users'] = is_a($bean, 'User') ? $bean->id : $bean->assigned_user_id;

        $object_arr = array_merge($object_arr, $object_override);

        $parsedSiteUrl = parse_url($sugar_config['site_url']);
        $host = $parsedSiteUrl['host'];
        if (!isset($parsedSiteUrl['port'])) {
            $parsedSiteUrl['port'] = 80;
        }

        $port		= ($parsedSiteUrl['port'] != 80) ? ":".$parsedSiteUrl['port'] : '';
        $path		= !empty($parsedSiteUrl['path']) ? $parsedSiteUrl['path'] : "";
        $cleanUrl	= "{$parsedSiteUrl['scheme']}://{$host}{$port}{$path}";

        $url =  $cleanUrl."/index.php?module={$bean->module_dir}&action=DetailView&record={$bean->id}";

        // $template->subject = str_replace("\$contact_user", "\$user", $template->subject);
        // $template->body_html = str_replace("\$contact_user", "\$user", $template->body_html);
        $template->body = str_replace("\$contact_user", "\$user", $template->body);
        // $template->subject = aowTemplateParser::parse_template($template->subject, $object_arr);
        // $template->body_html = aowTemplateParser::parse_template($template->body_html, $object_arr);
        // $template->body_html = str_replace("\$url", $url, $template->body_html);
        // $template->body_html = str_replace('$sugarurl', $sugar_config['site_url'], $template->body_html);
        $template->body = aowTemplateParser::parse_template($template->body, $object_arr);
        $template->body = str_replace("\$url", $url, $template->body);
        $template->body = str_replace('$sugarurl', $sugar_config['site_url'], $template->body);
    }
    // END STIC-Custom

}
