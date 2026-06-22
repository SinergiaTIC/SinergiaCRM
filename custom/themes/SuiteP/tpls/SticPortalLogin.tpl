{literal}
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>{/literal}{$TITLE|escape}{literal}</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;background:#f0f2f5;color:#333;display:flex;justify-content:center;align-items:center;min-height:100vh}
.card{background:#fff;max-width:400px;width:100%;margin:20px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,.1);padding:30px}
.logo{text-align:center;margin-bottom:20px}
.logo img{max-height:60px;max-width:212px}
h1{text-align:center;font-size:18px;margin-bottom:20px;color:#333}
.msg{padding:10px;border-radius:4px;margin-bottom:15px;font-size:13px}
.msg-error{background:#fdecea;color:#c62828;border:1px solid #f5c6cb}
.msg-success{background:#e8f5e9;color:#2e7d32;border:1px solid #c8e6c9}
label{display:block;font-size:13px;font-weight:600;margin-bottom:5px;color:#555}
input[type="text"],input[type="password"]{width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:4px;font-size:14px;margin-bottom:15px}
input:focus{outline:none;border-color:#1976d2;box-shadow:0 0 0 2px rgba(25,118,210,.2)}
.checkbox-label{display:flex;align-items:center;font-size:13px;color:#666;margin-bottom:15px}
.checkbox-label input{margin-right:8px}
.btn{display:block;width:100%;padding:12px;background:#1976d2;color:#fff;border:none;border-radius:4px;font-size:14px;font-weight:600;cursor:pointer;margin-bottom:8px}
.btn:hover{background:#1565c0}
.btn-magic{background:#2e7d32}.btn-magic:hover{background:#1b5e20}
.links{text-align:center;margin-top:15px;font-size:13px}
.links a{color:#1976d2;text-decoration:none}
.tabs{display:flex;margin-bottom:20px;border-bottom:2px solid #e0e0e0}
.tabs button{flex:1;background:none;border:none;padding:10px;cursor:pointer;font-size:14px;color:#666;border-bottom:2px solid transparent;margin-bottom:-2px}
.tabs button.active{color:#1976d2;border-bottom-color:#1976d2;font-weight:600}
.tab-content{display:none}.tab-content.active{display:block}
.help{font-size:12px;color:#888;margin-bottom:15px;padding:8px;background:#f5f5f5;border-radius:4px}
.app-list{margin-top:20px;padding:12px;background:#f5f5f5;border-radius:4px;font-size:12px;color:#666;text-align:center}
.app-badge{display:inline-block;margin:4px 8px;padding:2px 10px;background:#e8e8e8;border-radius:10px;font-size:11px}
.honeypot{position:absolute;left:-9999px}
</style>
</head>
<body>
<div class="card">
  <div class="logo"><img src="{/literal}{$LOGO_URL|escape}{literal}" alt="{/literal}{$TITLE|escape}{literal}"></div>
  <h1>{/literal}{$TITLE|escape}{literal}</h1>
{/literal}
  {if $ERROR}<div class="msg msg-error">{$ERROR|escape}</div>{/if}
  {if $MESSAGE}<div class="msg msg-success">{$MESSAGE|escape}</div>{/if}
  {if $MAGIC_ENABLED}
  <div class="tabs">
    <button id="tab-pw" class="{if $MODE neq 'magic_link'}active{/if}" onclick="switchTab('password')">Password</button>
    <button id="tab-ml" class="{if $MODE eq 'magic_link'}active{/if}" onclick="switchTab('magic_link')">Magic Link</button>
  </div>
  {/if}
  <form method="post">
    <div class="honeypot" aria-hidden="true"><input type="text" name="portal_hp" tabindex="-1" autocomplete="off"></div>
    <input type="hidden" name="csrf_token" value="{$CSRF_TOKEN|escape}">
    <input type="hidden" name="portal_mode" id="portal_mode" value="{$MODE|default:'password'}">
    {if $IS_OAUTH}
    <input type="hidden" name="client_id" value="{$OAUTH_CLIENT_ID|escape}">
    <input type="hidden" name="redirect_uri" value="{$OAUTH_REDIRECT_URI|escape}">
    <input type="hidden" name="state" value="{$OAUTH_STATE|escape}">
    <div class="help">You are signing in to authorize: <strong>{$OAUTH_CLIENT_ID|escape|truncate:40}</strong></div>
    {/if}
    <div id="ct-password" class="tab-content {if $MODE neq 'magic_link'}active{/if}">
      <label>Username</label><input type="text" name="username" autocomplete="username" required>
      <label>Password</label><input type="password" name="password" autocomplete="current-password">
      <label class="checkbox-label"><input type="checkbox" name="remember" value="1">Remember me</label>
      <button type="submit" class="btn">Sign In</button>
    </div>
    {if $MAGIC_ENABLED}
    <div id="ct-magic" class="tab-content {if $MODE eq 'magic_link'}active{/if}">
      <p class="help">Enter your username and we'll send you a one-click login link.</p>
      <label>Username</label><input type="text" name="username_magic" onchange="document.querySelector('[name=username]').value=this.value">
      <button type="submit" class="btn btn-magic">Send Magic Link</button>
    </div>
    {/if}
  </form>
  
  {if $PORTAL_APPS}
  <div class="app-list">
    <strong>Available apps:</strong><br>
    {foreach from=$PORTAL_APPS item=app}
    <span class="app-badge">{$app.name|escape}</span>
    {/foreach}
  </div>
  {/if}
<div class="links"><a href="index.php?entryPoint=sticPortalReset">Forgot password?</a></div>
</div>
{literal}
<script>
function switchTab(m){document.getElementById('portal_mode').value=m;document.getElementById('tab-pw').className=m==='password'?'active':'';document.getElementById('tab-ml').className=m==='magic_link'?'active':'';document.getElementById('ct-password').className='tab-content'+(m==='password'?' active':'');document.getElementById('ct-magic').className='tab-content'+(m==='magic_link'?' active':'');}
</script>
</body></html>
{/literal}
