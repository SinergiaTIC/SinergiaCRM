<?php
/**
 * This file is part of SinergiaCRM.
 * SinergiaCRM is a work developed by SinergiaTIC Association, based on SuiteCRM.
 * Copyright (C) 2013 - 2023 SinergiaTIC Association
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by
 * the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along
 * with this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SinergiaTIC Association at email address info@sinergiacrm.org.
 */

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once 'include/MVC/View/views/view.detail.php';
require_once 'SticInclude/Views.php';

class stic_Recycle_BinViewDetail extends ViewDetail
{
    public function __construct()
    {
        parent::__construct();
    }

    public function preDisplay()
    {
        parent::preDisplay();

        SticViews::preDisplay($this);
    }

    public function display()
    {
        global $log;

        $log->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': displaying recycle bin detail for record: ' . $this->bean->id);

        ob_start();
        parent::display();
        $html = ob_get_clean();

        $html = $this->hideUnwantedButtons($html);
        $html = $this->injectRestoreAction($html);

        echo $html;

        SticViews::display($this);
    }

    /**
     * Injects a CSS rule to hide "Print as PDF", "Signatures" and "Related Signatures"
     * dropdown items in the detail view, which are not useful for recycle bin entries.
     *
     * @param string $html Full rendered detail view HTML
     * @return string HTML with the CSS rule appended
     */
    private function hideUnwantedButtons($html)
    {
        $style = '<style>'
               . '.dropdown-menu li:has(input[onclick*="showPopup(\'pdf\')"]),'
               . '.dropdown-menu li:has(input[onclick*="showPopupSignature"]),'
               . '.dropdown-menu li:has(input[onclick*="showRelatedSignatures"])'
               . '{display:none!important}'
               . '</style>';
        $pos = strrpos($html, '</body>');
        if ($pos !== false) {
            return substr($html, 0, $pos) . $style . substr($html, $pos);
        }
        return $style . $html;
    }

    /**
     * Injects a "Restore" action into the detail view's Actions dropdown, with a
     * hidden form that posts to action=restore.
     *
     * @param string $html Full rendered detail view HTML
     * @return string HTML with the action injected
     */
    private function injectRestoreAction($html)
    {
        if (!empty($this->bean->recycle_restored)) {
            return $html;
        }

        $recordId = $this->bean->id;
        if (!self::isValidId($recordId)) {
            return $html;
        }

        $confirmMsg = translate('LBL_RESTORE_CONFIRM', 'stic_Recycle_Bin');
        $confirmMsgJs = json_encode($confirmMsg);
        $buttonLabel = translate('LBL_RESTORE_RECORD', 'stic_Recycle_Bin');
        $buttonLabelEsc = htmlspecialchars($buttonLabel, ENT_QUOTES);
        $formId = 'stic_rb_restore_' . preg_replace('/[^a-zA-Z0-9]/', '', $recordId);

        $restoreLi = '<li>'
                   . '<input type="button" class="button" onclick="if(confirm(' . $confirmMsgJs . ')){document.getElementById(\'' . $formId . '\').submit();}" value="' . $buttonLabelEsc . '"/>'
                   . '</li>';

        $hiddenForm = '<form id="' . $formId . '" method="post" action="index.php" style="display:none">'
                    . '<input type="hidden" name="module" value="stic_Recycle_Bin"/>'
                    . '<input type="hidden" name="action" value="restore"/>'
                    . '<input type="hidden" name="record" value="' . htmlspecialchars($recordId, ENT_QUOTES) . '"/>'
                    . '</form>';

        $marker = '<ul class="dropdown-menu">';
        $pos = strpos($html, $marker);
        if ($pos === false) {
            return $html . $hiddenForm;
        }
        $insertAt = $pos + strlen($marker);
        $html = substr($html, 0, $insertAt) . $restoreLi . substr($html, $insertAt);

        $bodyEnd = strrpos($html, '</body>');
        if ($bodyEnd !== false) {
            $html = substr($html, 0, $bodyEnd) . $hiddenForm . substr($html, $bodyEnd);
        } else {
            $html .= $hiddenForm;
        }
        return $html;
    }

    /**
     * Validates a SugarCRM-style UUID.
     *
     * @param string $id ID to validate
     * @return bool true if the value matches the UUID pattern
     */
    private static function isValidId($id)
    {
        return is_string($id) && preg_match('/^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/i', $id) === 1;
    }
}
