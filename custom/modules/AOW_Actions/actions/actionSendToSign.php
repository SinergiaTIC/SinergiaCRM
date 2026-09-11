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

require_once 'modules/AOW_Actions/actions/actionBase.php';

class actionSendToSign extends actionBase
{
    public function __construct($id = '')
    {
        parent::__construct($id);
    }

    public function loadJS()
    {
        return array();
    }

    public function edit_display($line, ?SugarBean $bean = null, $params = array())
    {
        $html = "<table border='0' cellpadding='0' cellspacing='0' width='100%' data-workflow-action='send-to-sign'>";

        // Signature process dropdown
        $signatures = $this->getModuleSignatureTemplates($bean->module_dir);
        $selectedSignature = $params['signature_id'] ?? '';

        $html .= '<tr>';
        $html .= '<td scope="row" valign="top"><label>' . translate('LBL_SENDTOSIGN_SIGNATURE', 'AOW_Actions') . ':<span class="required">*</span></label></td>';
        $html .= '<td valign="top">';
        $html .= "<select name='aow_actions_param[{$line}][signature_id]' id='aow_actions_param_signature_id_{$line}' style='width: 100%;'>";
        $html .= "<option value='' " . (empty($selectedSignature) ? 'selected' : '') . "></option>";
        foreach ($signatures as $sigId => $sigName) {
            $selected = $sigId === $selectedSignature ? 'selected' : '';
            $html .= "<option value='{$sigId}' {$selected}>{$sigName}</option>";
        }
        $html .= '</select>';
        $html .= '</td>';
        $html .= '</tr>';

        // Action type
        $selectedAction = $params['action_type'] ?? '';

        $html .= '<tr>';
        $html .= '<td scope="row" valign="top"><label>' . translate('LBL_SENDTOSIGN_ACTION_TYPE', 'AOW_Actions') . ':<span class="required">*</span></label></td>';
        $html .= '<td valign="top">';
        $html .= "<select name='aow_actions_param[{$line}][action_type]' id='aow_actions_param_action_type_{$line}' style='width: 100%;'>";
        $html .= "<option value='' " . (empty($selectedAction) ? 'selected' : '') . "></option>";
        $addOnlySelected = $selectedAction === 'add_only' ? 'selected' : '';
        $addEmailSelected = $selectedAction === 'add_and_email' ? 'selected' : '';
        $html .= "<option value='add_only' {$addOnlySelected}>" . translate('LBL_SENDTOSIGN_ADD_ONLY', 'AOW_Actions') . '</option>';
        $html .= "<option value='add_and_email' {$addEmailSelected}>" . translate('LBL_SENDTOSIGN_ADD_AND_EMAIL', 'AOW_Actions') . '</option>';
        $html .= '</select>';
        $html .= '</td>';
        $html .= '</tr>';

        $html .= '</table>';

        return $html;
    }

    public function run_action(SugarBean $bean, $params = array(), $in_save = false)
    {
        $signatureId = $params['signature_id'] ?? '';
        $actionType = $params['action_type'] ?? 'add_only';

        if (empty($signatureId)) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Signature ID is empty in workflow action.');
            return false;
        }

        global $current_user;
        $currentUserId = $current_user->id ?? '1';

        require_once 'custom/modules/stic_Signatures/SignatureSignersManager.php';

        $result = SignatureSignersManager::addSignersToSignature(
            $signatureId,
            $bean->module_dir,
            [$bean->id],
            $currentUserId
        );

        if (empty($result['created_signer_ids'])) {
            $GLOBALS['log']->warn('Line ' . __LINE__ . ': ' . __METHOD__ . ": No signers were created for signature {$signatureId} and record {$bean->id}.");
            return $result['ok'] > 0 || $result['ko'] === 0;
        }

        if ($actionType === 'add_and_email') {
            require_once 'modules/stic_Signers/Utils.php';

            foreach ($result['created_signer_ids'] as $signerId) {
                $emailSent = stic_SignersUtils::sendToSign($signerId, false);
                if (!$emailSent) {
                    $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ": Failed to send email to signer {$signerId}.");
                }
            }
        }

        return true;
    }

    protected function getModuleSignatureTemplates($module)
    {
        $db = DBManagerFactory::getInstance();
        $signatures = array();

        $sql = "SELECT id, name FROM stic_signatures WHERE main_module = '" . $db->quote($module) . "' AND deleted = 0 ORDER BY date_modified DESC";
        $result = $db->query($sql);
        while ($row = $db->fetchByAssoc($result)) {
            $signatures[$row['id']] = $row['name'];
        }

        return $signatures;
    }
}
