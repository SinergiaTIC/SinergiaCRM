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

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

// Incluye los archivos necesarios para cargar la clase de la vista
require_once('include/MVC/View/SugarView.php');
require_once('modules/stic_Signatures/views/view.signatureportal.php'); // Asegúrate de que esta ruta sea correcta

// Inicia una instancia de la clase de la vista
$view = new stic_SignaturePortal();

// Llama al método display() de la vista
$view->display();

// Termina la ejecución del script para asegurar que no se cargue nada adicional del framework de SuiteCRM
sugar_die('');