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

include_once "modules/stic_Advanced_Web_Forms/actions/coreActions.php";

/**
 * ShowMessageAction
 *
 * Acción terminal que redirige el navegador del usuario a una página con un mensaje, manteniendo el estilo del forumulario
 *
 * Implementa ITerminalAction para parar la ejecución el flujo
 */
class ShowMessageAction extends HookActionDefinition implements ITerminalAction
{
    public function __construct()
    {
        $this->isActive = false;
        $this->isUserSelectable = true;
        $this->category = 'navigation';
        $this->baseLabel = 'LBL_SHOW_MESSAGE_ACTION';
    }

    /**
     * Define los parámetros que se mostrarán en el wizard.
     */
    public function getParameters(): array
    {
        $paramTitle = new ActionParameterDefinition();
        $paramTitle->name = 'title';
        $paramTitle->text = $this->translate('TITLE_TEXT');
        $paramTitle->defaultValue = translate('LBL_THEME_PROCESSED_FORM_TITLE_VALUE', 'stic_Advanced_Web_Forms');
        $paramTitle->type = ActionParameterType::VALUE;
        $paramTitle->dataType = ActionDataType::TEXT;
        $paramTitle->required = true;

        $paramBody = new ActionParameterDefinition();
        $paramBody->name = 'message';
        $paramBody->text = $this->translate('MESSAGE_TEXT');
        $paramBody->defaultValue = translate('LBL_THEME_PROCESSED_FORM_TEXT_VALUE', 'stic_Advanced_Web_Forms');                                                     
        $paramBody->type = ActionParameterType::VALUE;
        $paramBody->dataType = ActionDataType::TEXTAREA; 
        $paramBody->required = true;

        return [$paramTitle, $paramBody];
    }

    /**
     * Ejecuta la lógica de la redirección a página con el mensaje.
     */
    public function execute(ExecutionContext $context, FormAction $actionConfig): ActionResult
    {
        if (!defined('sugarEntry') || !sugarEntry) {
            define('sugarEntry', true);
        }

        // Limpiar el buffer de salida para eliminar warnings o errores previos
        while (ob_get_level()) {
            ob_end_clean();
        }
        
        // Evitar mostrar nuevos errores o warnings
        ini_set('display_errors', 0);
        error_reporting(0);

        $title = $actionConfig->getResolvedParameter('title');
        $message = $actionConfig->getResolvedParameter('message');

        stic_AWFUtils::renderGenericResponse($context->formConfig, $title, $message);

        // Acción terminal: detenemos la ejecución del script
        exit;

        // Este código no se ejecutará, se introduce para evitar que haya execute sin return
        return new ActionResult(ResultStatus::OK, $actionConfig);
    }

}