<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

function get_stic_messages($type) {
    $beanId = $_REQUEST['record'];
    $return_array['select'] = 'SELECT stic_messages.id ';
    $return_array['from'] = ' FROM stic_messages ';
    $return_array['where'] = " WHERE stic_messages.parent_id = '{$beanId}'";

    if (isset($type) && ! empty($type['return_as_array'])) {
        return $return_array;
    }

    return $return_array['select'] . $return_array['from'] . $return_array['where'];
}