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
 * CheckSessionAction
 *
 * Acción que verifica la que el usuario actual tiene una sesión válida en el sistema.
 * 
 */
class CheckSessionAction extends HookActionDefinition implements IFrontendAction
{
    public function __construct()
    {
        $this->isActive = true;
        $this->category = 'security'; 
        $this->baseLabel = 'LBL_CHECK_SESSION_ACTION';
        $this->isUserSelectable = true;
        $this->order = -99;
    }

    /**
     * Define los parámetros que se mostrarán en el wizard.
     */
    public function getParameters(): array
    {
        $paramMsg = new ActionParameterDefinition();
        $paramMsg->name = 'error_message';
        $paramMsg->text = $this->translate('ERROR_MSG_TEXT'); 
        $paramMsg->type = ActionParameterType::VALUE;
        $paramMsg->dataType = ActionDataType::TEXTAREA;
        $paramMsg->defaultValue = $this->translate('ERROR_MSG_TEXT_DEFAULT');
        $paramMsg->required = true;
        
        return [$paramMsg];
    }

    /**
     * Ejecuta la lógica de la redirección.
     */
    public function execute(ExecutionContext $context, FormAction $actionConfig): ActionResult
    {
        global $current_user;
        
        // Verificación estricta al servidor en el momento del submit
        if (empty($current_user) || empty($current_user->id)) {
            $msg = $actionConfig->getResolvedParameter('error_message');
            return new ActionResult(ResultStatus::ERROR, $actionConfig, $msg);
        }

        return new ActionResult(ResultStatus::OK, $actionConfig, "User validated: " . $current_user->user_name);
    }

    /**
     * Devuelve los assets (CSS/JS) que deben inyectarse en el frontend para esta acción.
     * @param array $params Parámetros de la acción (sin resolver)
     * @param FormConfig|null $formConfig Configuración del formulario (si aplica)
     * @param string $formId ID del formulario (si aplica)
     * @return array array Estructura: ['script' => ['console.log("hi")'], 'css' => [], 'html' => []]
     */
    public function getFrontendAssets(array $params, ?FormConfig $formConfig = null, string $formId = ''): array {
        $errorMsg = $this->translate('ERROR_MSG_TEXT_DEFAULT'); 
        foreach($params as $p) {
            if ($p->name == 'error_message') $errorMsg = $p->value;
        }
        $checkingMsg = $this->translate('LBL_CHECK_SESSION_ACTION_CHECKING');
        $deniedTitle = $this->translate('LBL_CHECK_SESSION_ACTION_DENIED_TITLE');
        $loginMsg = $this->translate('LBL_CHECK_SESSION_ACTION_LOGIN');

        $script = <<<JS
        document.addEventListener('DOMContentLoaded', function() {
            // Bloqueo inicial visual
            const wrapper = document.querySelector('.awf-main-card');
            if (wrapper) {
                wrapper.style.visibility = 'hidden'; 
                const loader = document.createElement('div');
                loader.id = 'awf-session-loader';
                loader.innerHTML = '<div class="text-center p-5"><div class="spinner-border text-primary mb-2"></div><div>{$checkingMsg}</div><div>';
                wrapper.parentNode.insertBefore(loader, wrapper);
            }

            // Consulta AJAX
            fetch('index.php?entryPoint=stic_AWF_CheckSession&id={$formId}')
                .then(r => r.json())
                .then(data => {
                    const loader = document.getElementById('awf-session-loader');
                    if(loader) loader.remove();

                    if (data.is_logged) {
                        if(wrapper) wrapper.style.visibility = 'visible';
                    } else {
                        if(wrapper) wrapper.remove();
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'alert alert-danger m-5 text-center shadow';
                        errorDiv.innerHTML = '<h4>{$deniedTitle}</h4><p>{$errorMsg}</p><p><a href="index.php" class="btn btn-outline-danger btn-sm">{$loginMsg}</a></p>';
                        document.body.appendChild(errorDiv);
                    }
                })
                .catch(e => { console.error("Session check error", e); });
        });
JS;
        return ['script' => [$script]];
    }
}