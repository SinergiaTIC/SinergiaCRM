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

class stic_Registrations extends Basic
{
    public $new_schema = true;
    public $module_dir = 'stic_Registrations';
    public $object_name = 'stic_Registrations';
    public $table_name = 'stic_registrations';
    public $importable = true;

    public $id;
    public $name;
    public $date_entered;
    public $date_modified;
    public $modified_user_id;
    public $modified_by_name;
    public $created_by;
    public $created_by_name;
    public $description;
    public $deleted;
    public $created_by_link;
    public $modified_user_link;
    public $assigned_user_id;
    public $assigned_user_name;
    public $assigned_user_link;
    public $SecurityGroups;
    public $status;
    public $registration_date;
    public $not_participating_reason;
    public $rejection_reason;
    public $participation_type;
    public $special_needs;
    public $special_needs_description;
    public $attendance_percentage;
    public $attended_hours;
    public $qrcode;

    public function bean_implements($interface)
    {
        switch ($interface) {
            case 'ACL':
                return true;
        }

        return false;
    }

    /**
     * Generate a QR code using the library included in SuiteCRM TCPDF2DBarcode
     */

    private function generateQRCode()
    {
        global $sugar_config;

        // QR Url
        $url = $sugar_config['site_url'] .'/index.php?action=DetailView&module=stic_Registrations&record=' . $this->id;
        
        // Create a folder to save QR codes if it doesn't exist
        $qrFolder = 'custom/uploads/qrcodes/';
        if (!file_exists($qrFolder)) {
            mkdir($qrFolder, 0755, true);
        }

        // Path where the QR image will be saved
        $qrImagePath = $qrFolder . $this->id . '_qrcode.png';

        // Generate the QR code using the internal library
        $qrCode = new TCPDF2DBarcode($url, 'QRCODE,H');

        // Save the QR Code image as a PNG file
        $qrImage = $qrCode->getBarcodePngData(6, 6, array(0, 0, 0));
        file_put_contents($qrImagePath, $qrImage);

        return $sugar_config['site_url'] . '/' . $qrImagePath;
    }


    public function save($check_notify = true)
    {
        $this->qrcode = $this->generateQRCode();

        // Save the bean
        parent::save($check_notify);
    }

    public function save_relationship_changes($is_update, $exclude = array())
    {
        if (!empty($this->stic_registrations_stic_eventsstic_events_ida) && (trim($this->stic_registrations_stic_eventsstic_events_ida) != trim($this->rel_fields_before_value['stic_registrations_stic_eventsstic_events_ida']))) {
            // On new records, inherit amount from related event
            if (empty($this->session_amount) && !$is_update) {
                $eventBean = BeanFactory::getBean('stic_Events', $this->stic_registrations_stic_eventsstic_events_ida);
                $this->session_amount = $eventBean->session_amount;
            }
        }
        parent::save_relationship_changes($is_update, $exclude);
    }
}
