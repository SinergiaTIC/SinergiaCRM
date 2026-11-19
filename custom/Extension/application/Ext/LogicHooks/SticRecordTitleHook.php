<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
$hook_array['after_ui_frame'][] = array(
    1,
    'Render configured header title in DetailView',
    'SticInclude/SticRecordTitleRenderer.php',
    'SticRecordTitleRenderer',
    'renderHeaderTitleJs',
);
