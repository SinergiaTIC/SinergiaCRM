-- =============================================================================
-- Verifactu — Phase C.5: remove verifactu_previous_status_c (decision 9)
-- The field is no longer defined in vardefs; unregister it so Repair drops it
-- from the dictionary, then drop the column from aos_invoices_cstm.
-- =============================================================================

DELETE FROM `fields_meta_data`
WHERE `id` = 'AOS_Invoicesverifactu_previous_status_c'
  AND `custom_module` = 'AOS_Invoices';

ALTER TABLE `aos_invoices_cstm` DROP COLUMN `verifactu_previous_status_c`;
