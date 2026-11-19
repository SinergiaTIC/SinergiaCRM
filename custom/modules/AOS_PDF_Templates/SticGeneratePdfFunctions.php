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

if (!defined('sugarEntry') || !sugarEntry) {  
    die('Not A Valid Entry Point');  
}

/**
 * This file contains functions used for PDF generation and electronic signature processes.
 * These functions are kept separate from SticGeneratePdf.php to allow for reuse in other contexts.
 */

/**
 * STIC-Custom 20261119 ART - Conservative rework of the group tables rendering
 * Revision of the fix for the repeated headers with invoices
 * (https://github.com/SinergiaTIC/SinergiaCRM/pull/1381)
 *
 * Populates group lines in a given text template with data from line item groups.
 *
 * Renders once every table that contains article group variables
 * ($aos_line_item_groups_*). The goal is to keep, as much as possible, the
 * rendering of the original (pre-consolidation) algorithm, which is tolerant to
 * any template structure, and only change what is needed to render the static
 * header rows (rows without any $aos_* variable) exactly once, instead of once
 * per group:
 *
 * - Every table containing group variables is split into rows. Rows without any
 *   $aos_* variable are static (column headers, decorations...) and are rendered
 *   once. Rows with $aos_* variables are grouped into consecutive line-item
 *   blocks (product, service and mixed product+service rows; group rows form
 *   standalone blocks) and each block is rendered once per matching line item
 *   of the group, preserving the row order of the template. This way a product
 *   row followed by its description row keeps rendering together, as the
 *   original algorithm did.
 * - The markup surrounding the rows (<caption>, <colgroup>, <thead>, <tbody>...)
 *   is preserved: anything before the first content row and the rows before the
 *   first content row are rendered once at their original place, so <thead>
 *   keeps being repeated by the PDF engine on page breaks.
 * - Mixed rows (product and service variables in the same row) are rendered for
 *   every line item, emptying the family of variables that does not belong to
 *   the line item instead of rewriting it to $aos_null.
 * - Templates without group variables keep the previous behaviour: the whole
 *   text is populated with the individual line items.
 *
 * Safety net: whenever the structure of a template does not fit the model
 * (nested tables inside the group table, unclosed tables, structural tags
 * between content rows, group variables outside the tables, ...) the original
 * explode based algorithm is used for the whole text, so the rendering is
 * never worse than the one prior to the consolidation.
 *
 * @param string $text The text template to populate.
 * @param array $lineItemsGroups An array of line item groups.
 * @param array $lineItems An array of individual line items.
 * @param string $element The HTML element name used for grouping (e.g., 'table').
 * @return string The populated text template.
 */
function stic_populate_group_lines($text, $lineItemsGroups, $lineItems, $element = 'table')
{
    $groups = BeanFactory::newBean('AOS_Line_Item_Groups');
    $groupVarNames = array();
    foreach ($groups->field_defs as $name => $arr) {
        if (!((isset($arr['dbType']) && strtolower($arr['dbType']) == 'id') || $arr['type'] == 'id' || $arr['type'] == 'link')) {
            $groupVarNames[] = '$aos_line_item_groups_' . $name;
        }
    }

    // Check if any group variable exists in the text
    $hasGroupVars = false;
    foreach ($groupVarNames as $var) {
        if (strpos((string) $text, $var) !== false) {
            $hasGroupVars = true;
            break;
        }
    }

    if (empty($groupVarNames) || !$hasGroupVars) {
        $text = stic_populate_product_lines($text, $lineItems);
        $text = stic_populate_service_lines($text, $lineItems);
        return $text;
    }

    // STIC-Custom 20261119 ART - Conservative processing; null means the
    // template does not fit the model and the legacy algorithm must be used.
    $processed = stic_process_group_tables($text, $groupVarNames, $lineItemsGroups, $element);
    if ($processed === null) {
        return stic_populate_group_lines_legacy($text, $lineItemsGroups, $lineItems, $element);
    }

    return $processed;
}

/**
 * Checks whether an HTML block contains any line item group variable.
 *
 * @param string $block HTML block.
 * @param array $groupVarNames List of $aos_line_item_groups_* variable names.
 * @return bool
 */
function stic_table_has_group_vars($block, $groupVarNames)
{
    foreach ($groupVarNames as $var) {
        if (strpos($block, $var) !== false) {
            return true;
        }
    }
    return false;
}

/**
 * Walks the template text, finds every table and delegates the rendering of the
 * tables that contain group variables.
 *
 * @param string $text The text template to populate.
 * @param array $groupVarNames List of $aos_line_item_groups_* variable names.
 * @param array $lineItemsGroups Line item groups (group id => line items).
 * @param string $element The HTML element name used for grouping (e.g., 'table').
 * @return string|null The populated text, or null when the legacy algorithm must be used.
 */
function stic_process_group_tables($text, $groupVarNames, $lineItemsGroups, $element)
{
    if ((is_countable($lineItemsGroups) ? count($lineItemsGroups) : 0) === 0) {
        // Without groups the legacy behaviour applies
        return null;
    }

    $startElement = '<' . $element;
    $endElement = '</' . $element . '>';
    $openLen = strlen($startElement);
    $endLen = strlen($endElement);

    $processed = '';
    $remaining = (string) $text;

    while (($startPos = stripos($remaining, $startElement)) !== false) {
        // STIC-Custom 20261119 ART - Find the matching closing tag counting
        // nested tables, instead of taking the first closing tag found.
        $depth = 0;
        $cursor = $startPos;
        $endPos = false;
        while (true) {
            $nextOpen = stripos($remaining, $startElement, $cursor + $openLen);
            $nextClose = stripos($remaining, $endElement, $cursor + 1);
            if ($nextClose === false) {
                // Unclosed table: not a safe structure for the row based rendering
                return null;
            }
            if ($nextOpen !== false && $nextOpen < $nextClose) {
                $depth++;
                $cursor = $nextOpen;
                continue;
            }
            if ($depth === 0) {
                $endPos = $nextClose + $endLen;
                break;
            }
            $depth--;
            $cursor = $nextClose;
        }

        $processed .= substr($remaining, 0, $startPos);
        $block = substr($remaining, $startPos, $endPos - $startPos);
        $remaining = substr($remaining, $endPos);

        // Table without group variables - keep as is
        if (!stic_table_has_group_vars($block, $groupVarNames)) {
            $processed .= $block;
            continue;
        }

        // STIC-Custom 20261119 ART - A nested table inside a group table cannot
        // be safely rebuilt row by row: fall back to the legacy algorithm.
        if (stripos($block, $startElement, 1) !== false) {
            return null;
        }

        $rendered = stic_render_group_table($block, $groupVarNames, $lineItemsGroups);
        if ($rendered === null) {
            return null;
        }
        $processed .= $rendered;
    }

    $processed .= $remaining;

    // STIC-Custom 20261119 ART - Group variables outside the processed tables
    // (headings, text between tables...) were rendered by the legacy algorithm,
    // but are not rendered by the row based one. Fall back to the legacy
    // algorithm to keep them working.
    foreach ($groupVarNames as $var) {
        if (strpos($processed, $var) !== false) {
            return null;
        }
    }

    return $processed;
}

/**
 * Renders a single table that contains group variables, keeping its own markup.
 *
 * The table is rebuilt with the same opening tag, the same leading markup
 * (<caption>, <colgroup>, <thead>...), the static rows rendered once, the
 * content rows rendered per group in blocks and the trailing markup untouched.
 *
 * @param string $block The whole <table>...</table> block.
 * @param array $groupVarNames List of $aos_line_item_groups_* variable names.
 * @param array $lineItemsGroups Line item groups (group id => line items).
 * @return string|null The rendered table, or null when the legacy algorithm must be used.
 */
function stic_render_group_table($block, $groupVarNames, $lineItemsGroups)
{
    // STIC-Custom 20261119 ART - rows are always <tr> elements, no matter the
    // grouping element used for the tables.
    if (!preg_match('/^\s*<table[^>]*>/is', $block, $openMatch)) {
        return null;
    }
    $openTag = $openMatch[0];
    $inner = substr($block, strlen($openTag));

    $closePos = strripos($inner, '</table>');
    if ($closePos === false) {
        return null;
    }
    // STIC-Custom 20261119 ART - keep the closing tag exactly as it appears in
    // the template (it may use uppercase like the opening tag).
    $closeTag = substr($inner, $closePos);
    $tail = substr($inner, $closePos + strlen('</table>'));
    $inner = substr($inner, 0, $closePos);

    // Extract all <tr>...</tr> rows (case insensitive, multiline aware)
    if (!preg_match_all('/<tr\b[^>]*>.*?<\/tr>/is', $inner, $matches, PREG_OFFSET_CAPTURE)) {
        return null;
    }

    // Split the inner table markup into: leading markup + row items (each one
    // with the text found right after it) + trailing markup.
    $prefix = substr($inner, 0, $matches[0][0][1]);
    $items = array();
    $prevEnd = 0;
    foreach ($matches[0] as $i => $m) {
        if ($i > 0) {
            $items[$i - 1]['gap'] = substr($inner, $prevEnd, $m[1] - $prevEnd);
        }
        $items[] = array('row' => $m[0], 'gap' => '', 'consumed' => false);
        $prevEnd = $m[1] + strlen($m[0]);
    }
    $tailRows = substr($inner, $prevEnd);

    // Classify rows and group the content rows into blocks of the same family.
    // Static rows (no $aos_* variable) are rendered once:
    // - static rows before the first content row stay at their original place;
    // - any other static row is rendered once at the beginning of the table,
    //   so a column header placed after the first content row is no longer
    //   repeated for every group. Those hoisted rows carry their adjacent
    //   markup gaps with them (e.g. the </thead><tbody> boundary), so the
    //   original table structure is preserved.
    $aosVarPattern = '/\$aos_[a-z0-9_]+/i';
    $once = $prefix;
    $statics = array();
    $blocks = array();
    $inContent = false;
    $itemCount = count($items);
    foreach ($items as $i => $item) {
        $row = $item['row'];
        if (!preg_match($aosVarPattern, $row)) {
            if (!$inContent) {
                $once .= $row . $item['gap'];
            } else {
                // STIC-Custom 20261119 ART - hoisted static row: it takes with
                // it the markup gap found right before it (between it and the
                // previous content row) and the one found right after it, so
                // structural boundaries (</thead><tbody>...) are not lost.
                $before = '';
                if ($i > 0 && $items[$i - 1]['consumed'] === false) {
                    $before = $items[$i - 1]['gap'];
                    $items[$i - 1]['consumed'] = true;
                }
                $statics[] = $before . $row . $item['gap'];
                $item['consumed'] = true;
                $items[$i] = $item;
            }
            continue;
        }

        $inContent = true;
        $family = stic_row_family($row);
        $last = count($blocks) - 1;
        // STIC-Custom 20261119 ART - product, service and mixed rows belong to
        // the same line-item block, so a product row and its description row
        // render together for each product, like the original algorithm did.
        // Group rows (name, number, totals...) are standalone blocks.
        $sameBlock = ($last >= 0 && $blocks[$last]['family'] !== 'group' && $family !== 'group');
        if ($sameBlock) {
            $blocks[$last]['rows'][] = $row;
        } else {
            $blocks[] = array('family' => $family, 'rows' => array($row));
        }

        // STIC-Custom 20261119 ART - the gap after a content row is only
        // dropped when it is safe: the last content row always hands its gap
        // over to the trailing markup, and a gap followed by a static row is
        // carried by that row. Anything else (tags, text, variables...) means
        // the template does not fit the model.
        $gapConsumedByStatic = ($i + 1 < $itemCount) && !preg_match($aosVarPattern, $items[$i + 1]['row']);
        $isLastItem = ($i + 1 === $itemCount);
        if (!$gapConsumedByStatic && !$isLastItem
            && (strpos($item['gap'], '<') !== false || strpos($item['gap'], '$') !== false)) {
            return null;
        }
    }

    $once .= implode('', $statics);

    // Render the content blocks once per group
    $body = '';
    foreach ($lineItemsGroups as $group_id => $groupLineItems) {
        foreach ($blocks as $blockData) {
            $body .= stic_render_group_block($blockData['rows'], $groupLineItems, $group_id);
        }
    }

    return $openTag . $once . $body . $tailRows . $tail . $closeTag;
}

/**
 * Determines the family of variables contained in a content row.
 *
 * @param string $row The <tr>...</tr> block to classify.
 * @return string 'product', 'service', 'mixed' or 'group'.
 */
function stic_row_family($row)
{
    $hasProductVars = (bool) preg_match('/\$aos_products/i', $row);
    $hasServiceVars = (bool) preg_match('/\$aos_services/i', $row);
    if ($hasProductVars && $hasServiceVars) {
        return 'mixed';
    }
    if ($hasProductVars) {
        return 'product';
    }
    if ($hasServiceVars) {
        return 'service';
    }
    return 'group';
}

/**
 * Renders a block of consecutive content rows for one group.
 *
 * The block contains line-item rows (product, service or mixed). Every row of
 * the block is rendered for the same line item, so the row order of the
 * template is preserved (e.g. a product row followed by its description row
 * renders together for each product, like the original algorithm did):
 *
 * - A block with only product rows renders once per product line item.
 * - A block with only service rows renders once per service line item.
 * - A block that mixes families (a mixed row, or product rows next to service
 *   rows) renders once per line item, rendering each row only when its family
 *   matches the line item, so product rows are not rendered for services and
 *   vice versa. Mixed rows render for every line item, emptying the family of
 *   variables that does not belong to it instead of rewriting it to $aos_null.
 *
 * @param array $rows Consecutive content rows.
 * @param array $groupLineItems Line items of the group (line item id => product id).
 * @param string $group_id The group id used to populate the group variables.
 * @return string The rendered block.
 */
function stic_render_group_block($rows, $groupLineItems, $group_id)
{
    $families = array();
    foreach ($rows as $row) {
        $families[] = stic_row_family($row);
    }

    // STIC-Custom 20261119 ART - Group rows (group name, number, totals...)
    // form standalone blocks and render once per group.
    if (!in_array('product', $families, true) && !in_array('service', $families, true) && !in_array('mixed', $families, true)) {
        $html = '';
        foreach ($rows as $row) {
            $html .= templateParser::parse_template($row, array('AOS_Line_Item_Groups' => $group_id));
        }
        return $html;
    }

    $onlyProductRows = !in_array('service', $families, true) && !in_array('mixed', $families, true);
    $onlyServiceRows = !in_array('product', $families, true) && !in_array('mixed', $families, true);

    $html = '';
    foreach ((array) $groupLineItems as $lineItemId => $productId) {
        $isProduct = ($productId != null && $productId != '0');

        if ($onlyProductRows && !$isProduct) {
            continue;
        }
        if ($onlyServiceRows && $isProduct) {
            continue;
        }

        // STIC-Custom 20261119 ART - All the rows of the block are rendered for
        // the same line item, preserving the template order.
        foreach ($rows as $k => $row) {
            $rowFamily = $families[$k];
            if ($rowFamily === 'product' && !$isProduct) {
                continue;
            }
            if ($rowFamily === 'service' && $isProduct) {
                continue;
            }
            $html .= stic_render_group_row_for_line($row, $lineItemId, $productId, $group_id);
        }
    }

    return $html;
}

/**
 * Renders a single content row of a grouped table for one line item.
 *
 * Product variables are filled for product line items and service variables for
 * service line items. When a row mixes both families, the variables that do not
 * belong to the current line item are emptied instead of being rewritten to
 * $aos_null, which was causing the products to disappear.
 *
 * @param string $row The <tr>...</tr> block to render.
 * @param string $lineItemId The AOS_Products_Quotes line item id.
 * @param string|null $productId The related product id (null for services).
 * @param string $group_id The group id used to populate the group variables.
 * @return string The rendered row.
 */
function stic_render_group_row_for_line($row, $lineItemId, $productId, $group_id)
{
    $isProduct = ($productId != null && $productId != '0');

    if ($isProduct) {
        // Products: empty the service variables and fill the product ones.
        $rowHtml = preg_replace('/\$aos_services[a-z0-9_]*/i', '', $row);
        $beanArr = array(
            'AOS_Products_Quotes' => $lineItemId,
            'AOS_Products' => $productId,
            'AOS_Line_Item_Groups' => $group_id,
        );
    } else {
        // Services: empty the product variables and map the service ones to the
        // AOS_Products_Quotes fields (services are stored as product quote lines).
        $rowHtml = preg_replace('/\$aos_products[a-z0-9_]*/i', '', $row);
        $rowHtml = str_replace('$aos_services_quotes_service', '$aos_services_quotes_product', $rowHtml);
        $rowHtml = str_replace('$aos_services', '$aos_products', $rowHtml);
        $beanArr = array(
            'AOS_Products_Quotes' => $lineItemId,
            'AOS_Line_Item_Groups' => $group_id,
        );
    }

    return templateParser::parse_template($rowHtml, $beanArr);
}

/**
 * STIC-Custom 20261119 ART - Legacy algorithm kept as a safety net.
 *
 * This is the original explode based implementation used before the
 * consolidation of populate_group_lines. It is tolerant to any template
 * structure (nested tables, unclosed tags, uppercase tags, group variables
 * outside tables...) and is used whenever the conservative rendering does not
 * fit a template, so the output is never worse than the previous one.
 *
 * @param string $text The text template to populate.
 * @param array $lineItemsGroups An array of line item groups.
 * @param array $lineItems An array of individual line items.
 * @param string $element The HTML element name used for grouping (e.g., 'table').
 * @return string The populated text template.
 */
function stic_populate_group_lines_legacy($text, $lineItemsGroups, $lineItems, $element = 'table')
{
    $firstValue = '';
    $firstNum = 0;

    $lastValue = '';
    $lastNum = 0;

    $startElement = '<' . $element;
    $endElement = '</' . $element . '>';

    $groups = BeanFactory::newBean('AOS_Line_Item_Groups');
    foreach ($groups->field_defs as $name => $arr) {
        if (!((isset($arr['dbType']) && strtolower($arr['dbType']) == 'id') || $arr['type'] == 'id' || $arr['type'] == 'link')) {
            $curNum = strpos((string) $text, '$aos_line_item_groups_' . $name);
            if ($curNum) {
                if ($curNum < $firstNum || $firstNum == 0) {
                    $firstValue = '$aos_line_item_groups_' . $name;
                    $firstNum = $curNum;
                }
                if ($curNum > $lastNum) {
                    $lastValue = '$aos_line_item_groups_' . $name;
                    $lastNum = $curNum;
                }
            }
        }
    }
    if ($firstValue !== '' && $lastValue !== '') {
        // Converting Text
        $parts = explode($firstValue, $text);
        $text = $parts[0];
        $parts = explode($lastValue, $parts[1]);
        if ($lastValue == $firstValue) {
            $groupPart = $firstValue . $parts[0];
        } else {
            $groupPart = $firstValue . $parts[0] . $lastValue;
        }

        if ((is_countable($lineItemsGroups) ? count($lineItemsGroups) : 0) != 0) {
            // Read line start <tr> value
            $tcount = strrpos($text, $startElement);
            $lsValue = substr($text, $tcount);
            $tcount = strpos($lsValue, ">") + 1;
            $lsValue = substr($lsValue, 0, $tcount);

            // Read line end values
            $tcount = strpos($parts[1], $endElement) + strlen($endElement);
            $leValue = substr($parts[1], 0, $tcount);

            // Converting Line Items
            $obb = array();

            $tdTemp = explode($lsValue, $text);

            $groupPart = $lsValue . $tdTemp[(is_countable($tdTemp) ? count($tdTemp) : 0) - 1] . $groupPart . $leValue;

            $text = $tdTemp[0];

            foreach ($lineItemsGroups as $group_id => $lineItemsArray) {
                $groupPartTemp = stic_populate_product_lines($groupPart, $lineItemsArray);
                $groupPartTemp = stic_populate_service_lines($groupPartTemp, $lineItemsArray);

                $obb['AOS_Line_Item_Groups'] = $group_id;
                $text .= templateParser::parse_template($groupPartTemp, $obb);
                $text .= '<br />';
            }
            $tcount = strpos($parts[1], $endElement) + strlen($endElement);
            $parts[1] = substr($parts[1], $tcount);
        } else {
            $tcount = strrpos($text, $startElement);
            $text = substr($text, 0, $tcount);

            $tcount = strpos($parts[1], $endElement) + strlen($endElement);
            $parts[1] = substr($parts[1], $tcount);
        }

        $text .= $parts[1];
    } else {
        $text = stic_populate_product_lines($text, $lineItems);
        $text = stic_populate_service_lines($text, $lineItems);
    }

    return $text;
}

/**
 * Populates product lines in a given text template with data from line items.
 *
 * @param string $text The text template to populate.
 * @param array $lineItems An array of line items, where keys are IDs and values are product IDs.
 * @param string $element The HTML element name used for lines (e.g., 'tr').
 * @return string The populated text template.
 */
function stic_populate_product_lines($text, $lineItems, $element = 'tr')
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
            $curNum = strpos((string) $text, '$aos_products_quotes_' . $name);

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
            $curNum = strpos((string) $text, '$aos_products_' . $name);
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
        if ($firstNum === $lastNum) {
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

        $linePart = $lsValue . $tdTemp[(is_countable($tdTemp) ? count($tdTemp) : 0) - 1] . $linePart . $leValue;
        $parts = explode($linePart, $text);
        $text = $parts[0];

        // Converting Line Items
        if ((is_countable($lineItems) ? count($lineItems) : 0) != 0) {
            foreach ($lineItems as $id => $productId) {
                if ($productId != null && $productId != '0') {
                    $obb['AOS_Products_Quotes'] = $id;
                    $obb['AOS_Products'] = $productId;
                    $text .= templateParser::parse_template($linePart, $obb);
                }
            }
        }
        $partsCount = is_countable($parts) ? count($parts) : 0;

        for ($i = 1; $i < $partsCount; $i++) {
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
function stic_populate_service_lines($text, $lineItems, $element = 'tr')
{
    $firstValue = '';
    $firstNum = 0;

    $lastValue = '';
    $lastNum = 0;

    $startElement = '<' . $element;
    $endElement = '</' . $element . '>';

    $text = str_replace("\$aos_services_quotes_service", "\$aos_services_quotes_product", (string) $text);

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
        if ($firstNum === $lastNum) {
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

        $linePart = $lsValue . $tdTemp[(is_countable($tdTemp) ? count($tdTemp) : 0) - 1] . $linePart . $leValue;
        $parts = explode($linePart, $text);
        $text = $parts[0];

        // Converting Line Items
        if ((is_countable($lineItems) ? count($lineItems) : 0) != 0) {
            foreach ($lineItems as $id => $productId) {
                if ($productId == null || $productId == '0') {
                    $obb['AOS_Products_Quotes'] = $id;
                    $text .= templateParser::parse_template($linePart, $obb);
                }
            }
        }
        $partsCount = is_countable($parts) ? count($parts) : 0;

        for ($i = 1; $i < $partsCount; $i++) {
            $text .= $parts[$i];
        }
    }
    return $text;
}
