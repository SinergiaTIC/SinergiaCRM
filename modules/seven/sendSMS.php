<?php
require_once 'seven.php';

$sms = new seven;

$number = $_POST['number'] ?? '';
$id = $_POST['id'] ?? '';
$module = $_POST['module'] ?? '';

if (!empty($number)) {
    if (!empty($module) && !empty($id)) {
        $bean = BeanFactory::getBean($module);
        $sms->setRelation($bean->retrieve($id));
    }

    // STIC-Custom EPS 20240404
    $templateId = $_POST['template'];
    // if (!empty($templateId)) {
    //     $emailTemp = BeanFactory::newBean('EmailTemplates');
    //     $emailTemp->retrieve($templateId);
    //     seven_parse_template($bean, $emailTemp);
    //     $text = $emailTemp->body;
    //     $_POST['message'] = $text;
    // }
    // else {
    //     $text = $_POST['message'];
    // }
    $emailTemp = sevenReplaceEmailVariables($_POST['message'], $templateId);
    $text = $emailTemp->body;
    $_POST['message'] = $text;
    // END STIC-Custom

    $sms->setNumber($number);
    // STIC-Custom EPS 20240404
    // $res = $res = $sms->sendSMS();
    $res = $res = $sms->sendSMSwithText($text);
    // END STIC-Custom

    if (!$sms->isUserFriendlyResponses()) {
        echo json_encode($res);
        return;
    }

    $json = $res[0];
    $count = 0;
    $price = $json['total_price'];
    foreach ($json['messages'] as $message) {
        if (!$message['success']) continue;
        $count++;
    }

    $text = (new \SuiteCRM\LangText)
        ->getText('LBL_SEVEN_USER_FRIENDLY_RESPONSES_SMS', compact('count', 'price'), null, 'seven');

    echo json_encode([$text, $res[1]]);
}


// STIC-Custom EPS 20240404
function sevenReplaceEmailVariables($screenText, $templateId)
{
    // request validation before replace bean variables
        $request = $_REQUEST;

        $macro_nv = array();

        $focusName = $request['parent_type'];
        $focus = BeanFactory::getBean($focusName, $request['parent_id']);

        /**
         * @var EmailTemplate $emailTemplate
         */
        $emailTemplate = BeanFactory::getBean(
            'EmailTemplates',
            $templateId
        );
        $templateData = $emailTemplate->parse_email_template(
            array(
                'subject' => '',
                'body_html' => $screenText,
                'body' => $screenText,
            ),
            $focusName,
            $focus,
            $macro_nv
        );

    return $templateData['body'];
}





function seven_parse_template(SugarBean $bean, &$template, $object_override = array())
{
    global $sugar_config;

    require_once 'modules/AOW_Actions/actions/templateParser.php';
    require_once 'modules/AOW_WorkFlow/aow_utils.php';

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


