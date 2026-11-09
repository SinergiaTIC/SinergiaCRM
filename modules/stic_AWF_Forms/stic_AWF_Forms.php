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

class stic_AWF_Forms extends Basic
{
    public $new_schema = true;
    public $module_dir = 'stic_AWF_Forms';
    public $object_name = 'stic_AWF_Forms';
    public $table_name = 'stic_awf_forms';
    public $importable = false;

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
    public $start_date;
    public $end_date;
    public $public_url;
    public $processing_mode;
    public $configuration;
    public $custom_css;
    public $custom_js;
    public $custom_header_html;
    public $custom_footer_html;
    public $analytics_views;
    public $analytics_blocked;
    public $analytics_submissions;
    public $analytics_spam;

	
    public function bean_implements($interface)
    {
        switch($interface)
        {
            case 'ACL':
                return true;
        }

        return false;
    }

    public function cleanBean()
    {
        // Protect the configuration JSON from HTML sanitization.
        // cleanBean() runs purify_html() on every text-like field, treating the JSON as HTML
        // (structural quotes encoded, entities double-encoded); temporarily clearing the
        // property makes it skip the sanitization and it is restored before the write.

        $configurationBackup = $this->configuration ?? null;
        $this->configuration = null;
        parent::cleanBean();
        if ($configurationBackup !== null) {
            $this->configuration = $configurationBackup;
        }
    }

    public function save($check_notify = false)
    {
        // Persist the configuration JSON bit-for-bit.
        // The generic write applies from_html() (entities double-decoded) to every field:
        // entity-like or quoted sequences inside JSON values would corrupt the stored JSON.
        // When a new configuration is set on the bean (differs from fetched_row), entity-like
        // sequences inside JSON values are normalized first, and the pristine bytes are
        // restored with a targeted UPDATE after the standard write (raw MySQL escaping,
        // since DBManager::quote() decodes entities). Unrelated saves keep the standard write.

        if ($this->new_with_id && empty($this->fetched_row['id'])) {
            $this->reset_analytics_and_public_url();
        }

        $configurationPristine = is_string($this->configuration ?? null) ? $this->configuration : null;
        // New records have no fetched value, so any set configuration counts as new.
        $configurationModified = $configurationPristine !== null
            && $configurationPristine !== ($this->fetched_row['configuration'] ?? null);

        // Normalize entity-like sequences inside JSON values (e.g. AJAX-provided record
        // names containing &quot;) so the single html_entity_decode of the read paths
        // cannot break the JSON structure on the next editor load.
        if ($configurationModified) {
            require_once "modules/stic_AWF_Forms/Utils.php";
            $configurationPristine = stic_AWF_FormsUtils::normalizeConfigurationJson($configurationPristine);
        }

        $saveResult = parent::save($check_notify);

        // Restore pristine JSON bytes only when the configuration was explicitly set on the bean (new record or wizard save).
        if ($configurationModified) {
            global $db;
            $mysqli = $db->getDatabase();
            $escape = function_exists("mysqli_real_escape_string") && $mysqli !== null
                ? fn (string $v): string => mysqli_real_escape_string($mysqli, $v)
                : fn (string $v): string => addslashes($v);
            $safeId = $escape($this->id);
            $safeConfiguration = $escape($configurationPristine);
            $db->query("UPDATE stic_awf_forms SET configuration = '$safeConfiguration' WHERE id = '$safeId' AND deleted = 0");
        }

        return $saveResult;
    }

    public function reset_analytics_and_public_url()
    {
        $this->analytics_views = 0;
        $this->analytics_blocked = 0;
        $this->analytics_submissions = 0;
        $this->analytics_spam = 0;
        $this->analytics_referrers = '';
        $this->public_url = '';
    }
}
