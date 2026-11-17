/**
 * This file is part of SinergiaCRM.
 * SinergiaCRM is a work developed by SinergiaTIC Association, based on SuiteCRM.
 * Copyright (C) 2013 - 2026 SinergiaTIC Association
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
 * STICSelects
 * Global enhancement for all the <select> dropdowns in SinergiaCRM.
 *
 * Two independent features are provided:
 * 1. ALPHETICAL ORDER
 *    All dropdown options (except those explicitly customized) are sorted
 *    alphabetically so that it is easy to locate a value in any module.
 * 2. SEARCH BOX
 *    The <select> keeps its native look (it is the same control, with the same
 *    style, as any other dropdown). When it is opened a list with a search box
 *    is shown instead of the native list. It is granted automatically to lists
 *    with many options (threshold configurable in Administration / System
 *    Settings), can be forced with the class "stic-searchable-select"
 *    (same behaviour as the field selectors of the advanced forms -
 *    stic_AWF_Forms), and can be disabled with data-stic-search="false".
 *
 * The automatic alphabetical sort skips the controls in which the order is
 * already customized or semantic:
 * - single-select listboxes (size > 1). Multi-selects ARE sorted: moving the
 *   <option> nodes preserves the selected values.
 * - date and time combos
 * - selectize, select2, chosen and other widget-enhanced controls
 * - dropdown lists defined by the administrator in custom/include/language
 *   (created or edited through the Dropdown Editor of Studio / ModuleBuilder,
 *   including its "sort ascending / descending" buttons): these keep the
 *   order defined by the administrator. The server marks them with
 *   data-stic-sort="false" in the edit, search and mass update views
 *   (see EditView2, SearchForm2, MassUpdate and the stic_custom_dropdown
 *   Smarty plugin registered in Sugar_Smarty). Core dropdown lists are
 *   always sorted alphabetically
 * - explicit opt-outs: <select data-stic-sort="false"> or an ancestor with
 *   the same attribute / the class "stic-no-sort", and
 *   <select data-stic-search="false"> for the search box
 */
(function (window, document) {
    'use strict';

    var STICSelects = window.STICSelects || (window.STICSelects = {});

    var CURRENT_LANG = 'es';
    var SORT_CONTEXT_RE = /(selectize|selectized|select2|chzn-|chosen|no-selectize|stic-no-sort)/i;
    var WIDGET_CONTAINER_RE = /(selectize-control|select2-container|chosen-container)/i;
    var DATE_PART_RE = /(^|[-_])?(hour|hours|minute|minutes|seconds?|meridiem|ampm|secs?|mins?)$/i;
    /**
     * Number of options from which a dropdown automatically gets the search
     * box. Lists with fewer options stay native unless they are marked with
     * the class "stic-searchable-select". Configurable in Administration /
     * System Settings (sugar_config key "stic_search_selects_threshold",
     * exposed to JS as SUGAR.config.stic_search_selects_threshold):
     * - unset / not numeric: 20 (default)
     * - 0: the search box is granted to every (non-excluded) dropdown
     * - N > 0: granted when the list has N options or more
     * - negative (-1): the search box is never granted (not even to the
     *   controls explicitly marked with the class)
     * Mirror exclusions also live in SticSelectize.js (unSelectize list):
     * keep both lists in sync.
     */
    var searchThreshold = 20;

    function resolveSearchThreshold() {
        var value = null;
        try {
            if (window.SUGAR && window.SUGAR.config) {
                value = window.SUGAR.config.stic_search_selects_threshold;
            }
        } catch (e) { }
        var number = parseInt(value, 10);
        return isNaN(number) ? 20 : number;
    }

    // Options with this value are a SuiteCRM action, not data: the mass update
    // "clear field" entry (include/utils.php adds it with empty text). It is
    // pinned to the top, right after the empty values, and in the search box it
    // gets a proper label instead of the internal marker name (the option text
    // is empty, so without this the raw marker would be shown).
    var MASSUPDATE_CLEAR_MARKER = '__SugarMassUpdateClearField__';

    /**
     * Language used to sort the options alphabetically: the language selected
     * at login time. SuiteCRM loads the strings of the current session as
     * JavaScript caches named cache/jsLanguage/<language>.js (global) and
     * cache/jsLanguage/<module>/<language>.js (per module), so the code is read
     * from the src of the loaded script tags; it never defaults to a fixed
     * locale. Falls back to the browser language only as a last resort.
     */
    function resolveLanguage() {
        var code = '';
        try {
            var scripts = document && document.getElementsByTagName('script');
            // Matches both cache/jsLanguage/<lang>.js and cache/jsLanguage/<module>/<lang>.js.
            var reLangScript = /\/cache\/jsLanguage\/(?:[A-Za-z0-9_]+\/)?([A-Za-z_]+)\.js(\?.*)?$/;
            for (var i = 0; scripts && i < scripts.length; i++) {
                var src = norm(scripts[i].getAttribute ? scripts[i].getAttribute('src') : '');
                var m = reLangScript.exec(src);
                if (m && m[1]) {
                    code = m[1];
                    break;
                }
            }
        } catch (e) { }
        if (!code && typeof navigator !== 'undefined' && navigator.language) {
            code = navigator.language;
        }
        return code ? code.replace(/_/g, '-') : '';
    }

    var selfSorting = false;

    /**
     * Helpers - ES5 friendly
     */
    function norm(value) {
        return String(value === null || value === undefined ? '' : value);
    }

    /**
     * Returns an application string in the language of the session (the same
     * source used by the rest of the STIC scripts), with a fallback for the
     * environments where the JS language cache is not loaded.
     */
    function languageString(key, fallback) {
        try {
            if (window.SUGAR && window.SUGAR.language && typeof window.SUGAR.language.get === 'function') {
                var value = window.SUGAR.language.get('app_strings', key);
                if (typeof value === 'string' && value !== '') {
                    return value;
                }
            }
        } catch (e) { }
        return fallback;
    }

    /** Counter to build unique ids for the search inputs (label association). */
    var searchableCounter = 0;

    function trimText(value) {
        return norm(value).replace(/\s+/g, ' ').replace(/^\s+|\s+$/g, '');
    }

    function textOf(option) {
        var own = option && option.children && option.children.length
            ? option.children[option.children.length - 1].textContent
            : (option ? option.text : '');
        return trimText(own || (option && option.value));
    }

    function isEnabledByOption() {
        var html = document && document.documentElement;
        return !(html && html.getAttribute('data-stic-sort-selects') === 'false');
    }

    /**
     * Comparator used to put options in alphabetical (and natural numeric) order.
     * Options with an empty value are pinned to the top (placeholder behaviour).
     */
    function cmpOptions(a, b) {
        var av = norm(a.value), bv = norm(b.value);
        var at = textOf(a), bt = textOf(b);

        if (av === '' && bv !== '') {
            return -1;
        }
        if (bv === '' && av !== '') {
            return 1;
        }
        // The mass update "clear field" action goes right after the empties.
        if (av === MASSUPDATE_CLEAR_MARKER && bv !== MASSUPDATE_CLEAR_MARKER) {
            return -1;
        }
        if (bv === MASSUPDATE_CLEAR_MARKER && av !== MASSUPDATE_CLEAR_MARKER) {
            return 1;
        }
        if (at === '' && bt !== '') {
            return -1;
        }
        if (bt === '' && at !== '') {
            return 1;
        }

        try {
            return at.localeCompare(bt, CURRENT_LANG || undefined, {
                sensitivity: 'base',
                numeric: true,
                ignorePunctuation: true
            });
        } catch (e) {
            var al = at.toLowerCase(), bl = bt.toLowerCase();
            if (al < bl) {
                return -1;
            }
            if (al > bl) {
                return 1;
            }
            return 0;
        }
    }

    function sameOrder(original, sorted) {
        if (original.length !== sorted.length) {
            return false;
        }
        for (var i = 0; i < original.length; i++) {
            if (original[i] !== sorted[i]) {
                return false;
            }
        }
        return true;
    }

    /**
     * Whether a select must be skipped because of an existing customization.
     * This is the source of the exclusions; the Selectize-side mirror list
     * lives in SticSelectize.js (unSelectize): keep both lists in sync.
     */
    function shouldSkip(select) {
        if (!select || !select.tagName || select.tagName.toUpperCase() !== 'SELECT') {
            return true;
        }
        if (!select.options || select.options.length <= 1) {
            return true;
        }
        // Multi-selects are sorted too: moving <option> nodes preserves the
        // selected values. Only single-select listboxes (size > 1) keep their
        // defined order.
        if (!select.multiple && select.size && parseInt(select.size, 10) > 1) {
            return true;
        }
        if (document.activeElement === select) {
            return true;
        }
        if (select.getAttribute('data-stic-sort') === 'false') {
            return true;
        }

        var name = norm(select.name) + ' ' + norm(select.id);
        if (/datetimecombo/i.test(name)) {
            return true;
        }
        if (/_range_choice/i.test(name)) {
            return true;
        }
        // Studio/ModuleBuilder default-value selectors for enums: keep the
        // order returned by dropdownChanged() (custom order preserved).
        if (/^default(\[\])?$/i.test(trimText(select.name).replace(/\s+/g, '')) ||
            /^default(\[\])?$/i.test(trimText(select.id).replace(/\s+/g, ''))) {
            return true;
        }
        if (DATE_PART_RE.test(trimText(name).replace(/\s+/g, '_').toLowerCase())) {
            return true;
        }
        if (SORT_CONTEXT_RE.test(norm(select.className))) {
            return true;
        }

        var node = select.parentNode;
        while (node && node.nodeType === 1) {
            if (node.id === 'inlineSavedSearch') {
                return true;
            }
            if (node.getAttribute && node.getAttribute('data-stic-sort') === 'false') {
                return true;
            }
            var className = norm(node.className);
            if (WIDGET_CONTAINER_RE.test(className) || /datetimecombo/i.test(className)) {
                return true;
            }
            node = node.parentNode;
        }
        return false;
    }

    /**
     * Sorts the <option> elements of a parent (a <select> or an <optgroup>).
     * It detects whether the order has changed to avoid unnecessary DOM
     * mutations (and therefore uncontrolled MutationObserver feedback loops).
     */
    function sortOptions(parent) {
        var children = parent.children;
        var options = [];
        for (var i = 0; i < children.length; i++) {
            if (children[i].tagName === 'OPTION') {
                options.push(children[i]);
            }
        }
        if (options.length < 2) {
            return false;
        }
        var sorted = options.slice();
        sorted.sort(cmpOptions);
        if (sameOrder(options, sorted)) {
            return false;
        }
        for (var j = 0; j < sorted.length; j++) {
            parent.appendChild(sorted[j]);
        }
        return true;
    }

    /**
     * Sorts one <select> preserving the optgroup structure.
     */
    function sortSelect(select) {
        if (!isEnabledByOption() || shouldSkip(select)) {
            return;
        }
        var optgroups = select.querySelectorAll('optgroup');
        if (optgroups.length) {
            for (var i = 0; i < optgroups.length; i++) {
                sortOptions(optgroups[i]);
            }
            // Optional bare options (without optgroup) are sorted and kept before the first group.
            var bare = [];
            for (var j = 0; j < select.children.length; j++) {
                if (select.children[j].tagName === 'OPTION') {
                    bare.push(select.children[j]);
                }
            }
            if (bare.length > 1) {
                var sortedBare = bare.slice();
                sortedBare.sort(cmpOptions);
                if (!sameOrder(bare, sortedBare)) {
                    var anchor = optgroups[0];
                    for (var k = 0; k < sortedBare.length; k++) {
                        select.insertBefore(sortedBare[k], anchor);
                    }
                }
            }
        } else {
            sortOptions(select);
        }
        select.setAttribute('data-stic-sorted', '1');
    }

    /**
     * Sorts all the dropdowns contained in root.
     */
    function sortAll(root) {
        if (!isEnabledByOption()) {
            return;
        }
        var selects = (root || document).querySelectorAll('select');
        selfSorting = true;
        try {
            for (var i = 0; i < selects.length; i++) {
                sortSelect(selects[i]);
            }
        } finally {
            setTimeout(function () {
                selfSorting = false;
            }, 0);
        }
    }

    /**
     * Fires a native "change" event on an element so that existing onchange
     * handlers (populateVariables, onchange attributes...) keep working.
     */
    function fireChange(el) {
        var ev;
        try {
            ev = new Event('change', { bubbles: true });
        } catch (e) {
            ev = document.createEvent('Event');
            ev.initEvent('change', true, false);
        }
        el.dispatchEvent(ev);
    }

    /**
     * Resolves the accent color used by the current theme from the border of
     * the control itself (each theme / SticCustom palette styles the selects
     * with its own color). It is later applied through CSS custom properties
     * so the search list matches the active theme.
     */
    function themeAccent(select) {
        var color = '';
        try {
            var cs = window.getComputedStyle ? window.getComputedStyle(select) : null;
            if (cs) {
                color = cs.borderTopColor || '';
            }
        } catch (e) { }
        if (!color || color === 'transparent' || color === 'rgba(0, 0, 0, 0)') {
            color = '#b5bc31';
        }
        return color;
    }

    function themeRgb(color) {
        var m = /^#?([0-9a-f]{6})$/i.exec(norm(color).replace(/\s+/g, ''));
        if (m) {
            var n = parseInt(m[1], 16);
            return [(n >> 16) & 255, (n >> 8) & 255, n & 255].join(', ');
        }
        var parts = norm(color).match(/\d+(\.\d+)?/g);
        return (parts && parts.length >= 3) ? [parts[0], parts[1], parts[2]].join(', ') : '';
    }

    function themeHover(color) {
        var rgb = themeRgb(color);
        return rgb ? 'rgba(' + rgb + ', 0.13)' : '#F0F0F0';
    }

    /**
     * Search box for long dropdown lists.
     * The <select> is not modified nor hidden: it keeps the native appearance
     * shared by all the dropdowns. Only when it is opened, the native list is
     * replaced by this list with a search box (positioned below the control).
     */
    function makeSearchable(select) {
        if (!select || select.tagName !== 'SELECT' || select.multiple) {
            return;
        }
        if (select.getAttribute('data-stic-searchable') === '1') {
            return;
        }
        select.setAttribute('data-stic-searchable', '1');
        if (norm(select.className).indexOf('stic-searchable-select') === -1) {
            select.className = trimText(norm(select.className) + ' stic-searchable-select');
        }

        var dropdown = document.createElement('div');
        dropdown.className = 'stic-search-select__dropdown';
        dropdown.setAttribute('role', 'listbox');

        var searchInput = document.createElement('input');
        searchInput.type = 'text';
        searchInput.className = 'stic-search-select__search';
        searchInput.autocomplete = 'off';
        searchInput.placeholder = select.getAttribute('data-search-placeholder')
            || languageString('LBL_STIC_SEARCH_SELECT_PLACEHOLDER', 'Escribe para buscar...');

        // Associated (visually hidden) label, for accessibility.
        var inputId = 'stic-search-select-input-' + (++searchableCounter);
        searchInput.id = inputId;
        var searchLabel = document.createElement('label');
        searchLabel.className = 'stic-search-select__srlabel';
        searchLabel.setAttribute('for', inputId);
        searchLabel.textContent = languageString('LBL_STIC_SEARCH_SELECT_INPUT_LABEL', 'Buscar en la lista');

        var list = document.createElement('ul');
        list.className = 'stic-search-select__list';

        var emptyMsg = document.createElement('div');
        emptyMsg.className = 'stic-search-select__empty';
        emptyMsg.textContent = select.getAttribute('data-empty-text')
            || languageString('LBL_STIC_SEARCH_SELECT_NO_RESULTS', 'No se encontró nada');

        dropdown.appendChild(searchLabel);
        dropdown.appendChild(searchInput);
        dropdown.appendChild(list);
        dropdown.appendChild(emptyMsg);

        // Accessibility state of the control that opens the search list.
        select.setAttribute('aria-haspopup', 'listbox');
        select.setAttribute('aria-expanded', 'false');

        // Adapt the list to the active theme (accent from the control border).
        var accent = themeAccent(select);
        dropdown.style.setProperty('--stic-accent', accent);
        dropdown.style.setProperty('--stic-hover', themeHover(accent));
        var accentRgb = themeRgb(accent);
        dropdown.style.setProperty('--stic-selected', accentRgb ? 'rgba(' + accentRgb + ', 0.25)' : '#E4E8A8');

        (document.body || document.documentElement).appendChild(dropdown);

        var isOpen = false;
        var filterText = '';
        var activeIndex = -1;

        function optionEntries() {
            var entries = [];
            var clearLabel = languageString('LBL_STIC_SEARCH_SELECT_MASSUPDATE_CLEAR', 'Vaciar el valor');
            // Blank options ("'' => ''" entries) are shown with the application
            // "none" string so they are identifiable in the search list
            var noneLabel = languageString('LBL_NONE', '—');
            var push = function (option, groupLabel) {
                var value = norm(option.value);
                var text = (value === MASSUPDATE_CLEAR_MARKER)
                    ? clearLabel
                    : textOf(option);
                var isEmpty = text === '';
                var display;
                if (isEmpty && value === '') {
                    display = noneLabel;
                } else if (isEmpty) {
                    // Option with a value but no label (e.g. a field without a
                    // translatable vardef): never show the raw internal value,
                    // just a neutral dash, like the native blank row
                    display = '—';
                } else {
                    display = text;
                }
                entries.push({
                    value: value,
                    label: (groupLabel ? groupLabel + ' — ' : '') + display,
                    // Original text for sorting: blank options stay pinned to
                    // the top, exactly where the native list shows them
                    sortText: text,
                    search: (display + ' ' + groupLabel + ' ' + value).toLowerCase(),
                    disabled: !!option.disabled,
                    isEmpty: isEmpty
                });
            };
            var groups = select.querySelectorAll('optgroup');
            for (var g = 0; g < groups.length; g++) {
                var groupLabel = groups[g].getAttribute('label') || norm(groups[g].label);
                var groupOptions = groups[g].options;
                for (var i = 0; i < groupOptions.length; i++) {
                    push(groupOptions[i], trimText(groupLabel));
                }
            }
            for (var j = 0; j < select.options.length; j++) {
                if (select.options[j].parentNode === select) {
                    push(select.options[j], null);
                }
            }
            return entries;
        }

        function acceptable(entry) {
            return !filterText || entry.search.indexOf(filterText) !== -1;
        }

        function renderItems() {
            list.innerHTML = '';
            emptyMsg.style.display = 'none';
            activeIndex = -1;

            var entries = optionEntries();
            // Respect the defined order of custom/semantic dropdowns
            // (data-stic-sort="false"): keep the source order in the search list.
            if (select.getAttribute('data-stic-sort') !== 'false') {
                entries.sort(function (a, b) {
                    return cmpOptions({ value: a.value, text: a.sortText }, { value: b.value, text: b.sortText });
                });
            }

            var visible = [];
            for (var i = 0; i < entries.length; i++) {
                if (acceptable(entries[i])) {
                    visible.push(entries[i]);
                }
            }

            var selectedIndex = -1;
            var currentValue = norm(select.value);
            for (var j = 0; j < visible.length; j++) {
                (function (entry, index) {
                    var li = document.createElement('li');
                    li.className = 'stic-search-select__item';
                    li.setAttribute('data-value', entry.value);
                    li.setAttribute('role', 'option');
                    if (entry.disabled) {
                        li.className += ' is-disabled';
                        li.setAttribute('aria-disabled', 'true');
                    }
                    if (entry.isEmpty) {
                        li.className += ' is-empty';
                    }
                    // Mark the option currently selected in the <select>
                    if (selectedIndex === -1 && entry.value === currentValue) {
                        li.className += ' is-selected';
                        li.setAttribute('aria-selected', 'true');
                        selectedIndex = j;
                    }
                    li.textContent = entry.label;
                    li.addEventListener('mousedown', function (event) {
                        event.preventDefault();
                    });
                    if (!entry.disabled) {
                        li.addEventListener('click', function () {
                            choose(entry.value);
                        });
                        li.addEventListener('mouseenter', function () {
                            setActive(index);
                        });
                    }
                    list.appendChild(li);
                })(visible[j], j);
            }

            if (!visible.length) {
                emptyMsg.style.display = 'block';
            }

            // Make the currently selected option visible when the list opens
            if (selectedIndex >= 0 && list.children[selectedIndex] && !filterText) {
                if (list.children[selectedIndex].scrollIntoView) {
                    list.children[selectedIndex].scrollIntoView({ block: 'nearest' });
                }
            }
        }

        /**
         * Rebuilds the base classes of an item, keeping its state classes
         * (is-disabled, is-empty, is-selected) so that setActive does not
         * wipe them when moving the keyboard highlight.
         */
        function baseClasses(item) {
            var classes = 'stic-search-select__item';
            if (/(^| )is-disabled( |$)/.test(item.className)) {
                classes += ' is-disabled';
            }
            if (/(^| )is-empty( |$)/.test(item.className)) {
                classes += ' is-empty';
            }
            if (/(^| )is-selected( |$)/.test(item.className)) {
                classes += ' is-selected';
            }
            return classes;
        }

        function setActive(index) {
            var items = list.children;
            for (var i = 0; i < items.length; i++) {
                if (i === index) {
                    items[i].className = baseClasses(items[i]) + ' is-active';
                    activeIndex = i;
                    if (items[i].scrollIntoView) {
                        items[i].scrollIntoView({ block: 'nearest' });
                    }
                } else if (/(^| )is-active( |$)/.test(items[i].className)) {
                    items[i].className = baseClasses(items[i]);
                }
            }
        }

        /**
         * Finds the <option> of the select matching a value, to be able to
         * check whether it is disabled before choosing it.
         */
        function findOption(value) {
            for (var i = 0; i < select.options.length; i++) {
                if (norm(select.options[i].value) === value) {
                    return select.options[i];
                }
            }
            return null;
        }

        function choose(value) {
            var option = findOption(value);
            if (!option || option.disabled) {
                return;
            }
            if (select.value !== value) {
                select.value = value;
                fireChange(select);
            }
            close(null, false);
        }

        function position() {
            var rect = select.getBoundingClientRect();
            var width = rect.width > 180 ? rect.width : 180;
            dropdown.style.top = (rect.bottom + 2) + 'px';
            dropdown.style.left = rect.left + 'px';
            dropdown.style.width = width + 'px';
            // Keep it inside the viewport horizontally.
            var right = rect.left + width;
            var viewport = document.documentElement.clientWidth;
            if (right > viewport - 4) {
                dropdown.style.left = Math.max(4, viewport - width - 4) + 'px';
            }
        }

        function open() {
            if (select.disabled) {
                return;
            }
            // Some browsers (e.g. Safari) ignore the mousedown preventDefault
            // and open the native dropdown list as well. Blurring the select
            // closes that native list, so only the search list is visible and
            // it cannot cover or overlap the native options
            if (document.activeElement === select) {
                try {
                    select.blur();
                } catch (e) { }
            }
            isOpen = true;
            select.setAttribute('aria-expanded', 'true');
            renderItems();
            searchInput.value = filterText = '';
            position();
            dropdown.style.display = 'block';
            window.addEventListener('scroll', onScrollClose, true);
            window.addEventListener('resize', onScrollClose);
            setTimeout(function () {
                searchInput.focus();
            }, 0);
        }

        function close(event, keepFocus) {
            if (!isOpen) {
                return;
            }
            isOpen = false;
            select.setAttribute('aria-expanded', 'false');
            dropdown.style.display = 'none';
            window.removeEventListener('scroll', onScrollClose, true);
            window.removeEventListener('resize', onScrollClose);
            if (!keepFocus) {
                try {
                    select.focus();
                } catch (e) { }
            }
        }

        function onScrollClose(event) {
            // Keep the list open while the user scrolls inside it.
            var target = event ? event.target : null;
            if (target && target !== window && target !== document) {
                try {
                    if (dropdown.contains(target)) {
                        return;
                    }
                } catch (e) { }
            }
            close(null, true);
        }

        function moveActive(step) {
            var count = list.children.length;
            if (!count) {
                return;
            }
            var next = activeIndex + step;
            if (next < 0) {
                next = count - 1;
            }
            if (next >= count) {
                next = 0;
            }
            setActive(next);
        }

        function chooseActive() {
            if (activeIndex >= 0 && list.children[activeIndex]) {
                choose(list.children[activeIndex].getAttribute('data-value'));
            }
        }

        // Open instead of the native list, without changing the control look.
        select.addEventListener('mousedown', function (event) {
            if (select.disabled) {
                return;
            }
            event.preventDefault();
            if (isOpen) {
                close(null, false);
            } else {
                open();
            }
        });
        select.addEventListener('keydown', function (event) {
            if (event.key === 'Enter' || event.key === ' ' || event.key === 'ArrowDown' || event.key === 'ArrowUp') {
                event.preventDefault();
                if (isOpen) {
                    searchInput.focus();
                } else {
                    open();
                }
            } else if (event.key === 'Escape' && isOpen) {
                event.preventDefault();
                close(null, false);
            }
        });

        searchInput.addEventListener('keydown', function (event) {
            if (event.key === 'ArrowDown') {
                event.preventDefault();
                moveActive(1);
            } else if (event.key === 'ArrowUp') {
                event.preventDefault();
                moveActive(-1);
            } else if (event.key === 'Enter') {
                event.preventDefault();
                chooseActive();
            } else if (event.key === 'Tab') {
                event.preventDefault();
                close(null, false);
            } else if (event.key === 'Escape') {
                event.preventDefault();
                close(null, false);
            }
        });

        searchInput.addEventListener('input', function () {
            filterText = searchInput.value.toLowerCase();
            renderItems();
        });

        document.addEventListener('click', function (event) {
            if (!isOpen) {
                return;
            }
            if (event.target === select || dropdown.contains(event.target)) {
                return;
            }
            close(null, true);
        });
    }

    /**
     * Whether a select gets the search box.
     * It is granted automatically to lists with many options, and can be
     * forced with the class "stic-searchable-select" regardless of the number
     * of options. Like the alphabetical sort, it never touches customized or
     * widget-enhanced controls.
     */
    function isSearchCandidate(select) {
        if (!select || select.tagName !== 'SELECT') {
            return false;
        }
        if (searchThreshold < 0) {
            // Negative threshold: the search box is disabled everywhere
            return false;
        }
        if (select.getAttribute('data-stic-search') === 'false') {
            return false;
        }
        if (select.multiple || (select.size && parseInt(select.size, 10) > 1)) {
            return false;
        }
        if (/datetimecombo/i.test(norm(select.name) + ' ' + norm(select.id))) {
            return false;
        }
        if (/^default(\[\])?$/i.test(trimText(norm(select.name)).replace(/\s+/g, '')) ||
            /^default(\[\])?$/i.test(trimText(norm(select.id)).replace(/\s+/g, ''))) {
            return false;
        }
        if (SORT_CONTEXT_RE.test(norm(select.className))) {
            return false;
        }

        var explicit = norm(select.className).indexOf('stic-searchable-select') !== -1;
        if (!explicit && select.options.length <= searchThreshold) {
            return false;
        }

        var node = select.parentNode;
        while (node && node.nodeType === 1) {
            if (node.id === 'inlineSavedSearch') {
                return false;
            }
            if (node.getAttribute && node.getAttribute('data-stic-search') === 'false') {
                return false;
            }
            var className = norm(node.className);
            if (WIDGET_CONTAINER_RE.test(className) || /datetimecombo/i.test(className)) {
                return false;
            }
            node = node.parentNode;
        }
        return true;
    }

    /**
     * Searchable selects: field/module lists and any dropdown with many options.
     */
    function searchableAll(root) {
        var selects = (root || document).querySelectorAll('select');
        for (var i = 0; i < selects.length; i++) {
            if (isSearchCandidate(selects[i])) {
                makeSearchable(selects[i]);
            }
        }
    }

    /**
     * Injects the styles for the search list (once).
     * Only the opened list is styled: the closed control is the native select.
     */
    var styleInjected = false;
    function ensureCss() {
        if (styleInjected || !(document && document.head)) {
            return;
        }
        styleInjected = true;
        var style = document.createElement('style');
        style.type = 'text/css';
        style.id = 'stic-search-select-css';
        style.appendChild(document.createTextNode(
            '.stic-search-select__dropdown{position:fixed;z-index:100000;background:#FFFFFF;border:1px solid var(--stic-accent,#b5bc31);border-radius:4px;box-shadow:0 4px 14px rgba(0,0,0,.2);display:none;overflow:hidden;}' +
            '.stic-search-select__search{box-sizing:border-box;width:100%;border:none;border-bottom:1px solid var(--stic-accent,#E6E6E6);background:#FFFFFF;padding:7px 8px;font-size:13px;color:#353535;outline:none;height:auto;margin:0;font-family:inherit;}' +
            '.stic-search-select__list{list-style:none;margin:0;padding:0;max-height:240px;overflow-y:auto;}' +
            '.stic-search-select__item{padding:6px 8px;cursor:pointer;font-size:13px;color:#353535;font-family:inherit;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}' +
            '.stic-search-select__item.is-active,.stic-search-select__item:hover{background:var(--stic-hover,#F0F0F0);}' +
            '.stic-search-select__item.is-empty{font-style:italic;color:#8F8F8F;}' +
            '.stic-search-select__item.is-selected{background:var(--stic-selected,rgba(181,188,49,.25));font-weight:600;}' +
            '.stic-search-select__item.is-selected.is-active,.stic-search-select__item.is-selected:hover{background:var(--stic-selected,rgba(181,188,49,.25));}' +
            '.stic-search-select__empty{padding:10px 8px;color:#999999;font-style:italic;text-align:center;font-size:13px;font-family:inherit;}' +
            '.stic-search-select__item.is-disabled{color:#AAAAAA;cursor:not-allowed;}' +
            '.stic-search-select__item.is-disabled.is-active,.stic-search-select__item.is-disabled:hover{background:transparent;color:#AAAAAA;}' +
            '.stic-search-select__srlabel{position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);white-space:nowrap;border:0;}'
        ));
        document.head.appendChild(style);
    }

    /**
     * Initialization: sort every select and enhance the long ones.
     */
    var observerStarted = false;
    function init() {
        CURRENT_LANG = resolveLanguage();
        searchThreshold = resolveSearchThreshold();
        ensureCss();
        sortAll(document);
        searchableAll(document);

        if (observerStarted || !window.MutationObserver) {
            return;
        }
        observerStarted = true;

        var timer = null;
        var observer = new MutationObserver(function () {
            if (selfSorting) {
                return;
            }
            if (timer) {
                clearTimeout(timer);
            }
            timer = setTimeout(function () {
                timer = null;
                sortAll(document);
                searchableAll(document);
            }, 120);
        });
        observer.observe(document.body || document.documentElement, {
            childList: true,
            subtree: true
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            init();
        });
    } else {
        init();
    }

    // Public API
    STICSelects.sortSelect = sortSelect;
    STICSelects.sortAll = sortAll;
    STICSelects.makeSearchable = makeSearchable;
    STICSelects.searchableAll = searchableAll;
    STICSelects.cmpOptions = cmpOptions;

    window.STICSelects = STICSelects;
})(window, document);