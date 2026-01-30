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
// Prevents directly accessing this file from a web browser
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class ServerActionFactory {

    public function createAction(FormAction $actionConfig): ServerActionDefinition {
        
        $actionName = $actionConfig->name;    // Ex: 'SaveRecordAction' (nombre del fichero sin extensión y de la clase)
        $className = $actionName;             // Ex: 'SaveRecordAction' (nombre de la clase)

        // Definición de las rutas de búsqueda con prioridad
        $basePath = 'modules/stic_Advanced_Web_Forms/actions/Hook/';
        $searchPaths = [
            'custom/Extension/' . $basePath . $actionName . '.php',
            'custom/' . $basePath . $actionName . '.php',
            $basePath . $actionName . '.php',
        ];

        $filePath = null;

        // Buscamos el fichero en las rutas definidas
        foreach ($searchPaths as $path) {
            if (file_exists($path)) { 
                $filePath = $path;
                break;
            }
        }

        if (!$filePath) {
            throw new \InvalidArgumentException("There is no file with definition for Action with Name: {$className}");
        }

        require_once($filePath);
        if (!class_exists($className)) {
            throw new \RuntimeException("There is a file '{$filePath}', but do not has definition for Action with Name: '{$className}'");
        }
        
        // check that the class is a subclass of ServerActionDefinition
        $reflection = new \ReflectionClass($className);
        if (!$reflection->isSubclassOf(ServerActionDefinition::class)) {
            throw new \RuntimeException("The class '{$className}' is not a subclass of 'ServerActionDefinition'.");
        }

        /** @var ServerActionDefinition $action */
        $action = new $className();
        
        return $action;
    }
}