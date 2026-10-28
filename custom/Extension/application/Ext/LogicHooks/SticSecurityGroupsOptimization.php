<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

if (!isset($hook_array) || !is_array($hook_array)) {
    $hook_array = array();
}
if (!isset($hook_array['before_acl_query']) || !is_array($hook_array['before_acl_query'])) {
    $hook_array['before_acl_query'] = array();
}
$hook_array['before_acl_query'][] = array(
    1,
    'Optimize security group EXISTS subquery for list views',
    'custom/include/SticSecurityGroupsListViewOptimization.php',
    'Stic_SecurityGroupsListViewOptimization',
    'optimizeGroupWhere'
);