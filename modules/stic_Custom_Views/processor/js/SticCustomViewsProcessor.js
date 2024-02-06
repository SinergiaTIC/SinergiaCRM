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

/**
 * Process the View customization
 * @param {*} viewType : available view types: detailview, editview
 * @param {*} jsonRules : The rules to apply in a string with json structure. The rules will be applied in order
 * json format for rules: list of customizations, each with conditions and actions. 
 * {
 *  customizations: [
 *      {
 *          conditions: [], 
 *          actions: [
 *              {
 *                  type: tab_modification,
 *                  element: 4,
 *                  action: visible,
 *                  value: 0,
 *                  element_section: tab,
 *              },
 *          ],
 *      },
 *      {
 *          conditions: [
 *              {
 *                  field: stic_referral_agent_c,
 *                  operator: Equal_To
 *                  value: social_services
 *              },
 *          ],
 *          actions: [
 *              {
 *                  type: tab_modification,
 *                  element: 4,
 *                  action: visible,
 *                  value: 1,
 *                  element_section: tab,
 *              },
 *          ],
 *      },
 *  ]
 * }
 */
function processSticCustomView(viewType, jsonRules) {

}