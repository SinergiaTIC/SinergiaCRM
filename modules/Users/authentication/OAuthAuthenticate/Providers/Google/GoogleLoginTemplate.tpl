<div id="g_id_onload"></div>
<div id="g_id_onload"></div>
<div id="buttonDiv">
    <button id="google-login-btn" type="button" class="google-login-btn">
        <span class="google-logo" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 533.5 544.3">
                <path fill="#4285F4" d="M533.5 278.4c0-17.4-1.6-34.1-4.6-50.4H272v95.4h146.9
                c-6.3 33.9-25 62.7-53.2 81.8v68.1h85.9c50.3-46.4 81.9-114.9 
                81.9-194.9z"/>
                <path fill="#34A853" d="M272 544.3c72.6 0 133.6-24 178.1-65.4l-85.9-68.1
                c-23.9 16-54.5 25.4-92.2 25.4-70.9 0-130.9-47.9-152.4-112.2H30.1v70.4
                C74.1 482.9 167.3 544.3 272 544.3z"/>
                <path fill="#FBBC05" d="M119.6 323.9c-5.5-16-8.6-33.1-8.6-51s3.1-35 8.6-51V151.5H30.1
                C10.9 190.9 0 237.2 0 272.9s10.9 82 30.1 121.4l89.5-70.4z"/>
                <path fill="#EA4335" d="M272 107.7c39.5 0 74.9 13.6 102.7 40.2l77.1-77.1C405.6 24 
                344.6 0 272 0 167.3 0 74.1 61.4 30.1 151.5l89.5 70.4C141.1 
                155.6 201.1 107.7 272 107.7z"/>
            </svg>
        </span>
    {$OAUTH_LANG.LBL_OAUTH_AUTH_GOOGLE_AUTHENTICATION_TEXT}
    </button>
</div>
<input type="hidden" id="google_credentials" name="google_credentials" />

{literal}
<style>
    .google-login-btn {
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

    .google-login-btn:hover {
        background-color: #f7f7f7;
    }
    
    .google-logo {
        display: flex;
        align-items: center;
    }
</style>
<script>
    let googleParams = JSON.parse({/literal}'{$OAUTH_PARAMS}'{literal});

    $(document).ready(function () {
        if (
            googleParams &&
            (googleParams.enabled == true || googleParams.enabled == 'true' || googleParams.enabled === 'on') &&
            typeof googleParams.clientId === 'string' &&
            googleParams.clientId.trim() !== ''
        ) {
            const script = document.createElement('script');
            script.src = "https://accounts.google.com/gsi/client";
            script.async = true;
            script.defer = true;
            script.onload = initGoogleSignIn;
            document.head.appendChild(script);
        }
    });

    function initGoogleSignIn() {
        $('head').append(
            '<meta name="google-signin-client_id" content="' + googleParams.clientId + '" />'
        );
        setupGoogleButton();
    }

    function handleCredentialResponse(response) {
        if (!response || !response.credential) {
            console.error(SUGAR.language.get('Users', "LBL_OAUTH_AUTH_ERR_INVALID_TOKEN"));
            document.getElementById('post_error').textContent = SUGAR.language.get(
                'Users',
                "LBL_OAUTH_AUTH_ERR_INVALID_TOKEN"
            );
            return;
        }

        let id_token = response.credential;
        $('#google_credentials').val(id_token);
        $('#oauth_provider').val('Google');
        const form = document.getElementById('form');
        form.submit();
    }

    function setupGoogleButton() {
        google.accounts.id.initialize({
            client_id: googleParams.clientId,
            callback: handleCredentialResponse,
        });

        google.accounts.id.renderButton(document.getElementById("buttonDiv"), {
            theme: "outline",
            size: "large",
            type: "standard",
        });

        google.accounts.id.prompt();
    }
</script>
{/literal}