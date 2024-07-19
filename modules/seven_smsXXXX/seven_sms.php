<?php

class seven_sms extends Basic {
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
    public $lead_id; // non CRM field
    public $modified_by_name;
    public $modified_user_id;
    public $modified_user_link;
    public $module_dir = 'seven_sms';
    public $name;
    public $new_schema = true;
    public $object_name = 'seven_sms';
    public $recipient; // non CRM field
    public $SecurityGroups;
    public $sender; // non CRM field
    public $table_name = 'seven_sms';
    public $text; // non CRM field

    public function bean_implements($interface) {
        switch ($interface) {
            case 'ACL':
                return true;
        }

        return false;
    }
}
