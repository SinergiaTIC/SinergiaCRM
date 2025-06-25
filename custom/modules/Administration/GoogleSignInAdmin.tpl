

<form id="ConfigureGoogleSignInSettings" name="ConfigureGoogleSignInSettings" enctype='multipart/form-data' method="POST"
      action="index.php?module=Administration&action=GoogleSignInAdmin&do=save">

    <table width="100%" cellpadding="0" cellspacing="1" border="0" class="actionsContainer">
        <tr>
            <td>
                {$BUTTONS}
            </td>
        </tr>
    </table>

    <table width="100%" border="0" cellspacing="1" cellpadding="0" class="edit view">
        <tr>
            <td  scope="row" width="200">{$MOD.LBL_GOOGLE_SIGN_IN_ADMIN_CLIENT_ID}: </td>
            <td  >
                <input type='text' size='100'name='google_signin_clientid' value='{$config.google_signin_clientid}' >
            </td>
        </tr>
        <br>
        <tr>
            <td  scope="row" width="200">{$MOD.LBL_GOOGLE_SIGN_IN_ADMIN_CLIENT_SECRET}: </td>
            <td  >
                <input type='text' size='100' name='google_signin_clientsecret' value='{$config.google_signin_clientsecret}' >
            </td>
        </tr>
    </table>
    
    <div style="padding-top: 2px;">
        {$BUTTONS}
    </div>
</form>
