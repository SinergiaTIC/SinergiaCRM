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
//  - ONE flat chronology: all the records of every enabled relation merged
//    and sorted globally (newest first), without grouping by subpanel.
//  - The user can filter which relations (subpanels) are shown; the choice is
//    saved as a user preference through the same entry point.
//  - This file is re-executed by SUGAR.util.evalScript() on every ajaxUI
//    navigation, so it MUST remain idempotent (window.SticRelatedPanel guard +
//    delegated namespaced events).
//

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
    var PANEL_MARGIN = 16; // keep in sync with $rrp-gap in SticRelatedPanel.scss

    var state = {
        open: false,
        enabled: null,     // from status: panel enabled for this module
        adminPinned: false,// from status: admin "pinned" default
        hiddenRelations: [], // from status: relation (subpanel) keys hidden by the user
        userPrefs: { pinned: null, shrink: null }, // from status (null = follow the default)
        renderedKey: null,
        currentPayload: null,
        filterOpen: false,
        cache: {},
        cacheKeys: [],
        request: null,
        statusRequest: null,
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

    function filterPopoverNode() {
        var panel = panelNode();
        return panel ? panel.querySelector('.stic-rrp-filter-popover') : null;
    }

    function filterButtonNode() {
        var panel = panelNode();
        return panel ? panel.querySelector('.stic-rrp-tool-filter') : null;
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
        document.body.classList.toggle('stic-rrp-shrink', shrink && state.open && state.enabled !== false);

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
        var subpanels = document.getElementById('subpanel_list');
        if (!panel) {
            return;
        }
        // Base top from a STABLE reference. The toggle button used to be that
        // reference, but it is hidden while the panel is open (its own close
        // button replaces it), so it can no longer be measured. The navbar
        // height is fixed (76px) and is exactly the panel's initial top.
        var baseTop = 76;
        var toggle = document.getElementById('stic-rrp-toggle');
        if (toggle && toggle.offsetHeight > 0) {
            var measured = toggle.getBoundingClientRect().top;
            if (measured >= 20 && measured <= 400) {
                baseTop = measured;
            }
        }
        state.geomRetries = 0;
        var limit = null;
        if (subpanels) {
            // The panel must end where the bottom subpanels start (document
            // coordinate): that is the hard limit it can never cross.
            limit = subpanels.getBoundingClientRect().top + window.scrollY;
        } else {
            limit = document.documentElement.scrollHeight;
        }
        if (!limit) {
            return;
        }
        // The panel can never be taller than the viewport: otherwise its bottom
        // edge would sit at the end of the detail view and any scroll would
        // immediately slide it out of sight. Capping it to the viewport keeps it
        // "sticky" beside the detail view; the content scrolls inside the panel.
        var viewportHeight = window.innerHeight || document.documentElement.clientHeight || 0;
        var viewportLimit = viewportHeight > 0 ? viewportHeight - PANEL_MARGIN : limit;
        var height = Math.max(200, Math.min(limit - baseTop - PANEL_MARGIN, viewportLimit - baseTop));
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
        // Read the subpanels start live: the detail view can change height after
        // the initial measurement (lazy content, images, tabs), so a cached
        // document coordinate could leave a gap or overlap them.
        var subpanels = document.getElementById('subpanel_list');
        if (subpanels) {
            state.geom.limitDoc = subpanels.getBoundingClientRect().top + window.scrollY;
        }
        var allowedTop = state.geom.limitDoc - state.geom.height - PANEL_MARGIN - window.scrollY;
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
        } else {
            closeFilter();
            hideTooltip();
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
            state.hiddenRelations = Array.isArray(status.hidden_relations)
                ? status.hidden_relations.slice()
                : [];
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
        updateFilterButton([]);
        renderFilterPopover([]);
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
        updateFilterButton([]);
        renderFilterPopover([]);
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
        state.currentPayload = payload;
        hideTooltip();
        var content = contentNode();
        if (!content) {
            return;
        }
        clearNode(content);
        content.setAttribute('data-state', 'ready');

        var relations = payload && payload.relations ? payload.relations : [];
        var timeline = payload && payload.timeline ? payload.timeline : [];

        updateFilterButton(relations);
        renderFilterPopover(relations);

        if (!relations.length && !timeline.length) {
            content.setAttribute('data-state', 'empty');
            content.appendChild(el('div', 'stic-rrp-status stic-rrp-status-empty', lang('LBL_RRP_EMPTY')));
            return;
        }

        var visible = filterTimeline(timeline);
        if (!visible.length) {
            content.setAttribute('data-state', 'empty');
            content.appendChild(el('div', 'stic-rrp-status stic-rrp-status-empty', lang('LBL_RRP_FILTER_EMPTY')));
            var reset = el('a', 'stic-rrp-filter-all stic-rrp-filter-all-empty', lang('LBL_RRP_FILTER_ALL'));
            reset.setAttribute('href', 'javascript:void(0)');
            content.appendChild(reset);
            return;
        }

        content.appendChild(buildTimeline(visible));
    }

    /**
     * Whether a relation (subpanel key) is hidden by the user filter.
     */
    function isHiddenRelation(key) {
        return state.hiddenRelations.indexOf(key) !== -1;
    }

    /**
     * Timeline entries of the relations selected in the user filter.
     */
    function filterTimeline(timeline) {
        return timeline.filter(function (entry) {
            return !isHiddenRelation(entry.subpanel);
        });
    }

    /**
     * Updates the header filter button (shown/total badge). The filter is only
     * useful when more than one relation has records.
     */
    function updateFilterButton(relations) {
        var button = filterButtonNode();
        if (!button) {
            return;
        }
        var total = relations.length;
        var shown = 0;
        for (var i = 0; i < total; i++) {
            if (!isHiddenRelation(relations[i].key)) {
                shown++;
            }
        }
        var available = total > 1;
        button.hidden = !available;
        button.classList.toggle('is-active', available && state.filterOpen);
        button.setAttribute('aria-expanded', (available && state.filterOpen) ? 'true' : 'false');
        // Sync the checkboxes with the persisted user choice (they are rebuilt
        // when the popover re-renders).
        var popover = filterPopoverNode();
        if (popover) {
            var checks = popover.querySelectorAll('.stic-rrp-filter-check');
            for (var c = 0; c < checks.length; c++) {
                checks[c].checked = !isHiddenRelation(checks[c].value);
            }
        }
    }

    /**
     * Renders the filter popover (absolute, under the panel header) so it does
     * not consume vertical space in the timeline.
     */
    function renderFilterPopover(relations) {
        var popover = filterPopoverNode();
        if (!popover) {
            return;
        }
        clearNode(popover);
        var show = state.filterOpen && relations.length > 1;
        popover.hidden = !show;
        if (show) {
            popover.appendChild(buildFilter(relations));
        }
    }

    /**
     * Relation filter: one checkbox per relation source with records.
     */
    function buildFilter(relations) {
        var box = el('div', 'stic-rrp-filter');

        var head = el('div', 'stic-rrp-filter-head');
        head.appendChild(el('span', 'stic-rrp-filter-title', lang('LBL_RRP_FILTER_HINT')));
        var all = el('a', 'stic-rrp-filter-all', lang('LBL_RRP_FILTER_ALL'));
        all.setAttribute('href', 'javascript:void(0)');
        head.appendChild(all);
        var none = el('a', 'stic-rrp-filter-none', lang('LBL_RRP_FILTER_NONE', 'Select none'));
        none.setAttribute('href', 'javascript:void(0)');
        head.appendChild(none);
        box.appendChild(head);

        for (var i = 0; i < relations.length; i++) {
            var relation = relations[i];
            var item = el('label', 'stic-rrp-filter-item');
            var check = document.createElement('input');
            check.type = 'checkbox';
            check.className = 'stic-rrp-filter-check';
            check.value = relation.key || '';
            check.checked = !isHiddenRelation(relation.key);
            item.appendChild(check);
            item.appendChild(iconSpan(relation.icon, 'suitepicon-module-default'));
            item.appendChild(el('span', 'stic-rrp-filter-name', relation.title || relation.key || ''));
            item.appendChild(el('span', 'stic-rrp-filter-count', String(relation.count || 0)));
            box.appendChild(item);
        }
        return box;
    }

    /**
     * ONE flat chronology: no sections, no grouping by subpanel.
     */
    function buildTimeline(entries) {
        var list = el('ul', 'stic-rrp-timeline');
        for (var i = 0; i < entries.length; i++) {
            list.appendChild(buildEntry(entries[i]));
        }
        return list;
    }

    function buildEntry(record) {
        var node = el('li', 'stic-rrp-entry');
        node.setAttribute('data-subpanel', record.subpanel || '');
        node.__rrpRecord = record;

        var details = Array.isArray(record.details) ? record.details : [];

        // Left column: module icon (the tooltip is triggered on hover/focus).
        var side = el('div', 'stic-rrp-entry-side');
        var icon = iconSpan(record.icon, 'suitepicon-module-default');
        icon.classList.add('stic-rrp-entry-icon');
        if (details.length) {
            icon.setAttribute('tabindex', '0');
            icon.setAttribute('role', 'button');
            icon.setAttribute('aria-label', lang('LBL_RRP_INFO', 'Info'));
        }
        side.appendChild(icon);
        node.appendChild(side);

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
        if (record.relation) {
            main.appendChild(el('span', 'stic-rrp-entry-relation', record.relation));
        }
        // Date below the subtitle, full width for the text above it.
        if (record.date_label) {
            main.appendChild(el('span', 'stic-rrp-entry-date', record.date_label));
        }
        node.appendChild(main);

        return node;
    }

    var KNOWN_ICONS = new Set(['suitepicon-action-above','suitepicon-action-add','suitepicon-action-aeat-query','suitepicon-action-alerts-small','suitepicon-action-alerts','suitepicon-action-back','suitepicon-action-batch','suitepicon-action-bug','suitepicon-action-calendar','suitepicon-action-call','suitepicon-action-cancel','suitepicon-action-case','suitepicon-action-chart','suitepicon-action-check','suitepicon-action-clear','suitepicon-action-clock','suitepicon-action-close','suitepicon-action-cloud','suitepicon-action-collapse','suitepicon-action-color','suitepicon-action-comment','suitepicon-action-compare','suitepicon-action-configure','suitepicon-action-connector','suitepicon-action-contact','suitepicon-action-content','suitepicon-action-copy','suitepicon-action-create','suitepicon-action-customize','suitepicon-action-dashboard','suitepicon-action-data','suitepicon-action-date','suitepicon-action-delete','suitepicon-action-details','suitepicon-action-document','suitepicon-action-download','suitepicon-action-duplicate','suitepicon-action-edit','suitepicon-action-email','suitepicon-action-expand','suitepicon-action-export','suitepicon-action-file','suitepicon-action-filter','suitepicon-action-find','suitepicon-action-flag','suitepicon-action-folder','suitepicon-action-forward','suitepicon-action-fullscreen','suitepicon-action-graph','suitepicon-action-grid','suitepicon-action-group','suitepicon-action-help','suitepicon-action-history','suitepicon-action-home','suitepicon-action-image','suitepicon-action-import','suitepicon-action-info','suitepicon-action-key','suitepicon-action-language','suitepicon-action-link','suitepicon-action-list','suitepicon-action-location','suitepicon-action-lock','suitepicon-action-log','suitepicon-action-mail','suitepicon-action-map','suitepicon-action-meeting','suitepicon-action-menu','suitepicon-action-message','suitepicon-action-minus','suitepicon-action-mobile','suitepicon-action-money','suitepicon-action-more','suitepicon-action-move','suitepicon-action-new','suitepicon-action-next','suitepicon-action-note','suitepicon-action-notification','suitepicon-action-open','suitepicon-action-opportunity','suitepicon-action-options','suitepicon-action-order','suitepicon-action-page','suitepicon-action-password','suitepicon-action-pdf','suitepicon-action-phone','suitepicon-action-plus','suitepicon-action-preview','suitepicon-action-print','suitepicon-action-process','suitepicon-action-profile','suitepicon-action-project','suitepicon-action-properties','suitepicon-action-question','suitepicon-action-quote','suitepicon-action-rebuild','suitepicon-action-refresh','suitepicon-action-relate','suitepicon-action-remove','suitepicon-action-rename','suitepicon-action-report','suitepicon-action-reset','suitepicon-action-restore','suitepicon-action-role','suitepicon-action-save','suitepicon-action-search','suitepicon-action-security','suitepicon-action-select','suitepicon-action-send','suitepicon-action-settings','suitepicon-action-share','suitepicon-action-show','suitepicon-action-sort','suitepicon-action-star','suitepicon-action-start','suitepicon-action-stop','suitepicon-action-subpanel','suitepicon-action-sync','suitepicon-action-table','suitepicon-action-tag','suitepicon-action-task','suitepicon-action-team','suitepicon-action-template','suitepicon-action-test','suitepicon-action-theme','suitepicon-action-timeline','suitepicon-action-time','suitepicon-action-trash','suitepicon-action-unlock','suitepicon-action-upload','suitepicon-action-user','suitepicon-action-view','suitepicon-action-warning','suitepicon-action-web','suitepicon-action-workflow','suitepicon-action-zoom','suitepicon-admin-aclroles','suitepicon-admin-address-cache','suitepicon-admin-aobh-businesshours','suitepicon-admin-aod-settings','suitepicon-admin-aop-settings','suitepicon-admin-aos-settings','suitepicon-admin-backups','suitepicon-admin-campaign-email-settings','suitepicon-admin-connectors','suitepicon-admin-currencies','suitepicon-admin-diagnostic','suitepicon-admin-display-modules-and-subpanels','suitepicon-admin-donate','suitepicon-admin-dropdown-editor','suitepicon-admin-email-queue','suitepicon-admin-email-settings','suitepicon-admin-geocode-addresses','suitepicon-admin-geocoded-counts','suitepicon-admin-geocoding-test','suitepicon-admin-global-search','suitepicon-admin-google-maps-settings','suitepicon-admin-history-subpanel','suitepicon-admin-import','suitepicon-admin-inbound-email','suitepicon-admin-languages','suitepicon-admin-locale','suitepicon-admin-module-builder','suitepicon-admin-module-loader','suitepicon-admin-module-trackers','suitepicon-admin-oauth','suitepicon-admin-oauth-keys','suitepicon-admin-outbound-email','suitepicon-admin-password','suitepicon-admin-pdf-settings','suitepicon-admin-rebuild','suitepicon-admin-releases','suitepicon-admin-rename-modules','suitepicon-admin-repair','suitepicon-admin-roles','suitepicon-admin-scheduler','suitepicon-admin-security','suitepicon-admin-security-suite-group-management','suitepicon-admin-security-suite-settings','suitepicon-admin-stic-configure-main-menu','suitepicon-admin-stic-custom-views','suitepicon-admin-stic-messages','suitepicon-admin-stic-portal','suitepicon-admin-stic-recycle-bin','suitepicon-admin-stic-related-panel','suitepicon-admin-stic-settings','suitepicon-admin-stic-validation-actions','suitepicon-admin-studio','suitepicon-admin-system-settings','suitepicon-admin-themes','suitepicon-admin-upgrade-wizard','suitepicon-admin-user-management','suitepicon-admin-workflow','suitepicon-module-accounts','suitepicon-module-aclroles','suitepicon-module-aos-contracts','suitepicon-module-aos-invoices','suitepicon-module-aos-pdf-templates','suitepicon-module-aos-quotes','suitepicon-module-aow-workflow','suitepicon-module-bugs','suitepicon-module-calendar','suitepicon-module-calls','suitepicon-module-campaigns','suitepicon-module-campaignlog','suitepicon-module-campaigntrackers','suitepicon-module-cases','suitepicon-module-companyc','suitepicon-module-connectors','suitepicon-module-contacts','suitepicon-module-dashboards','suitepicon-module-documents','suitepicon-module-documentrevisions','suitepicon-module-dha-plantillasdocumentos','suitepicon-module-emailmarketing','suitepicon-module-emails','suitepicon-module-emailman','suitepicon-module-emailtemplates','suitepicon-module-employees','suitepicon-module-fp-events','suitepicon-module-fp-event-locations','suitepicon-module-inboundemail','suitepicon-module-kreports','suitepicon-module-leads','suitepicon-module-meetings','suitepicon-module-notes','suitepicon-module-oauth2clients','suitepicon-module-oauth2tokens','suitepicon-module-opportunities','suitepicon-module-outboundemailaccounts','suitepicon-module-project','suitepicon-module-projecttask','suitepicon-module-prospects','suitepicon-module-prospectlists','suitepicon-module-reports','suitepicon-module-roles','suitepicon-module-schedulers','suitepicon-module-schedulersjobs','suitepicon-module-securitygroups','suitepicon-module-stic-accounts-relationships','suitepicon-module-stic-assessments','suitepicon-module-stic-assets','suitepicon-module-stic-attendances','suitepicon-module-stic-awf-deferred-tickets','suitepicon-module-stic-awf-forms','suitepicon-module-stic-awf-incoming-events','suitepicon-module-stic-awf-links','suitepicon-module-stic-awf-response-details','suitepicon-module-stic-awf-responses','suitepicon-module-stic-bookings','suitepicon-module-stic-centers','suitepicon-module-stic-contacts-relationships','suitepicon-module-stic-conversations','suitepicon-module-stic-custom-views','suitepicon-module-stic-custom-view-customizations','suitepicon-module-stic-events','suitepicon-module-stic-families','suitepicon-module-stic-financial-products','suitepicon-module-stic-followups','suitepicon-module-stic-goals','suitepicon-module-stic-grants','suitepicon-module-stic-group-opportunities','suitepicon-module-stic-incorpora-locations','suitepicon-module-stic-job-applications','suitepicon-module-stic-job-offers','suitepicon-module-stic-journal','suitepicon-module-stic-medication','suitepicon-module-stic-medication-log','suitepicon-module-stic-messages','suitepicon-module-stic-messagesman','suitepicon-module-stic-organizational-environment','suitepicon-module-stic-payment-commitments','suitepicon-module-stic-payments','suitepicon-module-stic-payments-aggregated','suitepicon-module-stic-personal-environment','suitepicon-module-stic-prescription','suitepicon-module-stic-recycle-bin','suitepicon-module-stic-recycle-bin-relationships','suitepicon-module-stic-registrations','suitepicon-module-stic-remittances','suitepicon-module-stic-resources','suitepicon-module-stic-sepe-actions','suitepicon-module-stic-sepe-files','suitepicon-module-stic-sepe-incidents','suitepicon-module-stic-sessions','suitepicon-module-stic-signature-log','suitepicon-module-stic-signatures','suitepicon-module-stic-signers','suitepicon-module-stic-skills','suitepicon-module-stic-time-tracker','suitepicon-module-stic-training','suitepicon-module-stic-transactions','suitepicon-module-stic-validation-actions','suitepicon-module-stic-work-calendar','suitepicon-module-stic-work-experience','suitepicon-module-surveys','suitepicon-module-surveyquestions','suitepicon-module-surveyresponses','suitepicon-module-tasks','suitepicon-module-trackers','suitepicon-module-users']);
    function iconSpan(iconClass, fallbackClass) {
        if (iconClass && !KNOWN_ICONS.has(iconClass)) {
            iconClass = '';
        }
        var span = el('span', 'stic-rrp-icon suitepicon ' + (iconClass || fallbackClass || ''));
        if (!iconClass && fallbackClass) {
            span.className = 'stic-rrp-icon suitepicon ' + fallbackClass;
        }
        return span;
    }

    // -------------------------------------------------------- entry tooltip

    var tooltipNode = null;
    var tooltipHideTimer = null;

    function tooltipElement() {
        if (tooltipNode && document.body.contains(tooltipNode)) {
            return tooltipNode;
        }
        tooltipNode = el('div', 'stic-rrp-tooltip');
        tooltipNode.setAttribute('role', 'tooltip');
        tooltipNode.hidden = true;
        document.body.appendChild(tooltipNode);
        return tooltipNode;
    }

    /**
     * Shows a fixed tooltip with the rest of the subpanel columns of a record.
     * Triggered by the entry module icon or by the small info button.
     */
    function showTooltip(trigger) {
        if (!trigger) {
            return;
        }
        var entry = trigger.closest ? trigger.closest('.stic-rrp-entry') : null;
        var record = entry ? entry.__rrpRecord : null;
        var details = (record && Array.isArray(record.details)) ? record.details : [];
        if (!record || !details.length) {
            return;
        }
        if (tooltipHideTimer) {
            window.clearTimeout(tooltipHideTimer);
            tooltipHideTimer = null;
        }

        var tip = tooltipElement();
        clearNode(tip);
        tip.appendChild(el('div', 'stic-rrp-tooltip-title', record.title || record.id || ''));
        if (record.relation) {
            tip.appendChild(el('div', 'stic-rrp-tooltip-relation', record.relation));
        }
        var list = el('dl', 'stic-rrp-tooltip-details');
        for (var i = 0; i < details.length; i++) {
            list.appendChild(el('dt', null, details[i].label || ''));
            list.appendChild(el('dd', null, details[i].value || ''));
        }
        tip.appendChild(list);

        // Measure first (hidden visibility avoids a flash at 0,0), then place
        // it on the left of the trigger because the panel is on the right.
        tip.hidden = false;
        tip.style.visibility = 'hidden';
        var rect = trigger.getBoundingClientRect();
        var tipRect = tip.getBoundingClientRect();
        var margin = 8;
        var left = rect.left - tipRect.width - margin;
        if (left < margin) {
            left = Math.min(window.innerWidth - tipRect.width - margin, rect.right + margin);
        }
        if (left < margin) {
            left = margin;
        }
        var top = rect.top + (rect.height / 2) - (tipRect.height / 2);
        top = Math.max(margin, Math.min(top, window.innerHeight - tipRect.height - margin));
        tip.style.left = Math.round(left) + 'px';
        tip.style.top = Math.round(top) + 'px';
        tip.style.visibility = '';
    }

    function hideTooltip() {
        if (tooltipHideTimer) {
            window.clearTimeout(tooltipHideTimer);
            tooltipHideTimer = null;
        }
        if (tooltipNode) {
            tooltipNode.hidden = true;
            clearNode(tooltipNode);
        }
    }

    function scheduleHideTooltip() {
        if (tooltipHideTimer) {
            window.clearTimeout(tooltipHideTimer);
        }
        tooltipHideTimer = window.setTimeout(hideTooltip, 120);
    }

    // ----------------------------------------------------------- user filter

    /**
     * Toggles the relation filter popover (rendered from the cached payload).
     */
    function toggleFilter(event) {
        event.preventDefault();
        var relations = state.currentPayload && state.currentPayload.relations
            ? state.currentPayload.relations
            : [];
        if (relations.length < 2) {
            return;
        }
        state.filterOpen = !state.filterOpen;
        updateFilterButton(relations);
        renderFilterPopover(relations);
    }

    /**
     * Closes the filter popover (if open).
     */
    function closeFilter() {
        if (!state.filterOpen) {
            return;
        }
        var relations = state.currentPayload && state.currentPayload.relations
            ? state.currentPayload.relations
            : [];
        state.filterOpen = false;
        updateFilterButton(relations);
        renderFilterPopover(relations);
    }

    /**
     * Rebuilds the hidden-relation list from the checkboxes and persists it as a
     * user preference. The timeline is re-rendered in place.
     */
    function changeFilter(event) {
        var check = event.target.closest ? event.target.closest('.stic-rrp-filter-check') : null;
        if (!check) {
            return;
        }
        // The checkboxes live in the header popover since the filter moved
        // out of the timeline content.
        var popover = filterPopoverNode();
        var content = contentNode();
        var hidden = [];
        var checks = popover
            ? popover.querySelectorAll('.stic-rrp-filter-check')
            : (content ? content.querySelectorAll('.stic-rrp-filter-check') : []);
        for (var i = 0; i < checks.length; i++) {
            if (!checks[i].checked && checks[i].value) {
                hidden.push(checks[i].value);
            }
        }
        state.hiddenRelations = hidden;
        saveFilter();
        render(state.currentPayload);
    }

    /**
     * Shows every relation again.
     */
    function showAllRelations(event) {
        if (event) {
            event.preventDefault();
            // Keep the popover open: the outside-click handler runs after this
            // one, when the link has already been replaced by the re-render.
            event.__rrpFilterInteraction = true;
        }
        state.hiddenRelations = [];
        saveFilter();
        render(state.currentPayload);
    }

    /**
     * Hides every relation (the timeline shows the empty state; the popover
     * stays open with its per-relation checkboxes and the empty-state "select
     * all" link to come back).
     */
    function showNoRelations(event) {
        if (event) {
            event.preventDefault();
            event.__rrpFilterInteraction = true;
        }
        var relations = state.currentPayload && state.currentPayload.relations
            ? state.currentPayload.relations
            : [];
        var hidden = [];
        for (var i = 0; i < relations.length; i++) {
            if (relations[i].key) {
                hidden.push(String(relations[i].key));
            }
        }
        state.hiddenRelations = hidden;
        saveFilter();
        render(state.currentPayload);
    }

    /**
     * Persists the user relation filter (per module) in the user preferences.
     */
    function saveFilter() {
        var context = currentContext();
        if (!context.module) {
            return;
        }
        fetch(endpointUrl({ panel_action: 'saveFilter', module: context.module }), {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ hidden: state.hiddenRelations })
        }).catch(function () { });
    }

    // ---------------------------------------------------------------- events

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
        doc.on('click.rrp', '.stic-rrp-tool-filter', toggleFilter);
        doc.on('click.rrp', '.stic-rrp-filter-all', showAllRelations);
        doc.on('click.rrp', '.stic-rrp-filter-none', showNoRelations);
        doc.on('change.rrp', '.stic-rrp-filter-check', changeFilter);
        doc.on('keydown.rrp', function (event) {
            if (event.key !== 'Escape') {
                return;
            }
            if (state.filterOpen) {
                closeFilter();
            } else if (state.open) {
                setOpen(false);
            }
        });

        // Close the filter when clicking outside its popover and the button
        doc.on('click.rrp', function (event) {
            hideTooltip();
            if (event.__rrpFilterInteraction) {
                return;
            }
            if (!state.filterOpen) {
                return;
            }
            var target = jQuery(event.target);
            if (target.closest('.stic-rrp-filter-popover, .stic-rrp-tool-filter').length) {
                return;
            }
            closeFilter();
        });

        // Tooltip with the rest of the subpanel columns (entry icon + info "i")
        doc.on('mouseover.rrp', '.stic-rrp-entry-icon, .stic-rrp-entry-info', function (event) {
            if (event.relatedTarget && this.contains && this.contains(event.relatedTarget)) {
                return;
            }
            showTooltip(this);
        });
        doc.on('mouseout.rrp', '.stic-rrp-entry-icon, .stic-rrp-entry-info', function (event) {
            if (event.relatedTarget && this.contains && this.contains(event.relatedTarget)) {
                return;
            }
            scheduleHideTooltip();
        });
        doc.on('focusin.rrp', '.stic-rrp-entry-icon, .stic-rrp-entry-info', function () {
            showTooltip(this);
        });
        doc.on('focusout.rrp', '.stic-rrp-entry-icon, .stic-rrp-entry-info', function () {
            scheduleHideTooltip();
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
        // Activating the pin puts the panel in "pinned mode": always open,
        // pinned and with the detail view narrowed (shrink on).
        if (key === 'pinned' && prefs.pinned) {
            prefs.shrink = true;
            state.open = true;
        }
        state.userPrefs = prefs;
        applyVisualState();
        schedulePinnedLayout();

        var context = currentContext();
        var body = 'module=' + encodeURIComponent(context.module);
        body += '&' + key + '=' + (prefs[key] ? '1' : '0');
        if (key === 'pinned' && prefs.pinned) {
            body += '&shrink=1';
        }
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
            hideTooltip();
        }, { passive: true, capture: true });
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
                closeFilter();
                hideTooltip();
            }
        }
    }

    function init() {
        var panel = panelNode();
        if (!panel || isPrintRequest()) {
            return;
        }
        bindEvents();
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
                // Pinned mode: panel always open, pinned and with shrink on
                state.open = true;
                if (state.userPrefs.shrink === null) {
                    state.userPrefs.shrink = true;
                }
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
            jQuery(window).off('resize.rrp').on('resize.rrp', function () {
                hideTooltip();
                schedulePinnedLayout();
            });
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
