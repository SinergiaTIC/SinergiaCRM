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

                // Verificación de la Condición (si existe)
                if (!empty($actionConfig->condition_field)) {
                    if (!$this->checkCondition($actionConfig, $this->context)) {
                        $GLOBALS['log']->info('Line '.__LINE__.': '.__METHOD__.': '. "Skipping action '{$actionConfig->text}' because condition failed.");
                        
                        // Registramos la acción como saltada
                        $skippedResult = new ActionResult(ResultStatus::SKIPPED, $actionConfig, "Condition not met.");
                        $this->context->addActionResult($skippedResult);
                        continue; 
                    }
                }

                // Buscamos la acción a ejecutar (excepción si no existe)
                $actionExecutor = $this->factory->createAction($actionConfig);
                
                // Si es Terminal no la ejecutamos: paramos el bucle y la retornamos para que se ejecute después
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


                if ($actionResult->isWait()) {
                    // Marcamos que la respuesta está esperando
                    if ($this->context->responseBean) {
                        $this->context->responseBean->status = 'awaiting_action';
                        $this->context->responseBean->save();
                    }
                    
                    $GLOBALS['log']->info("Advanced Web Forms: Flow paused by action '{$actionConfig->name}'. Reason: " . $actionResult->message);

                    // Retornamos null para finalizar: El motor se pondrá en espera
                    return null; 
                }

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

    /**
     * Verifica si se cumple la condición para ejecutar una acción.
     * @param FormAction $action La acción a verificar
     * @param ExecutionContext $context El contexto de ejecución
     * @return bool True si se cumple la condición o no hay condición, false en caso contrario
     */
    private function checkCondition(FormAction $action, ExecutionContext $context): bool {
        $fieldKey = $action->condition_field; // Ex: "Contact0.newsletter"
        $expectedValue = $action->condition_value;

        // Convertimos la clave lógica a clave PHP (reemplazando '.' por '_')
        $phpKey = str_replace('.', '_', $fieldKey);

        // Buscamos el valor enviado en el formulario
        if (!isset($context->formData[$phpKey])) {
            $submittedValue = $context->formData[$phpKey];
        } else {
            $submittedValue = '0'; // Assumimos false si no ha llegado del formulario
        }

        // Comprobamos si el valor enviado coincide con el esperado
        if (is_array($submittedValue)) {
            return in_array($expectedValue, $submittedValue);
        }
        return $submittedValue == $expectedValue;
    }
}