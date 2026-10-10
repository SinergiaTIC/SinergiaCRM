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
        $html = $this->prependMergedNotice($html);
        if (!empty($this->bean->restored)) {
            $html = $this->hideActionsMenu($html);
        }

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
     * Hides the whole Actions dropdown when the entry offers no actions
     * (already restored records cannot be restored again).
     *
     * @param string $html Full rendered detail view HTML
     * @return string HTML with the CSS rule appended
     */
    private function hideActionsMenu($html)
    {
        $style = '<style>#detail_header_action_menu,li#tab-actions{display:none!important}</style>';
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
        if (!empty($this->bean->restored)) {
            return $html;
        }

        $recordId = $this->bean->id;
        if (!self::isValidId($recordId)) {
            return $html;
        }

        $confirmMsg = translate('LBL_RESTORE_CONFIRM', 'stic_Recycle_Bin');
        $confirmMsgJs = "'" . addslashes($confirmMsg) . "'";
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
     * Prepends a warning banner above all detail fields when the entry comes
     * from a merge. When the surviving record is known, a link to it is included.
     *
     * @param string $html Rendered detail view HTML
     * @return string HTML with the notice prepended
     */
    private function prependMergedNotice($html)
    {
        if (!$this->isMergeEntry()) {
            return $html;
        }

        $notice = '<div class="alert alert-warning" role="alert">'
            . htmlspecialchars(translate('LBL_MERGED_NOTICE', 'stic_Recycle_Bin'), ENT_QUOTES);

        $link = $this->getSurvivingRecordLink();
        if ($link !== null) {
            $notice .= ' ' . htmlspecialchars(translate('LBL_SURVIVING_RECORD', 'stic_Recycle_Bin'), ENT_QUOTES)
                . ': <a href="' . htmlspecialchars($link['url'], ENT_QUOTES) . '">'
                . htmlspecialchars($link['label'], ENT_QUOTES) . '</a>';
        }
        $notice .= '</div>';

        return $notice . $html;
    }

    /**
     * Resolves the surviving record link for a merged entry, if the master
     * record id is known. The name is resolved live so it reflects renames
     * after the merge; falls back to the stored snapshot and then the id.
     *
     * @return array|null Array with url and label, or null
     */
    private function getSurvivingRecordLink()
    {
        $recordModule = $this->bean->record_module ?? '';
        $masterId = $this->bean->merged_into_id ?? '';
        if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', (string)$recordModule)
            || !self::isValidId($masterId)
        ) {
            return null;
        }

        $seed = BeanFactory::newBean($recordModule);
        if (!$seed || empty($seed->table_name)
            || !preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', (string)$seed->table_name)
        ) {
            return null;
        }

        global $db;
        $result = $db->query(
            'SELECT name FROM `' . $seed->table_name . '`'
            . ' WHERE id = ' . $db->quoted($masterId) . ' AND deleted = 0 LIMIT 1'
        );
        $label = '';
        if ($result && $row = $db->fetchByAssoc($result)) {
            $label = $row['name'] ?? '';
        }
        if ($label === '') {
            $label = $this->bean->merged_into_name ?? '';
        }
        if ($label === '') {
            $label = $masterId;
        }

        return array(
            'url' => 'index.php?module=' . $recordModule . '&action=DetailView&record=' . $masterId,
            'label' => $label,
        );
    }

    /**
     * Tells whether the displayed entry comes from a merge (fallback to the
     * legacy merged flag for older entries).
     *
     * @return bool true if the entry is a merged record
     */
    private function isMergeEntry()
    {
        if (!empty($this->bean->deletion_source)) {
            return (string)$this->bean->deletion_source === 'merge';
        }
        return !empty($this->bean->merged);
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
