<?php
//prevents directly accessing this file from a web browser
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
$hook_array['before_save'][] = array(100, 'before save', 'modules/stic_Sessions/LogicHooksCode.php', 'stic_SessionsLogicHooks', 'before_save');
$hook_array['after_save'][] = array(100, 'after save', 'modules/stic_Sessions/LogicHooksCode.php', 'stic_SessionsLogicHooks', 'after_save');
$hook_array['after_relationship_add'][] = array(100, 'after_relationship_add', 'modules/stic_Sessions/LogicHooksCode.php', 'stic_SessionsLogicHooks', 'manage_relationships');
$hook_array['after_relationship_delete'][] = array(100, 'after_relationship_delete', 'modules/stic_Sessions/LogicHooksCode.php', 'stic_SessionsLogicHooks', 'manage_relationships');
