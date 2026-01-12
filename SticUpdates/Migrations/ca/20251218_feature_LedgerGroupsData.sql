-- Add ledger groups and subgroups to stic_ledger_accounts table
-- Date: 2025-12-18
-- Description: Inserts the 9 main ledger groups and their subgroups from stic_ledger_groups_list and stic_ledger_subgroups_list

-- Group 1: Finançament Bàsic
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '1 - Finançament Bàsic', NOW(), NOW(), '1', '1', 'Finançament Bàsic', 0, '1', 1, '', '', '', '1');

-- Group 2: Actiu no corrent
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '2 - Actiu no corrent', NOW(), NOW(), '1', '1', 'Actiu no corrent', 0, '1', 1, '', '', '', '2');

-- Group 3: Existències
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '3 - Existències', NOW(), NOW(), '1', '1', 'Existències', 0, '1', 1, '', '', '', '3');

-- Group 4: Acreedors i deutors per operacions comercials
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '4 - Acreedors i deutors per operacions comercials', NOW(), NOW(), '1', '1', 'Acreedors i deutors per operacions comercials', 0, '1', 1, '', '', '', '4');

-- Group 5: Comptes financers
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '5 - Comptes financers', NOW(), NOW(), '1', '1', 'Comptes financers', 0, '1', 1, '', '', '', '5');

-- Group 6: Compres i despeses
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '6 - Compres i despeses', NOW(), NOW(), '1', '1', 'Compres i despeses', 0, '1', 1, '', '', '', '6');

-- Group 7: Vendes i ingressos
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '7 - Vendes i ingressos', NOW(), NOW(), '1', '1', 'Vendes i ingressos', 0, '1', 1, '', '', '', '7');

-- Group 8: Despeses imputades al patrimoni net
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '8 - Despeses imputades al patrimoni net', NOW(), NOW(), '1', '1', 'Despeses imputades al patrimoni net', 0, '1', 1, '', '', '', '8');

-- Group 9: Ingressos imputats al patrimoni net
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '9 - Ingressos imputats al patrimoni net', NOW(), NOW(), '1', '1', 'Ingressos imputats al patrimoni net', 0, '1', 1, '', '', '', '9');

-- ========================================
-- SUBGROUPS (stic_ledger_subgroups_list)
-- ========================================

-- Subgroup 10: Capital (Group 1)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '10 - Capital', NOW(), NOW(), '1', '1', 'Capital', 0, '1', 1, '1_10', '', '', '1');

-- Subgroup 11: Reserves i Altres Instruments de Patrimoni Net (Group 1)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '11 - Reserves i Altres Instruments de Patrimoni Net', NOW(), NOW(), '1', '1', 'Reserves i Altres Instruments de Patrimoni Net', 0, '1', 1, '1_11', '', '', '1');

-- Subgroup 12: Resultats Pendents d'Aplicació (Group 1)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '12 - Resultats Pendents d\'Aplicació', NOW(), NOW(), '1', '1', 'Resultats Pendents d\'Aplicació', 0, '1', 1, '1_12', '', '', '1');

-- Subgroup 13: Subvencions, Donacions i Ajustos per Canvis de Valor (Group 1)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '13 - Subvencions, Donacions i Ajustos per Canvis de Valor', NOW(), NOW(), '1', '1', 'Subvencions, Donacions i Ajustos per Canvis de Valor', 0, '1', 1, '1_13', '', '', '1');

-- Subgroup 14: Provisions (Group 1)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '14 - Provisions', NOW(), NOW(), '1', '1', 'Provisions', 0, '1', 1, '1_14', '', '', '1');

-- Subgroup 15: Deutes a Llarg Termini amb Característiques Especials (Group 1)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '15 - Deutes a Llarg Termini amb Característiques Especials', NOW(), NOW(), '1', '1', 'Deutes a Llarg Termini amb Característiques Especials', 0, '1', 1, '1_15', '', '', '1');

-- Subgroup 16: Deutes a Llarg Termini amb Parts Vinculades (Group 1)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '16 - Deutes a Llarg Termini amb Parts Vinculades', NOW(), NOW(), '1', '1', 'Deutes a Llarg Termini amb Parts Vinculades', 0, '1', 1, '1_16', '', '', '1');

-- Subgroup 17: Deutes a Llarg Termini per Préstecs Rebuts, Emprèstits i Altres Conceptes (Group 1)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '17 - Deutes a Llarg Termini per Préstecs Rebuts, Emprèstits i Altres Conceptes', NOW(), NOW(), '1', '1', 'Deutes a Llarg Termini per Préstecs Rebuts, Emprèstits i Altres Conceptes', 0, '1', 1, '1_17', '', '', '1');

-- Subgroup 18: Passius per Fiances, Garanties i Altres Conceptes a Llarg Termini (Group 1)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '18 - Passius per Fiances, Garanties i Altres Conceptes a Llarg Termini', NOW(), NOW(), '1', '1', 'Passius per Fiances, Garanties i Altres Conceptes a Llarg Termini', 0, '1', 1, '1_18', '', '', '1');

-- Subgroup 19: Situacions Transitòries de Finançament (Group 1)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '19 - Situacions Transitòries de Finançament', NOW(), NOW(), '1', '1', 'Situacions Transitòries de Finançament', 0, '1', 1, '1_19', '', '', '1');

-- Subgroup 20: Immobilitzacions Intangibles (Group 2)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '20 - Immobilitzacions Intangibles', NOW(), NOW(), '1', '1', 'Immobilitzacions Intangibles', 0, '1', 1, '2_20', '', '', '2');

-- Subgroup 21: Immobilitzacions Materials (Group 2)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '21 - Immobilitzacions Materials', NOW(), NOW(), '1', '1', 'Immobilitzacions Materials', 0, '1', 1, '2_21', '', '', '2');

-- Subgroup 22: Inversions Immobiliàries (Group 2)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '22 - Inversions Immobiliàries', NOW(), NOW(), '1', '1', 'Inversions Immobiliàries', 0, '1', 1, '2_22', '', '', '2');

-- Subgroup 23: Immobilitzacions Materials en Curs (Group 2)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '23 - Immobilitzacions Materials en Curs', NOW(), NOW(), '1', '1', 'Immobilitzacions Materials en Curs', 0, '1', 1, '2_23', '', '', '2');

-- Subgroup 24: Inversions Financeres a Llarg Termini en Parts Vinculades (Group 2)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '24 - Inversions Financeres a Llarg Termini en Parts Vinculades', NOW(), NOW(), '1', '1', 'Inversions Financeres a Llarg Termini en Parts Vinculades', 0, '1', 1, '2_24', '', '', '2');

-- Subgroup 25: Altres Inversions Financeres a Llarg Termini (Group 2)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '25 - Altres Inversions Financeres a Llarg Termini', NOW(), NOW(), '1', '1', 'Altres Inversions Financeres a Llarg Termini', 0, '1', 1, '2_25', '', '', '2');

-- Subgroup 26: Fiances i Dipòsits Constituïts a Llarg Termini (Group 2)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '26 - Fiances i Dipòsits Constituïts a Llarg Termini', NOW(), NOW(), '1', '1', 'Fiances i Dipòsits Constituïts a Llarg Termini', 0, '1', 1, '2_26', '', '', '2');

-- Subgroup 28: Amortització Acumulada de l'Immobilitzat (Group 2)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '28 - Amortització Acumulada de l\'Immobilitzat', NOW(), NOW(), '1', '1', 'Amortització Acumulada de l\'Immobilitzat', 0, '1', 1, '2_28', '', '', '2');

-- Subgroup 29: Deteriorament de Valor d'Actius No Corrents (Group 2)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '29 - Deteriorament de Valor d\'Actius No Corrents', NOW(), NOW(), '1', '1', 'Deteriorament de Valor d\'Actius No Corrents', 0, '1', 1, '2_29', '', '', '2');

-- Subgroup 30: Comercials (Group 3)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '30 - Comercials', NOW(), NOW(), '1', '1', 'Comercials', 0, '1', 1, '3_30', '', '', '3');

-- Subgroup 31: Matèries Primeres (Group 3)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '31 - Matèries Primeres', NOW(), NOW(), '1', '1', 'Matèries Primeres', 0, '1', 1, '3_31', '', '', '3');

-- Subgroup 32: Altres Aprovisionaments (Group 3)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '32 - Altres Aprovisionaments', NOW(), NOW(), '1', '1', 'Altres Aprovisionaments', 0, '1', 1, '3_32', '', '', '3');

-- Subgroup 33: Productes en Curs (Group 3)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '33 - Productes en Curs', NOW(), NOW(), '1', '1', 'Productes en Curs', 0, '1', 1, '3_33', '', '', '3');

-- Subgroup 34: Productes Semiacabats (Group 3)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '34 - Productes Semiacabats', NOW(), NOW(), '1', '1', 'Productes Semiacabats', 0, '1', 1, '3_34', '', '', '3');

-- Subgroup 35: Productes Acabats (Group 3)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '35 - Productes Acabats', NOW(), NOW(), '1', '1', 'Productes Acabats', 0, '1', 1, '3_35', '', '', '3');

-- Subgroup 36: Subproductes, Residus i Materials Recuperats (Group 3)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '36 - Subproductes, Residus i Materials Recuperats', NOW(), NOW(), '1', '1', 'Subproductes, Residus i Materials Recuperats', 0, '1', 1, '3_36', '', '', '3');

-- Subgroup 39: Deteriorament de Valor de les Existències (Group 3)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '39 - Deteriorament de Valor de les Existències', NOW(), NOW(), '1', '1', 'Deteriorament de Valor de les Existències', 0, '1', 1, '3_39', '', '', '3');

-- Subgroup 40: Proveïdors (Group 4)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '40 - Proveïdors', NOW(), NOW(), '1', '1', 'Proveïdors', 0, '1', 1, '4_40', '', '', '4');

-- Subgroup 41: Acreedors Diversos (Group 4)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '41 - Acreedors Diversos', NOW(), NOW(), '1', '1', 'Acreedors Diversos', 0, '1', 1, '4_41', '', '', '4');

-- Subgroup 43: Clients (Group 4)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '43 - Clients', NOW(), NOW(), '1', '1', 'Clients', 0, '1', 1, '4_43', '', '', '4');

-- Subgroup 44: Deutors Diversos (Group 4)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '44 - Deutors Diversos', NOW(), NOW(), '1', '1', 'Deutors Diversos', 0, '1', 1, '4_44', '', '', '4');

-- Subgroup 46: Personal (Group 4)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '46 - Personal', NOW(), NOW(), '1', '1', 'Personal', 0, '1', 1, '4_46', '', '', '4');

-- Subgroup 47: Administracions Públiques (Group 4)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '47 - Administracions Públiques', NOW(), NOW(), '1', '1', 'Administracions Públiques', 0, '1', 1, '4_47', '', '', '4');

-- Subgroup 48: Ajustos per Periodificació (Group 4)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '48 - Ajustos per Periodificació', NOW(), NOW(), '1', '1', 'Ajustos per Periodificació', 0, '1', 1, '4_48', '', '', '4');

-- Subgroup 49: Deteriorament de Valor de Crèdits Comercials i Provisions a Curt Termini (Group 4)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '49 - Deteriorament de Valor de Crèdits Comercials i Provisions a Curt Termini', NOW(), NOW(), '1', '1', 'Deteriorament de Valor de Crèdits Comercials i Provisions a Curt Termini', 0, '1', 1, '4_49', '', '', '4');

-- Subgroup 50: Emprèstits, Deutes amb Característiques Especials i Altres Emissions Anàlogues a Curt Termini (Group 5)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '50 - Emprèstits, Deutes amb Característiques Especials i Altres Emissions Anàlogues a Curt Termini', NOW(), NOW(), '1', '1', 'Emprèstits, Deutes amb Característiques Especials i Altres Emissions Anàlogues a Curt Termini', 0, '1', 1, '5_50', '', '', '5');

-- Subgroup 51: Deutes a Curt Termini amb Parts Vinculades (Group 5)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '51 - Deutes a Curt Termini amb Parts Vinculades', NOW(), NOW(), '1', '1', 'Deutes a Curt Termini amb Parts Vinculades', 0, '1', 1, '5_51', '', '', '5');

-- Subgroup 52: Deutes a Curt Termini per Préstecs Rebuts i Altres Conceptes (Group 5)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '52 - Deutes a Curt Termini per Préstecs Rebuts i Altres Conceptes', NOW(), NOW(), '1', '1', 'Deutes a Curt Termini per Préstecs Rebuts i Altres Conceptes', 0, '1', 1, '5_52', '', '', '5');

-- Subgroup 53: Inversions Financeres a Curt Termini en Parts Vinculades (Group 5)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '53 - Inversions Financeres a Curt Termini en Parts Vinculades', NOW(), NOW(), '1', '1', 'Inversions Financeres a Curt Termini en Parts Vinculades', 0, '1', 1, '5_53', '', '', '5');

-- Subgroup 54: Altres Inversions Financeres a Curt Termini (Group 5)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '54 - Altres Inversions Financeres a Curt Termini', NOW(), NOW(), '1', '1', 'Altres Inversions Financeres a Curt Termini', 0, '1', 1, '5_54', '', '', '5');

-- Subgroup 55: Altres Comptes No Bancaris (Group 5)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '55 - Altres Comptes No Bancaris', NOW(), NOW(), '1', '1', 'Altres Comptes No Bancaris', 0, '1', 1, '5_55', '', '', '5');

-- Subgroup 56: Fiances i Dipòsits Rebuts i Constituïts a Curt Termini i Ajustos per Periodificació (Group 5)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '56 - Fiances i Dipòsits Rebuts i Constituïts a Curt Termini i Ajustos per Periodificació', NOW(), NOW(), '1', '1', 'Fiances i Dipòsits Rebuts i Constituïts a Curt Termini i Ajustos per Periodificació', 0, '1', 1, '5_56', '', '', '5');

-- Subgroup 57: Tresoreria (Group 5)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '57 - Tresoreria', NOW(), NOW(), '1', '1', 'Tresoreria', 0, '1', 1, '5_57', '', '', '5');

-- Subgroup 58: Actius No Corrents Mantinguts per a la Venda i Actius i Passius Associats (Group 5)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '58 - Actius No Corrents Mantinguts per a la Venda i Actius i Passius Associats', NOW(), NOW(), '1', '1', 'Actius No Corrents Mantinguts per a la Venda i Actius i Passius Associats', 0, '1', 1, '5_58', '', '', '5');

-- Subgroup 59: Deteriorament del Valor d'Inversions Financeres a Curt Termini i d'Actius No Corrents Mantinguts per a la Venda (Group 5)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '59 - Deteriorament del Valor d\'Inversions Financeres a Curt Termini i d\'Actius No Corrents Mantinguts per a la Venda', NOW(), NOW(), '1', '1', 'Deteriorament del Valor d\'Inversions Financeres a Curt Termini i d\'Actius No Corrents Mantinguts per a la Venda', 0, '1', 1, '5_59', '', '', '5');

-- Subgroup 60: Compres (Group 6)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '60 - Compres', NOW(), NOW(), '1', '1', 'Compres', 0, '1', 1, '6_60', '', '', '6');

-- Subgroup 61: Variació d'Existències (Group 6)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '61 - Variació d\'Existències', NOW(), NOW(), '1', '1', 'Variació d\'Existències', 0, '1', 1, '6_61', '', '', '6');

-- Subgroup 62: Serveis Exteriors (Group 6)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '62 - Serveis Exteriors', NOW(), NOW(), '1', '1', 'Serveis Exteriors', 0, '1', 1, '6_62', '', '', '6');

-- Subgroup 63: Tributs (Group 6)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '63 - Tributs', NOW(), NOW(), '1', '1', 'Tributs', 0, '1', 1, '6_63', '', '', '6');

-- Subgroup 64: Despeses de Personal (Group 6)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '64 - Despeses de Personal', NOW(), NOW(), '1', '1', 'Despeses de Personal', 0, '1', 1, '6_64', '', '', '6');

-- Subgroup 65: Altres Despeses de Gestió (Group 6)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '65 - Altres Despeses de Gestió', NOW(), NOW(), '1', '1', 'Altres Despeses de Gestió', 0, '1', 1, '6_65', '', '', '6');

-- Subgroup 66: Despeses Financeres (Group 6)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '66 - Despeses Financeres', NOW(), NOW(), '1', '1', 'Despeses Financeres', 0, '1', 1, '6_66', '', '', '6');

-- Subgroup 67: Pèrdues Procedents d'Actius No Corrents i Despeses Excepcionals (Group 6)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '67 - Pèrdues Procedents d\'Actius No Corrents i Despeses Excepcionals', NOW(), NOW(), '1', '1', 'Pèrdues Procedents d\'Actius No Corrents i Despeses Excepcionals', 0, '1', 1, '6_67', '', '', '6');

-- Subgroup 68: Dotacions per a Amortitzacions (Group 6)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '68 - Dotacions per a Amortitzacions', NOW(), NOW(), '1', '1', 'Dotacions per a Amortitzacions', 0, '1', 1, '6_68', '', '', '6');

-- Subgroup 69: Pèrdues per Deteriorament i Altres Dotacions (Group 6)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '69 - Pèrdues per Deteriorament i Altres Dotacions', NOW(), NOW(), '1', '1', 'Pèrdues per Deteriorament i Altres Dotacions', 0, '1', 1, '6_69', '', '', '6');

-- Subgroup 70: Vendes de Mercaderies, de Producció Pròpia, de Serveis, etc. (Group 7)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '70 - Vendes de Mercaderies, de Producció Pròpia, de Serveis, etc.', NOW(), NOW(), '1', '1', 'Vendes de Mercaderies, de Producció Pròpia, de Serveis, etc.', 0, '1', 1, '7_70', '', '', '7');

-- Subgroup 71: Variació d'Existències (Group 7)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '71 - Variació d\'Existències', NOW(), NOW(), '1', '1', 'Variació d\'Existències', 0, '1', 1, '7_71', '', '', '7');

-- Subgroup 73: Treballs Realitzats per a l'Empresa (Group 7)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '73 - Treballs Realitzats per a l\'Empresa', NOW(), NOW(), '1', '1', 'Treballs Realitzats per a l\'Empresa', 0, '1', 1, '7_73', '', '', '7');

-- Subgroup 74: Subvencions, Donacions i Llegats (Group 7)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '74 - Subvencions, Donacions i Llegats', NOW(), NOW(), '1', '1', 'Subvencions, Donacions i Llegats', 0, '1', 1, '7_74', '', '', '7');

-- Subgroup 75: Altres Ingressos de Gestió (Group 7)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '75 - Altres Ingressos de Gestió', NOW(), NOW(), '1', '1', 'Altres Ingressos de Gestió', 0, '1', 1, '7_75', '', '', '7');

-- Subgroup 76: Ingressos Financers (Group 7)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '76 - Ingressos Financers', NOW(), NOW(), '1', '1', 'Ingressos Financers', 0, '1', 1, '7_76', '', '', '7');

-- Subgroup 77: Beneficis Procedents d'Actius No Corrents i Ingressos Excepcionals (Group 7)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '77 - Beneficis Procedents d\'Actius No Corrents i Ingressos Excepcionals', NOW(), NOW(), '1', '1', 'Beneficis Procedents d\'Actius No Corrents i Ingressos Excepcionals', 0, '1', 1, '7_77', '', '', '7');

-- Subgroup 79: Excessos i Aplicacions de Provisions i de Pèrdues per Deteriorament (Group 7)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '79 - Excessos i Aplicacions de Provisions i de Pèrdues per Deteriorament', NOW(), NOW(), '1', '1', 'Excessos i Aplicacions de Provisions i de Pèrdues per Deteriorament', 0, '1', 1, '7_79', '', '', '7');

-- Subgroup 80: Despeses Financeres per Valoració d'Actius Financers (Group 8)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '80 - Despeses Financeres per Valoració d\'Actius Financers', NOW(), NOW(), '1', '1', 'Despeses Financeres per Valoració d\'Actius Financers', 0, '1', 1, '8_80', '', '', '8');

-- Subgroup 81: Despeses en Operacions de Cobertura (Group 8)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '81 - Despeses en Operacions de Cobertura', NOW(), NOW(), '1', '1', 'Despeses en Operacions de Cobertura', 0, '1', 1, '8_81', '', '', '8');

-- Subgroup 82: Despeses per Diferències de Conversió (Group 8)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '82 - Despeses per Diferències de Conversió', NOW(), NOW(), '1', '1', 'Despeses per Diferències de Conversió', 0, '1', 1, '8_82', '', '', '8');

-- Subgroup 83: Impost sobre Beneficis (Group 8)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '83 - Impost sobre Beneficis', NOW(), NOW(), '1', '1', 'Impost sobre Beneficis', 0, '1', 1, '8_83', '', '', '8');

-- Subgroup 84: Transferències de Subvencions, Donacions i Llegats (Group 8)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '84 - Transferències de Subvencions, Donacions i Llegats', NOW(), NOW(), '1', '1', 'Transferències de Subvencions, Donacions i Llegats', 0, '1', 1, '8_84', '', '', '8');

-- Subgroup 85: Despeses per Pèrdues Actuarials i Ajustos en Actius per Retribucions a Llarg Termini de Prestació Definida (Group 8)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '85 - Despeses per Pèrdues Actuarials i Ajustos en Actius per Retribucions a Llarg Termini de Prestació Definida', NOW(), NOW(), '1', '1', 'Despeses per Pèrdues Actuarials i Ajustos en Actius per Retribucions a Llarg Termini de Prestació Definida', 0, '1', 1, '8_85', '', '', '8');

-- Subgroup 86: Despeses per Actius No Corrents en Venda (Group 8)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '86 - Despeses per Actius No Corrents en Venda', NOW(), NOW(), '1', '1', 'Despeses per Actius No Corrents en Venda', 0, '1', 1, '8_86', '', '', '8');

-- Subgroup 89: Despeses de Participacions en Empreses del Grup o Associades amb Ajustos Valoratius Positius Anteriors (Group 8)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '89 - Despeses de Participacions en Empreses del Grup o Associades amb Ajustos Valoratius Positius Anteriors', NOW(), NOW(), '1', '1', 'Despeses de Participacions en Empreses del Grup o Associades amb Ajustos Valoratius Positius Anteriors', 0, '1', 1, '8_89', '', '', '8');

-- Subgroup 90: Ingressos Financers per Valoració d'Actius Financers (Group 9)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '90 - Ingressos Financers per Valoració d\'Actius Financers', NOW(), NOW(), '1', '1', 'Ingressos Financers per Valoració d\'Actius Financers', 0, '1', 1, '9_90', '', '', '9');

-- Subgroup 91: Ingressos en Operacions de Cobertura (Group 9)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '91 - Ingressos en Operacions de Cobertura', NOW(), NOW(), '1', '1', 'Ingressos en Operacions de Cobertura', 0, '1', 1, '9_91', '', '', '9');

-- Subgroup 92: Ingressos per Diferències de Conversió (Group 9)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '92 - Ingressos per Diferències de Conversió', NOW(), NOW(), '1', '1', 'Ingressos per Diferències de Conversió', 0, '1', 1, '9_92', '', '', '9');

-- Subgroup 94: Ingressos per Subvencions, Donacions i Llegats (Group 9)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '94 - Ingressos per Subvencions, Donacions i Llegats', NOW(), NOW(), '1', '1', 'Ingressos per Subvencions, Donacions i Llegats', 0, '1', 1, '9_94', '', '', '9');

-- Subgroup 95: Ingressos per Guanys Actuarials i Ajustos en Actius per Retribucions a Llarg Termini de Prestació Definida (Group 9)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '95 - Ingressos per Guanys Actuarials i Ajustos en Actius per Retribucions a Llarg Termini de Prestació Definida', NOW(), NOW(), '1', '1', 'Ingressos per Guanys Actuarials i Ajustos en Actius per Retribucions a Llarg Termini de Prestació Definida', 0, '1', 1, '9_95', '', '', '9');

-- Subgroup 96: Ingressos per Actius No Corrents en Venda (Group 9)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '96 - Ingressos per Actius No Corrents en Venda', NOW(), NOW(), '1', '1', 'Ingressos per Actius No Corrents en Venda', 0, '1', 1, '9_96', '', '', '9');

-- Subgroup 99: Ingressos de Participacions en el Patrimoni d'Empreses del Grup o Associades amb Ajustos Valoratius Negatius Anteriors (Group 9)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '99 - Ingressos de Participacions en el Patrimoni d\'Empreses del Grup o Associades amb Ajustos Valoratius Negatius Anteriors', NOW(), NOW(), '1', '1', 'Ingressos de Participacions en el Patrimoni d\'Empreses del Grup o Associades amb Ajustos Valoratius Negatius Anteriors', 0, '1', 1, '9_99', '', '', '9');
