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

/**
 * Abstract class for actions that operate on ONE Bean that has been saved by a previous action.
 * Automates:
 *   - The definition, obtaining and validation of the DataBlock parameter.
 *   - The obtaining of the BeanReference from the DataBlock.
 *   - The loading (retrieve) of the Bean.
 *   - Error management 
 */
abstract class ServerBeanActionDefinition extends ServerDataBlockActionDefinition {

    final public function executeWithBlock(ExecutionContext $context, FormAction $actionConfig, DataBlockResolved $block): ActionResult
    {
        // Get the Bean reference saved for the resolved instance
        $beanRef = $block->getInstanceBeanReference();
        if ($beanRef === null) {
            // If the data block itself is optional, skip the action gracefully
            if ($block->dataBlock->isOptional() || (isset($block->dataBlock->min_instances) && (int)$block->dataBlock->min_instances === 0)) {
                $GLOBALS['log']->info("ServerBeanAction: Skipped action '{$actionConfig->name}' because optional DataBlock '{$block->dataBlock->name}' was not created.");
                return new ActionResult(ResultStatus::SKIPPED, $actionConfig, "DataBlock '{$block->dataBlock->name}' is optional and was not created.");
            }

            return new ActionResult(ResultStatus::ERROR, $actionConfig, "There is no saved Bean for DataBlock {$block->dataBlock->name}.");
        }
        
        // Load the Bean
        $bean = BeanFactory::getBean($beanRef->moduleName, $beanRef->beanId);
        if ($bean === null) {
            return new ActionResult(ResultStatus::ERROR, $actionConfig, "Can not load Bean with ID {$beanRef->beanId} from module {$beanRef->moduleName}.");
        }

        // Execute the action with the Bean and the Data Block
        return $this->executeWithBean($context, $actionConfig, $bean, $block);
    }

    /**
     * Method to be implemented
     * Executes the action, receives the loaded bean and the main data block with the form data
     *
     * @param ExecutionContext $context The global context.
     * @param FormAction $actionConfig The configuration of the action.
     * @param SugarBean $bean The bean loaded from the DB (saved data).
     * @param DataBlockResolved $block The data block (form data).
     * @return ActionResult
     */
    public abstract function executeWithBean(ExecutionContext $context, FormAction $actionConfig, SugarBean $bean, DataBlockResolved $block): ActionResult;
}