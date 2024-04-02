<?php

class seven_sms_inbound extends Basic {
    public $account_id; // non CRM field
    public $contact_id; // non CRM field
    public $created_by;
    public $created_by_link;
    public $created_by_name;
    public $date_entered;
    public $date_modified;
    public $deleted;
    public $description;
    public $employee_id; // non CRM field
    public $id;
    public $importable = false;
    public $inbound_number; // non CRM field
    public $lead_id; // non CRM field
    public $modified_by_name;
    public $modified_user_id;
    public $modified_user_link;
    public $module_dir = 'seven_sms_inbound';
    public $msg_id; // non CRM field
    public $msg_text; // non CRM field
    public $msg_time; // non CRM field
    public $name;
    public $new_schema = true;
    public $object_name = 'seven_sms_inbound';
    public $SecurityGroups;
    public $sender; // non CRM field
    public $table_name = 'seven_sms_inbound';

    public function bean_implements($interface) {
        switch ($interface) {
            case 'ACL':
                return true;
        }

        return false;
    }
}
