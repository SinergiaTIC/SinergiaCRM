-- Post-install shrink of legacy contacts_cstm VARCHAR columns.
-- This file is run from post_install.txt, after SticUpdate's vardef repair pass.
-- These instances have reached the InnoDB row size limit for contacts_cstm;
-- shrinking fields that never approach 255 bytes reclaims row space.
-- IMPORTANT: run SticUpdates/Checks/20260915_checkContactsCstmDataLength.sql
-- before this migration on each instance. Review/truncate any values exceeding
-- the target lengths first to avoid errors in strict mode or silent truncation.

-- 1 field -> VARCHAR(50)
ALTER TABLE `contacts_cstm`
    MODIFY COLUMN `stic_identification_number_c`        VARCHAR(50) NULL DEFAULT NULL;

-- 1 field -> VARCHAR(150)
ALTER TABLE `contacts_cstm`
    MODIFY COLUMN `stic_tax_name_c`                    VARCHAR(150) NULL DEFAULT NULL;

-- 18 fields -> VARCHAR(100)
ALTER TABLE `contacts_cstm`
    MODIFY COLUMN `stic_professional_sector_other_c`    VARCHAR(100) NULL DEFAULT NULL,
    MODIFY COLUMN `inc_address_block_c`                 VARCHAR(100) NULL DEFAULT NULL,
    MODIFY COLUMN `inc_address_district_c`              VARCHAR(100) NULL DEFAULT NULL,
    MODIFY COLUMN `inc_address_door_c`                  VARCHAR(100) NULL DEFAULT NULL,
    MODIFY COLUMN `inc_address_floor_c`                 VARCHAR(100) NULL DEFAULT NULL,
    MODIFY COLUMN `inc_address_num_a_c`                  VARCHAR(100) NULL DEFAULT NULL,
    MODIFY COLUMN `inc_address_num_b_c`                  VARCHAR(100) NULL DEFAULT NULL,
    MODIFY COLUMN `inc_address_postal_code_c`            VARCHAR(100) NULL DEFAULT NULL,
    MODIFY COLUMN `inc_address_street_c`                VARCHAR(100) NULL DEFAULT NULL,
    MODIFY COLUMN `inc_driving_licenses_c`               VARCHAR(100) NULL DEFAULT NULL,
    MODIFY COLUMN `inc_employ_office_reg_time_c`         VARCHAR(100) NULL DEFAULT NULL,
    MODIFY COLUMN `inc_max_commuting_time_c`             VARCHAR(100) NULL DEFAULT NULL,
    MODIFY COLUMN `inc_state_c`                          VARCHAR(100) NULL DEFAULT NULL,
    MODIFY COLUMN `inc_municipality_c`                   VARCHAR(100) NULL DEFAULT NULL,
    MODIFY COLUMN `inc_town_c`                           VARCHAR(100) NULL DEFAULT NULL,
    MODIFY COLUMN `stic_time_availability_c`             VARCHAR(100) NULL DEFAULT NULL,
    MODIFY COLUMN `stic_pa_username_c`                   VARCHAR(100) NULL DEFAULT NULL,
    MODIFY COLUMN `stic_pa_password_c`                  VARCHAR(100) NULL DEFAULT NULL;

-- Target lengths match custom/Extension/modules/Contacts/Ext/Vardefs/SticVardefs.php.
