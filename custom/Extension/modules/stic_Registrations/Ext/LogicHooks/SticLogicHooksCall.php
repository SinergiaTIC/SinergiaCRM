<?php
//prevents directly accessing this file from a web browser
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
$hook_array['after_save'][] = array(100, 'after_save', 'modules/stic_Registrations/LogicHooksCode.php', 'stic_RegistrationsLogicHooks', 'after_save');
$hook_array['before_save'][] = array(100, 'before_save', 'modules/stic_Registrations/LogicHooksCode.php', 'stic_RegistrationsLogicHooks', 'before_save');
$hook_array['after_relationship_add'][] = array(100, 'after_relationship_add', 'modules/stic_Registrations/LogicHooksCode.php', 'stic_RegistrationsLogicHooks', 'manage_relationships');
$hook_array['after_relationship_delete'][] = array(100, 'after_relationship_delete', 'modules/stic_Registrations/LogicHooksCode.php', 'stic_RegistrationsLogicHooks', 'manage_relationships');


