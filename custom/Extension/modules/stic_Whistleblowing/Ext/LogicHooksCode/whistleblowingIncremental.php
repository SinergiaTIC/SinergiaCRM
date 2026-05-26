<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class Whistleblowing_Incremental {
    function incremental($bean, $event, $arguments) {

        $is_new = empty($bean->fetched_row['id']);

        $GLOBALS['log']->debug("BÚSTIA ÈTICA: Hook disparat. Tipus: " . $bean->type . " | Nou: " . ($is_new ? 'SÍ' : 'NO'));

        $db = DBManagerFactory::getInstance();
        if ($is_new && empty($bean->stic_code)) {
            $query = "SELECT MAX(CAST(stic_code AS UNSIGNED)) as max_val 
                        FROM stic_whistleblowing
                        WHERE deleted = 0";
            $result = $db->query($query);
            $row = $db->fetchByAssoc($result);
            $bean->stic_code = ($row['max_val']) ? intval($row['max_val']) + 1 : 1;
            $GLOBALS['log']->debug("BÚSTIA ÈTICA: S'ha assignat el número secuencial: " . $nextNum);
        }
    }
}