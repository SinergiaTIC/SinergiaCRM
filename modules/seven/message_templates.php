<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
if (!is_admin($current_user) && !defined('configurator_util')) sugar_die('Admin Only');

$id = null;

switch($_POST['seven_action']) {
    case 'save_template':
        $sevenTemplate = new seven_templates;
        $sevenTemplate->id = $_POST['seven_template_id'];
        $sevenTemplate->label = $_POST['seven_template_label'];
        $sevenTemplate->text = $_POST['seven_template_text'];
        $sevenTemplate->type = $_POST['seven_module_type'];
        $id = $sevenTemplate->save();
        break;
    case 'delete_template':
        $bean = BeanFactory::getBean('seven_templates');
        $bean->mark_deleted($_POST['seven_template_id']);
        $id = $bean->save();
        break;
}

/*header('Location: /index.php?module=seven&action=' . $_POST['seven_module_type'], false);
exit();*/
//return compact('id');
