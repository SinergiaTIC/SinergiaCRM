<?php
//prevents directly accessing this file from a web browser
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
$hook_array['before_save'][] = array(100, 'before_save', 'modules/stic_Assessments/LogicHooksCode.php', 'stic_AssessmentsLogicHooks', 'before_save');

