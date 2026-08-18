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
-- Feature: stic_Recycle_Bin_Relationships module
-- Renames recyclebin_id column to stic_recycle_bin_id to follow the standard
-- SinergiaCRM naming convention for foreign key columns (<module>_id).
-- Renames the corresponding index accordingly.
-- =========================================================================

ALTER TABLE `stic_recycle_bin_relationships`
    CHANGE COLUMN `recyclebin_id` `stic_recycle_bin_id` VARCHAR(36) NULL;

ALTER TABLE `stic_recycle_bin_relationships`
    DROP INDEX `idx_stic_rbr_bin_id`;

ALTER TABLE `stic_recycle_bin_relationships`
    ADD INDEX `idx_stic_rbr_recycle_bin_id` (`stic_recycle_bin_id`);
