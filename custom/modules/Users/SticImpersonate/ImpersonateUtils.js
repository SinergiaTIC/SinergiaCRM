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
 * Impersonation Utils
 */
document.addEventListener("DOMContentLoaded", function() {
    /**
     * Show impersonation indicator and message if impersonationData is defined
     */
    if (impersonationData === undefined || impersonationData === null) {
        return;
    }
    showImpersonationIndicator();
    showImpersonationMessage();
});

/**
 * Shows a message at the top of the page content indicating that the user is impersonating another user. 
 */
function showImpersonationMessage() {
    document.querySelectorAll('#stic-impersonation-message').forEach(el => el.remove());

    var ref = document.getElementById('pagecontent');
    if (ref.length === 0) {
        console.warn('Did not find #pagecontent, cannot insert impersonation message');
        return;
    }
    var message = document.createElement('div');
    message.id = 'stic-impersonation-message';
    message.className = 'msg-fatal-lock';   
    message.innerHTML = `
        ${modImpersonate.LBL_IMPERSONATE_MESSAGE_TITLE} 
        ${modImpersonate.LBL_IMPERSONATE_MESSAGE_DESCRIPTION}
        <strong>${impersonationData.target_user_name}</strong>
        ${modImpersonate.LBL_IMPERSONATE_MESSAGE_STOP_DESCRIPTION}
    `;
    // Inserting as the first child of the reference node
    ref.insertBefore(message, ref.firstChild);
}

/**
 * Shows an impersonation indicator in the top navigation bar.
 */
function showImpersonationIndicator() {
    // Removing previous indicators
    document.querySelectorAll('#stic-impersonation-indicator').forEach(el => el.remove());

    // Searching all #globalLinks to insert after each one desktop/mobile/tablet
    var refs = document.querySelectorAll('#globalLinks');
    if (refs.length === 0) {
        console.warn('Did not find #globalLinks, cannot insert impersonation message');
        return;
    }

    refs.forEach(ref => {
        var indicator = document.createElement(ref.tagName.toLowerCase());
        indicator.id = 'stic-impersonation-indicator';
        indicator.className = 'dropdown nav navbar-nav desktop_notifications impersonate-indicator';

        indicator.innerHTML = `
            <button class="alertsButton btn dropdown-toggle suitepicon suitepicon-action-view impersonate-indicator-icon user-menu-button"  data-toggle="dropdown" aria-expanded="false">
            </button>
            <ul class="dropdown-menu user-dropdown impersonation-dropdown" role="menu" aria-labelledby="with-label">
                <li role="presentation" class="dropdown-header">
                    ${modImpersonate.LBL_IMPERSONATE_MESSAGE_TITLE}
                </li>
                <li role="presentation">
                    <strong>${modImpersonate.LBL_IMPERSONATE_TARGET_USER}:</strong> ${impersonationData.target_user_full_name}
                </li>
                <li role="presentation">
                    <strong>${modImpersonate.LBL_IMPERSONATE_ORIGINAL_USER}:</strong> ${impersonationData.original_user_full_name}
                </li>
                <li role="presentation">
                    <a id="stop-impersonation-btn" href="index.php?module=Users&action=stopImpersonation" class="text-danger">
                        ✖ ${modImpersonate.LBL_IMPERSONATE_STOP_BUTTON}
                    </a>
                </li>
            </ul>
        `;

        // Inserting after the reference node
        ref.parentNode.insertBefore(indicator, ref.nextSibling);
    });
}