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

class Norma43
{
    private static $db;
    private static $warnings = [];
    private static $parsedAccounts = [];

    /**
     * Import a Norma 43 file and parse its contents
     * @param bool $preview If true, do not save changes to the database
     * @return array Summary of the import process
     */
    public static function importNorma43($preview = false)
    {
        global $db, $mod_strings;
        self::$db = $db;

        // Validate file upload
        if (empty($_FILES['file']['name'])) {
            return ['success' => false, 'error' => $mod_strings['LBL_ERROR_FILE_NOT_SELECTED']];
        }

        $upload_dir = 'upload/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $uploaded_file = $upload_dir . basename($_FILES['file']['name']);
        
        move_uploaded_file($_FILES['file']['tmp_name'], $uploaded_file);

        // Check MIME type
        $mime = mime_content_type($uploaded_file);
        if (strpos($mime, 'text') === false && $mime !== 'application/octet-stream') {
            return ['success' => false, 'error' => $mod_strings['LBL_ERROR_FILE_TEXT_PLAIN']];
        }

        // Check the first line to verify it's a Norma 43 file
        $firstLine = trim(fgets(fopen($uploaded_file, 'r')));
        if (substr($firstLine, 0, 2) !== '11') {
            return ['success' => false, 'error' => $mod_strings['LBL_ERROR_NORMA_43_INVALID']];
        }

        // Parse the Norma 43 file
        $parseResult = self::parseNorma43($uploaded_file);
        if (!$parseResult['success']) {
            return ['success' => false, 'error' => $parseResult['error']];
        }
        self::$parsedAccounts = $parseResult['accounts'];

        // Summary of the import process
        $summary = [
            'iban' => 0,
            'financial_product' => '',
            'initial_balance' => 0,
            'total_movements' => 0,
            'imported_movements' => 0,
            'skipped_duplicates' => 0,
            'new_movements' => [],
            'duplicates' => [],
            'warnings' => self::$warnings,
            'accounts' => []
        ];

        // To avoid duplicates within the same file and DB
        $importedHashes = [];

        foreach (self::$parsedAccounts as &$account) {
            // Generate IBAN from entity, office, and account number
            $account['iban'] = self::buildSpanishIBAN(
                preg_replace('/\D/', '', $account['entity_code']),
                preg_replace('/\D/', '', $account['office_code']),
                preg_replace('/\D/', '', $account['account_number'])
            );
            $productId = self::findOrCreateProduct($account, $preview);

            // Summary data for the first account
            $summary['iban'] = $account["iban"];
            $summary['financial_product'] = $account["account_name"];
            $summary['initial_balance'] = $account["initial_balance"];
            $summary['type'] = $account["type"];
            $summary['entity'] = self::parseEntity($account["entity_code"]);

            // Loop through movements
            foreach ($account['movements'] as $mov) {
                $summary['total_movements']++;
                // Map movement data to transaction fields
                $mapped = self::mapMovementData($mov);

                // Basic validation
                if (empty($mapped['transaction_date'])) {
                    $summary['warnings'][] = 'Movimiento sin fecha en cuenta ' . $account['iban'] . '. Se asigna la fecha de inicio de la cuenta.';
                    $mapped['transaction_date'] = $account['start_date'];
                }
                if (empty($mapped['amount']) || !is_numeric($mapped['amount'])) {
                    $summary['warnings'][] = 'Movimiento sin importe válido en cuenta ' . $account['iban'] . '. Se omite el movimiento.';
                    continue;
                }

                // Generate the hash of the movement
                $date   = trim($mapped['transaction_date']);
                $amount = round((float)$mapped['amount'], 2);
                $name   = trim(preg_replace('/\s+/', ' ', mb_strtolower($mapped['name'] ?? '', 'UTF-8')));
                $hash   = md5($productId . '|' . $date . '|' . number_format($amount, 2, '.', '') . '|' . $name);

                // Check for duplicates
                if (self::isDuplicate($mapped, $productId, $importedHashes)) {
                    $summary['skipped_duplicates']++;
                    $summary['duplicates'][] = $mapped;
                    continue;
                }

                $summary['new_movements'][] = $mapped;
            }
            // In preview mode, we do not insert movements or update balances
            if (!$preview) {
                self::updateProductBalance($productId, $account);
            }
        }

        $_SESSION['norma43_parsed_accounts'] = self::$parsedAccounts;
        $_SESSION['norma43_summary'] = $summary;
        return $summary;
    }

    /**
     * Finalize the import of parsed Norma 43 data
     * @param array $forceImports List of movement hashes to force import even if they are duplicates
     * @return array Summary of the import process
     */
    public static function finalizeImport($forceImports = [])
    {
        global $db;
        self::$db = $db;
        // Retrieve parsed accounts from session
        $parsedAccounts = $_SESSION['norma43_parsed_accounts'] ?? [];
        if (empty($parsedAccounts)) return [];

        $summary = ['imported_movements' => 0];

        // To avoid duplicates within the same file and DB
        $importedHashes = [];

        // Loop through accounts and movements to insert them
        foreach ($parsedAccounts as $account) {
            // Find or create the financial product again
            $productId = self::findOrCreateProduct($account);
            // Insert movements
            foreach ($account['movements'] as $mov) {
                $mapped = self::mapMovementData($mov);
                $date   = trim($mapped['transaction_date']);
                $amount = round((float)$mapped['amount'], 2);
                $name   = trim(preg_replace('/\s+/', ' ', mb_strtolower($mapped['name'] ?? '', 'UTF-8')));
                $hash   = md5($productId . '|' . $date . '|' . number_format($amount, 2, '.', '') . '|' . $name);

                // Avoiding duplicates in the database
                if (self::isDuplicate($mapped, $productId, $importedHashes)) continue;

                self::insertTransaction($mapped, $productId);
                $summary['imported_movements']++;
            }
            self::updateProductBalance($productId, $account);
        }

        unset($_SESSION['norma43_parsed_accounts']);
        return $summary;
    }

    /**
     * Parse a Norma 43 file and extract accounts and movements
     * @param string $filePath The path to the Norma 43 file
     * @return array The parsed accounts and movements
     */
    private static function parseNorma43($filePath)
    {
        global $mod_strings;

        $accounts = [];
        $currentAccount = null;
        $handle = fopen($filePath, 'r');
        if (!$handle) return ['success' => false, 'error' => $mod_strings['LBL_ERROR_FILE_CANNOT_OPEN']];

        while (($line = fgets($handle)) !== false) {
            $line = rtrim($line, "\r\n");
            $recordType = substr($line, 0, 2);
            switch ($recordType) {
                case '11':
                    // Case 11 - Account Header
                    // If there is a current account being processed, save it before starting a new one
                    if ($currentAccount) $accounts[] = $currentAccount;
                    $currentAccount = self::parseAccountHeader($line);
                    break;
                case '22':
                    // Case 22 - Movement Record
                    // Add movement to the current account
                    if ($currentAccount) $currentAccount['movements'][] = self::parseMovement($line);
                    break;
                case '23':
                    // Case 23 - Complementary Information
                    // Add complementary info to the last movement of the current account
                    if ($currentAccount && !empty($currentAccount['movements'])) {
                        $lastIndex = count($currentAccount['movements']) - 1;
                        $currentAccount['movements'][$lastIndex]['complementary'][] = self::parseComplementary($line);
                    }
                    break;
                case '33':
                    if ($currentAccount) {
                        // Case 33 - End of account (according to official Norma 43 standard)
                        // Positions 21-25: Number of debit entries (5 digits)
                        $debitCount = (int)substr($line, 20, 5);
                        
                        // Positions 26-39: Total Debit (14 digits, 2 decimals implicit)
                        $debitTotalStr = substr($line, 25, 14);
                        $debitTotal = (float)(intval($debitTotalStr) / 100);
                        
                        // Positions 40-44: Number of credit entries (5 digits)
                        $creditCount = (int)substr($line, 39, 5);
                        
                        // Positions 45-58: Total Credit (14 digits, 2 decimals implicit)
                        $creditTotalStr = substr($line, 44, 14);
                        $creditTotal = (float)(intval($creditTotalStr) / 100);
                        
                        // Position 59: Final balance sign (1=Debit, 2=Credit)
                        $finalBalanceSign = substr($line, 58, 1);
                        
                        // Positions 60-73: Final balance (14 digits, 2 decimals implicit)
                        $finalBalanceStr = substr($line, 59, 14);
                        $finalBalance = self::parseAmount($finalBalanceStr, $finalBalanceSign);
                        
                        // Positions 74-76: Currency (3 digits, e.g., 978 = EUR)
                        $currency = trim(substr($line, 73, 3));
                        
                        // Save account summary information
                        $currentAccount['debit_count'] = $debitCount;
                        $currentAccount['debit_total'] = -$debitTotal;  // Debit is negative
                        $currentAccount['credit_count'] = $creditCount;
                        $currentAccount['credit_total'] = $creditTotal; // Credit is positive
                        $currentAccount['final_balance'] = $finalBalance;
                        $currentAccount['final_currency'] = $currency;
                        
                        // Save the current account and reset for the next one
                        $accounts[] = $currentAccount;
                        $currentAccount = null;
                    }
                    break;
            }
        }
        // At the end of the file, if there's an account being processed, save it
        if ($currentAccount) $accounts[] = $currentAccount;
        fclose($handle);
        return ['success' => true, 'accounts' => $accounts];
    }

    /**
     * Parse the account header from Norma 43 format
     * @param string $line The line containing the account header
     * @return array The parsed account data
     */
    private static function parseAccountHeader($line)
    {
        return [
            'entity_code' => trim(substr($line, 2, 4)),
            'office_code' => trim(substr($line, 6, 4)),
            'account_number' => trim(substr($line, 10, 10)),
            'initial_balance' => self::parseAmount(substr($line, 33, 14), substr($line, 32, 1)),
            'account_name' => trim(substr($line, 51, 26)),
            'start_date' => self::parseDate(substr($line, 20, 6)),
            'movements' => []
        ];
    }

    /**
     * Parse the movement record from Norma 43 format
     * @param string $line The line containing the movement record
     * @return array The parsed movement data
     */
    private static function parseMovement($line)
    {
        // Positions according to Standard 43
        $operationDateStr = substr($line, 10, 6); // 11–16 -> Date transaction
        $sign             = substr($line, 27, 1); // 28 -> Sign
        $amountStr        = substr($line, 28, 14); // 29–42 -> Amount (14 digits without a decimal point)
        $methodCode       = substr($line, 22, 4); // 23-27 -> Method

        return [
            'operation_date' => self::parseDate($operationDateStr),
            'amount' => self::parseAmount($amountStr, $sign),
            'method' => self::parseMethod($methodCode),
            'complementary' => []
        ];
    }

    /**
     * Parse the code of the entity from Norma 43 format
     * @param string $entityCode The entity code (4 digits)
     * @return string The parsed entity name or null if unknown
     */
    public static function parseEntity($entityCode) {
        switch ($entityCode) {
            case '2080':
                $entity = 'Abanca Corporación Bancaria';
                break;
            case '0061':
                $entity = 'Banca March';
                break;
            case '0182':
                $entity = 'Banco Bilbao Vizcaya Argentaria (BBVA)';
                break;
            case '0234':
                $entity = 'Banco Caminos';
                break;
            case '0240':
                $entity = 'Banco de Crédito Social Cooperativo';
                break;
            case '0081':
                $entity = 'Banco Sabadell';
                break;
            case '0186':
                $entity = 'Banco Mediolanum';
                break;
            case '0049':
                $entity = 'Banco Santander';
                break;
            case '0128':
                $entity = 'Bankinter';
                break;
            case '2100':
                $entity = 'Caixabank';
                break;
            case '2045':
                $entity = 'Caja de Ahorros y Monte de Piedad de Ontinyent';
                break;
            case '3035':
                $entity = 'Caja Laboral Popular CC';
                break;
            case '3058':
                $entity = 'Cajamar Caja Rural';
                break;
            case '2000':
                $entity = 'Cecabank';
                break;
            case '1474':
                $entity = 'Citibank Europe PLC';
                break;
            case '0019':
                $entity = 'Deutsche Bank SAE';
                break;
            case '0239':
                $entity = 'EVO Banco';
                break;
            case '2085':
                $entity = 'Ibercaja Banco';
                break;
            case '1465':
                $entity = 'ING Bank NV';
                break;
            case '2095':
                $entity = 'Kutxabank';
                break;
            case '2103':
                $entity = 'Unicaja Banco';
                break;
            case '0011':
                $entity = 'Allfunds Bank';
                break;
            case '0241':
                $entity = 'A&G Banco';
                break;
            case '0220':
                $entity = 'Banco Finantia';
                break;
            case '0235':
                $entity = 'Banco Pichincha España';
                break;
            case '1490':
                $entity = 'Singular Bank';
                break;
            case '1491':
                $entity = 'Triodos Bank';
                break;
            case '1508':
                $entity = 'Renault Bank';
                break;
            case '8832':
                $entity = 'Bankinter Consumer Finance';
                break;
            case '8833':
                $entity = 'BPCE Equipment Solutions Iberia';
                break;
            case '8843':
                $entity = 'BANGE Credit E.F.C.';
                break;
        }

        return $entity;
    }

    /**
     * Parse the method code from Norma 43 format
     * @param string $methodCode The method code (4 digits)
     * @return string The parsed method or null if unknown
     */
    public static function parseMethod($methodCode) {
        switch ($methodCode) {
            case '0200':
            case '0400':
                $method = 'bizum';
                break;
            case '0304':
            case '0303':
                $method = 'direct_debit';
                break;
            case '1204':
                $method = 'card';
                break;
            case '1104':
                $method = 'cash';
                break;
        }

        return $method;
    }

    /**
     * Parse the complementary information from Norma 43 format
     * @param string $line The line containing the complementary information
     * @return string The parsed complementary information (trimmed)
     */
    private static function parseComplementary($line)
    {
        return trim(substr($line, 4));
    }

    /**
     * Parse the amount from Norma 43 format
     * @param string $amountStr The amount string (14 digits, 2 decimals implicit)
     * @param string $sign The sign character ('1' for debit, '2' for credit)
     * @return float The parsed amount as a decimal number
     */
    private static function parseAmount($amountStr, $sign)
    {
        // Convert to decimal number
        $amount = (float)(intval($amountStr) / 100);

        // Sign: 1 = Expense (negative), 2 = Income (positive)
        if ($sign == '1') {
            $amount = -$amount;
        }
        return $amount;
    }

    /**
     * Map a movement to the transaction fields
     * @param array $movement The movement data from the Norma 43 file
     * @return array The mapped transaction data
     */
    public static function mapMovementData($movement)
    {
        $mapped = [
            'transaction_date' => $movement['operation_date'] ?? null,
            'name' => 'Movimiento sin descripción',
            'amount' => $movement['amount'],
            'type' => ($movement['amount'] ?? 0) >= 0 ? 'income' : 'expense',
            'payment_method' => $movement['method'],
        ];

        $description_parts = [];

        if (!empty($movement['complementary'])) {
            foreach ($movement['complementary'] as $comp) {
                $clean_comp = trim($comp);

                // Normalize potentially malformed characters (convert to UTF-8 and clean up)
                $clean_comp = iconv(mb_detect_encoding($clean_comp, mb_detect_order(), true), "UTF-8//IGNORE", $clean_comp);

                // Replace non-printable or rare characters
                $clean_comp = preg_replace('/[^\PC\s]/u', '', $clean_comp);

                $clean_comp = preg_replace(
                    '/Fecha\s*de\s*opera[^\d:]{0,10}:\s*\d{2}[-\/]\d{2}[-\/]\d{4}/iu',
                    '',
                    $clean_comp
                );

                // Remove extra spaces
                $clean_comp = trim(preg_replace('/\s+/', ' ', $clean_comp));

                if (!empty($clean_comp)) {
                    $description_parts[] = $clean_comp;
                }
            }
        }

        $description = trim(implode(' ', $description_parts));

        if (!empty($description)) {
            $mapped['name'] = $description_parts[0];
            $mapped['description'] = $description;
        } elseif ($mapped['transaction_date']) {
            $mapped['name'] = 'Movimiento ' . $mapped['transaction_date'];
        }

        // Format the amount with a sign
        $signSymbol = ($mapped['amount'] >= 0) ? '+' : '-';
        $absAmount = abs($mapped['amount']);
        $mapped['amount_formatted'] = sprintf('%s%s €', $signSymbol, number_format($absAmount, 2, ',', '.'));

        return $mapped;
    }

    /**
     * Check if a transaction is a duplicate
     * @param array $mapped_data The mapped transaction data
     * @param string $productId The ID of the associated financial product
     * @param array $importedHashes Reference to the array of already imported hashes in the current session
     * @return bool True if the transaction is a duplicate, false otherwise
     */
    private static function isDuplicate($mapped_data, $productId, &$importedHashes = [])
    {
        global $db;

        // Get the IBAN of the financial product
        $product = BeanFactory::getBean('stic_Financial_Products', $productId);
        $iban = $product ? $product->iban : '';

        // Generate the transaction hash
        $hash = self::generateTransactionHash(
            $iban,
            $mapped_data['transaction_date'] ?? '',
            $mapped_data['amount'] ?? 0,
            $mapped_data['name'] ?? ''
        );

        // Internal check (duplicate within the same file)
        if (in_array($hash, $importedHashes)) {
            return true; // It already exists in the imported hashes
        }

        // Searching for duplicates in the database correctly linked to the product
        $query = "
            SELECT id
            FROM stic_transactions
            WHERE deleted = 0
            AND transaction_hash = " . $db->quoted($hash) . "
            LIMIT 1
        ";
        $result = $db->query($query);
        $existsInDb = $db->fetchByAssoc($result) !== false;

        // If not a duplicate, add it to the internal list
        if (!$existsInDb) {
            $importedHashes[] = $hash;
        }

        return $existsInDb;
    }

    /**
     * Insert a new transaction into the database
     * @param array $data The mapped transaction data
     * @param string $productId The ID of the associated financial product
     * @return string The ID of the newly created transaction
     */
    private static function insertTransaction($data, $productId)
    {
        global $current_user;

        $tx = BeanFactory::newBean('stic_Transactions');
        $tx->document_name = $data['name'];
        $tx->transaction_date = $data['transaction_date'];
        $tx->amount = $data['amount'];
        $tx->type = $data['type'];
        $tx->description = $data['description'];
        $tx->payment_method = $data['payment_method'];
        $tx->assigned_user_id = $current_user->id;
        $tx->status = 'pending';
        $tx->stic_trans4a5broducts_ida = $productId;

        // Get the IBAN of the financial product
        $product = BeanFactory::getBean('stic_Financial_Products', $productId);
        $iban = $product ? $product->iban : '';

        // Generate the movement hash for duplicate checking
        $tx->transaction_hash = md5(strtolower(trim(
            $iban . '|' . $data['transaction_date'] . '|' .
            number_format($data['amount'], 2, '.', '') . '|' . $data['name']
        )));

        $tx->save();
        return $tx->id;
    }

    /**
     * Find an existing financial product by IBAN or create a new one
     * @param array $account The account data from the Norma 43 file
     * @param bool $preview If true, do not save changes to the database
     * @return string The ID of the found or created financial product
     */
    private static function findOrCreateProduct($account, $preview = false)
    {
        global $app_list_strings, $current_user;

        $iban = $account['iban'];
        $product = BeanFactory::getBean('stic_Financial_Products')->retrieve_by_string_fields(['iban' => $iban]);

        if (!$product) {
            $product = BeanFactory::newBean('stic_Financial_Products');
            $product->iban = $iban;
            $product->initial_balance = $account['initial_balance'];
        }

        // Update or set other fields
        $product->type = !empty($account['type']) ? $account['type'] : 'current_account';
        $product->name = !empty($account['account_name']) ? $account['account_name'] : $product->type . ' - ' . $product->iban;
        $product->current_balance = $account['final_balance'] ?? $account['initial_balance'];
        $product->entity = self::parseEntity($account["entity_code"]);
        $product->assigned_user_id = $current_user->id;
        $product->start_date = empty($product->start_date) ? $account['start_date'] : $product->start_date;
        $product->active = empty($product->active) ? 1 : $product->active;

        if (!$preview) $product->save();

        return $product->id;
    }

    /**
     * Update the product balance based on its transactions
     * @param string $productId The ID of the financial product to update.
     * @param array $account Optional account data with 'final_balance' to compare.
     * @return void
     */
    public static function updateProductBalance($productId, $account = [])
    {
        // Recalculate the balance based on transactions
        $product = BeanFactory::getBean('stic_Financial_Products', $productId);
        if (!$product) return;

        // If 'final_balance' is provided in the account data, use it for comparison
        $finalBalanceN43 = $account['final_balance'] ?? $product->current_balance ?? 0;

        // Calculate the balance based on initial balance and transactions
        $calculatedBalance = self::calculateBalance($productId);
        $product->current_balance = $calculatedBalance;

        // Check for discrepancies greater than 0.01
        $difference = abs($calculatedBalance - $finalBalanceN43);
        $product->balance_error = ($difference > 0.01) ? 1 : 0;

        $product->save();
    }

    /**
     * Build a Spanish IBAN from entity, office, and account number
     * @param string $entity The bank entity code (4 digits)
     * @param string $office The bank office code (4 digits)
     * @param string $accountNumber The account number (10 digits)
     * @return string The constructed IBAN (24 characters)
     */
    private static function buildSpanishIBAN($entity, $office, $accountNumber)
    {
        // Calculate first check digit
        $weights1 = [4, 8, 5, 10, 9, 7, 3, 6];
        $sum1 = 0;
        $digits1 = str_split($entity . $office);
        foreach ($digits1 as $i => $d) {
            $sum1 += $d * $weights1[$i];
        }
        $dc1 = 11 - ($sum1 % 11);
        if ($dc1 == 11) $dc1 = 0;
        if ($dc1 == 10) $dc1 = 1;

        // Calculate second check digit
        $weights2 = [1, 2, 4, 8, 5, 10, 9, 7, 3, 6];
        $sum2 = 0;
        $digits2 = str_split($accountNumber);
        foreach ($digits2 as $i => $d) {
            $sum2 += $d * $weights2[$i];
        }
        $dc2 = 11 - ($sum2 % 11);
        if ($dc2 == 11) $dc2 = 0;
        if ($dc2 == 10) $dc2 = 1;
        // Construct the full CCC
        $ccc = $entity . $office . $dc1 . $dc2 . $accountNumber;
        // Calculate IBAN check digits
        $ibanTmp = $ccc . "142800";
        // Move first four characters to the end
        $ibanCheck = 98 - self::strMod($ibanTmp, 97);
        // Return the full IBAN
        return "ES" . str_pad($ibanCheck, 2, "0", STR_PAD_LEFT) . $ccc;
    }

    /**
     * Calculate the modulus of a large number represented as a string
     * @param string $num The large number as a string
     * @param int $mod The modulus to apply
     * @return int The result of num mod mod
     */
    private static function strMod($num, $mod)
    {
        $rest = 0;
        $len = strlen($num);
        for ($i = 0; $i < $len; $i++) {
            $rest = ($rest * 10 + (int)$num[$i]) % $mod;
        }
        return $rest;
    }

    /**
     * Calculate the current balance of a financial product based on its initial balance and transactions
     * @param string $productId The ID of the financial product
     * @return float The calculated current balance
     */
    private static function calculateBalance($productId)
    {
        global $db;
        
        // Get initial balance of the product
        $query = "
            SELECT initial_balance 
            FROM stic_financial_products 
            WHERE id = " . $db->quoted($productId) . "
            AND deleted = 0
        ";
        $result = $db->query($query);
        $row = $db->fetchByAssoc($result);
        // Start with the initial balance
        $balance = $row ? (float)$row['initial_balance'] : 0;
        
        // Sum all transactions linked to this product
        $query = "
            SELECT SUM(t.amount) as total
            FROM stic_transactions t
            INNER JOIN stic_transactions_stic_financial_products_c rel
                ON rel.stic_transactions_stic_financial_productsstic_transactions_idb = t.id
                AND rel.deleted = 0
            WHERE rel.stic_trans4a5broducts_ida = " . $db->quoted($productId) . "
            AND t.deleted = 0
        ";
        $result = $db->query($query);
        $row = $db->fetchByAssoc($result);
        
        // Add the sum of transactions to the initial balance
        if ($row && $row['total'] !== null) {
            $balance += (float)$row['total'];
        }
        
        // Round to 2 decimal places
        return round($balance, 2);
    }

    /**
     * Parse a date in YYMMDD format and return it as YYYY-MM-DD
     * @param string $dateStr The date string in YYMMDD format
     * @return string|null The parsed date in YYYY-MM-DD format, or null if invalid
     */
    private static function parseDate($dateStr)
    {
        if (strlen($dateStr) !== 6) return null;
        $year  = '20' . substr($dateStr, 0, 2);
        $month = substr($dateStr, 2, 2);
        $day   = substr($dateStr, 4, 2);
        if (!checkdate((int)$month, (int)$day, (int)$year)) return null;
        return "$year-$month-$day";
    }

    /**
     * Generate a unique hash for a transaction based on its key attributes
     * @param string $iban The IBAN of the financial product
     * @param string $date The transaction date
     * @param float $amount The transaction amount
     * @param string $name The transaction name/description
     * @return string The generated MD5 hash
     */
    private static function generateTransactionHash($iban, $date, $amount, $name)
    {
        $iban = trim(strtolower($iban ?? ''));
        $date = trim($date ?? '');
        $amount = number_format((float)$amount, 2, '.', '');
        $name = mb_strtolower(trim($name ?? ''), 'UTF-8');

        // Generate the hash
        return md5("{$iban}|{$date}|{$amount}|{$name}");
    }
}
