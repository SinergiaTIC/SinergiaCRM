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

/**
 * This file contains logic and functions needed to manage custom views behaviour
 *
 */

var sticCustomView = class sticCustomView {
    constructor(view) {
        this.view = view;
    }

    field(fieldName) { return new sticCustomViewItemField(this, fieldName); }
    panel(panelName) { return new sticCustomViewItemPanel(this, panelName); }
    tab(tabIndex)    { return new sticCustomViewItemTab(this, tabIndex); }


    /**
     * Process the View customization
     * @param {*} jsonRules : The rules to apply in a string with json structure. The rules will be applied in order
     * json format for rules: list of customizations, each with conditions and actions. 
     *  [
     *   {
     *    conditions: [], 
     *    actions: [{type: tab_modification, element: 4, action: visible, value: 0, element_section: tab}],
     *   },
     *   {
     *    conditions: [{field: stic_referral_agent_c, operator: Equal_To, value: social_services}],
     *    actions: [{type: tab_modification, element: 4, action: visible, value: 1, element_section: tab}],
     *   },
     *  ]
     */
    processSticCustomView(jsonRules) {
        var customizations = JSON.parse(jsonRules);
        if(Array.isArray(customizations) && customizations.length) {
            var self = this;
            customizations.forEach(customization => {
                self.addCustomization(customization.conditions, customization.actions);
            });
        }
    }

    /**
     * Adds a customization: a group of Conditions to apply a list of actions
     */
    addCustomization(conditions, actions) {
        // Bind every change involved in condition set
        if(Array.isArray(conditions) && conditions.length) {
            var self = this;
            conditions.forEach(condition => {
                self.field(condition.field).onChange(function() { 
                    self.checkConditionsAndApplyActions(conditions, actions); 
                });
            });
        }
        // Check conditions with current values
        this.checkConditionsAndApplyActions(conditions, actions); 
    }

    /**
     * Applies an action defined in an object
     * Example:
     * {
     *  type: tab_modification,
     *  element: 4,
     *  action: visible,
     *  value: 0,
     *  element_section: tab,
     * },
     */
    applyAction(action) {
        switch(action.type) {
            case "field_modification": return this.field(action.element).applyAction(action);
            case "panel_modification": return this.panel(action.element).applyAction(action);
            case "tab_modification":   return this.tab(action.element).applyAction(action);
        }
    }
    
    /**
     * Check a condition defined in an object
     * Example:
     * {
     *  field: stic_referral_agent_c,
     *  operator: Equal_To
     *  value: social_services
     * }
     */
    checkCondition(condition) {
        return this.field(condition.field).checkCondition(condition);
    }

    /**
     * Checks all conditions in a list in order to apply all actions
     */
    checkConditionsAndApplyActions(conditions, actions) {
        var value = true;
        if(Array.isArray(conditions) && conditions.length) {
            var self = this;
            conditions.forEach(condition => value &&= self.checkCondition(condition));
        }
        if(value) {
            if(Array.isArray(actions) && actions.length) {
                var self = this;
                actions.forEach(action => self.applyAction(action));
            }
        }
    }
}
