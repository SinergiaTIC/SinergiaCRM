<?php
//prevents directly accessing this file from a web browser
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
$hook_array['after_save'][] = array(100, 'after_save', 'modules/stic_Payment_Commitments/LogicHooksCode.php', 'stic_Payment_CommitmentsLogicHooks', 'after_save');
$hook_array['before_save'][] = array(100, 'before_save', 'modules/stic_Payment_Commitments/LogicHooksCode.php', 'stic_Payment_CommitmentsLogicHooks', 'before_save');

