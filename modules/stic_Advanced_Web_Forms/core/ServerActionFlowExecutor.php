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

class ServerActionFlowExecutor {
    private ExecutionContext $context; 
    private ServerActionFactory $factory;
    private ParameterResolverService $resolver;

    public function __construct(ExecutionContext $context) {
        $this->context = $context;
        $this->factory = new ServerActionFactory();
        $this->resolver = new ParameterResolverService();
    }

    /**
     * Ejecuta el flujo principal y gestiona los errores cambiando al flujo de error si es necesario.
     * @param FormFlow $flowConfig La definición del flujo a ejecutar.
     * @param ?FormFlow $errorFlowConfig La definición del flujo de error (null si no hay flujo de error).
     * @return ?FormAction La configuración de la acción Terminal pendiente de ejecutar, o null si ha acabado.
     */
    public function executeFlow(FormFlow $flowConfig, ?FormFlow $errorFlowConfig = null): ?FormAction {
        $lastActionConfig = null;
        try {
            $actions = $flowConfig->actions ?? [];
            foreach ($actions as $actionConfig) {
                $lastActionConfig = $actionConfig;

                // Buscamos la acción a ejecutar (excepción si no existe)
                $actionExecutor = $this->factory->createAction($actionConfig);

                // TODO: Verificación de Condiciones (si existen)
                // if (!$this->checkConditions($actionConfig->conditions, $this->context)) { continue; }

                // Si es Terminal no la ejecutamos: paramos el bucle y la retornamos
                if ($actionExecutor instanceof ITerminalAction) {
                    return $actionConfig;
                }

                // Resolución de Parámetros 
                $paramDefinitions  = $actionExecutor->getParameters();
                $paramConfigurations = $actionConfig->parameters;
                $resolvedParameters = $this->resolver->resolveAll($actionConfig, $paramDefinitions, $paramConfigurations, $this->context);
                $actionConfig->setResolvedParameters($resolvedParameters);

                // Ejecutamos la acción
                $actionResult = $actionExecutor->execute($this->context, $actionConfig); 

                // Actualización del Contexto
                $this->context->addActionResult($actionResult);

                // Detección de Error
                if ($actionResult->isError()) {
                    // Si hay flujo de error: cambio inmediato al flujo de error
                    if ($errorFlowConfig !== null) {
                        return $this->executeFlow($errorFlowConfig);
                    }
                    // Si no hay flujo de error, finalizamos
                    return null; 
                }
            }
        } catch (\Throwable $t) {
            // Captura cualquier Excepción y Fatal Error de PHP y lo convierte en un error del contexto
            $GLOBALS['log']->fatal('Line '.__LINE__.': '.__METHOD__.': '."CRITICAL ERROR in ServerActionFlowExecutor: " . $t->getMessage());
            $this->context->addError($t, $lastActionConfig);
            
            // Si hay flujo de error: cambio inmediato al flujo de error
            if ($errorFlowConfig !== null) {
                try {
                    return $this->executeFlow($errorFlowConfig);
                } catch (\Throwable $t2) {
                    $GLOBALS['log']->fatal('Line '.__LINE__.': '.__METHOD__.': '."Double Fault: Error flow failed too: " . $t2->getMessage());
                }
            }
            // Si no hay flujo de error, finalizamos
            return null; 
        }
        
        return null;
    }

}