<?php

// SMS Helper class to send SMS messages through Seven provider.
// Info about API can be found at: https://docs.seven.io/en/rest-api/endpoints/sms

require_once('modules/stic_Settings/Utils.php');
class SevenSMSHelper {

    protected bool $active = false;
    protected ?string $apiKey = null;
    protected ?string $sender;

    public function __construct() {
        // global $sugar_config;

        // $this->setActive($sugar_config['seven_active'] ?? false);
        $active = stic_SettingsUtils::getSetting('seven_active');
        $this->setActive($active);
        
        // $this->setApiKey($sugar_config['seven_api_key'] ?? '');
        $apiKey = stic_SettingsUtils::getSetting('seven_api_key');
        $this->setApiKey($apiKey);
        
        
        // $this->setSender($sugar_config['seven_sender'] ?? '');
        $sender = stic_SettingsUtils::getSetting('seven_sender');
        $this->setSender($sender);
    }

    public function getActive(): bool {
        return $this->active;
    }

    public function setActive(string $active): self {
        $this->active = 'yes' === $active;
        return $this;
    }

    public function getApiKey(): ?string {
        return $this->apiKey;
    }

    public function setApiKey(string $apiKey): self {
        $this->apiKey = $apiKey;
        return $this;
    }

    public function getSender(): ?string {
        return $this->sender;
    }

    public function setSender($sender): self {
        $this->sender = $sender;
        return $this;
    }

    public function sendMessage(?string $from, string $text, string $to): array {
        // $to = preg_replace('~\D~', '', $to);
        $to = preg_replace('~[^\d,]~', '', $to);
        $result = $this->apiCall($from, $text, $to);
        $resultArray = json_decode($result, true);
        if ($resultArray['success'] != 100) {
            return array('code' => stic_Messages::ERROR_NOT_SENT, 'message' => $result);
        }
        if ($resultArray['messages'][0]['success']) {
            return array('code' => stic_Messages::OK);
        }
        else {
            return array('code' => stic_Messages::ERROR_NOT_SENT, 'message' => $result);
        }
    }

    public function apiCall(?string $from, string $text, string $to): string {
        if (!$this->getActive()) return [null, null];

        $curlOpts = [
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'Content-type: application/json',
                'SentWith: SuiteCRM',
                'X-Api-Key: ' . $this->getApiKey(),
            ],
            CURLOPT_POSTFIELDS => json_encode(compact('from', 'text', 'to')),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 7500,
        ];
        $curl = curl_init('https://gateway.seven.io/api/sms');
        curl_setopt_array($curl, $curlOpts);
        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }


// TODOEPS: Eliminar codi comentat    
// function sevenReplaceEmailVariables($screenText, $templateId)
// public function sevenReplaceEmailVariables($screenText, $relatedId)
// public function sevenReplaceEmailVariables($screenText, $bean)
// {
//     // request validation before replace bean variables
//         $request = $_REQUEST;

//         $macro_nv = array();

//         // $focusName = $request['parent_type'];
//         $focusName = $bean->module_name;
//         // $focus = BeanFactory::getBean($focusName, $request['parent_id']);
//         // $focus = BeanFactory::getBean($focusName, $relatedId);
//         $focus = $bean;

//         /**
//          * @var EmailTemplate $emailTemplate
//          */
//         $emailTemplate = BeanFactory::newBean('EmailTemplates');
//         $templateData = $emailTemplate->parse_email_template(
//             array(
//                 'subject' => '',
//                 'body_html' => $screenText,
//                 'body' => $screenText,
//             ),
//             $focusName,
//             $focus,
//             $macro_nv
//         );

//     return $templateData['body'];
// }

// public function seven_parse_template(SugarBean $bean, &$template, $object_override = array())
// {
//     global $sugar_config;

//     require_once 'modules/AOW_Actions/actions/templateParser.php';
//     require_once 'modules/AOW_WorkFlow/aow_utils.php';

//     $object_arr[$bean->module_dir] = $bean->id;

//     foreach ($bean->field_defs as $bean_arr) {
//         if ($bean_arr['type'] == 'relate') {
//             if (isset($bean_arr['module']) &&  $bean_arr['module'] != '' && isset($bean_arr['id_name']) &&  $bean_arr['id_name'] != '' && $bean_arr['module'] != 'EmailAddress') {
//                 $idName = $bean_arr['id_name'];
//                 if (isset($bean->field_defs[$idName]) && $bean->field_defs[$idName]['source'] != 'non-db') {
//                     if (!isset($object_arr[$bean_arr['module']])) {
//                         $object_arr[$bean_arr['module']] = $bean->$idName;
//                     }
//                 }
//             }
//         } else {
//             if ($bean_arr['type'] == 'link') {
//                 if (!isset($bean_arr['module']) || $bean_arr['module'] == '') {
//                     $bean_arr['module'] = getRelatedModule($bean->module_dir, $bean_arr['name']);
//                 }
//                 if (isset($bean_arr['module']) &&  $bean_arr['module'] != ''&& !isset($object_arr[$bean_arr['module']])&& $bean_arr['module'] != 'EmailAddress') {
//                     $linkedBeans = $bean->get_linked_beans($bean_arr['name'], $bean_arr['module'], array(), 0, 1);
//                     if ($linkedBeans) {
//                         $linkedBean = $linkedBeans[0];
//                         if (!isset($object_arr[$linkedBean->module_dir])) {
//                             $object_arr[$linkedBean->module_dir] = $linkedBean->id;
//                         }
//                     }
//                 }
//             }
//         }
//     }

//     $object_arr['Users'] = is_a($bean, 'User') ? $bean->id : $bean->assigned_user_id;

//     $object_arr = array_merge($object_arr, $object_override);

//     $parsedSiteUrl = parse_url($sugar_config['site_url']);
//     $host = $parsedSiteUrl['host'];
//     if (!isset($parsedSiteUrl['port'])) {
//         $parsedSiteUrl['port'] = 80;
//     }

//     $port		= ($parsedSiteUrl['port'] != 80) ? ":".$parsedSiteUrl['port'] : '';
//     $path		= !empty($parsedSiteUrl['path']) ? $parsedSiteUrl['path'] : "";
//     $cleanUrl	= "{$parsedSiteUrl['scheme']}://{$host}{$port}{$path}";

//     $url =  $cleanUrl."/index.php?module={$bean->module_dir}&action=DetailView&record={$bean->id}";

//     // $template->subject = str_replace("\$contact_user", "\$user", $template->subject);
//     // $template->body_html = str_replace("\$contact_user", "\$user", $template->body_html);
//     $template->body = str_replace("\$contact_user", "\$user", $template->body);
//     // $template->subject = aowTemplateParser::parse_template($template->subject, $object_arr);
//     // $template->body_html = aowTemplateParser::parse_template($template->body_html, $object_arr);
//     // $template->body_html = str_replace("\$url", $url, $template->body_html);
//     // $template->body_html = str_replace('$sugarurl', $sugar_config['site_url'], $template->body_html);
//     $template->body = aowTemplateParser::parse_template($template->body, $object_arr);
//     $template->body = str_replace("\$url", $url, $template->body);
//     $template->body = str_replace('$sugarurl', $sugar_config['site_url'], $template->body);
// }

}
