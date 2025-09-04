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

class Norma43 {

    public static function importNorma43() {
        global $mod_strings;

        $error = false;
        // $GLOBALS['log']->fatal('>>> Entrando en Norma43::importNorma43');
        // $GLOBALS['log']->fatal('>>> $_FILES: ' . print_r($_FILES, true));

        // Comprobar si hay fichero
        if (!isset($_FILES['file']) || $_FILES['file']['error'] > 0) {
            SugarApplication::appendErrorMessage("Error subiendo fichero Norma 43.");
            return;
        }

        $filePath = $_FILES['file']['tmp_name'];

        // Leer CSV
        $rows = array_map(function($line) {
            return str_getcsv($line, ';');
        }, file($filePath));

        if (count($rows) < 4) {
            SugarApplication::appendErrorMessage("El fichero no tiene datos suficientes.");
            return;
        }

        // Cabecera de la cuenta
        $warded_person   = $rows[1][0];
        $iban             = $rows[1][1];
        $current_balance  = $rows[1][3];

        // Buscar o crear producto financiero
        $product = BeanFactory::getBean('stic_Financial_Products')
            ->retrieve_by_string_fields(['iban' => $iban]);

        if (!$product) {
            $product = BeanFactory::newBean('stic_Financial_Products');
        }

        $product->warded_person  = $warded_person;
        $product->iban            = $iban;
        $product->current_balance    = str_replace(['EUR', ','], ['', '.'], $current_balance);
        $product->save();

        // Movimientos
        $imported = 0;
        for ($i = 3; $i < count($rows); $i++) {
            if (count($rows[$i]) < 4) continue;

            list($concept, $date_transaction, $amount, $balance) = $rows[$i];

            $transaction = BeanFactory::newBean('stic_Transactions');
            $transaction->description       = $concept;
            $transaction->date_transaction  = date('Y-m-d', strtotime($date_transaction));
            $transaction->amount            = floatval(str_replace(',', '.', $amount));
            $product->current_balance             = str_replace(['EUR', ','], ['', '.'], $balance);

            // Ajusta el nombre real del campo relación
            $transaction->stic_financial_product = $product->id;

            $transaction->save();
            $imported++;
        }

        SugarApplication::appendErrorMessage("Importación finalizada. Movimientos importados: " . $imported);
    }
    
    // public static $xmlReturnDate = '';
    
    // public static function importNorma43($filePath) {
    //     $rows = array_map('str_getcsv', file($filePath));
        
    //     // Usar ';' como separador
    //     foreach ($rows as &$row) {
    //         $row = str_getcsv($row[0], ';');
    //     }
    
    //     // Extraer cabecera
    //     $warded_person = $rows[1][0];
    //     $iban = $rows[1][1];
    //     $current_balance = $rows[1][3];
    
    //     // Guardar/actualizar producto financiero
    //     $product = BeanFactory::getBean('stic_Financial_Products')->retrieve_by_string_fields([
    //         'iban' => $iban
    //     ]);
    //     if (!$product) {
    //         $product = BeanFactory::newBean('stic_Financial_Products');
    //     }
    //     $product->warded_person = $warded_person;
    //     $product->iban = $iban;
    //     $product->current_balance = str_replace(['EUR', ','], ['', '.'], $current_balance);
    //     $product->save();
    
    //     // Movimientos empiezan en la fila 3
    //     for ($i = 3; $i < count($rows); $i++) {
    //         if (count($rows[$i]) < 4) continue; // línea vacía
    //         list($concept, $date_transaction, $amount, $saldo) = $rows[$i];
    
    //         $transaction = BeanFactory::newBean('stic_Transactions');
    //         $transaction->description = $concept;
    //         $transaction->date_transaction = date('Y-m-d', strtotime($date_transaction));
    //         $transaction->amount = floatval(str_replace(',', '.', $amount));
    //         $transaction->saldo = str_replace(['EUR', ','], ['', '.'], $saldo);
    //         $transaction->financial_product = $product->id;
    //         $transaction->save();
    //     }
    // }

}