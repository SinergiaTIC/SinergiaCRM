<?php
require_once 'include/MVC/View/SugarView.php';

class AdministrationViewSticportalconfigaudit extends SugarView
{
    public function preDisplay()
    {
        global $current_user;
        if (!is_admin($current_user)) {
            sugar_die("Unauthorized access to administration.");
        }
        $this->ss->assign('RETURN_MODULE', 'Administration');
        $this->ss->assign('RETURN_ACTION', 'sticportalconfig');
    }

    public function display()
    {
        global $mod_strings, $db;
        // Purge audit records older than the configured retention period (admin-triggered maintenance)
        require_once 'SticInclude/Portal/ConfigUtils.php';
        SticPortalConfigUtils::purgeOldAudit();
        $audit = [];
        $r = $db->query("SELECT * FROM stic_portal_login_audit WHERE deleted=0 ORDER BY date_entered DESC LIMIT 100");
        while ($row = $db->fetchByAssoc($r)) {
            $row['result_label'] = ($row['success'] == 1)
                ? ($mod_strings['LBL_STIC_PORTAL_AUDIT_RESULT_SUCCESS'] ?? 'Success')
                : ($mod_strings['LBL_STIC_PORTAL_AUDIT_RESULT_FAILURE'] ?? 'Failure');

            $typeLabels = array(
                'Contact' => 'LBL_STIC_PORTAL_AUDIT_TYPE_CONTACT',
                'Contacts' => 'LBL_STIC_PORTAL_AUDIT_TYPE_CONTACT',
                'Account' => 'LBL_STIC_PORTAL_AUDIT_TYPE_ACCOUNT',
                'Accounts' => 'LBL_STIC_PORTAL_AUDIT_TYPE_ACCOUNT',
                'RESET_SENT' => 'LBL_STIC_PORTAL_AUDIT_TYPE_RESET_SENT',
            );
            $row['type_label'] = isset($typeLabels[$row['parent_type']])
                ? ($mod_strings[$typeLabels[$row['parent_type']]] ?? $row['parent_type'])
                : ($row['parent_type'] ?? '');

            $reasonLabels = array(
                'invalid_credentials' => 'LBL_STIC_PORTAL_AUDIT_REASON_INVALID_CREDENTIALS',
                'not_found' => 'LBL_STIC_PORTAL_AUDIT_REASON_NOT_FOUND',
                'locked_out' => 'LBL_STIC_PORTAL_AUDIT_REASON_LOCKED_OUT',
                'ip_locked' => 'LBL_STIC_PORTAL_AUDIT_REASON_IP_LOCKED',
            );
            $row['reason_label'] = isset($reasonLabels[$row['failure_reason']])
                ? ($mod_strings[$reasonLabels[$row['failure_reason']]] ?? $row['failure_reason'])
                : ($row['failure_reason'] ?? '');

            $methodLabels = array(
                'password' => 'LBL_STIC_PORTAL_AUDIT_METHOD_PASSWORD',
                'invitation' => 'LBL_STIC_PORTAL_AUDIT_METHOD_INVITATION',
                'magic_link' => 'LBL_STIC_PORTAL_AUDIT_METHOD_MAGIC_LINK',
                'admin_reset' => 'LBL_STIC_PORTAL_AUDIT_METHOD_ADMIN_RESET',
                'remember' => 'LBL_STIC_PORTAL_AUDIT_METHOD_REMEMBER',
            );
            $row['method_label'] = isset($methodLabels[$row['auth_method']])
                ? ($mod_strings[$methodLabels[$row['auth_method']]] ?? $row['auth_method'])
                : ($row['auth_method'] ?? '');

            $audit[] = $row;
        }
        $this->ss->assign('MOD', $mod_strings);
        $this->ss->assign('AUDIT', $audit);
        echo $this->ss->fetch('custom/modules/Administration/templates/SticPortalAudit.tpl');
    }
}
