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
 * Class to handle the generation of Lists of Prospects (LPO) based on signature processes.
 * This class provides methods to create LPOs for all signers or only pending signers
 * associated with a specific signature.
 */
class LPOTypes
{
  
    /**
     * Generate a List of Prospects (LPO) based on the signature ID, type, and label.
     *
     * @param string $signatureId The ID of the signature process.
     * @param string $type The type of LPO to generate (e.g., all signers, pending signers).
     * @param string $label A label to include in the LPO name.
     * @return array An array containing the status and details of the created or existing LPO.
     */
    public static function generateLPO($signatureId, $type, $label)
    {
        require_once 'modules/stic_Signatures/Utils.php';
        global $current_user, $mod_strings;

        if (empty($signatureId)) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ':  Signature ID is empty.');
            return false;
        }
        // Retrieve the signature bean
        $signatureBean = BeanFactory::getBean('stic_Signatures', $signatureId);

        if (empty($signatureBean)) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ':  Signature bean not found for ID ' . $signatureId);
            return false;
        }

        // Determine the person who sign module (Users or Contacts)
        $userOrContactsModule = explode(':', $signatureBean->signer_path)[0];

        // Set filter depent on type
        $filter = '';
        switch ($type) {
            case 'stic_Signatures__all_signers':
                $filter = "";
                break;
            case 'stic_Signatures__signers_pending':
                $filter = " AND status = 'pending' ";
                break;
            default:
                $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ':  Unsupported type for LPO creation: ' . $type);
                return false;
        }

        // Retrieve all signers associated with the signature
        $userOrContactsTargets = $signatureBean->get_linked_beans('stic_signatures_stic_signers', 'stic_Signers', '', 0, 0, 0, " parent_type = '{$userOrContactsModule}'  {$filter}");

        // Create a unique name for the LPO
        // Format: [PDF Template] - ([Label]) - [Date]
        $lpoName = "{$signatureBean->pdf_template} - ({$label}) - " . date('d-m-Y');

        // check  if LPO with same name exists
        $existingLPO = BeanFactory::getBean('ProspectLists')->get_full_list(
            '',
            "prospect_lists.name = '{$lpoName}' AND prospect_lists.deleted = 0"
        );

        // If no existing LPO, create a new one
        if (empty($existingLPO)) {
            // Create a new LPO (Prospect List)
            $LPOBean = BeanFactory::newBean('ProspectLists');
            $LPOBean->name = $lpoName;
            $LPOBean->list_type = 'default';
            $LPOBean->assigned_user_id = $signatureBean->assigned_user_id ?? $current_user->id;
            $LPOBean->assigned_user_name = $signatureBean->assigned_user_name ?? $current_user->user_name;
            $LPOBean->save();
            // SugarApplication::appendSuccessMessage("<span class='label label-success'> {$mod_strings['LBL_NOTIFICATION_CAMPAIGN_CREATED_FROM_SIGNATURE']} {$LPOBean->name}</span>");
        } else {
            $LPOBean = array_shift($existingLPO);
            // SugarApplication::appendSuccessMessage("<span class='label label-success'> {$mod_strings['LBL_NOTIFICATION_CAMPAIGN_ALREADY_EXISTS_FROM_SIGNATURE']} {$LPOBean->name}</span>");
        }

        // Link each signer to the LPO
        switch ($userOrContactsModule) {
            case 'Users':
                foreach ($userOrContactsTargets as $userTarget) {
                    $LPOBean->load_relationship('users');
                    $LPOBean->users->add($userTarget->parent_id);
                }

                break;
            case 'Contacts':
                foreach ($userOrContactsTargets as $contactTarget) {
                    $LPOBean->load_relationship('contacts');
                    $LPOBean->contacts->add($contactTarget->parent_id);
                }
                break;
            default:
                $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ':  Unsupported module for LPO creation: ' . $userOrContactsModule);
                return false;
        }

        return ['status' => 'success', 'lpoId' => $LPOBean->id, 'lpoName' => $LPOBean->name];

    }
}
