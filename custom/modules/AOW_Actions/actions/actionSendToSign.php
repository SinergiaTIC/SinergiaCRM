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

        require_once 'modules/stic_Signatures/SignatureSignersManager.php';

        $result = SignatureSignersManager::addSignersToSignature(
            $signatureId,
            $bean->module_dir,
            [$bean->id],
            $currentUserId
        );

        // Detect the "signer already attached" case (e.g. re-run of the workflow):
        // nothing added (ko>0, ok==0) but a signer already exists for this
        // signature+record -> it is not an error: continue (and still send the email).
        $alreadyAttached = false;
        $existingSignerIds = [];
        if (empty($result['ok']) && !empty($result['ko'])) {
            $db = DBManagerFactory::getInstance();
            $sigIdQ = $db->quote($signatureId);
            $recIdQ = $db->quote($bean->id);
            $resCount = $db->query("SELECT s.id FROM stic_signers s
                                    JOIN stic_signatures_stic_signers_c rel
                                      ON rel.stic_signatures_stic_signersstic_signers_idb = s.id AND rel.deleted = 0
                                    WHERE rel.stic_signatures_stic_signersstic_signatures_ida = '{$sigIdQ}'
                                      AND s.record_id = '{$recIdQ}'
                                      AND s.deleted = 0");
            while ($row = $db->fetchByAssoc($resCount)) {
                $existingSignerIds[] = $row['id'];
            }
            $alreadyAttached = !empty($existingSignerIds);
            if ($alreadyAttached) {
                $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ": Signer(s) already attached to signature {$signatureId} for record {$bean->id}. Continuing.");
            }
        }

        if ((empty($result['ok']) && empty($alreadyAttached)) || ($result['ok'] === 0 && !$alreadyAttached)) {
            $GLOBALS['log']->warn('Line ' . __LINE__ . ': ' . __METHOD__ . ": No signers were created for signature {$signatureId} and record {$bean->id}.");
            return false;
        }

        $emailSentCount = 0;
        $emailFailCount = 0;

        if ($actionType === 'add_and_email') {
            require_once 'modules/stic_Signers/Utils.php';

            // Newly created signers OR, if none were created (already attached),
            // the existing ones - so the email is still sent on re-runs.
            $signerIdsToSend = !empty($result['created_signer_ids'])
                ? $result['created_signer_ids']
                : $existingSignerIds;

            foreach ($signerIdsToSend as $signerId) {
                $emailSent = stic_SignersUtils::sendToSign($signerId, false);
                if ($emailSent) {
                    $emailSentCount++;
                } else {
                    $emailFailCount++;
                    $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ": Failed to send email to signer {$signerId}.");
                }
            }
        }

        $this->createNotification($bean, $signatureId, $result, $actionType, $emailSentCount, $emailFailCount);

        return true;
    }

    protected function createNotification(SugarBean $bean, $signatureId, array $result, $actionType, $emailSentCount, $emailFailCount)
    {
        $signatureName = '';
        $sig = BeanFactory::getBean('stic_Signatures', $signatureId);
        if ($sig && $sig->id) {
            $signatureName = $sig->name;
        }

        $recordName = $bean->name ?? $bean->id;
        $signerCount = count($result['created_signer_ids'] ?? []);

        $msg = "WorkFlow SendToSign: {$signerCount} firmante(s) añadido(s) al proceso de firma \"{$signatureName}\" para el registro \"{$recordName}\" ({$bean->module_dir}).";

        if ($actionType === 'add_and_email') {
            $msg .= " Email enviado a {$emailSentCount} firmante(s).";
            if ($emailFailCount > 0) {
                $msg .= " Fallos en el envío: {$emailFailCount}.";
            }
        }

        $alert = BeanFactory::newBean('Alerts');
        $alert->name = $msg;
        $alert->description = $msg;
        $alert->assigned_user_id = $GLOBALS['current_user']->id ?? '1';
        $alert->is_read = 0;
        $alert->type = 'info';
        $alert->target_module = 'stic_Signatures';
        $alert->url_redirect = "index.php?module=stic_Signatures&action=DetailView&record={$signatureId}";
        $alert->save();
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