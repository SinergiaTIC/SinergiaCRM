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

include_once "modules/stic_Advanced_Web_Forms/actions/CoreActions.php";

/**
 * RedirectAction
 *
 * Acción terminal que redirige el navegador del usuario a una URL específica
 * Opcionalmente, puede adjuntar datos del formulario a la petición mediante GET o POST.
 *
 * Implementa ITerminalAction para parar la ejecución el flujo
 */
class RedirectAction extends HookActionDefinition implements ITerminalAction
{
    public function __construct()
    {
        $this->isActive = true;
        $this->baseLabel = 'LBL_REDIRECT_ACTION';
    }

    /**
     * Define los parámetros que se mostrarán en el wizard.
     */
    public function getParameters(): array
    {
        // La URL destino (Obligatoria)
        $paramUrl = new ActionParameterDefinition();
        $paramUrl->name = 'url';
        $paramUrl->text = $this->translate('URL_TEXT'); 
        $paramUrl->description = $this->translate('URL_DESC');
        $paramUrl->type = ActionParameterType::VALUE;
        $paramUrl->dataType = ActionDataType::TEXT;
        $paramUrl->required = true;

        // El método de envio (GET / POST)
        $paramMethod = new ActionParameterDefinition();
        $paramMethod->name = 'method';
        $paramMethod->text = $this->translate('METHOD_TEXT');
        $paramMethod->description = $this->translate('METHOD_DESC');
        $paramMethod->type = ActionParameterType::VALUE;
        $paramMethod->dataType = ActionDataType::SELECT;
        $paramMethod->required = true;
        $paramMethod->defaultValue = 'POST';
        $paramMethod->options = [
            new ActionParameterOption('GET', $this->translate('METHOD_GET_TEXT')),
            new ActionParameterOption('POST', $this->translate('METHOD_POST_TEXT'))
        ];
        
        // Los campos a enviar (Opcional)
        $paramFields = new ActionParameterDefinition();
        $paramFields->name = 'fields_to_send';
        $paramFields->text = $this->translate('FIELDS_TEXT');
        $paramFields->description = $this->translate('FIELDS_DESC');
        $paramFields->type = ActionParameterType::VALUE;
        $paramFields->dataType = ActionDataType::FIELD_LIST;
        $paramFields->required = false; 

        return [$paramUrl, $paramMethod, $paramFields];
    }

    /**
     * Ejecuta la lógica de la redirección.
     */
    public function execute(ExecutionContext $context, FormAction $actionConfig): ActionResult
    {
        // Obtener los parámetros
        $url = $actionConfig->getResolvedParameter('url');
        $method = $actionConfig->getResolvedParameter('method'); 
        $data = $actionConfig->getResolvedParameter('fields_to_send') ?? [];

        // Ejecutar la redirección
        if ($method === 'POST' && !empty($data)) {
            $this->redirectWithPost($url, $data);
            exit;
        } else {
            if (!empty($data)) {
                // Añadimos los datos a la url
                $queryString = http_build_query($data);
                $separator = strpos($url, '?') === false ? '?' : '&';
                $url .= $separator . $queryString;
            }
            
            // Redireccionamos
            header("Location: " . $url);
            exit;
        }

        // Este código sólo se ejecutará si la redirección falla (No damos error)
        return new ActionResult(ResultStatus::OK, $actionConfig, "Redirecting to {$url}");
    }

    /**
     * Función de ayuda para imprimir un formulario html para enviar automáticamente
     */
    private function redirectWithPost(string $url, array $data): void
    {
        echo '<html>';
        echo '<head><title>'.$this->translate('REDIRECTING').'</title></head>';
        echo '<body onload="document.forms[\'redirectForm\'].submit();">';
        echo '<form name="redirectForm" action="' . htmlspecialchars($url) . '" method="POST">';
        foreach ($data as $key => $value) {
            if (is_scalar($value)) {
                echo '<input type="hidden" name="' . htmlspecialchars($key) . '" value="' . htmlspecialchars($value) . '">';
            }
        }
        echo '<noscript><input type="submit" value="'.$this->translate('SUBMIT_BUTTON').'"></noscript>';
        echo '</form>';
        echo '</body>';
        echo '</html>';
    }
}