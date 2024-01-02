<?php
//prevents directly accessing this file from a web browser
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
$hook_array['after_save'][] = array(100, 'after_save', 'custom/modules/Surveys/SticLogicHooksCode.php', 'SurveysLogicHooks', 'after_save');


