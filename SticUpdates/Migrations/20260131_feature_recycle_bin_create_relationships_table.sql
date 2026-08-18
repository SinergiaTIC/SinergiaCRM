-- This file is part of SinergiaCRM.
-- SinergiaCRM is a work developed by SinergiaTIC Association, based on SuiteCRM.
-- Copyright (C) 2013 - 2023 SinergiaTIC Association
--
-- This program is free software; you can redistribute it and/or modify it under
-- the terms of the GNU Affero General Public License version 3 as published by
-- the Free Software Foundation.
--
-- This program is distributed in the hope that it will be useful, but WITHOUT
-- ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
-- FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
-- details.
--
-- You should have received a copy of the GNU Affero General Public License along
-- with this program; if not, see http://www.gnu.org/licenses or write to the Free
-- Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
-- 02110-1301 USA.
--
-- You can contact SinergiaTIC Association at email address info@sinergiacrm.org.

-- =========================================================================
-- Feature: stic_Recycle_Bin module
-- Creates the second table for the recycle bin: stic_recycle_bin_relationships
-- (The stic_recycle_bin table itself is created automatically by Quick Repair
-- and Rebuild from modules/stic_Recycle_Bin/vardefs.php.)
-- =========================================================================

CREATE TABLE IF NOT EXISTS `stic_recycle_bin_relationships` (
    `id` VARCHAR(36) NOT NULL PRIMARY KEY,
    `name` VARCHAR(255) NULL,
    `date_entered` DATETIME NULL,
    `date_modified` DATETIME NULL,
    `modified_user_id` VARCHAR(36) NULL,
    `created_by` VARCHAR(36) NULL,
    `description` TEXT NULL,
    `deleted` TINYINT(1) DEFAULT 0,
    `stic_recycle_bin_id` VARCHAR(36) NULL,
    `recycle_record_id` VARCHAR(36) NULL,
    `recycle_relationship_name` VARCHAR(255) NULL,
    `recycle_join_table` VARCHAR(255) NULL,
    `recycle_related_module` VARCHAR(100) NULL,
    `recycle_related_record_id` VARCHAR(36) NULL,
    `recycle_related_record_name` VARCHAR(255) NULL,
    `recycle_restored` TINYINT(1) DEFAULT 0,
    `recycle_join_lhs_key` VARCHAR(100) NULL,
    `recycle_join_rhs_key` VARCHAR(100) NULL,
    INDEX `idx_stic_rbr_recycle_bin_id` (`stic_recycle_bin_id`),
    INDEX `idx_stic_rbr_record_id` (`recycle_record_id`),
    INDEX `idx_stic_rbr_related_id` (`recycle_related_record_id`),
    INDEX `idx_stic_rbr_restored` (`recycle_restored`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
