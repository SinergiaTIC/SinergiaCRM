<?php

require_once 'modules/Accounts/Account.php';

/**
 * Extend the Account class to add custom logic.
 * Due to the complexity and importance of the Account class and because it does not follow the standard SugarCRM structure of the SugarBean extension, this class is not used directly in the normal operation of SinergiaCRM
 */
class AccountsUtils
{
    /**
     * When the field Reason return mail takes the values "wrong_address, unknown, rejected"
     * will automatically generate a Call associated with the account, to address the reason for the return of the mail.
     * The reason for the return (field value) will be indicated in the subject of the call.
     *
     * @param Object $accountBean
     * @return void
     */
    public static function generateCallFromReturnMailReason($accountBean)
    {
        global $app_strings;
        $reasons = array('wrong_address', 'unknown', 'rejected');

        if (in_array($accountBean->stic_postal_mail_return_reason_c, $reasons)) {
            global $current_user, $timedate, $app_strings;

            // Create the new call
            $callBean = BeanFactory::getBean('Calls');
            $callDate = $timedate->fromDb(gmdate('Y-m-d H:i:s'));
            $callBean->date_start = $timedate->asUser($callDate, $current_user);
            $callBean->name = $app_strings['LBL_STIC_MAIL_RETURN_REASON'] . ": " . $GLOBALS['app_list_strings']['stic_postal_mail_return_reasons_list'][$accountBean->stic_postal_mail_return_reason_c];
            $callBean->assigned_user_id = (empty($accountBean->assigned_user_id) ? $current_user->id : $accountBean->assigned_user_id);
            $callBean->direction = 'Outbound';
            $callBean->status = 'Planned';
            $callBean->parent_type = 'Accounts';
            $callBean->parent_id = $accountBean->id;
            $callBean->save();
        }
    }
}
