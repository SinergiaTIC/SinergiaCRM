/**
 * This file is part of SinergiaCRM.
 * SinergiaCRM is a work developed by SinergiaTIC Association, based on SuiteCRM.
 * Copyright (C) 2013 - 2026 SinergiaTIC Association
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by
 * the Free Software Foundation.
 *
 * STIC-Custom 20261023 DEV - Related records panel
 * Behaviour of the administration configuration screen:
 *  - module bodies collapse/expand
 *  - subpanel rows are collapsible and draggable (the DOM order is the saved order)
 * See stic-ai-handbook: output/features/related-records-panel
 */

(function () {
    'use strict';

    var container = document.getElementById('rrp-config');
    if (!container || container.__rrpAdminInit) {
        return;
    }
    container.__rrpAdminInit = true;

    // -------------------------------------------------- collapse / expand

    container.addEventListener('click', function (event) {
        // Cancel (top and bottom toolbars)
        var cancel = event.target.closest('.js-rrp-cancel');
        if (cancel) {
            event.preventDefault();
            window.location.href = cancel.getAttribute('data-cancel-url')
                || 'index.php?module=Administration&action=index';
            return;
        }

        // Module body
        var moduleHead = event.target.closest('.stic-rrp-admin-module-head');
        if (moduleHead && !event.target.closest('input')) {
            var moduleNode = moduleHead.closest('.stic-rrp-admin-module');
            var body = moduleNode.querySelector('.stic-rrp-admin-module-body');
            var closed = moduleNode.classList.toggle('is-collapsed');
            if (body) {
                body.style.display = closed ? 'none' : '';
            }
            return;
        }

        // Subpanel config
        var expand = event.target.closest('.stic-rrp-admin-expand');
        if (expand) {
            var row = expand.closest('.stic-rrp-admin-row');
            var config = row.querySelector('.stic-rrp-admin-config');
            var hidden = row.classList.toggle('is-config-open');
            expand.setAttribute('aria-expanded', hidden ? 'true' : 'false');
            if (config) {
                config.style.display = hidden ? '' : 'none';
            }
        }
    });

    // Modules start collapsed
    var modules = container.querySelectorAll('.stic-rrp-admin-module');
    for (var i = 0; i < modules.length; i++) {
        modules[i].classList.add('is-collapsed');
        var body = modules[i].querySelector('.stic-rrp-admin-module-body');
        if (body) {
            body.style.display = 'none';
        }
    }
    // ... except the ones explicitly disabled by the admin, to draw attention
    var disabled = container.querySelectorAll('.stic-rrp-admin-module.is-disabled');
    for (var d = 0; d < disabled.length; d++) {
        disabled[d].classList.remove('is-collapsed');
        disabled[d].querySelector('.stic-rrp-admin-module-body').style.display = '';
    }

    // -------------------------------------------------- save (single JSON)

    var saveButtons = container.querySelectorAll('.js-rrp-save');
    var savingIndicators = container.querySelectorAll('.js-rrp-saving');

    function buildConfig() {
        var config = {
            enabled: !!document.querySelector('input[name="enabled"]').checked,
            pinned: !!document.querySelector('input[name="pinned"]').checked,
            max_per_section: parseInt(document.querySelector('input[name="max_per_section"]').value, 10) || 10,
            modules: {}
        };

        var modules = container.querySelectorAll('.stic-rrp-admin-module');
        for (var i = 0; i < modules.length; i++) {
            var moduleNode = modules[i];
            var moduleName = moduleNode.getAttribute('data-module');
            if (!moduleName) {
                continue;
            }
            var moduleEntry = {
                enabled: !!moduleNode.querySelector('.stic-rrp-admin-module-head input[type="checkbox"]').checked,
                subpanels: {}
            };
            var rows = moduleNode.querySelectorAll('.stic-rrp-admin-row');
            for (var r = 0; r < rows.length; r++) {
                var row = rows[r];
                var key = row.getAttribute('data-key');
                if (!key) {
                    continue;
                }
                var selectByName = function (suffix) {
                    return row.querySelector('select[name$="[' + suffix + ']"]');
                };
                moduleEntry.subpanels[key] = {
                    enabled: !!row.querySelector('input[type="checkbox"]').checked,
                    title_field: selectByName('title_field') ? selectByName('title_field').value : '',
                    subtitle_field: selectByName('subtitle_field') ? selectByName('subtitle_field').value : '',
                    date_field: selectByName('date_field') ? selectByName('date_field').value : ''
                };
            }
            config.modules[moduleName] = moduleEntry;
        }
        return config;
    }

    function setSaving(saving) {
        for (var b = 0; b < saveButtons.length; b++) {
            saveButtons[b].disabled = saving;
        }
        for (var s = 0; s < savingIndicators.length; s++) {
            savingIndicators[s].style.display = saving ? '' : 'none';
        }
    }

    function saveConfiguration(button) {
        setSaving(true);
        // The JSON travels as the raw request body: $_POST values are mangled
        // by the SuiteCRM request cleaning
        fetch(window.location.href + '&rrp_save=1', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(buildConfig())
        }).then(function (response) {
            return response.json();
        }).then(function (result) {
            if (result && result.saved) {
                window.location.href = window.location.href + '&rrp_saved=1';
            } else {
                setSaving(false);
                alert(button.getAttribute('data-error-text') || 'Save failed');
            }
        }).catch(function () {
            setSaving(false);
            alert(button.getAttribute('data-error-text') || 'Save failed');
        });
    }

    for (var sb = 0; sb < saveButtons.length; sb++) {
        saveButtons[sb].addEventListener('click', function () {
            saveConfiguration(this);
        });
    }

    // -------------------------------------------------- drag & drop order

    var dragged = null;

    container.addEventListener('dragstart', function (event) {
        var row = event.target.closest ? event.target.closest('.stic-rrp-admin-row') : null;
        if (!row) {
            return;
        }
        dragged = row;
        row.classList.add('is-dragging');
        event.dataTransfer.effectAllowed = 'move';
        try {
            event.dataTransfer.setData('text/plain', '');
        } catch (e) { }
    });

    container.addEventListener('dragend', function (event) {
        if (dragged) {
            dragged.classList.remove('is-dragging');
        }
        dragged = null;
    });

    container.addEventListener('dragover', function (event) {
        var target = event.target.closest ? event.target.closest('.stic-rrp-admin-row') : null;
        if (!target || !dragged || dragged === target) {
            return;
        }
        if (target.parentNode !== dragged.parentNode) {
            return; // rows can only be reordered within their module
        }
        event.preventDefault();
        event.dataTransfer.dropEffect = 'move';
        target.classList.add('is-drop-target');
    });

    container.addEventListener('dragleave', function (event) {
        var target = event.target.closest ? event.target.closest('.stic-rrp-admin-row') : null;
        if (target) {
            target.classList.remove('is-drop-target');
        }
    });

    container.addEventListener('drop', function (event) {
        var target = event.target.closest ? event.target.closest('.stic-rrp-admin-row') : null;
        if (!target || !dragged || dragged === target) {
            return;
        }
        if (target.parentNode !== dragged.parentNode) {
            return;
        }
        event.preventDefault();
        target.classList.remove('is-drop-target');
        var rect = target.getBoundingClientRect();
        var after = (event.clientY - rect.top) > rect.height / 2;
        target.parentNode.insertBefore(dragged, after ? target.nextSibling : target);
    });
})();
