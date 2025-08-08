<div id="g_id_onload"></div>
<div id="buttonDiv"></div>
<input type="hidden" id="google_credentials" name="google_credentials" />

{literal}
    <script>
        let googleSignInClientId = {/literal}JSON.parse('{$PROVIDER_GOOGLE_PARAMS}'); {literal}
    
        $(document).ready(function() {
            if (
                googleSignInClientId &&
                googleSignInClientId.auth_enabled == true &&
                typeof googleSignInClientId.auth_client_id === 'string' &&
                googleSignInClientId.auth_client_id.trim() !== ''
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
            $('head').append('<meta name="google-signin-client_id" content="' + googleSignInClientId.auth_client_id + '" />');
            setupGoogleButton();
        }
    
        function handleCredentialResponse(response) {
            if (!response || !response.credential) {
                console.error(SUGAR.language.get('Users', "LBL_GOOGLE_AUTH_ERR_INVALID_TOKEN"));
                document.getElementById('post_error').textContent = SUGAR.language.get('Users', "LBL_GOOGLE_AUTH_ERR_INVALID_TOKEN");
                return;
            }
            let id_token = response.credential;
            $('#google_credentials').val(id_token);
            $('#oauth_provider').val('Google');
            const form = document.getElementById('form');
            form.submit();
        }
    
        function createGoogleElements() {
            return `
                <div id="g_id_onload"></div>
                <div id="buttonDiv"></div>
                <input type="hidden" id="google_credentials" name="google_credentials" />
            `;
        }
    
        function setupGoogleButton() {
            google.accounts.id.initialize({
                client_id: googleSignInClientId.auth_client_id,
                callback: handleCredentialResponse,
            });
    
            google.accounts.id.renderButton(document.getElementById("buttonDiv"), {
                theme: "outline",
                size: "large",
            });
    
            google.accounts.id.prompt();
        }
    
    
    </script>
    {/literal}