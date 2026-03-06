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
 * Utility functions for SignaturePortal
 */

/**
 * Shows an alert message with HTML modal
 * @param {string} type - Type of alert: 'success', 'warning', 'error'
 * @param {string} title - Title of the alert
 * @param {string} message - Message content
 * @param {function} onClose - Optional callback function when alert is closed
 * @param {boolean} reloadOnClose - Whether to reload page when alert is closed
 */
function showAlert(type, title, message, onClose = null, reloadOnClose = false) {
    const typeClasses = {
        success: 'text-success',
        warning: 'text-warning', 
        error: 'text-danger'
    };

    const alertMessage = document.createElement('div');
    alertMessage.className = 'fixed inset-0 d-flex justify-content-center align-items-center z-50';
    alertMessage.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
    alertMessage.innerHTML = `
        <div class="bg-white p-4 rounded-3 shadow-lg text-center mx-4">
            <h3 class="fs-4 fw-bold ${typeClasses[type]} mb-3">${title}</h3>
            <p class="text-secondary mb-4">${message}</p>
            <button id="closeAlertBtn" class="btn btn-primary fw-semibold">${MODS.LBL_PORTAL_CLOSE}</button>
        </div>
    `;
    
    document.body.appendChild(alertMessage);
    
    document.getElementById('closeAlertBtn').addEventListener('click', () => {
        alertMessage.remove();
        if (onClose) {
            onClose();
        }
        if (reloadOnClose) {
            window.location.reload();
        }
    });
}
