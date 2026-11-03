/**
 * This file is part of SinergiaCRM.
 * SinergiaCRM is a work developed by SinergiaTIC Association, based on SuiteCRM.
 * Copyright (C) 2013 - 2026 SinergiaTIC Association
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation.
 *
 * You can contact SinergiaTIC Association at email address info@sinergiacrm.org.
 */

/**
 * SinergiaCRM → SinergiaDA SSO: parent side of the postMessage handshake.
 *
 * When SinergiaDA is embedded in an iframe, it asks the parent page for a
 * short-lived token with {type: 'sda-sso-request'}; the parent fetches it from
 * the CRM entry point (requires an active CRM session) and answers with
 * {type: 'sda-sso-token', token}.
 *
 * The answer is only sent to a window that is really one of this page's frames
 * and whose origin matches the frame URL, so a third party window cannot trick
 * the CRM into handing over a token.
 */
(function () {
    'use strict';

    if (window.__sticSdaSsoParentInstalled) {
        return;
    }
    window.__sticSdaSsoParentInstalled = true;

    var ENDPOINT = 'index.php?entryPoint=stic_sda_sso_token';

    function originOf(url) {
        try {
            return new URL(url, window.location.href).origin;
        } catch (e) {
            return null;
        }
    }

    /**
     * Returns the iframe element matching a message source, or null when the
     * source is not one of the frames embedded in this page.
     */
    function frameForSource(source) {
        var frames = document.querySelectorAll('iframe');
        for (var i = 0; i < frames.length; i++) {
            if (frames[i].contentWindow === source) {
                return frames[i];
            }
        }
        return null;
    }

    window.addEventListener('message', function (event) {
        var data = event && event.data;
        if (!data || data.type !== 'sda-sso-request' || !event.source) {
            return;
        }

        var frame = frameForSource(event.source);
        if (!frame) {
            return;
        }

        var expectedOrigin = originOf(frame.getAttribute('src') || '');
        if (!expectedOrigin || expectedOrigin !== event.origin) {
            return;
        }

        fetch(ENDPOINT, { credentials: 'same-origin', headers: { Accept: 'application/json' } })
            .then(function (response) {
                return response.ok ? response.json() : null;
            })
            .then(function (payload) {
                if (!payload || !payload.token) {
                    return;
                }
                event.source.postMessage({ type: 'sda-sso-token', token: payload.token }, event.origin);
            })
            .catch(function () {
                /* SinergiaDA falls back to its own flow when there is no answer */
            });
    });
})();
