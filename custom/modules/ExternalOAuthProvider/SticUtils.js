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
    
        // Add change event listener
        connector = document.getElementById('connector');
        connector.addEventListener("change", function() 
        {
            connectorValue = connector.options[connector.selectedIndex].value
            switch (connectorValue) {
                case "Stic Google":
                    configSticGoogleOptions();
                break;

                case "Stic Microsoft":
                    configSticMicrosoftOptions();
                break;

                case "Google":
                    // Add Scope Option, Authorization URL Options and Extra Provider Param
                    configGoogleOptions();
                break;

                case "Microsoft":
                    configMicrosoftOptions();
                break;

                case "Generic":
                    configGenericOptions() 
                break;
            }
        });

        window.addEventListener("load", function() {
            connectorValue = connector.options[connector.selectedIndex].value
            // If a record is being created, the Google Stic options are loaded since it is the default option 
            if ((document.forms['EditView'].elements['record'].value == '') && (connectorValue =='Stic Google')) {
                configSticGoogleOptions();
            }
        });

    break;
}

/* CONFIG CONNECTOR FUNCTIONS */

/**
 * Add Scope Option, Authorization URL Options and Extra Provider Param
 */
function configSticGoogleOptions(type = 'Google') 
{
    // Add Scope Option    
    addScopeOptions(type);

    // Add Authorization URL Options
    addGoogleURLAndAuthorizationURLOptions();

    // Extra Provider Param
    addExtraProvidersParams(type);
}

/**
 * Add Scope Option, Authorization URL Options and Extra Provider Param
 */
function configSticMicrosoftOptions(type = 'Microsoft') 
{
    //Delete Authorization And AccessToken URLs
    deleteAuthorizationAndAccessTokenURLs();

    // Add Scope Option    
    addScopeOptions(type);

    // Delete all Authorization URL Options clicking in all remove buttons 
    clickRemoveButton("authorize_url_options"); 

    // Extra Provider Param
    addExtraProvidersParams(type);
}

/**
 * Add Authorization URL Options and Extra Provider Param
 */
function configGoogleOptions(type = 'Google') 
{
    // Delete all Scope Options clicking in all remove buttons
    clickRemoveButton("scope"); 

    // Add Authorization URL Options
    addGoogleURLAndAuthorizationURLOptions();

    // Extra Provider Param
    addExtraProvidersParams(type);
}

/**
 * Add Scope Option, Authorization URL Options and Extra Provider Param
 */
function configMicrosoftOptions(type = 'Microsoft') 
{
    //Delete Authorization And AccessToken URLs
    deleteAuthorizationAndAccessTokenURLs();

    // Delete all Scope Options clicking in all remove buttons
    clickRemoveButton("scope"); 

    // Delete all Authorization URL Options clicking in all remove buttons 
    clickRemoveButton("authorize_url_options"); 

    // Extra Provider Param
    addExtraProvidersParams(type);
}


/**
 * Add Authorization URL Options and Extra Provider Param
 */
function configGenericOptions() 
{
    //Delete Authorization And AccessToken URLs
    deleteAuthorizationAndAccessTokenURLs();

    // Delete all Scope Options clicking in all remove buttons
    clickRemoveButton("scope"); 

    // Delete all Authorization URL Options clicking in all remove buttons 
    clickRemoveButton("authorize_url_options"); 

    // Delete all Extra Providers Params clicking in all remove buttons
    clickRemoveButton("extra_provider_params"); 
}





/* AUX FUNCTIONS */  

/**
 * add Scope Options Params
 */
function addScopeOptions(connectorType) 
{
    // Delete all Scope Options clicking in all remove buttons
    clickRemoveButton("scope"); 
    
    // Add Scope Options depending on the connector type
    switch (connectorType) 
    {
        case "Google":
            document.querySelector('[data-field="scope"] .add-btn').click();
            document.querySelector('input[name="scope-value[]"]').value = "https://mail.google.com/";
        break;

        case "Microsoft":
            document.querySelector('[data-field="scope"] .add-btn').click();
            document.querySelector('[data-field="scope"] .add-btn').click();
            document.querySelector('[data-field="scope"] .add-btn').click();
        
            // Fill Authorization URL Options keys
            document.querySelectorAll('input[name="scope-value[]"]').forEach((input, index) => {
                switch (index) 
                {
                    case 0: 
                        input.value = "https://outlook.office.com/IMAP.AccessAsUser.All";
                    break;

                    case 1:
                        input.value = "offline_access";
                    break;

                    case 2:
                        input.value = "User.Read";
                    break;
                }
            });            
        break;
    }
}

/**
 * add Authorization URL Options
 */
function addGoogleURLAndAuthorizationURLOptions() 
{
    // Authorization URL
    $urlAuthorize = document.getElementById("url_authorize");
    $urlAuthorize.value = "https://accounts.google.com/o/oauth2/auth";

    // Access Token URL
    $urlAccessToken = document.getElementById("url_access_token");
    $urlAccessToken.value = "https://oauth2.googleapis.com/token";

    // Authorization URL Options
    // Delete all Authorization URL Options clicking in all remove buttons 
    clickRemoveButton("authorize_url_options"); 

    // Add Authorization URL Options depending on the connector type
    document.querySelector('[data-field="authorize_url_options"] .add-btn').click();
    document.querySelector('[data-field="authorize_url_options"] .add-btn').click();

    // Fill Authorization URL Options keys
    document.querySelectorAll('input[name="authorize_url_options-key[]"]').forEach((input, index) => {
        if (index == 0) {
            input.value = "approval_prompt";
        } else {
            input.value = "access_type";
        }
    });
    
    // Fill Authorization URL Options values
    document.querySelectorAll('input[name="authorize_url_options-value[]"]').forEach((input, index) => {
        if (index == 0) {
            input.value = "force";
        } else {
            input.value = "offline";
        }
    });
}

/**
 * add Extra Providers Params
 */
function addExtraProvidersParams(connectorType) 
{
    // Delete all Extra Providers Params clicking in all remove buttons
    clickRemoveButton("extra_provider_params"); 

    // Add Extra Providers Params depending on the connector type
    switch (connectorType) 
    {
        case "Google":
            document.querySelector('[data-field="extra_provider_params"] .add-btn').click();
            document.querySelector('input[name="extra_provider_params-key[]"]').value = "urlResourceOwnerDetails";
            document.querySelectorAll('input[name="authorize_url_options-value[]"]').value = "";
        break;

        case "Microsoft":
            document.querySelector('[data-field="extra_provider_params"] .add-btn').click();
            document.querySelector('input[name="extra_provider_params-key[]"]').value = "urlResourceOwnerDetails";
            document.querySelectorAll('input[name="authorize_url_options-value[]"]').value = "";
        break;
    }
}


/**
 * Delete Authorization And AccessToken URLs
 */
function deleteAuthorizationAndAccessTokenURLs() 
{
    // Authorization URL
    $urlAuthorize = document.getElementById("url_authorize");
    $urlAuthorize.value = "";

    // Access Token URL
    $urlAccessToken = document.getElementById("url_access_token");
    $urlAccessToken.value = "";
}



/**
 * add Extra Providers Params
 */
function clickRemoveButton(elem) 
{
    // Delete all Extra Providers Params clicking in all remove buttons
    const removeButtons = document.querySelectorAll('[data-field="' + elem + '"] .remove-btn');
    removeButtons.forEach((removeButton) => {
        removeButton.click();
    });
}
