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
if (! defined('sugarEntry') || ! sugarEntry) {
    die('Not A Valid Entry Point');
}

// Cargamos todas las clases necesarias para el proceso de Acciones
include_once "modules/stic_Advanced_Web_Forms/actions/coreActions.php";

/**
 * ExampleAction
 * - Esta es la implementación de una acción de ejemplo que sirve como plantilla para desarrolladores de nuevas acciones.
 * - Esta acción es del tipo Hook, se ejecutará en el servidor una vez recibida la respuesta del formulario.
 *
 * * NOMENCLATURA DE FICHEROS:
 *   - El nombre de la clase (Ej: 'ExampleAction') debe coincidir exactamente con el nombre del fichero (Ej: 'ExampleAction.php').
 *
 * * UBICACIÓN:
 *   - El fichero debe colocarse en una subcarpeta de 'modules/stic_Advanced_Web_Forms/actions/' según su tipo. Para esta acción, sería:
 *     .../actions/Hook/ExampleAction.php
 *   - O bien en la carpeta 'custom' para extensiones:
 *     .../custom/modules/stic_Advanced_Web_Forms/actions/Hook/ExampleAction.php
 */
class ExampleAction extends HookActionDefinition
{

    /**
     * Constructor
     */
    public function __construct()
    {
        // Indicamos si la acción está activa o no (si debe aparecer en el wizard o no).
        $this->isActive = false;

        // Indicamos si la acción es una acción común (true) o avanzada (false).
        $this->isCommon = false;

        // Indicamos la categoría de la acción (del listado stic_advanced_web_forms_action_category_list)
        $this->category = 'data';

        // El prefijo a usar para las etiquetas multiidioma usadas por la aplicación
        // Estas etiquetas deberán definirse en los ficheros de idioma del módulo stic_Advanced_Web_Forms
        $this->baseLabel = 'LBL_EXAMPLE_ACTION';
    }

    /**
     * getTitle()
     * (Opcional) Devuelve el título de la acción.
     * Se recomienda NO sobreescribir esta función y usar la traducción de 'LBL_EXAMPLE_ACTION_TITLE'.
     * @return string El título a mostrar en el wizard.
     */
    public function getTitle(): string
    {
        // return $this->translate('TITLE'); // Método recomendado: traducirá la etiqueta "LBL_EXAMPLE_ACTION_TITLE"
        return "Example Action (Commented)"; // Valor fijo para el ejemplo
    }

    /**
     * getDescription()
     * (Opcional) Devuelve la descripción de la acción.
     * Se recomienda NO sobreescribir esta función y usar la traducción de 'LBL_EXAMPLE_ACTION_DESC'.
     * @return string La descripción a mostrar en el wizard.
     */
    public function getDescription(): string
    {
        return "This is a template for developers. It shows how to define and use all parameter types.";
    }

    /**
     * getParameters()
     * Define LOS PARÁMETROS que esta acción necesita.
     * El wizard utilizará esta definición para construir la interfaz de configuración de la acción.
     * @return ActionParameterDefinition[] Un array de definiciones de parámetros.
     */
    public function getParameters(): array
    {
        // En esta función se definen los parámetros que espera la acción.
        // El ParameterResolver usará esta definición para saber qué valor entregar en la función execute().

        // --- 1. Ejemplo de Parámetro VALUE ---
        // El parámetro es un valor fijo, definido en el momento de crear el formulario.
        // Al ejecutar, recibiremos un valor "plano" (string, int, bool, o un objeto \DateTime).
        $paramValue               = new ActionParameterDefinition();
        $paramValue->name         = 'fixed_message';
        $paramValue->text         = 'Fixed Message (VALUE)';
        $paramValue->description  = 'A simple text value.';
        $paramValue->type         = ActionParameterType::VALUE; 
        $paramValue->dataType     = ActionDataType::TEXT;  
        $paramValue->defaultValue = "default value";       // El ParameterResolver usará este valor si no se configura.
        $paramValue->required     = true;

        // --- 2. Ejemplo de Parámetro DATA_BLOCK ---
        // El parámetro es todo un bloque de datos (configuración + datos del $_POST).
        // Al ejecutar, recibiremos un objeto 'DataBlockResolved'.
        $paramBlock                   = new ActionParameterDefinition();
        $paramBlock->name             = 'block_to_process';
        $paramBlock->text             = 'Data Block (DATA_BLOCK)';
        $paramBlock->description      = 'Select the main DataBlock for this action.';
        $paramBlock->type             = ActionParameterType::DATA_BLOCK;
        $paramBlock->supportedModules = ['Contacts']; // (Opcional) El wizard filtrará para mostrar solo bloques de 'Contacts'.
        $paramBlock->required         = true;

        // --- 3. Ejemplo de Parámetro FIELD ---
        // El parámetro es un campo específico del formulario.
        // Al ejecutar, recibiremos un objeto 'DataBlockFieldResolved'.
        $paramField              = new ActionParameterDefinition();
        $paramField->name        = 'email_field';
        $paramField->text        = 'Email Field (FIELD)';
        $paramField->description = 'Select the field that contains the recipient\'s email.';
        $paramField->type        = ActionParameterType::FIELD;
        $paramField->required    = true;

        // --- 4. Ejemplo de Parámetro CRM_RECORD ---
        // El parámetro es una referencia a un registro concreto del CRM (ej: 'Module|id').
        // Al ejecutar, recibiremos un objeto 'BeanReference'.
        $paramBean                   = new ActionParameterDefinition();
        $paramBean->name             = 'related_record';
        $paramBean->text             = 'Related Record (CRM_RECORD)';
        $paramBean->description      = 'A fixed value (e.g., "Contacts|1234-uuid-5678") or a field containing this reference.';
        $paramBean->type             = ActionParameterType::CRM_RECORD;
        $paramBean->supportedModules = ['Contacts', 'Leads', 'Accounts']; // (Opcional) El wizard puede usar esto para mostrar un selector de beans.
        $paramBean->required         = false;

        // --- 5. Ejemplo de Parámetro OPTION_SELECTOR ---
        // El parámetro puede ser obtenido de distintas formas (es un selector de opciones).
        // Al ejecutar, recibiremos un objeto 'OptionSelectorResolved'.
        $paramSelector              = new ActionParameterDefinition();
        $paramSelector->name        = 'recipient_selector';
        $paramSelector->text        = 'Recipient (OPTION_SELECTOR)';
        $paramSelector->description = 'Choose where to get the recipient from.';
        $paramSelector->type        = ActionParameterType::OPTION_SELECTOR;

        // Creamos las opciones para el selector
        
        // Opción 1: Un valor fijo (VALUE)
        $optFixed                     = new ActionSelectorOptionDefinition();
        $optFixed->resolvedType       = ActionParameterType::VALUE; // Indica qué recibirá la acción
        $optFixed->name               = 'opt_fixed';
        $optFixed->text               = 'Fixed Email';
        $optFixed->supportedDataTypes = [ActionDataType::EMAIL]; // Instrucción para el wizard: el input debe ser de tipo email

        // Opción 2: Un bloque de datos (DATA_BLOCK)
        $optBlock                   = new ActionSelectorOptionDefinition();
        $optBlock->resolvedType     = ActionParameterType::DATA_BLOCK; // Indica qué recibirá la acción
        $optBlock->name             = 'opt_data_block';
        $optBlock->text             = 'Data Block (DATA_BLOCK)';
        $optBlock->supportedModules = ['Contacts', 'Leads', 'Accounts']; // Instrucción para el wizard: el bloque debe referenciarse a uno de estos módulos

        // Opción 3: Un campo del formulario (FIELD)
        $optField                     = new ActionSelectorOptionDefinition();
        $optField->resolvedType       = ActionParameterType::FIELD; // Indica qué recibirá la acción
        $optField->name               = 'opt_field';
        $optField->text               = 'Form Field';
        $optField->supportedDataTypes = [ActionDataType::EMAIL, ActionDataType::TEXT]; // Instrucción para el wizard: filtrar campos

        // Opción 4: Un registro fijo (CRM_RECORD)
        $optBean                   = new ActionSelectorOptionDefinition();
        $optBean->resolvedType     = ActionParameterType::CRM_RECORD; // Indica qué recibirá la acción
        $optBean->name             = 'opt_bean';
        $optBean->text             = 'Created Record (Bean)';
        $optBean->supportedModules = ['Contacts', 'Leads'];

        // Opción 5: Un campo (FIELD) de tipo RELATE que apunta a Users
        $optFieldRel                     = new ActionSelectorOptionDefinition();
        $optFieldRel->resolvedType       = ActionParameterType::FIELD; // Indica qué recibirá la acción
        $optFieldRel->name               = 'opt_field_rel';
        $optFieldRel->text               = 'Form Field (User Relation)';
        $optFieldRel->supportedDataTypes = [ActionDataType::RELATE]; // Instrucción para el wizard: filtrar campos 'relate'
        $optFieldRel->supportedModules   = ['Users']; // Instrucción para el wizard: filtrar campos que apunten a 'Users'

        $paramSelector->selectorOptions = [$optFixed, $optBlock, $optField, $optBean, $optFieldRel];

        // Devolvemos todos los parámetros definidos
        return [
            $paramValue,
            $paramBlock,
            $paramField,
            $paramBean,
            $paramSelector,
        ];
    }

    /**
     * execute()
     * ESTE ES EL NÚCLEO DE LA ACCIÓN.
     * Esta función se ejecuta cuando el flujo llega a esta acción.
     *
     * @param ExecutionContext $context El contexto global de la ejecución.
     * @param FormAction $actionConfig La configuración específica de ESTA acción.
     * @return ActionResult El resultado de la ejecución.
     */
    public function execute(ExecutionContext $context, FormAction $actionConfig): ActionResult
    {
        // Para depuración: podemos volcar el contexto o los parámetros al log
        $GLOBALS['log']->debug("Starting ExampleAction. ActionConfig ID: " . $actionConfig->id);

        // --- 1. Obtener Parámetros Resueltos ---
        // El ParameterResolver ya ha hecho el trabajo.
        // Solo tenemos que pedir los valores usando la función de ayuda de FormAction.
        
        // Parámetro VALUE
        // No es necesario pasar un valor por defecto aquí, porque el ParameterResolver
        // ya ha aplicado el 'defaultValue' definido en getParameters().
        $message = $actionConfig->getResolvedParameter('fixed_message');
        // $message es ahora un string, int, bool o \DateTime

        // Parámetro DATA_BLOCK
        /** @var ?DataBlockResolved $block */
        $block = $actionConfig->getResolvedParameter('block_to_process');
        // $block es un objeto DataBlockResolved

        // Parámetro FIELD
        /** @var ?DataBlockFieldResolved $field */
        $field = $actionConfig->getResolvedParameter('email_field');
        // $field es un objeto DataBlockFieldResolved

        // Parámetro CRM_RECORD
        /** @var ?BeanReference $beanRef */
        $beanRef = $actionConfig->getResolvedParameter('related_record');
        // $beanRef es un objeto BeanReference

        // Parámetro OPTION_SELECTOR
        /** @var ?OptionSelectorResolved $selector */
        $selector = $actionConfig->getResolvedParameter('recipient_selector');
        // $selector es un objeto OptionSelectorResolved


        // --- 2. Utilizar los Parámetros ---

        // Ejemplo con DATA_BLOCK: Acceder a los datos
        $GLOBALS['log']->debug("Processing block: " . $block->dataBlock->name);
        foreach ($block->formData as $fieldName => $fieldResolved) { 
            // $fieldResolved es un DataBlockFieldResolved
            $GLOBALS['log']->debug("  -> CRM Field: {$fieldName}, Value (type " . gettype($fieldResolved->value) . "): " . print_r($fieldResolved->value, true));
        }
        foreach ($block->detachedData as $fieldName => $fieldResolved) { 
            $GLOBALS['log']->debug("  -> Detached Field: {$fieldName}, Value (type " . gettype($fieldResolved->value) . "): " . print_r($fieldResolved->value, true));
        }

        // Ejemplo con FIELD: Acceder al valor y a la definición
        $fieldValue = $field->value; 
        $isDetached = $field->isDetached(); 
        $fieldLabel = $field->dataBlockField?->label ?? 'N/A'; 
        $GLOBALS['log']->debug("Processing field: {$field->formKey}, Label: {$fieldLabel}, Value: {$fieldValue}, IsDetached: {$isDetached}");

        // Ejemplo con OPTION_SELECTOR: Comprobar la opción elegida
        switch ($selector->selectedOptionName) { 
            case 'opt_fixed':
                $recipient = $selector->resolvedValue; // Es un string
                $GLOBALS['log']->debug("Selector: 'opt_fixed' chosen. Value: {$recipient}");
                break;
            case 'opt_field':
                /** @var ?DataBlockFieldResolved $recipientField */
                $recipientField = $selector->resolvedValue;
                $recipient      = $recipientField?->value;
                $GLOBALS['log']->debug("Selector: 'opt_field' chosen. Value: {$recipient}");
                break;
            case 'opt_bean':
                /** @var ?BeanReference $recipientBean */
                $recipientBean = $selector->resolvedValue;
                $recipient     = $recipientBean?->beanId;
                $GLOBALS['log']->debug("Selector: 'opt_bean' chosen. BeanID: {$recipient}");
                break;
        }

        // --- 3. Ejecutar la Lógica de Negocio (Aquí no hacemos nada) ---
        // ...
        // $bean = BeanFactory::getBean('Contacts', $beanRef->beanId);
        // $bean->description = $message;
        // $bean->save();
        // ...

        // --- 4. Devolver el Resultado ---

        // Creamos un resultado OK
        $actionResult = new ActionResult(ResultStatus::OK, $actionConfig, "Example action executed successfully.");

        // Si creamos o modificamos Beans, debemos registrarlo.

        // CASO 1: Suponemos que creamos/modificamos el bean para el bloque de datos que hemos recibido ($block):
        // Simulamos la creación o carga del bean (en una acción real, esto sería el resultado de $bean->save())
        $beanFromBlock = BeanFactory::newBean($block->dataBlock->module);  // Usamos el módulo del bloque
        $beanFromBlock->id = '1234-uuid-5678';                             // ID del bean (ficticio)
        $action = BeanModificationType::CREATED;                           // Acción realizada
        // Debemos registrar el Bean y su modificación en la respuesta y el bloque de datos
        $actionResult->registerBeanModificationFromBlock($beanFromBlock, $block, $action);

        // CASO 2: Suponemos que creamos/modificamos otro bean no asociado directamente a ningún bloque de datos:
        $beanNote = BeanFactory::newBean('Notes');         // Simulamos la creación de un bean de Nota
        $beanNote->id = '8765-uuid-4321';                  // ID del bean (ficticio)
        $action = BeanModificationType::UPDATED;           // Acción realizada
        // Debemos registrar el Bean y su modificación en la respuesta
        $actionResult->registerBeanModification($beanNote, $action);
        
        return $actionResult;
    }
}