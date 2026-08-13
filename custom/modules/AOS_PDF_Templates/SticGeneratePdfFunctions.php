<?php
/**
 * This file is part of SinergiaCRM.
 * SinergiaCRM is a work developed by SinergiaTIC Association, based on SuiteCRM.
 * Copyright (C) 2013 - 2023 SinergiaTIC Association
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SinergiaTIC Association at email address info@sinergiacrm.org.
 */

/**
 * This file contains functions used for PDF generation and electronic signature processes.
 * These functions are kept separate from SticGeneratePdf.php to allow for reuse in other contexts.
 */

/**
 * Populates group lines in a given text template with data from line item groups.
 *
 * @param string $text The text template to populate.
 * @param array $lineItemsGroups An array of line item groups.
 * @param array $lineItems An array of individual line items.
 * @param string $element The HTML element name used for grouping (e.g., 'table').
 * @return string The populated text template.
 */
function populate_group_lines($text, $lineItemsGroups, $lineItems, $element = 'table')
{
    // STIC-Custom 20260813 ART - Repeated Headers in PDF Templates with Invoices
    // https://github.com/SinergiaTIC/SinergiaCRM/pull/
    // $firstValue = '';
    // $firstNum = 0;

    // $lastValue = '';
    // $lastNum = 0;

    // $startElement = '<' . $element;
    // $endElement = '</' . $element . '>';

    // $groups = BeanFactory::newBean('AOS_Line_Item_Groups');
    // foreach ($groups->field_defs as $name => $arr) {
    //     if (!((isset($arr['dbType']) && strtolower($arr['dbType']) == 'id') || $arr['type'] == 'id' || $arr['type'] == 'link')) {
    //         $curNum = strpos($text, '$aos_line_item_groups_' . $name);
    //         if ($curNum) {
    //             if ($curNum < $firstNum || $firstNum == 0) {
    //                 $firstValue = '$aos_line_item_groups_' . $name;
    //                 $firstNum = $curNum;
    //             }
    //             if ($curNum > $lastNum) {
    //                 $lastValue = '$aos_line_item_groups_' . $name;
    //                 $lastNum = $curNum;
    //             }
    //         }
    //     }
    // }
    // if ($firstValue !== '' && $lastValue !== '') {
    //     // Converting Text
    //     $parts = explode($firstValue, $text);
    //     $text = $parts[0];
    //     $parts = explode($lastValue, $parts[1]);
    //     if ($lastValue == $firstValue) {
    //         $groupPart = $firstValue . $parts[0];
    //     } else {
    //         $groupPart = $firstValue . $parts[0] . $lastValue;
    //     }

    //     if (count($lineItemsGroups) != 0) {
    //         // Read line start <tr> value
    //         $tcount = strrpos($text, $startElement);
    //         $lsValue = substr($text, $tcount);
    //         $tcount = strpos($lsValue, ">") + 1;
    //         $lsValue = substr($lsValue, 0, $tcount);

    //         // Read line end values
    //         $tcount = strpos($parts[1], $endElement) + strlen($endElement);
    //         $leValue = substr($parts[1], 0, $tcount);

    //         // Converting Line Items
    //         $obb = array();

    //         $tdTemp = explode($lsValue, $text);

    //         $groupPart = $lsValue . $tdTemp[count($tdTemp) - 1] . $groupPart . $leValue;

    //         $text = $tdTemp[0];

    //         foreach ($lineItemsGroups as $group_id => $lineItemsArray) {
    //             $groupPartTemp = populate_product_lines($groupPart, $lineItemsArray);
    //             $groupPartTemp = populate_service_lines($groupPartTemp, $lineItemsArray);

    //             $obb['AOS_Line_Item_Groups'] = $group_id;
    //             $text .= templateParser::parse_template($groupPartTemp, $obb);
    //             $text .= '<br />';
    //         }
    //         $tcount = strpos($parts[1], $endElement) + strlen($endElement);
    //         $parts[1] = substr($parts[1], $tcount);
    //     } else {
    //         $tcount = strrpos($text, $startElement);
    //         $text = substr($text, 0, $tcount);

    //         $tcount = strpos($parts[1], $endElement) + strlen($endElement);
    //         $parts[1] = substr($parts[1], $tcount);
    //     }

    //     $text .= $parts[1];
    // } else {
    //     $text = populate_product_lines($text, $lineItems);
    //     $text = populate_service_lines($text, $lineItems);
    // }

    // return $text;
    $startElement = '<' . $element;
    $endElement = '</' . $element . '>';

    $groups = BeanFactory::newBean('AOS_Line_Item_Groups');
    $groupVarNames = array();
    foreach ($groups->field_defs as $name => $arr) {
        if (!((isset($arr['dbType']) && strtolower($arr['dbType']) == 'id') || $arr['type'] == 'id' || $arr['type'] == 'link')) {
            $groupVarNames[] = '$aos_line_item_groups_' . $name;
        }
    }

    if (empty($groupVarNames)) {
        $text = populate_product_lines($text, $lineItems);
        $text = populate_service_lines($text, $lineItems);
        return $text;
    }

    // Check if any group variable exists in the text
    $hasGroupVars = false;
    foreach ($groupVarNames as $var) {
        if (strpos($text, $var) !== false) {
            $hasGroupVars = true;
            break;
        }
    }

    if (!$hasGroupVars) {
        $text = populate_product_lines($text, $lineItems);
        $text = populate_service_lines($text, $lineItems);
        return $text;
    }

    // Find all table blocks and process each one that contains group variables
    $aosVarPattern = '/\$aos_[a-z_]+/i';
    $processedText = '';
    $remainingText = $text;

    while (($tableStartPos = strpos($remainingText, $startElement)) !== false) {
        // Find the end of this table
        $tableEndPos = strpos($remainingText, $endElement, $tableStartPos);
        if ($tableEndPos === false) {
            break;
        }
        $tableEndPos += strlen($endElement);

        // Extract text before this table
        $beforeTable = substr($remainingText, 0, $tableStartPos);
        $tableBlock = substr($remainingText, $tableStartPos, $tableEndPos - $tableStartPos);

        // Check if this table contains group variables
        $tableHasGroupVars = false;
        foreach ($groupVarNames as $var) {
            if (strpos($tableBlock, $var) !== false) {
                $tableHasGroupVars = true;
                break;
            }
        }

        if ($tableHasGroupVars && (is_countable($lineItemsGroups) ? count($lineItemsGroups) : 0) != 0) {
            // Extract all <tr>...</tr> blocks from this table
            $trStartTag = '<tr';
            $trEndTag = '</tr>';
            $rows = array();
            $searchPos = 0;

            while (($rowStart = strpos($tableBlock, $trStartTag, $searchPos)) !== false) {
                $rowEnd = strpos($tableBlock, $trEndTag, $rowStart);
                if ($rowEnd === false) {
                    break;
                }
                $rowEnd += strlen($trEndTag);
                $rows[] = substr($tableBlock, $rowStart, $rowEnd - $rowStart);
                $searchPos = $rowEnd;
            }

            // Separate header rows (no $aos_* variables) from content rows
            $headerRows = array();
            $contentRows = array();

            foreach ($rows as $row) {
                if (!preg_match($aosVarPattern, $row)) {
                    $headerRows[] = $row;
                } else {
                    $contentRows[] = $row;
                }
            }

            // Extract the opening <table...> tag once
            $tableOpenTag = '';
            if (preg_match('/(<table[^>]*>)/i', $tableBlock, $matches)) {
                $tableOpenTag = $matches[1];
            }

            // Build the table header (rendered once, no $aos_* variables)
            $tableHeaderHtml = '';
            if (!empty($headerRows) && $tableOpenTag !== '') {
                $tableHeaderHtml = $tableOpenTag . '<tbody>' . implode('', $headerRows) . '</tbody></table>';
            }

            // Build the table for group iteration (content rows with $aos_* variables only)
            $tableForIteration = '';
            if (!empty($contentRows)) {
                if ($tableOpenTag !== '') {
                    $tableForIteration = $tableOpenTag . '<tbody>' . implode('', $contentRows) . '</tbody></table>';
                } else {
                    $tableForIteration = implode('', $contentRows);
                }
            }

            // Append text before table
            $processedText .= $beforeTable;

            // Render table header once
            if (!empty($tableHeaderHtml)) {
                $processedText .= templateParser::parse_template($tableHeaderHtml, array());
            }

            // Iterate through groups
            $obb = array();
            foreach ($lineItemsGroups as $group_id => $lineItemsArray) {
                $groupPartTemp = populate_product_lines($tableForIteration, $lineItemsArray);
                $groupPartTemp = populate_service_lines($groupPartTemp, $lineItemsArray);

                $obb['AOS_Line_Item_Groups'] = $group_id;
                $processedText .= templateParser::parse_template($groupPartTemp, $obb);
                $processedText .= '<br />';
            }
        } else {
            // Table without group variables - keep as is
            $processedText .= $beforeTable . $tableBlock;
        }

        $remainingText = substr($remainingText, $tableEndPos);
    }

    // Append any remaining text after the last table
    $processedText .= $remainingText;

    return $processedText;
    // END STIC-Custom
}

/**
 * Populates product lines in a given text template with data from line items.
 *
 * @param string $text The text template to populate.
 * @param array $lineItems An array of line items, where keys are IDs and values are product IDs.
 * @param string $element The HTML element name used for lines (e.g., 'tr').
 * @return string The populated text template.
 */
function populate_product_lines($text, $lineItems, $element = 'tr')
{
    $firstValue = '';
    $firstNum = 0;

    $lastValue = '';
    $lastNum = 0;

    $startElement = '<' . $element;
    $endElement = '</' . $element . '>';

    // Find first and last valid line values for products quotes
    $product_quote = BeanFactory::newBean('AOS_Products_Quotes');
    foreach ($product_quote->field_defs as $name => $arr) {
        if (!((isset($arr['dbType']) && strtolower($arr['dbType']) == 'id') || $arr['type'] == 'id' || $arr['type'] == 'link')) {
            $curNum = strpos($text, '$aos_products_quotes_' . $name);

            if ($curNum) {
                if ($curNum < $firstNum || $firstNum == 0) {
                    $firstValue = '$aos_products_quotes_' . $name;
                    $firstNum = $curNum;
                }
                if ($curNum > $lastNum) {
                    $lastValue = '$aos_products_quotes_' . $name;
                    $lastNum = $curNum;
                }
            }
        }
    }

    // Find first and last valid line values for products
    $product = BeanFactory::newBean('AOS_Products');
    foreach ($product->field_defs as $name => $arr) {
        if (!((isset($arr['dbType']) && strtolower($arr['dbType']) == 'id') || $arr['type'] == 'id' || $arr['type'] == 'link')) {
            $curNum = strpos($text, '$aos_products_' . $name);
            if ($curNum) {
                if ($curNum < $firstNum || $firstNum == 0) {
                    $firstValue = '$aos_products_' . $name;
                    $firstNum = $curNum;
                }
                if ($curNum > $lastNum) {
                    $lastValue = '$aos_products_' . $name;
                    $lastNum = $curNum;
                }
            }
        }
    }

    if ($firstValue !== '' && $lastValue !== '') {
        // Converting Text
        $tparts = explode($firstValue, $text);
        $temp = $tparts[0];

        // Check if there is only one line item
        if ($firstNum == $lastNum) {
            $linePart = $firstValue;
        } else {
            $tparts = explode($lastValue, $tparts[1]);
            $linePart = $firstValue . $tparts[0] . $lastValue;
        }

        $tcount = strrpos($temp, $startElement);
        $lsValue = substr($temp, $tcount);
        $tcount = strpos($lsValue, ">") + 1;
        $lsValue = substr($lsValue, 0, $tcount);

        // Read line end values
        $tcount = strpos($tparts[1], $endElement) + strlen($endElement);
        $leValue = substr($tparts[1], 0, $tcount);
        $tdTemp = explode($lsValue, $temp);

        $linePart = $lsValue . $tdTemp[count($tdTemp) - 1] . $linePart . $leValue;
        $parts = explode($linePart, $text);
        $text = $parts[0];

        // Converting Line Items
        if (count($lineItems) != 0) {
            foreach ($lineItems as $id => $productId) {
                if ($productId != null && $productId != '0') {
                    $obb['AOS_Products_Quotes'] = $id;
                    $obb['AOS_Products'] = $productId;
                    $text .= templateParser::parse_template($linePart, $obb);
                }
            }
        }

        for ($i = 1; $i < count($parts); $i++) {
            $text .= $parts[$i];
        }
    }
    return $text;
}

/**
 * Populates service lines in a given text template with data from line items.
 *
 * @param string $text The text template to populate.
 * @param array $lineItems An array of line items, where keys are IDs and values are product IDs.
 * @param string $element The HTML element name used for lines (e.g., 'tr').
 * @return string The populated text template.
 */
function populate_service_lines($text, $lineItems, $element = 'tr')
{
    $firstValue = '';
    $firstNum = 0;

    $lastValue = '';
    $lastNum = 0;

    $startElement = '<' . $element;
    $endElement = '</' . $element . '>';

    $text = str_replace("\$aos_services_quotes_service", "\$aos_services_quotes_product", $text);

    // Find first and last valid line values for products quotes
    $product_quote = BeanFactory::newBean('AOS_Products_Quotes');
    foreach ($product_quote->field_defs as $name => $arr) {
        if (!((isset($arr['dbType']) && strtolower($arr['dbType']) == 'id') || $arr['type'] == 'id' || $arr['type'] == 'link')) {
            $curNum = strpos($text, '$aos_services_quotes_' . $name);
            if ($curNum) {
                if ($curNum < $firstNum || $firstNum == 0) {
                    $firstValue = '$aos_products_quotes_' . $name;
                    $firstNum = $curNum;
                }
                if ($curNum > $lastNum) {
                    $lastValue = '$aos_products_quotes_' . $name;
                    $lastNum = $curNum;
                }
            }
        }
    }
    if ($firstValue !== '' && $lastValue !== '') {
        $text = str_replace("\$aos_products", "\$aos_null", $text);
        $text = str_replace("\$aos_services", "\$aos_products", $text);

        // Converting Text
        $tparts = explode($firstValue, $text);
        $temp = $tparts[0];

        // Check if there is only one line item
        if ($firstNum == $lastNum) {
            $linePart = $firstValue;
        } else {
            $tparts = explode($lastValue, $tparts[1]);
            $linePart = $firstValue . $tparts[0] . $lastValue;
        }

        $tcount = strrpos($temp, $startElement);
        $lsValue = substr($temp, $tcount);
        $tcount = strpos($lsValue, ">") + 1;
        $lsValue = substr($lsValue, 0, $tcount);

        // Read line end values
        $tcount = strpos($tparts[1], $endElement) + strlen($endElement);
        $leValue = substr($tparts[1], 0, $tcount);
        $tdTemp = explode($lsValue, $temp);

        $linePart = $lsValue . $tdTemp[count($tdTemp) - 1] . $linePart . $leValue;
        $parts = explode($linePart, $text);
        $text = $parts[0];

        // Converting Line Items
        if (count($lineItems) != 0) {
            foreach ($lineItems as $id => $productId) {
                if ($productId == null || $productId == '0') {
                    $obb['AOS_Products_Quotes'] = $id;
                    $text .= templateParser::parse_template($linePart, $obb);
                }
            }
        }

        for ($i = 1; $i < count($parts); $i++) {
            $text .= $parts[$i];
        }
    }
    return $text;
}