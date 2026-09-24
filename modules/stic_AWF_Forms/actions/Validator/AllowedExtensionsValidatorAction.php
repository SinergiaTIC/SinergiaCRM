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

include_once "modules/stic_AWF_Forms/actions/coreActions.php";

/**
 * AllowedExtensionsValidatorAction
 *
 * Validates that an uploaded file has an allowed extension.
 * Client-side JS validation checks the file name extension.
 * Server-side enforcement is handled by SaveDocumentBlockAction.
 */
class AllowedExtensionsValidatorAction extends ValidatorActionDefinition {
    public function __construct() {
        $this->isActive = true;
        $this->baseLabel = 'LBL_ALLOWED_EXTENSIONS_VALIDATOR_ACTION';
        $this->supportedDataTypes = [ActionDataType::FILE];
    }

    public function getParameters(): array {
        $paramExtensions = new ActionParameterDefinition();
        $paramExtensions->name = 'extensions';
        $paramExtensions->text = $this->translate('EXTENSIONS_TEXT');
        $paramExtensions->type = ActionParameterType::VALUE;
        $paramExtensions->dataType = ActionDataType::TEXT;
        $paramExtensions->required = true;
        $paramExtensions->defaultValue = 'pdf,jpg,png,jpeg,gif';
        return [$paramExtensions];
    }

    /**
     * Returns rules to automatically apply this validation.
     * Can filter by field type (vardef type) editor in form (subtype_in_form), or by name pattern (regex).
     * @return array ex: ['types' => ['email'], 'subtypes_in_form' => ['text_email'], 'name_patterns' => ['/^email/i']]
     */
    public function getAutoApplyRules(): array {
        return [
            'types' => ['file'], // File type
        ];
    }

    public function getDefaultErrorMessage(): string {
        return $this->translate('ERROR_MESSAGE_TEXT');
    }

    public function getValidationJS(): string {
        return <<<JS
(value, params, formElement) => {
    if (!value) return true;
    
    // Locate the actual file input
    const inputEl = Array.from(formElement.querySelectorAll('input[type="file"]')).find(i => i.value === value);
    if (!inputEl || !inputEl.files || !inputEl.files[0]) return true;
    
    const file = inputEl.files[0];
    const allowed = (params.extensions || 'pdf,jpg,png,jpeg,gif').split(',').map(s => s.trim().toLowerCase());
    const ext = file.name.split('.').pop().toLowerCase();
    
    return allowed.includes(ext);
}
JS;
    }

    public function validateBackend(mixed $value, array $params): bool {
        if (empty($_FILES)) {
            return true; 
        }
        
        $allowed = array_map('trim', explode(',', strtolower($params['extensions'] ?? 'pdf,jpg,png,jpeg,gif')));
        
        foreach ($_FILES as $file) {
            if (is_array($file) && $file['error'] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                if (!in_array($ext, $allowed, true)) {
                    return false; // Extension bypass detected on backend
                }
            }
        }
        return true;
    }
}
