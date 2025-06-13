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


/* HEADER */
// Set module name
var module = "ExternalOAuthProvider";

/* INCLUDES */

/* VALIDATION DEPENDENCIES */

/* DIRECT VALIDATION CALLBACKS */

/* VIEWS CUSTOM CODE */
switch (viewType()) {
    case "edit":
    case "quickcreate":
    case "popup":
        // Get connector selected value and show de default config info
        connectorInput = document.getElementById('connector');
        connectorValue = connectorInput.options[connectorInput.selectedIndex].value;
        showDefaultConfigConnector('edit', connectorValue);

        // Add change event listener
        connectorInput.addEventListener("change", function () {
            connectorValue = connectorInput.options[connectorInput.selectedIndex].value;
            showDefaultConfigConnector('edit', connectorValue);
        });

    break;

    case "detail":
        // Get connector selected value and show de default config info
        connectorValue = document.getElementById('connector_span').textContent.trim();
        showDefaultConfigConnector('detail', connectorValue);
    break;
}


/**
 * Configure default values ​​depending on the selected connector and display information depending of view type
 */
function showDefaultConfigConnector(view, connectorValue) 
{
    switch (connectorValue) {
        case "Stic Google":
            sticGoogleDefaultConfig = {
                scope: [
                    "https://mail.google.com/"
                ],
                urlOptions: {
                    urlAuthorize:"https://accounts.google.com/o/oauth2/auth",
                    urlAccessToken:"https://oauth2.googleapis.com/token",
                },
                authorizeUrlOptions: {
                    approval_prompt:"force",
                    access_type:"offline",
                },
                extraProviderParams:{
                    urlResourceOwnerDetails:'',
                }
            };
            displayDefaultConfigOfConnector(view, sticGoogleDefaultConfig);
        break;

        case "Stic Microsoft":
            sticMicrosoftDefaultConfig = {
                scope: [
                    "https://outlook.office.com/IMAP.AccessAsUser.All",
                    "offline_access",
                    "User.Read"
                ],
                extraProviderParams:{
                    urlResourceOwnerDetails:'',
                }
            };
            displayDefaultConfigOfConnector(view, sticMicrosoftDefaultConfig);
        break;

        case "Microsoft":
            microsoftDefaultConfig = {
                extraProviderParams:{
                    urlResourceOwnerDetails:'',
                }
            };
            displayDefaultConfigOfConnector(view, microsoftDefaultConfig);
        break;

        case "Generic":
            genericDefaultConfig = null;
            displayDefaultConfigOfConnector(view, genericDefaultConfig);
        break;
    }
}


/**
 * Displays the default configuration of the selected connector depending on the view the user is in.
 */
function displayDefaultConfigOfConnector(view, data)
{
    // Remove defaultConfigData Div Element if exists
    defaultConfigDataDiv = document.getElementById('defaultConfigData');
    if (defaultConfigDataDiv) {
        defaultConfigDataDiv.remove();
    }
    if (data) 
    {
        // Create defaultConfigData Div Element
        defaultConfigDataDiv = document.createElement('div');
        defaultConfigDataDiv.id = 'defaultConfigData';

        // Create Table Element
        const table = document.createElement('table');
        table.style.backgroundColor = '#F5F5F5';   
        table.style.float = 'right';
        table.style.minWidth = '45%'; 

        if (view == 'edit') {
            table.style.margin = '0px 4% 2% 2%';
        } else {
            table.style.margin = '0px 0px 2% 2%';
        }

        // Create Row Elements
        const tbody = document.createElement('tbody');
        addRow(tbody, SUGAR.language.get('ExternalOAuthProvider', 'LBL_CONNECTOR_DEFAULT_CONFIGURED_OPTIONS'));

        Object.entries(data).forEach(([key, value]) => 
        {
            switch (key) 
            {
                case 'scope':
                    field = SUGAR.language.get('ExternalOAuthProvider', 'LBL_SCOPE');
                    addRow(tbody, field);
                    value.forEach( elem => {
                        addRow(tbody, '', elem, false);
                    });
                    break;
                    
                case 'urlOptions':
                case 'authorizeUrlOptions':
                case 'extraProviderParams':
                    if (key == 'authorizeUrlOptions') {
                        field = SUGAR.language.get('ExternalOAuthProvider', 'LBL_AUTHORIZE_URL_OPTIONS');;
                    } else {
                        field = SUGAR.language.get('ExternalOAuthProvider', 'LBL_EXTRA_PROVIDER_PARAMS');;
                    }
                    addRow(tbody, field);
                    Object.entries(value).forEach(([k, v]) => 
                    {
                        option = k + ': '
                        addRow(tbody, option, v, false);
                    });
                    break;
            }
        });
        table.appendChild(tbody);
        defaultConfigDataDiv.appendChild(table);

        // Create "clear" Div Element
        const clearDiv = document.createElement('div');
        clearDiv.className = 'clear';

        // Select the row element that will remain below the div to be added
        if (view == 'edit') {
            row = $('#connector_span').closest('.edit-view-row-item').nextAll('.clear').first();
        } else {
            row = $('div.detail-view-row-item[data-field="connector"]').closest('.detail-view-row').next();
        }

        // Insert clearDiv and defaultConfigDataDiv before the first "clear" after the Connector field
        $(clearDiv).insertBefore(row);
        $(defaultConfigDataDiv).insertBefore(row);
        $(clearDiv).insertBefore(row);
    }
}

/**
 * Add a row to the body of the table with the values ​​received in key and value
 */
function addRow(tbody, key, value = '', header = true)
{
    const row = document.createElement('tr');

    if (key) {
        const tdKey = document.createElement('td');
        tdKey.textContent = key;
        tdKey.style.padding = '1rem';
        tdKey.style.width = '40%';
        if (header) {
            tdKey.style.fontWeight = 'bold';   
        };
        row.appendChild(tdKey);
    }

    const tdValue = document.createElement('td');
    tdValue.textContent = value;
    tdValue.style.width = '60%';
    tdValue.style.textAlign = 'left';
    tdValue.style.padding = '1rem';
    row.appendChild(tdValue);

    tbody.appendChild(row);
}
