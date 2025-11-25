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
 * ExampleAction (Guía Completa para Desarrolladores)
 * - Esta es la implementación de una acción de ejemplo que sirve como plantilla para desarrolladores de nuevas acciones.
 * - Esta acción es del tipo Hook, se ejecutará en el servidor una vez recibida la respuesta del formulario.
 *
 * * Esta clase es una plantilla exhaustiva que muestra CÓMO implementar todos los tipos
 * de parámetros y lógicas disponibles en el sistema de Formularios Web Avanzados.
 * 
 * * NOMENCLATURA DE FICHEROS:
 *   - El nombre de la clase (Ej: 'ExampleAction') debe coincidir exactamente con el nombre del fichero (Ej: 'ExampleAction.php').
 *
 * * UBICACIÓN:
 *   - El fichero debe colocarse en una subcarpeta de 'modules/stic_Advanced_Web_Forms/actions/' según su tipo. Para esta acción, sería:
 *     .../actions/Hook/ExampleAction.php
 *   - O bien en la carpeta 'custom' para extensiones:
 *     .../custom/modules/stic_Advanced_Web_Forms/actions/Hook/ExampleAction.php
 * 
 * * TIPOS DE ACCIÓN (Herencia):
 *   1. HookActionDefinition: Acción genérica (ej: Enviar Email, Redirección).
 *   2. HookDataBlockActionDefinition: Acción ligada a un Bloque de Datos (ej: Crear Registro).
 *   3. HookBeanActionDefinition: Acción que requiere un Bean ya guardado (ej: Relacionar, Añadir a LPO).
 * 
 * - En este ejemplo usamos la base genérica (HookActionDefinition) para mostrar todos los casos.
 */
class ExampleAction extends HookActionDefinition
{

    /**
     * Constructor
     */
    public function __construct()
    {
        // Indicamos si la acción está activa o no.
        $this->isActive = true;

        // Indicamos si el usuario puede seleccionar esta acción en el wizard al crear un formulario.
        $this->isUserSelectable = true;

        // Indicamos si la acción es una acción común (true) o avanzada (false).
        $this->isCommon = true;

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

        // =========================================================================================
        // TIPO 1: VALORES SIMPLES (ActionParameterType::VALUE)
        // =========================================================================================

        // 1.1. Texto / Número / Email / Fecha
        // El Wizard mostrará un <input> según el tipo de datos.
        $paramText = new ActionParameterDefinition();
        $paramText->name = 'simple_text';
        $paramText->text = 'Texto Simple (VALUE)';
        $paramText->type = ActionParameterType::VALUE; 
        $paramText->dataType = ActionDataType::TEXT; // También: INTEGER, FLOAT, BOOLEAN, DATE, EMAIL...
        $paramText->defaultValue = "Valor por defecto";
        $paramText->required = true;

        // 1.2. Lista Desplegable Simple (SELECT)
        // El Wizard mostrará un <select> con las opciones fijas definidas aquí.
        $paramSelect = new ActionParameterDefinition();
        $paramSelect->name = 'simple_select';
        $paramSelect->text = 'Selección Simple (VALUE + SELECT)';
        $paramSelect->type = ActionParameterType::VALUE;
        $paramSelect->dataType = ActionDataType::SELECT; // Indica al Wizard que pinte un combo
        $paramSelect->options = [
            new ActionParameterOption('option_a', 'Opción A'),
            new ActionParameterOption('option_b', 'Opción B (Recomendada)')
        ];
        $paramSelect->defaultValue = 'option_b';

        // 1.3. Lista de Campos (FIELD_LIST)
        // El Wizard mostrará un selector de campos del formulario que se traducirán en un listado de campos separados por comas.
        // El Backend resolverá esto automáticamente en un array asociativo [nombre_campo => valor].
        $paramFieldList = new ActionParameterDefinition();
        $paramFieldList->name = 'fields_to_process';
        $paramFieldList->text = 'Lista de Campos (VALUE + FIELD_LIST)';
        $paramFieldList->description = 'Escribe campos separados por comas (ej: Bloque.campo1, _detached.Bloque.campo2)';
        $paramFieldList->type = ActionParameterType::VALUE;
        $paramFieldList->dataType = ActionDataType::FIELD_LIST;


        // =========================================================================================
        // TIPO 2: ELEMENTOS DEL FORMULARIO
        // =========================================================================================

        // 2.1. Bloque de Datos (DATA_BLOCK)
        // El Wizard muestra un desplegable con los bloques definidos.
        // El Backend entrega un objeto 'DataBlockResolved' con configuración y datos.
        $paramBlock = new ActionParameterDefinition();
        $paramBlock->name = 'target_block';
        $paramBlock->text = 'Bloque de Datos (DATA_BLOCK)';
        $paramBlock->type = ActionParameterType::DATA_BLOCK;
        $paramBlock->supportedModules = ['Contacts', 'Leads']; // Opcional: Filtro por módulo
        $paramBlock->required = true;

        // 2.2. Campo Único (FIELD)
        // El Wizard muestra un desplegable con todos los campos del formulario.
        // El Backend entrega un objeto 'DataBlockFieldResolved'.
        $paramField = new ActionParameterDefinition();
        $paramField->name = 'source_field';
        $paramField->text = 'Campo Origen (FIELD)';
        $paramField->type = ActionParameterType::FIELD;
        // Opcional: Filtrar qué campos muestra el Wizard según su tipo en CRM
        $paramField->supportedDataTypes = [ActionDataType::EMAIL, ActionDataType::TEXT]; 
        $paramField->required = true;


        // =========================================================================================
        // TIPO 3: REFERENCIAS AL CRM (CRM_RECORD)
        // =========================================================================================

        // 3.1. Registro CRM
        // El Wizard permite seleccionar un registro existente (ID fijo) o usar un campo compatible.
        // El Backend entrega un objeto 'BeanReference' (Modulo + ID).
        $paramRecord = new ActionParameterDefinition();
        $paramRecord->name = 'template_record';
        $paramRecord->text = 'Plantilla / Registro (CRM_RECORD)';
        $paramRecord->type = ActionParameterType::CRM_RECORD;
        $paramRecord->supportedModules = ['EmailTemplates']; // Vital para que el Wizard sepa qué buscar
        $paramRecord->required = false;


        // =========================================================================================
        // TIPO 4: SELECTOR DE OPCIONES (OPTION_SELECTOR)
        // Permite al usuario elegir el "origen" del dato (polimorfismo).
        // =========================================================================================

        $paramSelector = new ActionParameterDefinition();
        $paramSelector->name = 'dynamic_source';
        $paramSelector->text = 'Origen Dinámico (OPTION_SELECTOR)';
        $paramSelector->description = 'Selecciona de dónde obtener la información.';
        $paramSelector->type = ActionParameterType::OPTION_SELECTOR;
        
        // Definimos las opciones disponibles para el usuario:

        // Opción A: Contexto (EMPTY) - No requiere input del usuario, el valor es implícito.
        $optOwner = new ActionSelectorOptionDefinition();
        $optOwner->name = 'opt_owner';
        $optOwner->text = 'El usuario actual (Contexto)';
        $optOwner->resolvedType = ActionParameterType::EMPTY; // Backend recibirá NULL como valor

        // Opción B: Bloque (DATA_BLOCK)
        $optBlockSrc = new ActionSelectorOptionDefinition();
        $optBlockSrc->name = 'opt_block';
        $optBlockSrc->text = 'Desde un Bloque de Datos';
        $optBlockSrc->resolvedType = ActionParameterType::DATA_BLOCK;

        // Opción C: Valor Fijo (VALUE)
        $optFixed = new ActionSelectorOptionDefinition();
        $optFixed->name = 'opt_fixed';
        $optFixed->text = 'Valor Manual';
        $optFixed->resolvedType = ActionParameterType::VALUE;

        $paramSelector->selectorOptions = [$optOwner, $optBlockSrc, $optFixed];


        return [
            $paramText, $paramSelect, $paramFieldList, 
            $paramBlock, $paramField, $paramRecord, 
            $paramSelector
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
        // ------------------------------------------------------------------------
        // 1. RECUPERACIÓN DE PARÁMETROS (Ya resueltos por ParameterResolverService)
        // ------------------------------------------------------------------------

        // VALUE (Simple) -> string|int|bool
        $textValue = $actionConfig->getResolvedParameter('simple_text');

        // VALUE (Select) -> string (la 'value' de la opción seleccionada)
        $selectValue = $actionConfig->getResolvedParameter('simple_select'); // ej: 'option_a'

        // VALUE (Field List) -> array asociativo ['Bloque.Campo' => 'Valor Resuelto']
        $fieldsMap = $actionConfig->getResolvedParameter('fields_to_process') ?? [];
        foreach ($fieldsMap as $fieldKey => $fieldVal) {
            $GLOBALS['log']->debug("Field List Item: $fieldKey = $fieldVal");
        }

        // DATA_BLOCK -> DataBlockResolved object
        /** @var DataBlockResolved $block */
        $block = $actionConfig->getResolvedParameter('target_block');
        // Acceso a datos: $block->formData['campo_crm']
        // Acceso a definición: $block->dataBlock->module

        // FIELD -> DataBlockFieldResolved object
        /** @var DataBlockFieldResolved $field */
        $field = $actionConfig->getResolvedParameter('source_field');
        // Acceso a valor: $field->value
        // Acceso a definición: $field->dataBlockField (puede ser null si es _detached)

        // CRM_RECORD -> BeanReference object
        /** @var BeanReference $recordRef */
        $recordRef = $actionConfig->getResolvedParameter('template_record');
        // Acceso al bean (Lazy Loading): $recordRef?->getBean()

        // OPTION_SELECTOR -> OptionSelectorResolved object
        /** @var OptionSelectorResolved $selector */
        $selector = $actionConfig->getResolvedParameter('dynamic_source');
        
        // Lógica según la opción elegida por el usuario
        if ($selector) {
            switch ($selector->selectedOptionName) {
                case 'opt_owner':
                    // EMPTY: El valor es null, usamos lógica de contexto
                    $userId = $GLOBALS['current_user']->id;
                    break;
                case 'opt_block':
                    // DATA_BLOCK: El valor es DataBlockResolved
                    /** @var DataBlockResolved $selBlock */
                    $selBlock = $selector->resolvedValue;
                    break;
                case 'opt_fixed':
                    // VALUE: El valor es string
                    $fixedVal = $selector->resolvedValue;
                    break;
            }
        }

        // ------------------------------------------------------------------------
        // 2. LÓGICA DE NEGOCIO (Ejemplo)
        // ------------------------------------------------------------------------

        // Supongamos que creamos un Bean basado en los datos
        $newBean = BeanFactory::newBean('Notes');
        $newBean->name = "Nota generada: " . $textValue;
        $newBean->description = "Opción elegida: " . $selectValue;
        
        // Vinculamos al bloque seleccionado (si tiene un bean guardado)
        $parentRef = $block->dataBlock->getBeanReference();
        if ($parentRef) {
            $newBean->parent_type = $parentRef->moduleName;
            $newBean->parent_id = $parentRef->beanId;
        }
        
        $newBean->save();


        // ------------------------------------------------------------------------
        // 3. RESULTADO Y AUDITORÍA
        // ------------------------------------------------------------------------

        $result = new ActionResult(ResultStatus::OK, $actionConfig, "Acción de ejemplo finalizada.");

        // REGISTRAR MODIFICACIONES (Fundamental para la trazabilidad)
        
        // Opción A: Si hemos modificado/creado un bean "suelto" (no ligado a un bloque del form)
        // Usamos la nueva firma que acepta el objeto SugarBean directamente.
        $result->registerBeanModification($newBean, BeanModificationType::CREATED);

        // Opción B: Si hubiéramos modificado el bean del 'target_block'
        // $result->registerBeanModificationFromBlock($blockBean, $block, BeanModificationType::UPDATED);

        return $result;
    }
}