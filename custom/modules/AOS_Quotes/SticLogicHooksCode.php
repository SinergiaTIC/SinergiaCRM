<?php

class AOS_QuotesLogicHooks
{

    public function after_save(&$bean, $event, $arguments)
    {
        // If mass duplicate
        if ($_REQUEST['mass_duplicate']) {
            global $db, $sugar_config;
            include_once 'SticInclude/Utils.php';

            // *****************************************
            // 1) Duplicate related AOS_Line_Item_Groups records.
            // The query should ensure that we always retrieve some records in case there are no defined line groups (UNION SELECT NULL).
            $lineGroupsQuery = "SELECT
                                    id
                                FROM
                                    aos_line_item_groups
                                WHERE
                                    deleted = 0
                                    AND parent_type = 'AOS_Quotes' AND parent_id='{$bean->fromId}'
                                UNION SELECT NULL";

            $resultsLines = $db->query($lineGroupsQuery);

            while ($rowLines = $db->fetchByAssoc($resultsLines)) {

                // Change the parent_id field to point to the new bean id
                $lineChanges = ['parent_id' => $bean->id];

                // line group is duplicated only if the configuration is enabled.
                if ($sugar_config['aos']['lineItems']['enableGroups'] == true) {
                    $lineId = SticUtils::duplicateBeanRecord('AOS_Line_Item_Groups', $rowLines['id'], $lineChanges);
                } 

                // *****************************************
                // 2) For each Line Group, duplicate related AOS_Products_Quotes records
                $productQuery = "SELECT
                                    id
                                FROM
                                    aos_products_quotes
                                WHERE
                                    deleted = 0
                                    AND parent_type = 'AOS_Quotes'
                                    AND parent_id = '{$bean->fromId}'
                                    AND (group_id = '{$rowLines['id']}' || group_id IS null)
                                    ";

                $resultsProducts = $db->query($productQuery);

                while ($rowProduct = $db->fetchByAssoc($resultsProducts)) {
                    // Change the parent_id field to point to the new bean id
                    $productChanges = ['parent_id' => $bean->id, 'group_id' => $lineId];
                    $productId = SticUtils::duplicateBeanRecord('AOS_Products_Quotes', $rowProduct['id'], $productChanges);
                }
            }
        }
    }
}
