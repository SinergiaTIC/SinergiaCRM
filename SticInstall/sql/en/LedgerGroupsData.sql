-- Add ledger groups and subgroups to stic_ledger_accounts table
-- Date: 2025-12-18
-- Description: Inserts the 9 main ledger groups and their subgroups from stic_ledger_groups_list and stic_ledger_subgroups_list

-- Group 1: Basic Financing
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '1 - Basic Financing', NOW(), NOW(), '1', '1', 'Basic Financing', 0, '1', 1, '', '', '', '1');

-- Group 2: Non-current Assets
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '2 - Non-current Assets', NOW(), NOW(), '1', '1', 'Non-current Assets', 0, '1', 1, '', '', '', '2');

-- Group 3: Inventories
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '3 - Inventories', NOW(), NOW(), '1', '1', 'Inventories', 0, '1', 1, '', '', '', '3');

-- Group 4: Creditors and Debtors from Commercial Operations
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '4 - Creditors and Debtors from Commercial Operations', NOW(), NOW(), '1', '1', 'Creditors and Debtors from Commercial Operations', 0, '1', 1, '', '', '', '4');

-- Group 5: Financial Accounts
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '5 - Financial Accounts', NOW(), NOW(), '1', '1', 'Financial Accounts', 0, '1', 1, '', '', '', '5');

-- Group 6: Purchases and Expenses
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '6 - Purchases and Expenses', NOW(), NOW(), '1', '1', 'Purchases and Expenses', 0, '1', 1, '', '', '', '6');

-- Group 7: Sales and Income
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '7 - Sales and Income', NOW(), NOW(), '1', '1', 'Sales and Income', 0, '1', 1, '', '', '', '7');

-- Group 8: Expenses Attributed to Net Equity
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '8 - Expenses Attributed to Net Equity', NOW(), NOW(), '1', '1', 'Expenses Attributed to Net Equity', 0, '1', 1, '', '', '', '8');

-- Group 9: Income Attributed to Net Equity
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '9 - Income Attributed to Net Equity', NOW(), NOW(), '1', '1', 'Income Attributed to Net Equity', 0, '1', 1, '', '', '', '9');

-- ========================================
-- SUBGROUPS (stic_ledger_subgroups_list)
-- ========================================

-- Subgroup 10: Capital (Group 1)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '10 - Capital', NOW(), NOW(), '1', '1', 'Capital', 0, '1', 1, '1_10', '', '', '1');

-- Subgroup 11: Reserves and Other Net Equity Instruments (Group 1)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '11 - Reserves and Other Net Equity Instruments', NOW(), NOW(), '1', '1', 'Reserves and Other Net Equity Instruments', 0, '1', 1, '1_11', '', '', '1');

-- Subgroup 12: Pending Application Results (Group 1)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '12 - Pending Application Results', NOW(), NOW(), '1', '1', 'Pending Application Results', 0, '1', 1, '1_12', '', '', '1');

-- Subgroup 13: Grants, Donations and Value Change Adjustments (Group 1)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '13 - Grants, Donations and Value Change Adjustments', NOW(), NOW(), '1', '1', 'Grants, Donations and Value Change Adjustments', 0, '1', 1, '1_13', '', '', '1');

-- Subgroup 14: Provisions (Group 1)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '14 - Provisions', NOW(), NOW(), '1', '1', 'Provisions', 0, '1', 1, '1_14', '', '', '1');

-- Subgroup 15: Long-term Debts with Special Characteristics (Group 1)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '15 - Long-term Debts with Special Characteristics', NOW(), NOW(), '1', '1', 'Long-term Debts with Special Characteristics', 0, '1', 1, '1_15', '', '', '1');

-- Subgroup 16: Long-term Debts with Related Parties (Group 1)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '16 - Long-term Debts with Related Parties', NOW(), NOW(), '1', '1', 'Long-term Debts with Related Parties', 0, '1', 1, '1_16', '', '', '1');

-- Subgroup 17: Long-term Debts from Received Loans, Bonds and Other Concepts (Group 1)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '17 - Long-term Debts from Received Loans, Bonds and Other Concepts', NOW(), NOW(), '1', '1', 'Long-term Debts from Received Loans, Bonds and Other Concepts', 0, '1', 1, '1_17', '', '', '1');

-- Subgroup 18: Liabilities for Bonds, Guarantees and Other Long-term Concepts (Group 1)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '18 - Liabilities for Bonds, Guarantees and Other Long-term Concepts', NOW(), NOW(), '1', '1', 'Liabilities for Bonds, Guarantees and Other Long-term Concepts', 0, '1', 1, '1_18', '', '', '1');

-- Subgroup 19: Transitional Financing Situations (Group 1)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '19 - Transitional Financing Situations', NOW(), NOW(), '1', '1', 'Transitional Financing Situations', 0, '1', 1, '1_19', '', '', '1');

-- Subgroup 20: Intangible Fixed Assets (Group 2)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '20 - Intangible Fixed Assets', NOW(), NOW(), '1', '1', 'Intangible Fixed Assets', 0, '1', 1, '2_20', '', '', '2');

-- Subgroup 21: Tangible Fixed Assets (Group 2)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '21 - Tangible Fixed Assets', NOW(), NOW(), '1', '1', 'Tangible Fixed Assets', 0, '1', 1, '2_21', '', '', '2');

-- Subgroup 22: Real Estate Investments (Group 2)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '22 - Real Estate Investments', NOW(), NOW(), '1', '1', 'Real Estate Investments', 0, '1', 1, '2_22', '', '', '2');

-- Subgroup 23: Tangible Fixed Assets in Progress (Group 2)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '23 - Tangible Fixed Assets in Progress', NOW(), NOW(), '1', '1', 'Tangible Fixed Assets in Progress', 0, '1', 1, '2_23', '', '', '2');

-- Subgroup 24: Long-term Financial Investments in Related Parties (Group 2)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '24 - Long-term Financial Investments in Related Parties', NOW(), NOW(), '1', '1', 'Long-term Financial Investments in Related Parties', 0, '1', 1, '2_24', '', '', '2');

-- Subgroup 25: Other Long-term Financial Investments (Group 2)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '25 - Other Long-term Financial Investments', NOW(), NOW(), '1', '1', 'Other Long-term Financial Investments', 0, '1', 1, '2_25', '', '', '2');

-- Subgroup 26: Long-term Bonds and Deposits (Group 2)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '26 - Long-term Bonds and Deposits', NOW(), NOW(), '1', '1', 'Long-term Bonds and Deposits', 0, '1', 1, '2_26', '', '', '2');

-- Subgroup 28: Accumulated Amortization of Fixed Assets (Group 2)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '28 - Accumulated Amortization of Fixed Assets', NOW(), NOW(), '1', '1', 'Accumulated Amortization of Fixed Assets', 0, '1', 1, '2_28', '', '', '2');

-- Subgroup 29: Impairment of Non-current Assets (Group 2)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '29 - Impairment of Non-current Assets', NOW(), NOW(), '1', '1', 'Impairment of Non-current Assets', 0, '1', 1, '2_29', '', '', '2');

-- Subgroup 30: Commercial (Group 3)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '30 - Commercial', NOW(), NOW(), '1', '1', 'Commercial', 0, '1', 1, '3_30', '', '', '3');

-- Subgroup 31: Raw Materials (Group 3)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '31 - Raw Materials', NOW(), NOW(), '1', '1', 'Raw Materials', 0, '1', 1, '3_31', '', '', '3');

-- Subgroup 32: Other Supplies (Group 3)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '32 - Other Supplies', NOW(), NOW(), '1', '1', 'Other Supplies', 0, '1', 1, '3_32', '', '', '3');

-- Subgroup 33: Work in Progress (Group 3)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '33 - Work in Progress', NOW(), NOW(), '1', '1', 'Work in Progress', 0, '1', 1, '3_33', '', '', '3');

-- Subgroup 34: Semi-finished Products (Group 3)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '34 - Semi-finished Products', NOW(), NOW(), '1', '1', 'Semi-finished Products', 0, '1', 1, '3_34', '', '', '3');

-- Subgroup 35: Finished Products (Group 3)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '35 - Finished Products', NOW(), NOW(), '1', '1', 'Finished Products', 0, '1', 1, '3_35', '', '', '3');

-- Subgroup 36: By-products, Waste and Recovered Materials (Group 3)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '36 - By-products, Waste and Recovered Materials', NOW(), NOW(), '1', '1', 'By-products, Waste and Recovered Materials', 0, '1', 1, '3_36', '', '', '3');

-- Subgroup 39: Impairment of Inventories (Group 3)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '39 - Impairment of Inventories', NOW(), NOW(), '1', '1', 'Impairment of Inventories', 0, '1', 1, '3_39', '', '', '3');

-- Subgroup 40: Suppliers (Group 4)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '40 - Suppliers', NOW(), NOW(), '1', '1', 'Suppliers', 0, '1', 1, '4_40', '', '', '4');

-- Subgroup 41: Sundry Creditors (Group 4)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '41 - Sundry Creditors', NOW(), NOW(), '1', '1', 'Sundry Creditors', 0, '1', 1, '4_41', '', '', '4');

-- Subgroup 43: Customers (Group 4)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '43 - Customers', NOW(), NOW(), '1', '1', 'Customers', 0, '1', 1, '4_43', '', '', '4');

-- Subgroup 44: Sundry Debtors (Group 4)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '44 - Sundry Debtors', NOW(), NOW(), '1', '1', 'Sundry Debtors', 0, '1', 1, '4_44', '', '', '4');

-- Subgroup 46: Personnel (Group 4)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '46 - Personnel', NOW(), NOW(), '1', '1', 'Personnel', 0, '1', 1, '4_46', '', '', '4');

-- Subgroup 47: Public Administrations (Group 4)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '47 - Public Administrations', NOW(), NOW(), '1', '1', 'Public Administrations', 0, '1', 1, '4_47', '', '', '4');

-- Subgroup 48: Accrual Adjustments (Group 4)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '48 - Accrual Adjustments', NOW(), NOW(), '1', '1', 'Accrual Adjustments', 0, '1', 1, '4_48', '', '', '4');

-- Subgroup 49: Impairment of Trade Credits and Short-term Provisions (Group 4)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '49 - Impairment of Trade Credits and Short-term Provisions', NOW(), NOW(), '1', '1', 'Impairment of Trade Credits and Short-term Provisions', 0, '1', 1, '4_49', '', '', '4');

-- Subgroup 50: Bonds, Special Characteristic Debts and Other Analogous Short-term Issues (Group 5)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '50 - Bonds, Special Characteristic Debts and Other Analogous Short-term Issues', NOW(), NOW(), '1', '1', 'Bonds, Special Characteristic Debts and Other Analogous Short-term Issues', 0, '1', 1, '5_50', '', '', '5');

-- Subgroup 51: Short-term Debts with Related Parties (Group 5)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '51 - Short-term Debts with Related Parties', NOW(), NOW(), '1', '1', 'Short-term Debts with Related Parties', 0, '1', 1, '5_51', '', '', '5');

-- Subgroup 52: Short-term Debts from Received Loans and Other Concepts (Group 5)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '52 - Short-term Debts from Received Loans and Other Concepts', NOW(), NOW(), '1', '1', 'Short-term Debts from Received Loans and Other Concepts', 0, '1', 1, '5_52', '', '', '5');

-- Subgroup 53: Short-term Financial Investments in Related Parties (Group 5)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '53 - Short-term Financial Investments in Related Parties', NOW(), NOW(), '1', '1', 'Short-term Financial Investments in Related Parties', 0, '1', 1, '5_53', '', '', '5');

-- Subgroup 54: Other Short-term Financial Investments (Group 5)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '54 - Other Short-term Financial Investments', NOW(), NOW(), '1', '1', 'Other Short-term Financial Investments', 0, '1', 1, '5_54', '', '', '5');

-- Subgroup 55: Other Non-banking Accounts (Group 5)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '55 - Other Non-banking Accounts', NOW(), NOW(), '1', '1', 'Other Non-banking Accounts', 0, '1', 1, '5_55', '', '', '5');

-- Subgroup 56: Short-term Bonds and Deposits Received and Constituted and Accrual Adjustments (Group 5)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '56 - Short-term Bonds and Deposits Received and Constituted and Accrual Adjustments', NOW(), NOW(), '1', '1', 'Short-term Bonds and Deposits Received and Constituted and Accrual Adjustments', 0, '1', 1, '5_56', '', '', '5');

-- Subgroup 57: Treasury (Group 5)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '57 - Treasury', NOW(), NOW(), '1', '1', 'Treasury', 0, '1', 1, '5_57', '', '', '5');

-- Subgroup 58: Non-current Assets Held for Sale and Associated Assets and Liabilities (Group 5)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '58 - Non-current Assets Held for Sale and Associated Assets and Liabilities', NOW(), NOW(), '1', '1', 'Non-current Assets Held for Sale and Associated Assets and Liabilities', 0, '1', 1, '5_58', '', '', '5');

-- Subgroup 59: Impairment of Short-term Financial Investments and Non-current Assets Held for Sale (Group 5)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '59 - Impairment of Short-term Financial Investments and Non-current Assets Held for Sale', NOW(), NOW(), '1', '1', 'Impairment of Short-term Financial Investments and Non-current Assets Held for Sale', 0, '1', 1, '5_59', '', '', '5');

-- Subgroup 60: Purchases (Group 6)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '60 - Purchases', NOW(), NOW(), '1', '1', 'Purchases', 0, '1', 1, '6_60', '', '', '6');

-- Subgroup 61: Inventory Variation (Group 6)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '61 - Inventory Variation', NOW(), NOW(), '1', '1', 'Inventory Variation', 0, '1', 1, '6_61', '', '', '6');

-- Subgroup 62: External Services (Group 6)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '62 - External Services', NOW(), NOW(), '1', '1', 'External Services', 0, '1', 1, '6_62', '', '', '6');

-- Subgroup 63: Taxes (Group 6)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '63 - Taxes', NOW(), NOW(), '1', '1', 'Taxes', 0, '1', 1, '6_63', '', '', '6');

-- Subgroup 64: Personnel Expenses (Group 6)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '64 - Personnel Expenses', NOW(), NOW(), '1', '1', 'Personnel Expenses', 0, '1', 1, '6_64', '', '', '6');

-- Subgroup 65: Other Management Expenses (Group 6)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '65 - Other Management Expenses', NOW(), NOW(), '1', '1', 'Other Management Expenses', 0, '1', 1, '6_65', '', '', '6');

-- Subgroup 66: Financial Expenses (Group 6)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '66 - Financial Expenses', NOW(), NOW(), '1', '1', 'Financial Expenses', 0, '1', 1, '6_66', '', '', '6');

-- Subgroup 67: Losses from Non-current Assets and Exceptional Expenses (Group 6)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '67 - Losses from Non-current Assets and Exceptional Expenses', NOW(), NOW(), '1', '1', 'Losses from Non-current Assets and Exceptional Expenses', 0, '1', 1, '6_67', '', '', '6');

-- Subgroup 68: Amortization Provisions (Group 6)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '68 - Amortization Provisions', NOW(), NOW(), '1', '1', 'Amortization Provisions', 0, '1', 1, '6_68', '', '', '6');

-- Subgroup 69: Impairment Losses and Other Provisions (Group 6)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '69 - Impairment Losses and Other Provisions', NOW(), NOW(), '1', '1', 'Impairment Losses and Other Provisions', 0, '1', 1, '6_69', '', '', '6');

-- Subgroup 70: Sales of Goods, Own Production, Services, etc. (Group 7)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '70 - Sales of Goods, Own Production, Services, etc.', NOW(), NOW(), '1', '1', 'Sales of Goods, Own Production, Services, etc.', 0, '1', 1, '7_70', '', '', '7');

-- Subgroup 71: Inventory Variation (Group 7)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '71 - Inventory Variation', NOW(), NOW(), '1', '1', 'Inventory Variation', 0, '1', 1, '7_71', '', '', '7');

-- Subgroup 73: Work Performed for the Company (Group 7)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '73 - Work Performed for the Company', NOW(), NOW(), '1', '1', 'Work Performed for the Company', 0, '1', 1, '7_73', '', '', '7');

-- Subgroup 74: Grants, Donations and Legacies (Group 7)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '74 - Grants, Donations and Legacies', NOW(), NOW(), '1', '1', 'Grants, Donations and Legacies', 0, '1', 1, '7_74', '', '', '7');

-- Subgroup 75: Other Management Income (Group 7)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '75 - Other Management Income', NOW(), NOW(), '1', '1', 'Other Management Income', 0, '1', 1, '7_75', '', '', '7');

-- Subgroup 76: Financial Income (Group 7)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '76 - Financial Income', NOW(), NOW(), '1', '1', 'Financial Income', 0, '1', 1, '7_76', '', '', '7');

-- Subgroup 77: Profits from Non-current Assets and Exceptional Income (Group 7)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '77 - Profits from Non-current Assets and Exceptional Income', NOW(), NOW(), '1', '1', 'Profits from Non-current Assets and Exceptional Income', 0, '1', 1, '7_77', '', '', '7');

-- Subgroup 79: Excess and Applications of Provisions and Impairment Losses (Group 7)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '79 - Excess and Applications of Provisions and Impairment Losses', NOW(), NOW(), '1', '1', 'Excess and Applications of Provisions and Impairment Losses', 0, '1', 1, '7_79', '', '', '7');

-- Subgroup 80: Financial Expenses for Financial Asset Valuation (Group 8)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '80 - Financial Expenses for Financial Asset Valuation', NOW(), NOW(), '1', '1', 'Financial Expenses for Financial Asset Valuation', 0, '1', 1, '8_80', '', '', '8');

-- Subgroup 81: Expenses in Hedging Operations (Group 8)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '81 - Expenses in Hedging Operations', NOW(), NOW(), '1', '1', 'Expenses in Hedging Operations', 0, '1', 1, '8_81', '', '', '8');

-- Subgroup 82: Exchange Rate Difference Expenses (Group 8)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '82 - Exchange Rate Difference Expenses', NOW(), NOW(), '1', '1', 'Exchange Rate Difference Expenses', 0, '1', 1, '8_82', '', '', '8');

-- Subgroup 83: Income Tax (Group 8)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '83 - Income Tax', NOW(), NOW(), '1', '1', 'Income Tax', 0, '1', 1, '8_83', '', '', '8');

-- Subgroup 84: Transfers of Grants, Donations and Legacies (Group 8)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '84 - Transfers of Grants, Donations and Legacies', NOW(), NOW(), '1', '1', 'Transfers of Grants, Donations and Legacies', 0, '1', 1, '8_84', '', '', '8');

-- Subgroup 85: Expenses for Actuarial Losses and Adjustments in Assets for Long-term Defined Benefit Remuneration (Group 8)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '85 - Expenses for Actuarial Losses and Adjustments in Assets for Long-term Defined Benefit Remuneration', NOW(), NOW(), '1', '1', 'Expenses for Actuarial Losses and Adjustments in Assets for Long-term Defined Benefit Remuneration', 0, '1', 1, '8_85', '', '', '8');

-- Subgroup 86: Expenses for Non-current Assets Held for Sale (Group 8)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '86 - Expenses for Non-current Assets Held for Sale', NOW(), NOW(), '1', '1', 'Expenses for Non-current Assets Held for Sale', 0, '1', 1, '8_86', '', '', '8');

-- Subgroup 89: Expenses from Participations in Group or Associated Companies with Previous Positive Valuation Adjustments (Group 8)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '89 - Expenses from Participations in Group or Associated Companies with Previous Positive Valuation Adjustments', NOW(), NOW(), '1', '1', 'Expenses from Participations in Group or Associated Companies with Previous Positive Valuation Adjustments', 0, '1', 1, '8_89', '', '', '8');

-- Subgroup 90: Financial Income for Financial Asset Valuation (Group 9)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '90 - Financial Income for Financial Asset Valuation', NOW(), NOW(), '1', '1', 'Financial Income for Financial Asset Valuation', 0, '1', 1, '9_90', '', '', '9');

-- Subgroup 91: Income in Hedging Operations (Group 9)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '91 - Income in Hedging Operations', NOW(), NOW(), '1', '1', 'Income in Hedging Operations', 0, '1', 1, '9_91', '', '', '9');

-- Subgroup 92: Exchange Rate Difference Income (Group 9)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '92 - Exchange Rate Difference Income', NOW(), NOW(), '1', '1', 'Exchange Rate Difference Income', 0, '1', 1, '9_92', '', '', '9');

-- Subgroup 94: Income from Grants, Donations and Legacies (Group 9)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '94 - Income from Grants, Donations and Legacies', NOW(), NOW(), '1', '1', 'Income from Grants, Donations and Legacies', 0, '1', 1, '9_94', '', '', '9');

-- Subgroup 95: Income from Actuarial Gains and Adjustments in Assets for Long-term Defined Benefit Remuneration (Group 9)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '95 - Income from Actuarial Gains and Adjustments in Assets for Long-term Defined Benefit Remuneration', NOW(), NOW(), '1', '1', 'Income from Actuarial Gains and Adjustments in Assets for Long-term Defined Benefit Remuneration', 0, '1', 1, '9_95', '', '', '9');

-- Subgroup 96: Income from Non-current Assets Held for Sale (Group 9)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '96 - Income from Non-current Assets Held for Sale', NOW(), NOW(), '1', '1', 'Income from Non-current Assets Held for Sale', 0, '1', 1, '9_96', '', '', '9');

-- Subgroup 99: Income from Participations in Equity of Group or Associated Companies with Previous Negative Valuation Adjustments (Group 9)
INSERT INTO stic_ledger_accounts (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, active, subgroup, account, subaccount, ledger_group)
VALUES (UUID(), '99 - Income from Participations in Equity of Group or Associated Companies with Previous Negative Valuation Adjustments', NOW(), NOW(), '1', '1', 'Income from Participations in Equity of Group or Associated Companies with Previous Negative Valuation Adjustments', 0, '1', 1, '9_99', '', '', '9');