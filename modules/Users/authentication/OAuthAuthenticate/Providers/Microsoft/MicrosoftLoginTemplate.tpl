<button id="ms-login-btn" type="button" class="ms-login-btn">
    <span class="ms-logo" aria-hidden="true">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24">
            <rect width="11" height="11" fill="#F35325" />
            <rect x="13" width="11" height="11" fill="#81BC06" />
            <rect y="13" width="11" height="11" fill="#05A6F0" />
            <rect x="13" y="13" width="11" height="11" fill="#FFBA08" />
        </svg>
    </span>
    {$OAUTH_LANG.LBL_OAUTH_AUTH_GOOGLE_AUTHENTICATION_TEXT}
</button>

<input type="hidden" id="microsoft_credentials" name="microsoft_credentials" />

{literal}
<style>
    .ms-login-btn {
        background-color: white;
        border: 1px solid #dadce0;
        color: #3c4043;
        font-weight: 500;
        font-family: "Roboto", sans-serif;
        padding: 0 12px;
        height: 40px;
        border-radius: 4px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-sizing: border-box;
        width: 100%;
        text-align: center;
        user-select: none;
        transition: background-color 0.2s ease;
    }

    .ms-login-btn:hover {
        background-color: #f7f7f7;
    }

    .ms-logo {
        display: flex;
        align-items: center;
    }
</style>
<script src="modules/Users/authentication/OAuthAuthenticate/Providers/Microsoft/msal-browser.min.js"></script>
<script>
    let microsoftParams = {/literal}JSON.parse('{$OAUTH_PARAMS}');{literal}
    let msalInstance = "";
    $(document).ready(function () {
        if (
            microsoftParams &&
            (microsoftParams.enabled == true || microsoftParams.enabled == 'true' || microsoftParams.enabled === 'on') &&
            typeof microsoftParams.clientId === 'string' &&
            microsoftParams.clientId.trim() !== '' &&
            typeof microsoftParams.tenantId === 'string' &&
            microsoftParams.tenantId.trim() !== ''
        ) {
            const msalConfig = {
                auth: {
                    clientId: microsoftParams.clientId,
                    authority: 'https://login.microsoftonline.com/' + microsoftParams.tenantId, 
                    redirectUri: microsoftParams.redirectUri,
                },
                cache: {
                    cacheLocation: "sessionStorage",
                    storeAuthStateInCookie: false,
                }
            };

            msalInstance = new msal.PublicClientApplication(msalConfig);

            msalInstance.initialize().then(() => {
                document.getElementById('ms-login-btn').addEventListener('click', loginWithMicrosoft);
            }).catch(error => {
                console.error("MSAL Initialization Error", error);
            });
        }
    });
    

    function loginWithMicrosoft() {
        const loginRequest = {
            scopes: microsoftParams.scopes.split(' '),
        };

        msalInstance.loginPopup(loginRequest)
            .then(response => {
                if (response.idToken) {
                    document.getElementById('microsoft_credentials').value = response.idToken;
                    document.getElementById('oauth_provider').value = 'Microsoft';
                    const form = document.getElementById('form');
                    form.submit();
                } else {
                    console.error(SUGAR.language.get('Users', "LBL_OAUTH_AUTH_ERR_INVALID_TOKEN"), response);
                    document.getElementById('post_error').textContent = SUGAR.language.get(
                        'Users',
                        "LBL_OAUTH_AUTH_ERR_INVALID_TOKEN"
                    );
                }
            })
            .catch(error => {
                console.error(SUGAR.language.get('Users', "LBL_OAUTH_AUTH_ERR_INVALID_TOKEN"), error);
                document.getElementById('post_error').textContent = SUGAR.language.get(
                    'Users',
                    "LBL_OAUTH_AUTH_ERR_INVALID_TOKEN"
                );
            });
    }
</script>
{/literal}