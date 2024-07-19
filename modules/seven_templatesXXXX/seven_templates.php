<?php

class seven_templates extends Basic {
    public $created_by;
    public $created_by_link;
    public $created_by_name;
    public $date_entered;
    public $date_modified;
    public $deleted;
    public $description;
    public $id;
    public $importable = false;
    public $label; // non CRM field
    public $modified_by_name;
    public $modified_user_id;
    public $modified_user_link;
    public $module_dir = 'seven_templates';
    public $name;
    public $new_schema = true;
    public $object_name = 'seven_templates';
    public $SecurityGroups;
    public $table_name = 'seven_templates';
    public $text; // non CRM field
    public $type; // non CRM field

    public function bean_implements($interface) {
        switch ($interface) {
            case 'ACL':
                return true;
        }

        return false;
    }
}
