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
include_once "modules/stic_AWF_Forms/Utils.php";

/**
 * SaveRecordWithRelationsAction
 *
 * Action that saves a Data Block and injects FK values for outgoing 1-N relationships
 * in a single save, avoiding a separate re-save via RelateRecordsAction.
 */
class SaveRecordWithRelationsAction extends HookDataBlockActionDefinition {
    public function __construct() {
        $this->isActive = true;
        $this->isUserSelectable = false;
        $this->isAutomatic = true;
        $this->category = 'data';
        $this->baseLabel = 'LBL_SAVE_RECORD_WITH_RELATIONS_ACTION';
    }

    protected function getCustomParameters(): array {
        $paramConfigs = new ActionParameterDefinition();
        $paramConfigs->name = 'relation_configs';
        $paramConfigs->text = $this->translate('RELATION_CONFIGS_TEXT');
        $paramConfigs->type = ActionParameterType::VALUE;
        $paramConfigs->dataType = ActionDataType::TEXT;
        $paramConfigs->required = false;
        return [$paramConfigs];
    }

    /**
     * Executes the action, receiving the resolved and validated main data block.
     * Behaves like SaveRecordAction but injects 1-N FK values before saving.
     */
    public function executeWithBlock(ExecutionContext $context, FormAction $actionConfig, DataBlockResolved $block): ActionResult
    {
        global $db, $beanList;

        $module = $block->dataBlock->module;
        if (!isset($beanList[$module])) {
            return new ActionResult(ResultStatus::ERROR, $actionConfig, "The configured module '{$module}' is not available on the system.");
        }

        $bean = null;
        $onDuplicateAction = null;
        $modifications = [];

        // Duplicate detection logic
        $duplicateRules = $block->dataBlock->duplicate_detections ?? [];
        foreach ($duplicateRules as $rule) {
            $scalarFields = [];
            $emailValues = [];
            $skipRule = false;
            $candidateIds = null;

            $foundBean = null;
            $tempBean = BeanFactory::newBean($module);
            if (!$tempBean) {
                return new ActionResult(ResultStatus::ERROR, $actionConfig, "Failed to create a new instance of the module '{$module}'.");
            }

            // Build the search fields for this rule
            foreach ($rule->fields as $fieldName) {
                $fieldValue = $block->getFieldValue($fieldName)?->value;

                if ($fieldValue === null || $fieldValue === '') {
                    $skipRule = false;
                    break;
                }
                if (stic_AWF_FormsUtils::isEmailField($tempBean->field_defs[$fieldName], $fieldName)) {
                    $emailValues[] = $fieldValue;
                } else {
                    $scalarFields[$fieldName] = $fieldValue;
                }
            }
            if ($skipRule) {
                continue;
            }

            // Email duplicate check
            if (!empty($emailValues)) {
                foreach ($emailValues as $email) {
                    $sql = "SELECT DISTINCT ebr.bean_id 
                            FROM email_addr_bean_rel ebr
                            INNER JOIN email_addresses ea ON ebr.email_address_id = ea.id
                            WHERE ebr.bean_module = '{$module}'
                                AND ebr.deleted = 0 
                                AND ea.deleted = 0
                                AND ea.email_address = '" . $db->quote($email) . "'";

                    $result = $db->query($sql);
                    $idsFoundForThisEmail = [];
                    while ($row = $db->fetchByAssoc($result)) {
                        $idsFoundForThisEmail[] = $row['bean_id'];
                    }
                    if ($candidateIds === null) {
                        $candidateIds = $idsFoundForThisEmail;
                    } else {
                        $candidateIds = array_intersect($candidateIds, $idsFoundForThisEmail);
                    }
                    if (empty($candidateIds)) {
                        break;
                    }
                }
                if (empty($candidateIds)) {
                   continue;
                }
            }

            // Scalar duplicate check
            $foundBean = null;

            if ($candidateIds !== null) {
                foreach ($candidateIds as $id) {
                    $beanToCheck = BeanFactory::getBean($module, $id);
                    if ($beanToCheck) {
                        $match = true;
                        foreach ($scalarFields as $sField => $sValue) {
                            if (($beanToCheck->$sField ?? null) != $sValue) {
                                $match = false;
                                break;
                            }
                        }
                        if ($match) {
                            $foundBean = $beanToCheck;
                            break;
                        }
                    }
                }
            } else {
                if (!empty($scalarFields)) {
                    $tempBean = BeanFactory::newBean($module);
                    $foundBean = $tempBean->retrieve_by_string_fields($scalarFields);
                }
            }

            if ($foundBean !== null) {
                if (!empty($foundBean->id)) {
                    $foundBean->retrieve($foundBean->id);
                }
                $bean = $foundBean;
                $onDuplicateAction = $rule->on_duplicate;

                $fieldLabels = [];
                foreach ($rule->fields as $fName) {
                    $fieldDef = $block->dataBlock->fields[$fName] ?? null;
                    if ($fieldDef) {
                        $label = !empty($fieldDef->label) ? $fieldDef->label : (!empty($fieldDef->text_original) ? $fieldDef->text_original : $fName);
                        $fieldLabels[] = rtrim($label, ': ');
                    } else {
                        $fieldLabels[] = $fName;
                    }
                }
                $matchedRuleFields = implode(', ', $fieldLabels);

                break;
            }
        }

        // Action Logic (Create or Handle Duplicate) and performed modifications
        $modificationType = null;
        $modifications = [];

        if ($bean === null) {
            // No duplicate, create a new one
            $bean = BeanFactory::newBean($module);
            if (!$bean) {
                return new ActionResult(ResultStatus::ERROR, $actionConfig, "Failed to create a new instance of the module '{$module}'.");
            }

            if (!empty($context->defaultAssignedUserId)) {
                $bean->assigned_user_id = $context->defaultAssignedUserId;
            }
            $modifications = $this->populateBean($bean, $block);
            if (property_exists($bean, 'fromAWF')) {
                $bean->fromAWF = true;
            }
            // Inject FK values for outgoing 1-N relationships before save
            $injectedFks = $this->injectRelationFks($context, $actionConfig, $bean, $block);
            $bean->save(false);

            $modificationType = BeanModificationType::CREATED;

        } else {
            // Duplicate found, apply the rule
            switch ($onDuplicateAction) {
                case OnDuplicateAction::ERROR:
                    return new ActionResult(ResultStatus::ERROR, $actionConfig, "Duplicate record found for module {$module}.");

                case OnDuplicateAction::UPDATE:
                    $modifications = $this->populateBean($bean, $block);
                    $modificationType = BeanModificationType::UPDATED;

                    if (property_exists($bean, 'fromAWF')) {
                        $bean->fromAWF = true;
                    }
                    $injectedFks = $this->injectRelationFks($context, $actionConfig, $bean, $block);
                    $bean->save(false);
                    break;

                case OnDuplicateAction::ENRICH:
                    $modifications = $this->enrichBean($bean, $block);
                    $modificationType = BeanModificationType::ENRICHED;

                    if (property_exists($bean, 'fromAWF')) {
                        $bean->fromAWF = true;
                    }
                    $injectedFks = $this->injectRelationFks($context, $actionConfig, $bean, $block, true);
                    $bean->save(false);
                    break;

                case OnDuplicateAction::SKIP:
                default:
                    $modifications = $this->skipBean($bean, $block);
                    $modificationType = BeanModificationType::SKIPPED;
                    $injectedFks = false;
                    break;
            }
        }

        // Find out if the record has actually been modified
        $hasPhysicalChanges = false;
        foreach ($modifications as $key => $mod) {
            if ($mod->status === FieldModificationStatus::APPLIED) {
                $hasPhysicalChanges = true;
                break;
            }
        }
        if (!$hasPhysicalChanges && in_array($modificationType, [BeanModificationType::UPDATED, BeanModificationType::ENRICHED])) {
            $modificationType = BeanModificationType::UNCHANGED;
        }

        // If FKs were injected and this is a new bean, recalculate the name field
        if (!empty($injectedFks) && $modificationType === BeanModificationType::CREATED) {
            $this->recalculateNameIfNeeded($bean, $block, $context);
        }

        // Logging and Return
        $actionResult = new ActionResult(ResultStatus::OK, $actionConfig);

        $actionResult->registerBeanModificationFromBlock($bean, $block, $modificationType, $modifications);

        if (isset($matchedRuleFields) && $matchedRuleFields !== null) {
            $dataToLog = [
                ['key' => 'duplicate_rule_matched', 'label' => $this->translate('DUPLICATE_RULE_MATCHED_TEXT'), 'value' => $matchedRuleFields],
            ];
            $actionResult->registerActionMetadata($bean, $dataToLog);
        }

        // Log injected FK relationships
        if (!empty($injectedFks)) {
            $metadata = [];
            $configsJson = $actionConfig->getResolvedParameter('relation_configs', '');
            if (!empty($configsJson)) {
                $configs = json_decode($configsJson, true);
                if (is_array($configs)) {
                    foreach ($configs as $cfg) {
                        $relName = $cfg['relationship_name'] ?? '';
                        $idName = $cfg['id_name'] ?? '';
                        $targetBlockId = $cfg['target_block_id'] ?? '';
                        $targetBlock = $context->formConfig->data_blocks[$targetBlockId] ?? null;
                        $targetBeanRef = $targetBlock?->getBeanReference();
                        $targetId = $targetBeanRef?->beanId ?? '';
                        $metadata[] = ['key' => 'injected_fk', 'label' => $idName, 'value' => "{$relName} → {$targetId}"];
                    }
                }
            }
            if (!empty($metadata)) {
                $actionResult->registerActionMetadata($bean, $metadata);
            }
        }

        return $actionResult;
    }

    /**
     * Injects FK values for 1-N relationships before the first save.
     * @return bool True if any FK was injected.
     */
    private function injectRelationFks(ExecutionContext $context, FormAction $actionConfig, SugarBean $bean, DataBlockResolved $block, bool $isEnrichMode = false): bool
    {
        $configsJson = $actionConfig->getResolvedParameter('relation_configs', '');
        if (empty($configsJson)) {
            return false;
        }

        $configs = json_decode($configsJson, true);
        if (!is_array($configs) || empty($configs)) {
            return false;
        }

        $isExistingRecord = !empty($bean->id) && !$bean->new_with_id;

        $injectedAny = false;
        foreach ($configs as $config) {
            $idName = $config['id_name'] ?? '';
            $targetBlockId = $config['target_block_id'] ?? '';
            $relationName = $config['relationship_name'] ?? '';

            if (empty($idName) || empty($targetBlockId)) {
                continue;
            }

            $targetBlock = $context->formConfig->data_blocks[$targetBlockId] ?? null;
            if (!$targetBlock) {
                $GLOBALS['log']->warn("SaveRecordWithRelationsAction: Target block '{$targetBlockId}' not found for relationship '{$relationName}'.");
                continue;
            }

            $targetBeanRef = $targetBlock->getBeanReference();
            if (!$targetBeanRef || empty($targetBeanRef->beanId)) {
                $GLOBALS['log']->warn("SaveRecordWithRelationsAction: Target block '{$targetBlock->name}' has no bean ID. Check action order.");
                continue;
            }

            // In enrich mode, protect existing FK values for existing records
            if ($isEnrichMode && $isExistingRecord && !empty($bean->{$idName})) {
                $GLOBALS['log']->debug("SaveRecordWithRelationsAction: Enrich mode — protected existing FK '{$idName}' = '{$bean->{$idName}}'.");
                continue;
            }

            $bean->{$idName} = $targetBeanRef->beanId;
            $GLOBALS['log']->debug("SaveRecordWithRelationsAction: Injected FK '{$idName}' = '{$targetBeanRef->beanId}' from relationship '{$relationName}'.");

            // Populate the relate display field (e.g., account_name) so that
            // LogicHooks/Workflows reading it right after save get the value.
            // Uses reverse vardef lookup to handle both core fields (account_id→account_name)
            // and Studio-created custom fields (projecte_id_c→projecte_c).
            $nameField = null;
            foreach ($bean->field_defs as $fieldName => $def) {
                if (isset($def['type'], $def['id_name']) && $def['type'] === 'relate' && $def['id_name'] === $idName) {
                    $nameField = $fieldName;
                    break;
                }
            }
            if ($nameField) {
                $parentModule = $bean->field_defs[$nameField]['module'] ?? '';
                $rname = $bean->field_defs[$nameField]['rname'] ?? 'name';
                if ($parentModule) {
                    $parentBean = BeanFactory::getBean($parentModule, $targetBeanRef->beanId);
                    if ($parentBean && $parentBean->id === $targetBeanRef->beanId && isset($parentBean->$rname)) {
                        $bean->$nameField = $parentBean->$rname;
                        $GLOBALS['log']->debug("SaveRecordWithRelationsAction: Populated relate field '{$nameField}' = '{$parentBean->$rname}'.");
                    }
                }
            }

            $injectedAny = true;
        }

        return $injectedAny;
    }

    /**
     * Recalculates the name field if it was not user-defined and the bean was created in this context.
     */
    private function recalculateNameIfNeeded(SugarBean $bean, DataBlockResolved $block, ExecutionContext $context): void
    {
        $nameFieldInBlock = $block->getFieldValue('name');
        $nameIsUserDefined = $nameFieldInBlock && !empty($nameFieldInBlock->value);

        if (!$nameIsUserDefined && $this->wasBeanCreatedInThisContext($bean->id, $context)) {
            $bean->retrieve($bean->id);
            $bean->name = '';
            $bean->save();
        }
    }

    private function wasBeanCreatedInThisContext(string $beanId, ExecutionContext $context): bool
    {
        foreach ($context->actionResults as $result) {
            foreach ($result->modifiedBeans as $modBean) {
                if ($modBean->beanId === $beanId && $modBean->modificationType === BeanModificationType::CREATED) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Fills a bean with all form data (overwrites).
     */
    private function populateBean(SugarBean $bean, DataBlockResolved $block): array
    {
        $modifications = [];

        foreach ($block->formData as $fieldName => $fieldResolved) {
            if ($fieldResolved === null) continue;

            $newValue = $fieldResolved->value;
            $fieldDef = $bean->field_defs[$fieldName] ?? null;

            $isRelate = ($fieldDef && isset($fieldDef['type']) && $fieldDef['type'] === 'relate' && !empty($fieldDef['id_name']));
            $targetField = $isRelate ? $fieldDef['id_name'] : $fieldName;

            if (stic_AWF_FormsUtils::isEmailField($bean->field_defs[$targetField], $targetField)) {
                if ($targetField === 'email') {
                    $targetField = 'email1';
                }
                $oldValue = $bean->$targetField ?? null;
            } else {
                $oldValue = isset($bean->$targetField) ? $bean->$targetField : null;
            }

            if ($oldValue != $newValue) {
                $bean->$targetField = $newValue;
                $modifications[$targetField] = new FieldModification($targetField, FieldModificationStatus::APPLIED, $newValue, $oldValue);
            } else {
                $modifications[$targetField] = new FieldModification($targetField, FieldModificationStatus::UNCHANGED, $newValue, $oldValue);
            }
        }
        return $modifications;
    }

    /**
     * Fills a bean only with empty fields (enriches).
     */
    private function enrichBean(SugarBean $bean, DataBlockResolved $block): array
    {
        $modifications = [];

        foreach ($block->formData as $fieldName => $fieldResolved) {
            if ($fieldResolved === null) continue;

            $newValue = $fieldResolved->value;
            $fieldDef = $bean->field_defs[$fieldName] ?? null;

            $isRelate = ($fieldDef && isset($fieldDef['type']) && $fieldDef['type'] === 'relate' && !empty($fieldDef['id_name']));
            $targetField = $isRelate ? $fieldDef['id_name'] : $fieldName;

            $oldValue = isset($bean->$targetField) ? $bean->$targetField : null;
            $isEmpty = ($oldValue === null || $oldValue === '');

            $isBoolean = ($fieldDef && isset($fieldDef['type']) && ($fieldDef['type'] === 'bool' || $fieldDef['type'] === 'boolean'));
            if ($isBoolean) {
                $newValue = ($newValue === true || $newValue === 1 || $newValue === '1');
                if ($newValue) {
                    $isEmpty = ($isEmpty || $oldValue === false || $oldValue === 0 || $oldValue === '0');
                }
            }

            if ($isEmpty && $newValue !== null && $newValue !== '') {
                $bean->$targetField = $newValue;
                $modifications[$targetField] = new FieldModification($targetField, FieldModificationStatus::APPLIED, $newValue, $oldValue);
            } elseif (!$isEmpty && $newValue !== null && $newValue !== '' && $oldValue != $newValue) {
                $modifications[$targetField] = new FieldModification($targetField, FieldModificationStatus::IGNORED_ENRICH, $newValue, $oldValue);
            } else {
                $modifications[$targetField] = new FieldModification($targetField, FieldModificationStatus::UNCHANGED, $newValue, $oldValue);
            }
        }

        return $modifications;
    }

    /**
     * Skips a bean.
     */
    private function skipBean(SugarBean $bean, DataBlockResolved $block): array
    {
        $modifications = [];
        foreach ($block->formData as $fieldName => $fieldResolved) {
            if ($fieldResolved === null) continue;

            $fieldDef = $bean->field_defs[$fieldName] ?? null;
            $isRelate = ($fieldDef && isset($fieldDef['type']) && $fieldDef['type'] === 'relate' && !empty($fieldDef['id_name']));
            $targetField = $isRelate ? $fieldDef['id_name'] : $fieldName;

            $oldValue = isset($bean->$targetField) ? $bean->$targetField : null;

            $modifications[$targetField] = new FieldModification($targetField, FieldModificationStatus::SKIPPED_DUPLICATE, $fieldResolved->value, $oldValue);
        }
        return $modifications;
    }
}
