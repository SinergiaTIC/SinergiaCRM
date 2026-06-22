<style>
.pap-overlay{display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);z-index:99999;justify-content:center;align-items:center}
.pap-dialog{background:#fff;border-radius:8px;padding:30px;max-width:480px;width:90%;box-shadow:0 4px 20px rgba(0,0,0,0.3)}
.pap-dialog h3{margin:0 0 15px 0;font-size:16px}
.pap-field{margin-bottom:15px}
.pap-label{display:block;font-size:12px;font-weight:600;margin-bottom:4px;color:#555}
.pap-select{width:100%;border:1px solid #ddd;border-radius:4px;font-size:13px;color:#333;background:#fff}
.pap-info{margin-bottom:20px;padding:8px 12px;background:#e3f2fd;border-radius:4px;font-size:11px;color:#555}
.pap-info i{margin-right:4px}
.pap-info ul{padding-left:16px;margin:4px 0}
.pap-info a{color:#1976d2}
.pap-actions{text-align:right}
.pap-btn-cancel{padding:8px 16px;border:1px solid #ddd;background:#f5f5f5;border-radius:4px;cursor:pointer;margin-right:8px;font-size:13px}
.pap-btn-execute{padding:8px 16px;background:#1976d2;color:#fff;border:none;border-radius:4px;cursor:pointer;font-size:13px}
</style>

<div id="portalActionsPopup" class="pap-overlay">
<div class="pap-dialog">
    <h3>Portal Actions</h3>
    <div class="pap-field">
        <label class="pap-label">Action</label>
        <select id="portalActionType" class="pap-select">
            <option value="invitation">Send Invitation Email</option>
            <option value="pwreset">Send Password Reset</option>
        </select>
    </div>
    <div class="pap-field">
        <label class="pap-label">Target App for Redirect</label>
        <select id="portalAppSelect" class="pap-select"></select>
    </div>
    <div class="pap-info">
        <i class="glyphicon glyphicon-info-sign"></i>
        <strong>How it works:</strong>
        <ul>
            <li>An email is sent with a reset link to the contact.</li>
            <li>If the portal username is not set, the contact email is used as username.</li>
            <li>The contact clicks the link, sets a password, and can log in.</li>
            <li>If a target app is selected, they are redirected back to the app after setting the password.</li>
        </ul>
        <strong>Requirements:</strong> The contact must have an email address. Configure apps in <a href="index.php?module=Administration&amp;action=sticportalconfig" target="_blank">Administration &rarr; Portal Configuration</a>.
    </div>
    <div class="pap-actions">
        <button onclick="closePortalActionsPopup()" class="pap-btn-cancel">Cancel</button>
        <button onclick="executePortalAction()" class="pap-btn-execute">Execute</button>
    </div>
</div></div>
