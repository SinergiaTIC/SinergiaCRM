-- =====================================================================
-- Pre-check for SticUpdates/Migrations/20260915_hotfix_shrinkContactsCstmColumns.sql
--
-- Purpose: verify, on any SinergiaCRM database, whether the 20
-- contacts_cstm columns being shrunk still hold data that would NOT fit
-- the new target length. Review manually before the migration, otherwise
-- the ALTER fails in strict mode / silently truncates data.
--
-- HOW TO USE (read-only; run it in each instance's database):
--   mysql -u <user> -p <sinergiadb> < SticUpdates/Checks/20260915_checkContactsCstmDataLength.sql
--
-- HOW TO READ THE OUTPUT:
--   Over_LIMIT=YES  -> stored data does not fit the new length; fix
--                      before applying the migration
--   OVER_LIMIT=NO   -> safe to shrink
-- =====================================================================
SELECT
    'stic_identification_number_c'      AS field_name,
    255 AS current_size,
    50  AS target_size,
    MAX(CHAR_LENGTH(stic_identification_number_c)) AS max_stored_length,
    IF(MAX(CHAR_LENGTH(stic_identification_number_c)) > 50, 'YES', 'NO') AS over_limit
FROM contacts_cstm

UNION ALL SELECT 'stic_professional_sector_other_c', 255, 100,
    MAX(CHAR_LENGTH(stic_professional_sector_other_c)),
    IF(MAX(CHAR_LENGTH(stic_professional_sector_other_c)) > 100, 'YES', 'NO') FROM contacts_cstm

UNION ALL SELECT 'stic_tax_name_c', 255, 150,
    MAX(CHAR_LENGTH(stic_tax_name_c)),
    IF(MAX(CHAR_LENGTH(stic_tax_name_c)) > 150, 'YES', 'NO') FROM contacts_cstm

UNION ALL SELECT 'inc_address_block_c', 255, 100,
    MAX(CHAR_LENGTH(inc_address_block_c)),
    IF(MAX(CHAR_LENGTH(inc_address_block_c)) > 100, 'YES', 'NO') FROM contacts_cstm

UNION ALL SELECT 'inc_address_district_c', 255, 100,
    MAX(CHAR_LENGTH(inc_address_district_c)),
    IF(MAX(CHAR_LENGTH(inc_address_district_c)) > 100, 'YES', 'NO') FROM contacts_cstm

UNION ALL SELECT 'inc_address_door_c', 255, 100,
    MAX(CHAR_LENGTH(inc_address_door_c)),
    IF(MAX(CHAR_LENGTH(inc_address_door_c)) > 100, 'YES', 'NO') FROM contacts_cstm

UNION ALL SELECT 'inc_address_floor_c', 255, 100,
    MAX(CHAR_LENGTH(inc_address_floor_c)),
    IF(MAX(CHAR_LENGTH(inc_address_floor_c)) > 100, 'YES', 'NO') FROM contacts_cstm

UNION ALL SELECT 'inc_address_num_a_c', 255, 100,
    MAX(CHAR_LENGTH(inc_address_num_a_c)),
    IF(MAX(CHAR_LENGTH(inc_address_num_a_c)) > 100, 'YES', 'NO') FROM contacts_cstm

UNION ALL SELECT 'inc_address_num_b_c', 255, 100,
    MAX(CHAR_LENGTH(inc_address_num_b_c)),
    IF(MAX(CHAR_LENGTH(inc_address_num_b_c)) > 100, 'YES', 'NO') FROM contacts_cstm

UNION ALL SELECT 'inc_address_postal_code_c', 255, 100,
    MAX(CHAR_LENGTH(inc_address_postal_code_c)),
    IF(MAX(CHAR_LENGTH(inc_address_postal_code_c)) > 100, 'YES', 'NO') FROM contacts_cstm

UNION ALL SELECT 'inc_address_street_c', 255, 100,
    MAX(CHAR_LENGTH(inc_address_street_c)),
    IF(MAX(CHAR_LENGTH(inc_address_street_c)) > 100, 'YES', 'NO') FROM contacts_cstm

UNION ALL SELECT 'inc_driving_licenses_c', 255, 100,
    MAX(CHAR_LENGTH(inc_driving_licenses_c)),
    IF(MAX(CHAR_LENGTH(inc_driving_licenses_c)) > 100, 'YES', 'NO') FROM contacts_cstm

UNION ALL SELECT 'inc_employ_office_reg_time_c', 255, 100,
    MAX(CHAR_LENGTH(inc_employ_office_reg_time_c)),
    IF(MAX(CHAR_LENGTH(inc_employ_office_reg_time_c)) > 100, 'YES', 'NO') FROM contacts_cstm

UNION ALL SELECT 'inc_max_commuting_time_c', 255, 100,
    MAX(CHAR_LENGTH(inc_max_commuting_time_c)),
    IF(MAX(CHAR_LENGTH(inc_max_commuting_time_c)) > 100, 'YES', 'NO') FROM contacts_cstm

UNION ALL SELECT 'inc_state_c', 255, 100,
    MAX(CHAR_LENGTH(inc_state_c)),
    IF(MAX(CHAR_LENGTH(inc_state_c)) > 100, 'YES', 'NO') FROM contacts_cstm

UNION ALL SELECT 'inc_municipality_c', 255, 100,
    MAX(CHAR_LENGTH(inc_municipality_c)),
    IF(MAX(CHAR_LENGTH(inc_municipality_c)) > 100, 'YES', 'NO') FROM contacts_cstm

UNION ALL SELECT 'inc_town_c', 255, 100,
    MAX(CHAR_LENGTH(inc_town_c)),
    IF(MAX(CHAR_LENGTH(inc_town_c)) > 100, 'YES', 'NO') FROM contacts_cstm

UNION ALL SELECT 'stic_time_availability_c', 255, 100,
    MAX(CHAR_LENGTH(stic_time_availability_c)),
    IF(MAX(CHAR_LENGTH(stic_time_availability_c)) > 100, 'YES', 'NO') FROM contacts_cstm

UNION ALL SELECT 'stic_pa_username_c', 255, 100,
    MAX(CHAR_LENGTH(stic_pa_username_c)),
    IF(MAX(CHAR_LENGTH(stic_pa_username_c)) > 100, 'YES', 'NO') FROM contacts_cstm

UNION ALL SELECT 'stic_pa_password_c', 255, 100,
    MAX(CHAR_LENGTH(stic_pa_password_c)),
    IF(MAX(CHAR_LENGTH(stic_pa_password_c)) > 100, 'YES', 'NO') FROM contacts_cstm;
