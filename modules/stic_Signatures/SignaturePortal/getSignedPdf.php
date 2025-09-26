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

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

global $sugar_config;

// Obtener el ID del firmante de la URL
$signerId = $_REQUEST['signerId'] ?? null;

if ($signerId) {
    $pdfPath = $sugar_config['upload_dir'] . $signerId . '_signed.pdf';

    if (file_exists($pdfPath)) {
        // Establecer las cabeceras HTTP correctas
        header('Content-type: application/pdf');
        header('Content-Disposition: inline; filename="' . basename($pdfPath) . '"');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . filesize($pdfPath));

        // Limpiar el búfer de salida antes de enviar el archivo
        ob_clean();
        flush();

        // Leer y enviar el contenido del archivo
        readfile($pdfPath);
        exit;
    } else {
        http_response_code(404);
        die('Archivo PDF no encontrado.');
    }
} else {
    http_response_code(400);
    die('ID de firmante no proporcionado.');
}
