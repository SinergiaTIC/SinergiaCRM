-- Add ledger groups and subgroups to stic_ledger_accounts table
-- Date: 2025-12-18
-- Description: Inserts the 9 main ledger groups and their subgroups from stic_ledger_groups_list and stic_ledger_subgroups_list

-- Group 1: Financiación Básica
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '1 - Financiación Básica', NOW(), NOW(), '1', '1', 'Financiación Básica', 0, '1', 1, '', '', '', '1');

-- Group 2: Activo no corriente
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '2 - Activo no corriente', NOW(), NOW(), '1', '1', 'Activo no corriente', 0, '1', 1, '', '', '', '2');

-- Group 3: Existencias
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '3 - Existencias', NOW(), NOW(), '1', '1', 'Existencias', 0, '1', 1, '', '', '', '3');

-- Group 4: Acreedores y deudores por operaciones comerciales
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '4 - Acreedores y deudores por operaciones comerciales', NOW(), NOW(), '1', '1', 'Acreedores y deudores por operaciones comerciales', 0, '1', 1, '', '', '', '4');

-- Group 5: Cuentas financieras
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '5 - Cuentas financieras', NOW(), NOW(), '1', '1', 'Cuentas financieras', 0, '1', 1, '', '', '', '5');

-- Group 6: Compras y gastos
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '6 - Compras y gastos', NOW(), NOW(), '1', '1', 'Compras y gastos', 0, '1', 1, '', '', '', '6');

-- Group 7: Ventas e ingresos
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '7 - Ventas e ingresos', NOW(), NOW(), '1', '1', 'Ventas e ingresos', 0, '1', 1, '', '', '', '7');

-- Group 8: Gastos imputados al patrimonio neto
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '8 - Gastos imputados al patrimonio neto', NOW(), NOW(), '1', '1', 'Gastos imputados al patrimonio neto', 0, '1', 1, '', '', '', '8');

-- Group 9: Ingresos imputados al patrimonio neto
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '9 - Ingresos imputados al patrimonio neto', NOW(), NOW(), '1', '1', 'Ingresos imputados al patrimonio neto', 0, '1', 1, '', '', '', '9');

-- ========================================
-- SUBGROUPS (stic_ledger_subgroups_list)
-- ========================================

-- Subgroup 10: Capital (Group 1)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '10 - Capital', NOW(), NOW(), '1', '1', 'Capital', 0, '1', 1, '1_10', '', '', '1');

-- Subgroup 11: Reservas y Otros Instrumentos de Patrimonio Neto (Group 1)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '11 - Reservas y Otros Instrumentos de Patrimonio Neto', NOW(), NOW(), '1', '1', 'Reservas y Otros Instrumentos de Patrimonio Neto', 0, '1', 1, '1_11', '', '', '1');

-- Subgroup 12: Resultados Pendientes de Aplicación (Group 1)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '12 - Resultados Pendientes de Aplicación', NOW(), NOW(), '1', '1', 'Resultados Pendientes de Aplicación', 0, '1', 1, '1_12', '', '', '1');

-- Subgroup 13: Subvenciones, Donaciones y Ajustes por Cambios de Valor (Group 1)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '13 - Subvenciones, Donaciones y Ajustes por Cambios de Valor', NOW(), NOW(), '1', '1', 'Subvenciones, Donaciones y Ajustes por Cambios de Valor', 0, '1', 1, '1_13', '', '', '1');

-- Subgroup 14: Provisiones (Group 1)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '14 - Provisiones', NOW(), NOW(), '1', '1', 'Provisiones', 0, '1', 1, '1_14', '', '', '1');

-- Subgroup 15: Deudas a Largo Plazo con Características Especiales (Group 1)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '15 - Deudas a Largo Plazo con Características Especiales', NOW(), NOW(), '1', '1', 'Deudas a Largo Plazo con Características Especiales', 0, '1', 1, '1_15', '', '', '1');

-- Subgroup 16: Deudas a Largo Plazo con Partes Vinculadas (Group 1)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '16 - Deudas a Largo Plazo con Partes Vinculadas', NOW(), NOW(), '1', '1', 'Deudas a Largo Plazo con Partes Vinculadas', 0, '1', 1, '1_16', '', '', '1');

-- Subgroup 17: Deudas a Largo Plazo por Préstamos Recibidos, Empréstitos y Otros Conceptos (Group 1)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '17 - Deudas a Largo Plazo por Préstamos Recibidos, Empréstitos y Otros Conceptos', NOW(), NOW(), '1', '1', 'Deudas a Largo Plazo por Préstamos Recibidos, Empréstitos y Otros Conceptos', 0, '1', 1, '1_17', '', '', '1');

-- Subgroup 18: Pasivos por Fianzas, Garantías y Otros Conceptos a Largo Plazo (Group 1)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '18 - Pasivos por Fianzas, Garantías y Otros Conceptos a Largo Plazo', NOW(), NOW(), '1', '1', 'Pasivos por Fianzas, Garantías y Otros Conceptos a Largo Plazo', 0, '1', 1, '1_18', '', '', '1');

-- Subgroup 19: Situaciones Transitorias de Financiación (Group 1)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '19 - Situaciones Transitorias de Financiación', NOW(), NOW(), '1', '1', 'Situaciones Transitorias de Financiación', 0, '1', 1, '1_19', '', '', '1');

-- Subgroup 20: Inmovilizaciones Intangibles (Group 2)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '20 - Inmovilizaciones Intangibles', NOW(), NOW(), '1', '1', 'Inmovilizaciones Intangibles', 0, '1', 1, '2_20', '', '', '2');

-- Subgroup 21: Inmovilizaciones Materiales (Group 2)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '21 - Inmovilizaciones Materiales', NOW(), NOW(), '1', '1', 'Inmovilizaciones Materiales', 0, '1', 1, '2_21', '', '', '2');

-- Subgroup 22: Inversiones Inmobiliarias (Group 2)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '22 - Inversiones Inmobiliarias', NOW(), NOW(), '1', '1', 'Inversiones Inmobiliarias', 0, '1', 1, '2_22', '', '', '2');

-- Subgroup 23: Inmovilizaciones Materiales en Curso (Group 2)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '23 - Inmovilizaciones Materiales en Curso', NOW(), NOW(), '1', '1', 'Inmovilizaciones Materiales en Curso', 0, '1', 1, '2_23', '', '', '2');

-- Subgroup 24: Inversiones Financieras a Largo Plazo en Partes Vinculadas (Group 2)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '24 - Inversiones Financieras a Largo Plazo en Partes Vinculadas', NOW(), NOW(), '1', '1', 'Inversiones Financieras a Largo Plazo en Partes Vinculadas', 0, '1', 1, '2_24', '', '', '2');

-- Subgroup 25: Otras Inversiones Financieras a Largo Plazo (Group 2)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '25 - Otras Inversiones Financieras a Largo Plazo', NOW(), NOW(), '1', '1', 'Otras Inversiones Financieras a Largo Plazo', 0, '1', 1, '2_25', '', '', '2');

-- Subgroup 26: Fianzas y Depósitos Constituidos a Largo Plazo (Group 2)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '26 - Fianzas y Depósitos Constituidos a Largo Plazo', NOW(), NOW(), '1', '1', 'Fianzas y Depósitos Constituidos a Largo Plazo', 0, '1', 1, '2_26', '', '', '2');

-- Subgroup 28: Amortización Acumulada del Inmovilizado (Group 2)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '28 - Amortización Acumulada del Inmovilizado', NOW(), NOW(), '1', '1', 'Amortización Acumulada del Inmovilizado', 0, '1', 1, '2_28', '', '', '2');

-- Subgroup 29: Deterioro de Valor de Activos No Corrientes (Group 2)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '29 - Deterioro de Valor de Activos No Corrientes', NOW(), NOW(), '1', '1', 'Deterioro de Valor de Activos No Corrientes', 0, '1', 1, '2_29', '', '', '2');

-- Subgroup 30: Comerciales (Group 3)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '30 - Comerciales', NOW(), NOW(), '1', '1', 'Comerciales', 0, '1', 1, '3_30', '', '', '3');

-- Subgroup 31: Materias Primas (Group 3)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '31 - Materias Primas', NOW(), NOW(), '1', '1', 'Materias Primas', 0, '1', 1, '3_31', '', '', '3');

-- Subgroup 32: Otros Aprovisionamientos (Group 3)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '32 - Otros Aprovisionamientos', NOW(), NOW(), '1', '1', 'Otros Aprovisionamientos', 0, '1', 1, '3_32', '', '', '3');

-- Subgroup 33: Productos en Curso (Group 3)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '33 - Productos en Curso', NOW(), NOW(), '1', '1', 'Productos en Curso', 0, '1', 1, '3_33', '', '', '3');

-- Subgroup 34: Productos Semiterminados (Group 3)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '34 - Productos Semiterminados', NOW(), NOW(), '1', '1', 'Productos Semiterminados', 0, '1', 1, '3_34', '', '', '3');

-- Subgroup 35: Productos Terminados (Group 3)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '35 - Productos Terminados', NOW(), NOW(), '1', '1', 'Productos Terminados', 0, '1', 1, '3_35', '', '', '3');

-- Subgroup 36: Subproductos, Residuos y Materiales Recuperados (Group 3)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '36 - Subproductos, Residuos y Materiales Recuperados', NOW(), NOW(), '1', '1', 'Subproductos, Residuos y Materiales Recuperados', 0, '1', 1, '3_36', '', '', '3');

-- Subgroup 39: Deterioro de Valor de las Existencias (Group 3)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '39 - Deterioro de Valor de las Existencias', NOW(), NOW(), '1', '1', 'Deterioro de Valor de las Existencias', 0, '1', 1, '3_39', '', '', '3');

-- Subgroup 40: Proveedores (Group 4)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '40 - Proveedores', NOW(), NOW(), '1', '1', 'Proveedores', 0, '1', 1, '4_40', '', '', '4');

-- Subgroup 41: Acreedores Varios (Group 4)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '41 - Acreedores Varios', NOW(), NOW(), '1', '1', 'Acreedores Varios', 0, '1', 1, '4_41', '', '', '4');

-- Subgroup 43: Clientes (Group 4)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '43 - Clientes', NOW(), NOW(), '1', '1', 'Clientes', 0, '1', 1, '4_43', '', '', '4');

-- Subgroup 44: Deudores Varios (Group 4)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '44 - Deudores Varios', NOW(), NOW(), '1', '1', 'Deudores Varios', 0, '1', 1, '4_44', '', '', '4');

-- Subgroup 46: Personal (Group 4)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '46 - Personal', NOW(), NOW(), '1', '1', 'Personal', 0, '1', 1, '4_46', '', '', '4');

-- Subgroup 47: Administraciones Públicas (Group 4)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '47 - Administraciones Públicas', NOW(), NOW(), '1', '1', 'Administraciones Públicas', 0, '1', 1, '4_47', '', '', '4');

-- Subgroup 48: Ajustes por Periodificación (Group 4)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '48 - Ajustes por Periodificación', NOW(), NOW(), '1', '1', 'Ajustes por Periodificación', 0, '1', 1, '4_48', '', '', '4');

-- Subgroup 49: Deterioro de Valor de Créditos Comerciales y Provisiones a Corto Plazo (Group 4)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '49 - Deterioro de Valor de Créditos Comerciales y Provisiones a Corto Plazo', NOW(), NOW(), '1', '1', 'Deterioro de Valor de Créditos Comerciales y Provisiones a Corto Plazo', 0, '1', 1, '4_49', '', '', '4');

-- Subgroup 50: Empréstitos, Deudas con Características Especiales y Otras Emisiones Análogas a Corto Plazo (Group 5)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '50 - Empréstitos, Deudas con Características Especiales y Otras Emisiones Análogas a Corto Plazo', NOW(), NOW(), '1', '1', 'Empréstitos, Deudas con Características Especiales y Otras Emisiones Análogas a Corto Plazo', 0, '1', 1, '5_50', '', '', '5');

-- Subgroup 51: Deudas a Corto Plazo con Partes Vinculadas (Group 5)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '51 - Deudas a Corto Plazo con Partes Vinculadas', NOW(), NOW(), '1', '1', 'Deudas a Corto Plazo con Partes Vinculadas', 0, '1', 1, '5_51', '', '', '5');

-- Subgroup 52: Deudas a Corto Plazo por Préstamos Recibidos y Otros Conceptos (Group 5)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '52 - Deudas a Corto Plazo por Préstamos Recibidos y Otros Conceptos', NOW(), NOW(), '1', '1', 'Deudas a Corto Plazo por Préstamos Recibidos y Otros Conceptos', 0, '1', 1, '5_52', '', '', '5');

-- Subgroup 53: Inversiones Financieras a Corto Plazo en Partes Vinculadas (Group 5)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '53 - Inversiones Financieras a Corto Plazo en Partes Vinculadas', NOW(), NOW(), '1', '1', 'Inversiones Financieras a Corto Plazo en Partes Vinculadas', 0, '1', 1, '5_53', '', '', '5');

-- Subgroup 54: Otras Inversiones Financieras a Corto Plazo (Group 5)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '54 - Otras Inversiones Financieras a Corto Plazo', NOW(), NOW(), '1', '1', 'Otras Inversiones Financieras a Corto Plazo', 0, '1', 1, '5_54', '', '', '5');

-- Subgroup 55: Otras Cuentas No Bancarias (Group 5)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '55 - Otras Cuentas No Bancarias', NOW(), NOW(), '1', '1', 'Otras Cuentas No Bancarias', 0, '1', 1, '5_55', '', '', '5');

-- Subgroup 56: Fianzas y Depósitos Recibidos y Constituidos a Corto Plazo y Ajustes por Periodificación (Group 5)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '56 - Fianzas y Depósitos Recibidos y Constituidos a Corto Plazo y Ajustes por Periodificación', NOW(), NOW(), '1', '1', 'Fianzas y Depósitos Recibidos y Constituidos a Corto Plazo y Ajustes por Periodificación', 0, '1', 1, '5_56', '', '', '5');

-- Subgroup 57: Tesorería (Group 5)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '57 - Tesorería', NOW(), NOW(), '1', '1', 'Tesorería', 0, '1', 1, '5_57', '', '', '5');

-- Subgroup 58: Activos No Corrientes Mantenidos para la Venta y Activos y Pasivos Asociados (Group 5)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '58 - Activos No Corrientes Mantenidos para la Venta y Activos y Pasivos Asociados', NOW(), NOW(), '1', '1', 'Activos No Corrientes Mantenidos para la Venta y Activos y Pasivos Asociados', 0, '1', 1, '5_58', '', '', '5');

-- Subgroup 59: Deterioro del Valor de Inversiones Financieras a Corto Plazo y de Activos No Corrientes Mantenidos para la Venta (Group 5)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '59 - Deterioro del Valor de Inversiones Financieras a Corto Plazo y de Activos No Corrientes Mantenidos para la Venta', NOW(), NOW(), '1', '1', 'Deterioro del Valor de Inversiones Financieras a Corto Plazo y de Activos No Corrientes Mantenidos para la Venta', 0, '1', 1, '5_59', '', '', '5');

-- Subgroup 60: Compras (Group 6)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '60 - Compras', NOW(), NOW(), '1', '1', 'Compras', 0, '1', 1, '6_60', '', '', '6');

-- Subgroup 61: Variación de Existencias (Group 6)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '61 - Variación de Existencias', NOW(), NOW(), '1', '1', 'Variación de Existencias', 0, '1', 1, '6_61', '', '', '6');

-- Subgroup 62: Servicios Exteriores (Group 6)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '62 - Servicios Exteriores', NOW(), NOW(), '1', '1', 'Servicios Exteriores', 0, '1', 1, '6_62', '', '', '6');

-- Subgroup 63: Tributos (Group 6)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '63 - Tributos', NOW(), NOW(), '1', '1', 'Tributos', 0, '1', 1, '6_63', '', '', '6');

-- Subgroup 64: Gastos de Personal (Group 6)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '64 - Gastos de Personal', NOW(), NOW(), '1', '1', 'Gastos de Personal', 0, '1', 1, '6_64', '', '', '6');

-- Subgroup 65: Otros Gastos de Gestión (Group 6)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '65 - Otros Gastos de Gestión', NOW(), NOW(), '1', '1', 'Otros Gastos de Gestión', 0, '1', 1, '6_65', '', '', '6');

-- Subgroup 66: Gastos Financieros (Group 6)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '66 - Gastos Financieros', NOW(), NOW(), '1', '1', 'Gastos Financieros', 0, '1', 1, '6_66', '', '', '6');

-- Subgroup 67: Pérdidas Procedentes de Activos No Corrientes y Gastos Excepcionales (Group 6)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '67 - Pérdidas Procedentes de Activos No Corrientes y Gastos Excepcionales', NOW(), NOW(), '1', '1', 'Pérdidas Procedentes de Activos No Corrientes y Gastos Excepcionales', 0, '1', 1, '6_67', '', '', '6');

-- Subgroup 68: Dotaciones para Amortizaciones (Group 6)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '68 - Dotaciones para Amortizaciones', NOW(), NOW(), '1', '1', 'Dotaciones para Amortizaciones', 0, '1', 1, '6_68', '', '', '6');

-- Subgroup 69: Pérdidas por Deterioro y Otras Dotaciones (Group 6)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '69 - Pérdidas por Deterioro y Otras Dotaciones', NOW(), NOW(), '1', '1', 'Pérdidas por Deterioro y Otras Dotaciones', 0, '1', 1, '6_69', '', '', '6');

-- Subgroup 70: Ventas de Mercaderías, de Producción Propia, de Servicios, etc. (Group 7)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '70 - Ventas de Mercaderías, de Producción Propia, de Servicios, etc.', NOW(), NOW(), '1', '1', 'Ventas de Mercaderías, de Producción Propia, de Servicios, etc.', 0, '1', 1, '7_70', '', '', '7');

-- Subgroup 71: Variación de Existencias (Group 7)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '71 - Variación de Existencias', NOW(), NOW(), '1', '1', 'Variación de Existencias', 0, '1', 1, '7_71', '', '', '7');

-- Subgroup 73: Trabajos Realizados para la Empresa (Group 7)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '73 - Trabajos Realizados para la Empresa', NOW(), NOW(), '1', '1', 'Trabajos Realizados para la Empresa', 0, '1', 1, '7_73', '', '', '7');

-- Subgroup 74: Subvenciones, Donaciones y Legados (Group 7)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '74 - Subvenciones, Donaciones y Legados', NOW(), NOW(), '1', '1', 'Subvenciones, Donaciones y Legados', 0, '1', 1, '7_74', '', '', '7');

-- Subgroup 75: Otros Ingresos de Gestión (Group 7)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '75 - Otros Ingresos de Gestión', NOW(), NOW(), '1', '1', 'Otros Ingresos de Gestión', 0, '1', 1, '7_75', '', '', '7');

-- Subgroup 76: Ingresos Financieros (Group 7)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '76 - Ingresos Financieros', NOW(), NOW(), '1', '1', 'Ingresos Financieros', 0, '1', 1, '7_76', '', '', '7');

-- Subgroup 77: Beneficios Procedentes de Activos No Corrientes e Ingresos Excepcionales (Group 7)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '77 - Beneficios Procedentes de Activos No Corrientes e Ingresos Excepcionales', NOW(), NOW(), '1', '1', 'Beneficios Procedentes de Activos No Corrientes e Ingresos Excepcionales', 0, '1', 1, '7_77', '', '', '7');

-- Subgroup 79: Excesos y Aplicaciones de Provisiones y de Pérdidas por Deterioro (Group 7)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '79 - Excesos y Aplicaciones de Provisiones y de Pérdidas por Deterioro', NOW(), NOW(), '1', '1', 'Excesos y Aplicaciones de Provisiones y de Pérdidas por Deterioro', 0, '1', 1, '7_79', '', '', '7');

-- Subgroup 80: Gastos Financieros por Valoración de Activos Financieros (Group 8)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '80 - Gastos Financieros por Valoración de Activos Financieros', NOW(), NOW(), '1', '1', 'Gastos Financieros por Valoración de Activos Financieros', 0, '1', 1, '8_80', '', '', '8');

-- Subgroup 81: Gastos en Operaciones de Cobertura (Group 8)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '81 - Gastos en Operaciones de Cobertura', NOW(), NOW(), '1', '1', 'Gastos en Operaciones de Cobertura', 0, '1', 1, '8_81', '', '', '8');

-- Subgroup 82: Gastos por Diferencias de Conversión (Group 8)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '82 - Gastos por Diferencias de Conversión', NOW(), NOW(), '1', '1', 'Gastos por Diferencias de Conversión', 0, '1', 1, '8_82', '', '', '8');

-- Subgroup 83: Impuesto sobre Beneficios (Group 8)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '83 - Impuesto sobre Beneficios', NOW(), NOW(), '1', '1', 'Impuesto sobre Beneficios', 0, '1', 1, '8_83', '', '', '8');

-- Subgroup 84: Transferencias de Subvenciones, Donaciones y Legados (Group 8)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '84 - Transferencias de Subvenciones, Donaciones y Legados', NOW(), NOW(), '1', '1', 'Transferencias de Subvenciones, Donaciones y Legados', 0, '1', 1, '8_84', '', '', '8');

-- Subgroup 85: Gastos por Pérdidas Actuariales y Ajustes en Activos por Retribuciones a Largo Plazo de Prestación Definida (Group 8)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '85 - Gastos por Pérdidas Actuariales y Ajustes en Activos por Retribuciones a Largo Plazo de Prestación Definida', NOW(), NOW(), '1', '1', 'Gastos por Pérdidas Actuariales y Ajustes en Activos por Retribuciones a Largo Plazo de Prestación Definida', 0, '1', 1, '8_85', '', '', '8');

-- Subgroup 86: Gastos por Activos No Corrientes en Venta (Group 8)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '86 - Gastos por Activos No Corrientes en Venta', NOW(), NOW(), '1', '1', 'Gastos por Activos No Corrientes en Venta', 0, '1', 1, '8_86', '', '', '8');

-- Subgroup 89: Gastos de Participaciones en Empresas del Grupo o Asociadas con Ajustes Valorativos Positivos Anteriores (Group 8)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '89 - Gastos de Participaciones en Empresas del Grupo o Asociadas con Ajustes Valorativos Positivos Anteriores', NOW(), NOW(), '1', '1', 'Gastos de Participaciones en Empresas del Grupo o Asociadas con Ajustes Valorativos Positivos Anteriores', 0, '1', 1, '8_89', '', '', '8');

-- Subgroup 90: Ingresos Financieros por Valoración de Activos Financieros (Group 9)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '90 - Ingresos Financieros por Valoración de Activos Financieros', NOW(), NOW(), '1', '1', 'Ingresos Financieros por Valoración de Activos Financieros', 0, '1', 1, '9_90', '', '', '9');

-- Subgroup 91: Ingresos en Operaciones de Cobertura (Group 9)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '91 - Ingresos en Operaciones de Cobertura', NOW(), NOW(), '1', '1', 'Ingresos en Operaciones de Cobertura', 0, '1', 1, '9_91', '', '', '9');

-- Subgroup 92: Ingresos por Diferencias de Conversión (Group 9)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '92 - Ingresos por Diferencias de Conversión', NOW(), NOW(), '1', '1', 'Ingresos por Diferencias de Conversión', 0, '1', 1, '9_92', '', '', '9');

-- Subgroup 94: Ingresos por Subvenciones, Donaciones y Legados (Group 9)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '94 - Ingresos por Subvenciones, Donaciones y Legados', NOW(), NOW(), '1', '1', 'Ingresos por Subvenciones, Donaciones y Legados', 0, '1', 1, '9_94', '', '', '9');

-- Subgroup 95: Ingresos por Ganancias Actuariales y Ajustes en Activos por Retribuciones a Largo Plazo de Prestación Definida (Group 9)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '95 - Ingresos por Ganancias Actuariales y Ajustes en Activos por Retribuciones a Largo Plazo de Prestación Definida', NOW(), NOW(), '1', '1', 'Ingresos por Ganancias Actuariales y Ajustes en Activos por Retribuciones a Largo Plazo de Prestación Definida', 0, '1', 1, '9_95', '', '', '9');

-- Subgroup 96: Ingresos por Activos No Corrientes en Venta (Group 9)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '96 - Ingresos por Activos No Corrientes en Venta', NOW(), NOW(), '1', '1', 'Ingresos por Activos No Corrientes en Venta', 0, '1', 1, '9_96', '', '', '9');

-- Subgroup 99: Ingresos de Participaciones en el Patrimonio de Empresas del Grupo o Asociadas con Ajustes Valorativos Negativos Anteriores (Group 9)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '99 - Ingresos de Participaciones en el Patrimonio de Empresas del Grupo o Asociadas con Ajustes Valorativos Negativos Anteriores', NOW(), NOW(), '1', '1', 'Ingresos de Participaciones en el Patrimonio de Empresas del Grupo o Asociadas con Ajustos Valorativos Negativos Anteriores', 0, '1', 1, '9_99', '', '', '9');