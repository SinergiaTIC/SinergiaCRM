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

class ActionDiscoveryService {
    /** @var string[] Rutas para la búsqueda de acciones */
    private static array $basePaths = [
        'modules/stic_Advanced_Web_Forms/actions/',
        'custom/modules/stic_Advanced_Web_Forms/actions/',
    ];

    /**
     * Escanea todas las rutas configuradas para descubrir acciones, gestionando las anulaciones (sobre-escrituras) en las rutas de custom.
     * @param ActionType[] $availableTypes Los tipos de acciones a descubrir (vacío para todos)
     * @return ActionDefinition[] Las acciones descubiertas
     */
    public static function discoverActions(array $availableTypes = []): array {
        $discoveredFiles = [];
        if (empty($availableTypes)) {
            $availableTypes = ActionType::cases();
        }

        // Iteramos sobre los directorios base: core -> custom - > custom/Extension
        foreach (self::$basePaths as $basePath) {
            if (!is_dir($basePath)) {
                continue;
            }
            // Iteramos dinámicamente sobre los tipos de acciones
            foreach ($availableTypes as $actionType) {
                $typePath = $basePath . $actionType->value . '/';
                if (is_dir($typePath)) {
                    $searchPath = $typePath.'*.php';
                    foreach (glob($searchPath) as $filePath) {
                        $actionName = pathinfo($filePath, PATHINFO_FILENAME); // Ex: 'SaveRecordAction'
                        $discoveredFiles[$actionName] = $filePath; // El último archivo encontrado (con mayor prioridad) sobrescribe los anteriores
                    }
                }
            }
        }

        // Instanciamos las definiciones de acción descubiertas
        $discoveredActions = [];
        foreach ($discoveredFiles as $actionName => $filePath) {
            require_once($filePath);
            if (!class_exists($actionName)) {
                $GLOBALS['log']->warning("Line ".__LINE__.": ".__METHOD__.": File {$filePath} exists, but class {$actionName} is not defined.");
                continue;
            }
            try {
                $reflection = new \ReflectionClass($actionName);
                if ($reflection->isAbstract() || !$reflection->isSubclassOf(ActionDefinition::class)) { 
                    $GLOBALS['log']->warning("Line ".__LINE__.": ".__METHOD__.": Class {$actionName} is abstract or not a subclass of ActionDefinition.");
                    continue; 
                }
                /** @var ActionDefinition $actionInstance */
                $actionInstance = new $actionName();
                $discoveredActions[$actionName] = $actionInstance;

            } catch (\Exception $e) {
                $GLOBALS['log']->error("Line ".__LINE__.": ".__METHOD__.": Error creating an instance for action {$actionName}: " . $e->getMessage());
            }
        }

        return array_values($discoveredActions);
    }

    
}