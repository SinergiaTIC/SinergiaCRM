<?php
/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2018 SalesAgility Ltd.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
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
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo and "Supercharged by SuiteCRM" logo. If the display of the logos is not
 * reasonably feasible for technical reasons, the Appropriate Legal Notices must
 * display the words "Powered by SugarCRM" and "Supercharged by SuiteCRM".
 */

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class stic_MessagesMan extends SugarBean
{

    /** @var string */
    public $id;

    /** @var string */
    public $deleted;

    /** @var string */
    public $date_created;

    /** @var string */
    public $date_modified;

    /** @var string */
    public $module;

    /** @var string */
    public $module_id;

    /** @var string */
    public $marketing_id;

    /** @var string */
    public $campaign_id;

    /** @var string */
    public $user_id;

    /** @var string */
    public $list_id;

    /** @var string */
    public $in_queue;

    /** @var string */
    public $in_queue_date;

    /** @var string */
    public $template_id;

    /** @var string */
    public $send_date_time;

    /** @var string */
    public $table_name = "stic_messagesman";

    /** @var string */
    public $object_name = "stic_MessagesMan";

    /** @var string */
    public $module_dir = "stic_MessagesMan";

    /** @var string */
    public $send_attempts;

    /** @var string */
    public $related_id;

    /** @var string */
    public $related_type;

    // TODOEPS: Campos necesarios?
    // /** @var  EmailTemplate $current_emailtemplate */
    // public $current_emailtemplate;

    // /** @var bool $related_confirm_opt_in*/
    // public $related_confirm_opt_in;

    // /** @var bool  $test*/
    // public $test = false;

    // /** @var array  $notes_array*/
    // public $notes_array = array();

    // /** @var array $verified_email_marketing_ids */
    // public $verified_email_marketing_ids = array();

    // /** @var bool $new_schema */
    // public $new_schema = true;

    // /**
    //  * last opt in warning stored
    //  *
    //  * @var bool
    //  */
    // protected $optInWarn;

    /**
     * @var string
     */
    protected $targetId;

    /**
     * @return string
     */
    public function toString()
    {
        return "stic_MessagesMan:\nid = $this->id ,user_id= $this->user_id module = $this->module , related_id = $this->related_id , related_type = $this->related_type ,list_id = $this->list_id, send_date_time= $this->send_date_time\n";
    }

    // This is used to retrieve related fields from form posts.
    public $additional_column_fields = array();

    /**
     * EmailMan constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }



    /**
     * @param string $order_by
     * @param string $where
     * @param array $filter
     * @param array $params
     * @param int $show_deleted
     * @param string $join_type
     * @param bool $return_array
     * @param null $parentbean
     * @param bool $singleSelect
     * @param bool $ifListForExport
     * @return array|string
     */
    public function create_new_list_query(
        $order_by,
        $where,
        $filter = array(),
        $params = array(),
        $show_deleted = 0,
        $join_type = '',
        $return_array = false,
        $parentbean = null,
        $singleSelect = false,
        $ifListForExport = false
    ) {
        $query = array('select' => '', 'from' => '', 'where' => '', 'order_by' => '');

        $recipientName = $this->getRecipientNameStatement();
        $recipientPhone = $this->getRecipientPhoneStatement();

        $query['select'] =
            'SELECT '
            . $this->table_name
            . '.* , '
            . 'campaigns.name as campaign_name, '
            . 'stic_message_marketing.name as message_name, '
            // . '(CASE related_type '
            // . 'WHEN \'Contacts\' THEN '
            // . $this->db->concat('contacts', array('first_name', 'last_name'), '&nbsp;')
            // . ' '
            // . 'WHEN \'Leads\' THEN '
            // . $this->db->concat('leads', array('first_name', 'last_name'), '&nbsp;')
            // . ' '
            // . 'WHEN \'Accounts\' THEN accounts.name '
            // . 'WHEN \'Users\' THEN '
            // . $this->db->concat('users', array('first_name', 'last_name'), '&nbsp;') . ' '
            // . "WHEN 'Prospects' THEN "
            // . $this->db->concat('prospects', array('first_name', 'last_name'), '&nbsp;')
            // . ' '
            // . 'END) recipient_name';
            . $recipientName . ' recipient_name,'
            . $recipientPhone . ' recipient_phone';

        $query['from'] =
            ' '
            . 'FROM ' . $this->table_name .' '
            . 'LEFT JOIN users ON users.id = '
                . $this->table_name . '.related_id '
                . 'and '
                . $this->table_name . '.related_type =\'Users\' '
            . 'LEFT JOIN contacts ON contacts.id = '
                . $this->table_name .'.related_id '
                . 'and '
                . $this->table_name .'.related_type =\'Contacts\' '
            . 'LEFT JOIN leads ON leads.id = '
                . $this->table_name .'.related_id '
                . 'and '
                . $this->table_name .'.related_type =\'Leads\' '
            . 'LEFT JOIN accounts ON accounts.id = '
            . $this->table_name  . '.related_id and '.$this->table_name.'.related_type =\'Accounts\' '
            . 'LEFT JOIN prospects ON prospects.id = '.$this->table_name.'.related_id and '.$this->table_name.'.related_type =\'Prospects\' '
            . 'LEFT JOIN prospect_lists ON prospect_lists.id = '.$this->table_name.'.list_id '
            // . 'LEFT JOIN email_addr_bean_rel ON email_addr_bean_rel.bean_id = '.$this->table_name.'.related_id and '.$this->table_name.'.related_type = email_addr_bean_rel.bean_module and email_addr_bean_rel.primary_address = 1 and email_addr_bean_rel.deleted=0 '
            . 'LEFT JOIN campaigns ON campaigns.id = '.$this->table_name.'.campaign_id '
            . 'LEFT JOIN stic_message_marketing ON stic_message_marketing.id = '.$this->table_name.'.marketing_id ';

        $where_auto = " $this->table_name.deleted=0";

        if (!empty($where)) {
            $query['where'] = "WHERE $where AND " . $where_auto;
        } else {
            $query['where'] = "WHERE " . $where_auto;
        }

        if (isset($params['group_by'])) {
            $query['group_by'] .= " GROUP BY {$params['group_by']}";
        }

        $order_by = $this->process_order_by($order_by);
        if (!empty($order_by)) {
            $query['order_by'] = ' ORDER BY ' . $order_by;
        }

        if ($return_array) {
            return $query;
        }

        return $query['select'] . $query['from'] . $query['where'] . $query['order_by'];
    }

    protected function getRecipientNameStatement() {
        require_once('modules/stic_Messages/Utils.php');

        $recipientNameStatement = "(CASE related_type ";
        foreach(stic_MessagesUtils::getMessageableModules() as $module) {
            $nameFieldName = stic_MessagesUtils::getNameFieldForSql($module);
            $recipientNameStatement .= "WHEN '{$module}' THEN {$nameFieldName} ";
        }
        $recipientNameStatement.= " END)";

        return $recipientNameStatement;
    }

    protected function getRecipientPhoneStatement() {
        require_once('modules/stic_Messages/Utils.php');

        $recipientPhoneStatement = "(CASE related_type ";
        foreach(stic_MessagesUtils::getMessageableModules() as $module) {
            $phoneFieldName = stic_MessagesUtils::getPhoneFieldForSql($module);
            $recipientPhoneStatement .= "WHEN '{$module}' THEN {$phoneFieldName} ";
        }
        $recipientPhoneStatement.= " END)";

        return $recipientPhoneStatement;
    }


    /**
     * @param $order_by
     * @param $where
     * @param int $show_deleted
     * @return string
     */
    public function create_list_query($order_by, $where, $show_deleted = 0)
    {
        $query =
            "SELECT $this->table_name.* ,campaigns.name as campaign_name,email_marketing.name as message_name,(CASE related_type WHEN 'Contacts' THEN "
            . $this->db->concat('contacts', array('first_name', 'last_name'), '&nbsp;')
            . "WHEN 'Leads' THEN "
            . $this->db->concat('leads', array('first_name', 'last_name'), '&nbsp;')
            . "WHEN 'Accounts' THEN accounts.name WHEN 'Users' THEN "
            . $this->db->concat('users', array('first_name', 'last_name'), '&nbsp;')
            . "WHEN 'Prospects' THEN "
            . $this->db->concat('prospects', array('first_name', 'last_name'), '&nbsp;')
            . "END) recipient_name";
        $query .= '    FROM '.$this->table_name.' '
            . 'LEFT JOIN users ON users.id = '.$this->table_name.'.related_id and '.$this->table_name.'.related_type =\'Users\' '
            . 'LEFT JOIN contacts ON contacts.id = '.$this->table_name.'.related_id and '.$this->table_name.'.related_type =\'Contacts\' '
            . 'LEFT JOIN leads ON leads.id = '.$this->table_name.'.related_id and '.$this->table_name.'.related_type =\'Leads\' '
            . 'LEFT JOIN accounts ON accounts.id = '.$this->table_name.'.related_id and '.$this->table_name.'.related_type =\'Accounts\' '
            . 'LEFT JOIN prospects ON prospects.id = '.$this->table_name.'.related_id and '.$this->table_name.'.related_type =\'Prospects\' '
            . 'LEFT JOIN prospect_lists ON prospect_lists.id = '.$this->table_name.'.list_id '
            // . 'LEFT JOIN email_addr_bean_rel ON email_addr_bean_rel.bean_id = '
            // . $this->table_name.'.related_id and '
            // . $this->table_name.'.related_type = email_addr_bean_rel.bean_module and email_addr_bean_rel.primary_address = 1 and email_addr_bean_rel.deleted=0 '
            . 'LEFT JOIN campaigns ON campaigns.id = '.$this->table_name.'.campaign_id '
            . 'LEFT JOIN stic_message_marketing ON stic_message_marketing.id = '.$this->table_name.'.marketing_id';


        $where_auto = " $this->table_name.deleted=0";

        if ($where != "") {
            $query .= "where $where AND " . $where_auto;
        } else {
            $query .= "where " . $where_auto;
        }

        $order_by = $this->process_order_by($order_by);
        if (!empty($order_by)) {
            $query .= ' ORDER BY ' . $order_by;
        }

        return $query;
    }

  

    // TODOEPS: Revisar si es necesaria
    /**
     * @return array
     */
    // public function get_list_view_data()
    // {
    //     global $locale, $current_user;
    //     $temp_array = parent::get_list_view_array();

    //     $related_type = isset($temp_array['RELATED_TYPE']) ? $temp_array['RELATED_TYPE'] : null;

    //     if (!isset($temp_array['RELATED_ID'])) {
    //         LoggerManager::getLogger()->warn('EmailMan List view array has not related id for list view data');
    //         $tempArrayRelatedId = null;
    //     } else {
    //         $tempArrayRelatedId = $temp_array['RELATED_ID'];
    //     }

    //     $related_id = $tempArrayRelatedId;
    //     $is_person = SugarModule::get($related_type)->moduleImplements('Person');

    //     if ($is_person) {
    //         $query = "SELECT first_name, last_name FROM " . strtolower($related_type) . " WHERE id ='" . $related_id . "'";
    //     } else {
    //         $query = "SELECT name FROM " . strtolower($related_type) . " WHERE id ='" . $related_id . "'";
    //     }

    //     $result = $this->db->query($query);
    //     $row = $this->db->fetchByAssoc($result);

    //     if ($row) {
    //         $temp_array['RECIPIENT_NAME'] = $is_person ? $locale->getLocaleFormattedName(
    //             $row['first_name'],
    //             $row['last_name'],
    //             ''
    //         ) : $row['name'];
    //     }

    //     //also store the recipient_email address
    //     $query = "SELECT addr.email_address FROM email_addresses addr,email_addr_bean_rel eb WHERE eb.deleted=0 AND addr.id=eb.email_address_id AND bean_id ='" . $related_id . "' AND primary_address = '1'";

    //     $result = $this->db->query($query);
    //     $row = $this->db->fetchByAssoc($result);
    //     if ($row) {
    //         $temp_array['RECIPIENT_EMAIL'] = $row['email_address'];
    //     }

    //     if (!isset($temp_array['RECIPIENT_EMAIL'])) {
    //         LoggerManager::getLogger()->warn('EmailMan List view array has not recipient email for list view data');
    //         $temArrayRecipientEmail = null;
    //     } else {
    //         $temArrayRecipientEmail = $temp_array['RECIPIENT_EMAIL'];
    //     }

    //     $this->email1 = $temArrayRecipientEmail;
    //     $temp_array['EMAIL1_LINK'] = $current_user->getEmailLink('email1', $this, '', '', 'ListView');

    //     return $temp_array;
    // }


    public function sendMessage($sender, $templateId, $type, $test = false) {
        require_once 'modules/stic_Messages/Utils.php';
        require_once 'modules/stic_Settings/Utils.php';
        include_once 'modules/EmailTemplates/EmailTemplate.php';

        $bean = BeanFactory::getBean($this->related_type, $this->related_id);
        $targetPhone = stic_MessagesUtils::getPhoneForMessage($bean);

        if (!$test){
            $mustBeBlocked = $this->checkPresentInExemptLists();
            if ($mustBeBlocked) {
                $this->saveLog('blocked', $targetPhone, true);
                return true;
            }
    
            $alreadySent = $this->checkAlreadySent($targetPhone);
            if ($alreadySent) {
                $this->saveLog('blocked', $targetPhone, true);
                return true; 
            }
        }

        // Recuperamos el template (si lo hay)
        $emailTemplate = BeanFactory::getBean('EmailTemplates', $templateId);
        $txt = $emailTemplate->body;

        $messageBean = BeanFactory::newBean('stic_Messages');
        $txt = $messageBean->replaceTemplateVariables($txt, $bean);

        $messageBean->sender = $sender;
        $messageBean->template_id_c = $templateId;
        $messageBean->status = 'sent';
        $messageBean->type = $type;
        $messageBean->direction = 'outbound';
        $messageBean->phone = $targetPhone;
        $messageBean->message = $txt;
        $name = $messageBean->fillName($bean->module_name, $bean->id);
        $messageBean->name = $name;
        $messageBean->parent_type = $relatedType;
        $messageBean->parent_id = $relatedId;
        $messageBean->save();

        if ($messageBean->status != 'sent') {
            // TODOEPS
            $this->saveLog('send error', $targetPhone, true);
            return false;
        }
        $this->saveLog('targeted', $targetPhone, true, $test);

        return true;
    }

    protected function checkAlreadySent($targetPhone) {
        $db = DBManagerFactory::getInstance();

        $query = "select 1 from campaign_log where more_information='{$targetPhone}' and marketing_id='{$this->marketing_id}' and deleted = 0 and activity_type='targeted'";

        $result = $db->getOne($query);

        return $result;
    }

    public function checkPresentInExemptLists() {
        $db = DBManagerFactory::getInstance();
        $sql = "
            select 1
            from  prospect_list_campaigns plc 
            join prospect_lists pl on pl.id = plc.prospect_list_id 
            join prospect_lists_prospects plp on plp.prospect_list_id = pl.id
            WHERE plc.campaign_id = '{$this->campaign_id}'
            AND pl.list_type = 'exempt'
            and plp.related_id = '{$this->related_id}'
            and plp.related_type =  '{$this->related_type}'
            and plp.deleted = 0
            and pl.deleted = 0
            and plc.deleted = 0
        ";

        $result = $db->getOne($sql);

        if ($result) {
            return true;
        }
        
        return false;
    }

    public function saveLog($activity_type, $targetPhone, $delete=false, $test = false) {
        // TODOEPS: If attempts is not implemented, we can remove the $delete parameter
        global $timedate;

         //create new campaign log record.
         $campaign_log = BeanFactory::newBean('CampaignLog');
         $campaign_log->campaign_id = $this->campaign_id;
         $campaign_log->target_id = $this->related_id;
         $campaign_log->target_type = $this->related_type;
         $campaign_log->marketing_id = $this->marketing_id;

         if (!$test) {
             $campaign_log->more_information = $targetPhone;
         }

         $campaign_log->activity_type = $activity_type;
         $campaign_log->activity_date = $timedate->nowDb(); //$timedate->now(); //TODOEPS: Recuperar el temps amb segons
         $campaign_log->list_id = $this->list_id;
         $campaign_log->related_id = $this->related_id;
         $campaign_log->related_type = $this->related_type;
        //  $campaign_log->resend_type = $resend_type;
         $campaign_log->save();

         // TODOEPS: Remove comments once tested finalized 
         if($delete) {
            $this->id = (int)$this->id;
            $query = "DELETE FROM stic_messagesman WHERE id = {$this->id}";
            $this->db->query($query);
         }
    }



    /**
     * @param $email_address
     * @param bool $delete
     * @param null $email_id
     * @param null $email_type
     * @param null $activity_type
     * @param null $resend_type
     */
    public function set_as_sent(
        $phone,
        $delete = true,
        $email_id = null,
        $email_type = null,
        $activity_type = null,
        $resend_type = null
    ) {
        global $timedate;

        $this->send_attempts++;
        $this->id = (int)$this->id;
        if ($delete || $this->send_attempts > 5) {

            //create new campaign log record.
            $campaign_log = BeanFactory::newBean('CampaignLog');
            $campaign_log->campaign_id = $this->campaign_id;
            $campaign_log->target_tracker_key = $this->getTargetId();
            $campaign_log->target_id = $this->related_id;
            $campaign_log->target_type = $this->related_type;
            $campaign_log->marketing_id = $this->marketing_id;

            // if test suppress duplicate email address checking.
            if (!$this->test) {
                $campaign_log->more_information = $email_address;
            }
            $campaign_log->activity_type = $activity_type;
            $campaign_log->activity_date = $timedate->now();
            $campaign_log->list_id = $this->list_id;
            $campaign_log->related_id = $email_id;
            $campaign_log->related_type = $email_type;
            $campaign_log->resend_type = $resend_type;
            $campaign_log->save();

            $query = "DELETE FROM stic_messagesman WHERE id = $this->id";
            $this->db->query($query);
        } else {
            // try to send the email again a day later.
            $query = 'UPDATE ' . $this->table_name . " SET in_queue='1', send_attempts='$this->send_attempts', in_queue_date=" . $this->db->now() . " WHERE id = $this->id";
            $this->db->query($query);
        }
    }

        /**
     * @param $order_by
     * @param $where
     * @param array $filter
     * @param array $params
     * @param int $show_deleted
     * @param string $join_type
     * @param bool $return_array
     * @param null $parentbean
     * @param bool $singleSelect
     * @return string
     */
    public function create_queue_items_query(
        $order_by,
        $where,
        $filter = array(),
        $params = array(),
        $show_deleted = 0,
        $join_type = '',
        $return_array = false,
        $parentbean = null,
        $singleSelect = false
    ) {
        if ($return_array) {
            return parent::create_new_list_query(
                $order_by,
                $where,
                $filter,
                $params,
                $show_deleted,
                $join_type,
                $return_array,
                $parentbean,
                $singleSelect
            );
        }

        $query =
            "SELECT $this->table_name.* , campaigns.name as campaign_name, stic_message_marketing.name as message_name, (CASE related_type WHEN 'Contacts' THEN "
            . $this->db->concat('contacts', array('first_name', 'last_name'), '&nbsp;') . " WHEN 'Leads' THEN "
            . $this->db->concat('leads', array('first_name', 'last_name'), '&nbsp;') . " WHEN 'Accounts' THEN accounts.name WHEN 'Users' THEN "
            . $this->db->concat('users', array('first_name', 'last_name'), '&nbsp;') . " WHEN 'Prospects' THEN "
            . $this->db->concat('prospects', array('first_name', 'last_name'), '&nbsp;') . ' '
            . "END) recipient_name";

        $query .=
            ' FROM '. $this->table_name
            . ' '
            . 'LEFT JOIN users ON users.id = '. $this->table_name .'.related_id and '. $this->table_name .'.related_type =\'Users\' '
            . 'LEFT JOIN contacts ON contacts.id = '. $this->table_name .'.related_id and '. $this->table_name .'.related_type =\'Contacts\' '
            . 'LEFT JOIN leads ON leads.id = '. $this->table_name .'.related_id and '. $this->table_name .'.related_type =\'Leads\' '
            . 'LEFT JOIN accounts ON accounts.id = '. $this->table_name .'.related_id and '. $this->table_name .'.related_type =\'Accounts\' '
            . 'LEFT JOIN prospects ON prospects.id = '. $this->table_name .'.related_id and '. $this->table_name .'.related_type =\'Prospects\' '
            . 'LEFT JOIN prospect_lists ON prospect_lists.id = '. $this->table_name .'.list_id '
            // . 'LEFT JOIN email_addr_bean_rel ON email_addr_bean_rel.bean_id = '. $this->table_name .'.related_id and '
            // . $this->table_name
            // .'.related_type = email_addr_bean_rel.bean_module and email_addr_bean_rel.primary_address = 1 and email_addr_bean_rel.deleted=0 '
            . 'LEFT JOIN campaigns ON campaigns.id = '. $this->table_name .'.campaign_id '
            // . 'LEFT JOIN email_marketing ON email_marketing.id = '. $this->table_name .'.marketing_id ';
            . 'LEFT JOIN stic_message_marketing ON stic_message_marketing.id = '. $this->table_name .'.marketing_id ';

            //B.F. #37943
        if (isset($params['group_by'])) {
            $group_by = str_replace("stic_messagesman", "em", (string) $params['group_by']);
            $query .= "INNER JOIN (select min(id) as id from stic_messagesman em GROUP BY $group_by) secondary on {$this->table_name}.id = secondary.id ";
        }

        $where_auto = " $this->table_name.deleted=0";

        if ($where != "") {
            $query .= "WHERE $where AND " . $where_auto;
        } else {
            $query .= "WHERE " . $where_auto;
        }

        $order_by = $this->process_order_by($order_by);
        if (!empty($order_by)) {
            $query .= ' ORDER BY ' . $order_by;
        }

        return $query;
    }


}
