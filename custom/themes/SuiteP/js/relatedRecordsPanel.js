/**
 * This file is part of SinergiaCRM.
 * SinergiaCRM is a work developed by SinergiaTIC Association, based on SuiteCRM.
 * Copyright (C) 2013 - 2026 SinergiaTIC Association
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by
 * the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along
 * with this program; if not, see http://www.gnu.org/licenses or write to the
 * Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SinergiaTIC Association at email address info@sinergiacrm.org.
 */

/* global SUGAR, jQuery */

// STIC-Custom 20261023 DEV - Related records panel (right side bar)
//
// Client side of the "related records" panel injected in the record detail views.
//  - Collapsible right bar, open/close state persisted in a cookie
//    (same pattern as the left sidebar: themes/SuiteP/js/style.js -> loadSidebar()).
//  - A "status" request is issued on every detail view load to know whether the
//    panel is enabled for the module, whether it is PINNED to the right and
//    whether empty subpanels are shown.
//  - Pinned mode: the panel stays beside the detail panels only; its height ends
//    where the bottom subpanels start, and the fields area gets a right margin.
//  - Lazy load: data is fetched from the sticRelatedPanel entry point only when
//    the panel is opened, and cached in memory per module:record.
//  - Sections: one per configured subpanel (empty ones at the bottom, count 0),
//    collapsible, with a "View all" link that scrolls to the bottom subpanel.
//  - The user can reorder the sections (drag & drop); the order is saved as a
//    user preference through the same entry point and can be restored.
//  - This file is re-executed by SUGAR.util.evalScript() on every ajaxUI
//    navigation, so it MUST remain idempotent (window.SticRelatedPanel guard +
//    delegated namespaced events).
//
// See stic-ai-handbook: output/features/related-records-panel

(function () {
    'use strict';

    if (window.SticRelatedPanel) {
        // Re-executed after an ajaxUI navigation: the detail view content (and the
        // panel shell) is brand new, so re-apply the visual state and reload data
        // if the record changed.
        window.SticRelatedPanel.refresh();
        return;
    }

    var PANEL_ID = 'stic-related-panel';
    var COOKIE_NAME = 'rightbartoggle';
    var DESKTOP_MIN_WIDTH = 1200;
    var CACHE_LIMIT = 10;

    var state = {
        open: false,
        enabled: null,     // from status: panel enabled for this module
        adminPinned: false,// from status: admin "pinned" default
        hideEmpty: false,  // from status
        userPrefs: { pinned: null, shrink: null }, // from status (null = follow the default)
        renderedKey: null,
        cache: {},
        cacheKeys: [],
        request: null,
        statusRequest: null,
        dragKey: null,
        geom: null         // pinned layout geometry (baseTop, limitDoc, height)
    };

    /**
     * Effective values: the user preference overrides the admin default.
     */
    function effectivePinned() {
        return state.userPrefs.pinned !== null ? !!state.userPrefs.pinned : !!state.adminPinned;
    }

    function effectiveShrink() {
        return state.userPrefs.shrink !== null ? !!state.userPrefs.shrink : true;
    }

    // ------------------------------------------------------------------ utils

    function lang(key, fallback) {
        var value = SUGAR.language.get('app_strings', key);
        return (typeof value === 'string' && value !== '' && value !== 'undefined') ? value : (fallback || '');
    }

    function panelNode() {
        return document.getElementById(PANEL_ID);
    }

    function contentNode() {
        var panel = panelNode();
        return panel ? panel.querySelector('.stic-rrp-content') : null;
    }

    function clearNode(node) {
        while (node && node.firstChild) {
            node.removeChild(node.firstChild);
        }
    }

    function el(tagName, className, text) {
        var node = document.createElement(tagName);
        if (className) {
            node.className = className;
        }
        if (typeof text === 'string' && text !== '') {
            node.textContent = text; // Safe by construction: never innerHTML with server data.
        }
        return node;
    }

    function isPrintRequest() {
        return /([?&])(print=true|to_pdf=1)($|&|#)/.test(window.location.search + window.location.hash);
    }

    function endpointUrl(params) {
        var url = 'index.php?entryPoint=sticRelatedPanel';
        for (var key in params) {
            if (!params.hasOwnProperty(key)) {
                continue;
            }
            url += '&' + key + '=' + encodeURIComponent(params[key]);
        }
        return url;
    }

    /**
     * Current module/record from the DetailView form, with URL fallback.
     */
    function currentContext() {
        var module = '';
        var record = '';
        var form = document.getElementById('formDetailView');
        if (form) {
            var moduleInput = form.querySelector('input[name="module"]');
            var recordInput = form.querySelector('input[name="record"]');
            module = moduleInput ? moduleInput.value : '';
            record = recordInput ? recordInput.value : '';
        }
        if (!module || !record) {
            var url = decodeURIComponent(window.location.hash || window.location.search || '');
            var moduleMatch = url.match(/module=([A-Za-z0-9_\-]+)/);
            var recordMatch = url.match(/record=([a-zA-Z0-9\-]{6,36})/);
            module = module || (moduleMatch ? moduleMatch[1] : '');
            record = record || (recordMatch ? recordMatch[1] : '');
        }
        return { module: module, record: record };
    }

    // ------------------------------------------------------------------ state

    function applyVisualState() {
        var panel = panelNode();
        if (!panel) {
            return;
        }
        var pinned = effectivePinned();
        var shrink = effectiveShrink();

        panel.classList.toggle('is-open', state.open);
        panel.setAttribute('aria-hidden', state.open ? 'false' : 'true');

        var toggle = document.getElementById('stic-rrp-toggle');
        if (toggle) {
            toggle.setAttribute('aria-expanded', state.open ? 'true' : 'false');
            toggle.setAttribute('title', state.open
                ? (toggle.getAttribute('data-title-close') || '')
                : (toggle.getAttribute('data-title-open') || ''));
            toggle.classList.toggle('stic-rrp-hidden', state.enabled === false);
        }

        document.body.classList.toggle('stic-rrp-open', state.open);
        document.body.classList.toggle('stic-rrp-pinned', pinned && state.open && state.enabled !== false);
        document.body.classList.toggle('stic-rrp-shrink', pinned && state.open && shrink && state.enabled !== false);

        // Header toolbar (user preferences) state
        var pinTool = panel.querySelector('.stic-rrp-tool-pin');
        if (pinTool) {
            pinTool.classList.toggle('is-active', pinned);
            pinTool.setAttribute('aria-pressed', pinned ? 'true' : 'false');
            pinTool.setAttribute('title', pinned
                ? (pinTool.getAttribute('data-title-on') || '')
                : (pinTool.getAttribute('data-title-off') || ''));
        }
        var shrinkTool = panel.querySelector('.stic-rrp-tool-shrink');
        if (shrinkTool) {
            shrinkTool.classList.toggle('is-active', shrink);
            shrinkTool.setAttribute('aria-pressed', shrink ? 'true' : 'false');
            shrinkTool.setAttribute('title', shrink
                ? (shrinkTool.getAttribute('data-title-on') || '')
                : (shrinkTool.getAttribute('data-title-off') || ''));
        }

        if (pinned && state.open) {
            applyPinnedLayout();
        } else {
            // Leave the plain fixed layout (full height, CSS top) untouched
            panel.style.top = '';
            panel.style.height = '';
            panel.style.bottom = '';
            state.geom = null;
        }
    }

    /**
     * Pinned mode geometry. The panel is position:fixed and behaves like a
     * "sticky" column beside the detail view:
     *  - While the detail view is on screen, the panel stays put (fixed at its
     *    base top, below the navbar): scrolling does not move it.
     *  - Its bottom edge can never go below the point where the bottom subpanels
     *    start (document coordinate). When the user scrolls down towards the
     *    subpanels, the panel slides up with the content instead of covering
     *    them (see updatePinnedPosition, hooked to the scroll event).
     * All the measurements are document-absolute, so nothing depends on the
     * scroll position at the moment of the calculation.
     */
    function applyPinnedLayout() {
        var panel = panelNode();
        var detailView = document.querySelector('#content .detail-view') || document.querySelector('.detail-view');
        var subpanels = document.getElementById('subpanel_list');
        if (!panel) {
            return;
        }
        // Base top from a STABLE reference: the toggle button is fixed too and its
        // inline style is never touched (the panel's own computed top can NOT be
        // used because we slide the panel by setting its inline top).
        var toggle = document.getElementById('stic-rrp-toggle');
        var baseTop = toggle ? toggle.getBoundingClientRect().top : 76;
        if (baseTop < 20 || baseTop > 400) {
            // Implausible value (measured during a transient layout state, e.g.
            // right in the middle of an ajaxUI content injection): retry shortly
            if (!state.geomRetries || state.geomRetries < 6) {
                state.geomRetries = (state.geomRetries || 0) + 1;
                window.setTimeout(function () {
                    state.geomRetries = 0;
                    applyPinnedLayout();
                }, 150);
            }
            return;
        }
        state.geomRetries = 0;
        var limit = null;
        if (subpanels) {
            limit = subpanels.getBoundingClientRect().top + window.scrollY;
        }
        if (detailView) {
            var detailBottom = detailView.getBoundingClientRect().bottom + window.scrollY + 20;
            limit = (limit === null) ? detailBottom : Math.min(limit, detailBottom);
        }
        if (limit === null) {
            return;
        }
        var height = Math.max(200, limit - baseTop - 10);
        state.geom = { baseTop: baseTop, limitDoc: limit, height: height };
        panel.style.height = Math.round(height) + 'px';
        panel.style.bottom = 'auto';
        updatePinnedPosition();
    }

    /**
     * Scrolls the pinned panel with the content once its bottom edge would enter
     * the subpanels area: the panel stays fixed while the detail view is visible
     * and slides up (never down over the subpanels) as the page scrolls.
     */
    function updatePinnedPosition() {
        var panel = panelNode();
        if (!panel || !state.geom) {
            return;
        }
        var allowedTop = state.geom.limitDoc - state.geom.height - 10 - window.scrollY;
        var top = Math.min(state.geom.baseTop, allowedTop);
        panel.style.top = Math.round(top) + 'px';
        // Completely hidden once it slides past the navbar: keep it that way
        panel.style.visibility = (top + state.geom.height <= state.geom.baseTop * 0.5) ? 'hidden' : '';
    }

    /**
     * Recomputes the pinned height shortly after interactions that can change the
     * detail view height (tab switching, ajaxUI reload) or the window size.
     */
    var pinnedLayoutTimer = null;
    function schedulePinnedLayout() {
        if (!effectivePinned() || !state.open) {
            return;
        }
        if (pinnedLayoutTimer) {
            window.clearTimeout(pinnedLayoutTimer);
        }
        pinnedLayoutTimer = window.setTimeout(applyPinnedLayout, 60);
    }

    function setOpen(open) {
        state.open = !!open && state.enabled !== false;
        applyVisualState();
        if (typeof jQuery !== 'undefined' && jQuery.cookie) {
            jQuery.cookie(COOKIE_NAME, state.open ? 'expanded' : 'collapsed');
        }
        if (state.open) {
            ensureLoaded();
        }
    }

    // ---------------------------------------------------------------- status

    function loadStatus(context) {
        if (!context.module) {
            return Promise.resolve(null);
        }
        if (state.statusRequest && state.statusRequest.abort) {
            state.statusRequest.abort();
        }
        var controller = new AbortController();
        state.statusRequest = controller;
        return fetch(endpointUrl({ panel_action: 'status', module: context.module }), {
            credentials: 'same-origin',
            headers: { 'Accept': 'application/json' },
            signal: controller.signal
        }).then(function (response) {
            if (!response.ok) {
                throw new Error('HTTP ' + response.status);
            }
            return response.json();
        }).then(function (status) {
            state.statusRequest = null;
            state.enabled = !!status.enabled;
            state.adminPinned = !!status.pinned;
            state.hideEmpty = !!status.hide_empty;
            state.userPrefs = (status.user_prefs && typeof status.user_prefs === 'object')
                ? {
                    pinned: (status.user_prefs.pinned === null || status.user_prefs.pinned === undefined) ? null : !!status.user_prefs.pinned,
                    shrink: (status.user_prefs.shrink === null || status.user_prefs.shrink === undefined) ? null : !!status.user_prefs.shrink
                }
                : { pinned: null, shrink: null };
            return status;
        }).catch(function () {
            state.statusRequest = null;
            return null;
        });
    }

    // ------------------------------------------------------------------ data

    function cacheKey(context) {
        return context.module + ':' + context.record;
    }

    function cachePut(key, payload) {
        state.cache[key] = payload;
        state.cacheKeys.push(key);
        while (state.cacheKeys.length > CACHE_LIMIT) {
            delete state.cache[state.cacheKeys.shift()];
        }
    }

    function showLoading() {
        var content = contentNode();
        if (!content) {
            return;
        }
        clearNode(content);
        content.setAttribute('data-state', 'loading');
        content.appendChild(el('div', 'stic-rrp-status', lang('LBL_RRP_LOADING', '...')));
    }

    function showError() {
        var content = contentNode();
        if (!content) {
            return;
        }
        clearNode(content);
        content.setAttribute('data-state', 'error');
        content.appendChild(el('div', 'stic-rrp-status stic-rrp-status-error', lang('LBL_RRP_ERROR')));
        var retry = el('button', 'stic-rrp-retry', lang('LBL_RRP_RETRY'));
        retry.setAttribute('type', 'button');
        content.appendChild(retry);
    }

    function ensureLoaded() {
        var context = currentContext();
        if (!context.module || !context.record) {
            return;
        }

        var key = cacheKey(context);
        if (state.cache[key]) {
            render(state.cache[key]);
            return;
        }

        if (state.request && state.request.abort) {
            state.request.abort();
        }
        showLoading();

        var controller = new AbortController();
        state.request = controller;

        fetch(endpointUrl({ module: context.module, record: context.record }), {
            credentials: 'same-origin',
            headers: { 'Accept': 'application/json' },
            signal: controller.signal
        }).then(function (response) {
            if (!response.ok) {
                throw new Error('HTTP ' + response.status);
            }
            return response.json();
        }).then(function (payload) {
            if (controller.signal.aborted) {
                return;
            }
            state.request = null;
            if (payload && payload.enabled === false) {
                state.enabled = false;
                applyVisualState();
                return;
            }
            cachePut(key, payload);
            render(payload);
        }).catch(function (error) {
            if (error && error.name === 'AbortError') {
                return;
            }
            state.request = null;
            showError();
        });
    }

    // ---------------------------------------------------------------- render

    function render(payload) {
        var content = contentNode();
        if (!content) {
            return;
        }
        clearNode(content);
        content.setAttribute('data-state', 'ready');

        var sections = payload && payload.sections ? payload.sections : [];
        if (!sections.length) {
            content.setAttribute('data-state', 'empty');
            content.appendChild(el('div', 'stic-rrp-status stic-rrp-status-empty', lang('LBL_RRP_EMPTY')));
            return;
        }

        for (var i = 0; i < sections.length; i++) {
            content.appendChild(buildSection(sections[i]));
        }

        // User order tools (drag & drop reset)
        var tools = el('div', 'stic-rrp-tools');
        var reset = el('a', 'stic-rrp-reset-order', lang('LBL_RRP_RESET_ORDER'));
        reset.setAttribute('href', 'javascript:void(0)');
        reset.setAttribute('title', lang('LBL_RRP_RESET_ORDER_TITLE'));
        tools.appendChild(el('span', 'stic-rrp-drag-hint', lang('LBL_RRP_DRAG_HINT')));
        tools.appendChild(reset);
        content.appendChild(tools);
    }

    function buildSection(section) {
        var node = el('section', 'stic-rrp-section' + (section.empty ? ' is-empty' : ''));
        node.setAttribute('data-key', section.key || '');
        node.setAttribute('draggable', 'true');

        // Header (collapsible + drag handle)
        var head = el('div', 'stic-rrp-section-head');
        head.setAttribute('role', 'button');
        head.setAttribute('tabindex', '0');
        head.setAttribute('aria-expanded', 'true');

        var handle = el('span', 'suitepicon suitepicon-action-sorting-none stic-rrp-section-handle');
        handle.setAttribute('title', lang('LBL_RRP_DRAG_HINT'));
        head.appendChild(handle);
        head.appendChild(iconSpan(section.icon, 'suitepicon-action-list'));
        head.appendChild(el('span', 'stic-rrp-section-title', section.title || section.key || ''));
        head.appendChild(el('span', 'stic-rrp-section-count' + (section.empty ? ' stic-rrp-count-empty' : ''),
            String(section.count || 0)));
        head.appendChild(el('span', 'suitepicon suitepicon-action-caret stic-rrp-section-caret'));
        node.appendChild(head);

        // Entries (timeline)
        var list = el('ul', 'stic-rrp-entries');
        var records = section.records || [];
        for (var i = 0; i < records.length; i++) {
            list.appendChild(buildEntry(records[i]));
        }
        if (!records.length) {
            list.appendChild(el('li', 'stic-rrp-entry stic-rrp-entry-empty', lang('LBL_RRP_NO_RECORDS')));
        }
        node.appendChild(list);

        // "View all" link: scrolls to the bottom subpanel and expands it
        if (section.has_more) {
            var more = el('a', 'stic-rrp-more', lang('LBL_RRP_VIEW_ALL').replace('{0}', String(section.count)));
            more.setAttribute('href', 'javascript:void(0)');
            more.setAttribute('data-subpanel', section.key || '');
            node.appendChild(more);
        }
        return node;
    }

    function buildEntry(record) {
        var node = el('li', 'stic-rrp-entry');

        node.appendChild(iconSpan(record.icon, 'suitepicon-action-info'));

        var main = el('div', 'stic-rrp-entry-main');
        if (record.url) {
            var title = el('a', 'stic-rrp-entry-title', record.title || record.id || '');
            title.setAttribute('href', record.url);
            main.appendChild(title);
        } else {
            main.appendChild(el('span', 'stic-rrp-entry-title', record.title || record.id || ''));
        }
        if (record.subtitle) {
            main.appendChild(el('span', 'stic-rrp-entry-subtitle', record.subtitle));
        }
        node.appendChild(main);

        if (record.right_label) {
            node.appendChild(el('span', 'stic-rrp-entry-date', record.right_label));
        }
        return node;
    }

    function iconSpan(iconClass, fallbackClass) {
        var span = el('span', 'stic-rrp-icon suitepicon ' + (iconClass || fallbackClass || ''));
        if (!iconClass && fallbackClass) {
            span.className = 'stic-rrp-icon suitepicon ' + fallbackClass;
        }
        return span;
    }

    // ---------------------------------------------------------- user ordering

    /**
     * Persists the current DOM order of the (non-empty) sections.
     * Empty sections always stay at the bottom and are not part of the order.
     */
    function saveCurrentOrder() {
        var context = currentContext();
        if (!context.module) {
            return;
        }
        var keys = [];
        var sections = document.querySelectorAll('.stic-rrp-section:not(.is-empty)');
        for (var i = 0; i < sections.length; i++) {
            var key = sections[i].getAttribute('data-key');
            if (key) {
                keys.push(key);
            }
        }
        var body = new URLSearchParams();
        body.set('module', context.module);
        keys.forEach(function (key) {
            body.append('order[]', key);
        });
        fetch(endpointUrl({ panel_action: 'saveOrder' }), {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'Accept': 'application/json'
            },
            body: body.toString()
        }).catch(function () { });
    }

    function resetOrder() {
        var context = currentContext();
        if (!context.module) {
            return;
        }
        var body = new URLSearchParams();
        body.set('module', context.module);
        fetch(endpointUrl({ panel_action: 'resetOrder' }), {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'Accept': 'application/json'
            },
            body: body.toString()
        }).then(function () {
            // Drop the cached payload and reload: the server applies the admin order
            delete state.cache[cacheKey(context)];
            ensureLoaded();
        }).catch(function () { });
    }

    // ---------------------------------------------------------------- events

    function viewAll(event) {
        event.preventDefault();
        var key = event.currentTarget.getAttribute('data-subpanel');
        if (!key) {
            return;
        }
        var subpanel = document.getElementById('whole_subpanel_' + key);
        if (!subpanel) {
            return;
        }
        var subpanelTitle = document.getElementById('subpanel_title_' + key);
        if (subpanelTitle) {
            subpanelTitle.click(); // Expand the collapsed subpanel first
        }
        subpanel.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function toggleSection(event) {
        var section = event.currentTarget.closest('.stic-rrp-section');
        if (!section) {
            return;
        }
        var collapsed = section.classList.toggle('is-collapsed');
        event.currentTarget.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
    }

    function bindEvents() {
        var doc = jQuery(document);
        doc.off('.rrp');

        doc.on('click.rrp', '#stic-rrp-toggle', function (event) {
            event.preventDefault();
            setOpen(!state.open);
        });
        doc.on('click.rrp', '.stic-rrp-close', function (event) {
            event.preventDefault();
            setOpen(false);
        });
        doc.on('click.rrp', '.stic-rrp-retry', function (event) {
            event.preventDefault();
            ensureLoaded();
        });
        doc.on('click.rrp', '.stic-rrp-reset-order', function (event) {
            event.preventDefault();
            resetOrder();
        });
        doc.on('click.rrp keydown.rrp', '.stic-rrp-section-head', function (event) {
            if (event.type === 'keydown' && event.key !== 'Enter' && event.key !== ' ') {
                return;
            }
            event.preventDefault();
            toggleSection(event);
        });
        doc.on('click.rrp', '.stic-rrp-more', viewAll);
        doc.on('keydown.rrp', function (event) {
            if (event.key === 'Escape' && state.open) {
                setOpen(false);
            }
        });

        // User preferences toolbar (panel header)
        doc.on('click.rrp', '.stic-rrp-tool-pin', function (event) {
            event.preventDefault();
            toggleUserPref('pinned');
        });
        doc.on('click.rrp', '.stic-rrp-tool-shrink', function (event) {
            event.preventDefault();
            toggleUserPref('shrink');
        });
    }

    /**
     * Toggles one user preference (pin / shrink), saves it and re-applies the
     * visual state immediately.
     */
    function toggleUserPref(key) {
        var prefs = { pinned: state.userPrefs.pinned, shrink: state.userPrefs.shrink };
        var effective = (key === 'pinned') ? effectivePinned() : effectiveShrink();
        // New value: explicit opposite of the effective value (overrides any default)
        prefs[key] = !effective;
        state.userPrefs = prefs;
        applyVisualState();
        schedulePinnedLayout();

        var context = currentContext();
        var body = 'module=' + encodeURIComponent(context.module);
        body += '&' + key + '=' + (prefs[key] ? '1' : '0');
        fetch(endpointUrl({ panel_action: 'saveprefs' }), {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'Accept': 'application/json'
            },
            body: body
        }).catch(function () { });
    }

    /**
     * Scroll handler (bound once): slides the pinned panel along with the content
     * so it never covers the bottom subpanels.
     */
    function bindScroll() {
        if (window.__rrpScroll) {
            return;
        }
        window.__rrpScroll = true;
        window.addEventListener('scroll', function () {
            if (state.geom) {
                updatePinnedPosition();
            }
        }, { passive: true });
    }

    function bindDragAndDrop() {
        var content = contentNode();
        if (!content || content.__rrpDnd) {
            return;
        }
        content.__rrpDnd = true;

        content.addEventListener('dragstart', function (event) {
            var section = event.target.closest ? event.target.closest('.stic-rrp-section') : null;
            if (!section) {
                return;
            }
            state.dragKey = section.getAttribute('data-key');
            section.classList.add('is-dragging');
            event.dataTransfer.effectAllowed = 'move';
            try {
                event.dataTransfer.setData('text/plain', state.dragKey);
            } catch (e) { }
        });
        content.addEventListener('dragend', function (event) {
            var section = event.target.closest ? event.target.closest('.stic-rrp-section') : null;
            if (section) {
                section.classList.remove('is-dragging');
            }
            state.dragKey = null;
        });
        content.addEventListener('dragover', function (event) {
            var target = event.target.closest ? event.target.closest('.stic-rrp-section') : null;
            if (!target || !state.dragKey) {
                return;
            }
            event.preventDefault();
            event.dataTransfer.dropEffect = 'move';
            target.classList.add('is-drop-target');
        });
        content.addEventListener('dragleave', function (event) {
            var target = event.target.closest ? event.target.closest('.stic-rrp-section') : null;
            if (target) {
                target.classList.remove('is-drop-target');
            }
        });
        content.addEventListener('drop', function (event) {
            event.preventDefault();
            var target = event.target.closest ? event.target.closest('.stic-rrp-section') : null;
            var dragging = state.dragKey
                ? content.querySelector('.stic-rrp-section.is-dragging')
                : null;
            if (!target || !dragging || dragging === target) {
                return;
            }
            target.classList.remove('is-drop-target');

            // Insert the dragged section before/after the target depending on the pointer
            var rect = target.getBoundingClientRect();
            var after = (event.clientY - rect.top) > rect.height / 2;
            target.parentNode.insertBefore(dragging, after ? target.nextSibling : target);

            // Empty sections must remain at the bottom
            normalizeEmptySections();

            saveCurrentOrder();
        });
    }

    /**
     * Moves the empty sections back to the bottom of the list.
     */
    function normalizeEmptySections() {
        var content = contentNode();
        if (!content) {
            return;
        }
        var empties = content.querySelectorAll('.stic-rrp-section.is-empty');
        var tools = content.querySelector('.stic-rrp-tools');
        for (var i = 0; i < empties.length; i++) {
            content.insertBefore(empties[i], tools || null);
        }
    }

    // ----------------------------------------------------------------- init

    /**
     * Called on every re-execution (ajaxUI navigation): re-applies the visual
     * state to the freshly injected shell and reloads data when the record
     * changed while the panel is open.
     */
    function refresh() {
        var panel = panelNode();
        if (!panel || isPrintRequest()) {
            return;
        }
        bindDragAndDrop();
        bindScroll();
        applyVisualState();

        var context = currentContext();
        var key = cacheKey(context);
        if (key !== state.renderedKey) {
            state.renderedKey = key;
            if (state.enabled === false) {
                applyVisualState();
                return;
            }
            if (state.open) {
                ensureLoaded();
            } else {
                var content = contentNode();
                if (content) {
                    clearNode(content);
                    content.setAttribute('data-state', 'idle');
                }
            }
        }
    }

    function init() {
        var panel = panelNode();
        if (!panel || isPrintRequest()) {
            return;
        }
        bindEvents();
        bindDragAndDrop();
        bindScroll();
        applyVisualState();

        var context = currentContext();
        state.renderedKey = null;

        // The status call decides the initial behaviour of the panel
        loadStatus(context).then(function () {
            applyVisualState();

            // Pinned mode forces the panel open (the cookie still allows closing it)
            var cookieValue = null;
            if (typeof jQuery !== 'undefined' && jQuery.cookie) {
                cookieValue = jQuery.cookie(COOKIE_NAME);
            }
            if (state.enabled === false) {
                state.open = false;
            } else if (effectivePinned()) {
                state.open = cookieValue !== 'collapsed';
            } else {
                state.open = cookieValue === 'expanded' && window.innerWidth >= DESKTOP_MIN_WIDTH;
            }

            applyVisualState();
            state.renderedKey = null;
            refresh();
            if (state.open) {
                ensureLoaded();
            }

            // Keep the pinned layout in sync: window resize and detail view tab
            // switching. The scroll handler slides the panel along the content
            // when the user approaches the bottom subpanels.
            jQuery(window).off('resize.rrp').on('resize.rrp', schedulePinnedLayout);
            jQuery(document).off('click.rrpTabs').on('click.rrpTabs', '.nav-tabs a, .panel-heading a', schedulePinnedLayout);
        });
    }

    window.SticRelatedPanel = {
        refresh: refresh,
        state: state
    };

    if (document.readyState === 'loading') {
        jQuery(document).ready(init);
    } else {
        init();
    }
})();
