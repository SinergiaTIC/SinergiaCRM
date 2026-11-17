<?php
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

require_once 'include/MVC/View/views/view.popup.php';
require_once 'SticInclude/Views.php';

#[\AllowDynamicProperties]
class stic_SignaturesViewPopup extends ViewPopup
{

    public function __construct()
    {

        parent::__construct();

    }

    public function preDisplay()
    {

        parent::preDisplay();

        SticViews::preDisplay($this);

    }
    public function display()
    {
 
        parent::display();
 
        SticViews::display($this);
 
        echo getVersionedScript("modules/stic_Signatures/Utils.js");

        // Client-side filter for AWF wizard: only show signatures whose main_module
        // matches the data blocks of the form. Uses window.opener (no AWF core changes).
        global $db;
        $sigMap = [];
        $res = $db->query("SELECT id, main_module FROM stic_signatures WHERE deleted = 0");
        while ($r = $db->fetchByAssoc($res)) {
            $sigMap[$r['id']] = $r['main_module'];
        }
        $sigMapJson = json_encode($sigMap);
        echo "<script>
        (function() {
            var sigMap = " . $sigMapJson . ";
            function filterSignatures() {
                try {
                    var op = window.opener;
                    if (!op || !op.Alpine) return;
                    // Determine the module of the data block selected in the 'Firmante' dropdown
                    var ae = op.Alpine.store('actionEditor');
                    var selectedBlockId = '';
                    if (ae && ae.action && ae.action.parameters) {
                        var p = ae.action.parameters.find(function(x){ return x.name === 'data_block_id'; });
                        if (p) selectedBlockId = p.value;
                    }
                    var blocks = (op.alpineComponent && op.alpineComponent.formConfig) ? (op.alpineComponent.formConfig.data_blocks || []) : [];
                    var mods = {};
                    if (selectedBlockId) {
                        blocks.forEach(function(b){ if (b.id === selectedBlockId && b.module) mods[b.module] = true; });
                    }
                    // Fallback: if no block selected, use all data blocks' modules
                    if (Object.keys(mods).length === 0) {
                        blocks.forEach(function(b){ if (b.module) mods[b.module] = true; });
                    }
                    var modKeys = Object.keys(mods);
                    if (modKeys.length === 0) return;
                    var rows = document.querySelectorAll('tr');
                    rows.forEach(function(tr) {
                        var html = tr.innerHTML;
                        var m = html.match(/send_back\\s*\\(\\s*'[^']*'\\s*,\\s*'([^']+)'/);
                        var id = m ? m[1] : null;
                        if (!id || !sigMap[id]) return;
                        var mm = sigMap[id];
                        if (mm && !mods[mm]) {
                            tr.style.display = 'none';
                        }
                    });
                } catch (e) { /* no-op */ }
            }
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', filterSignatures);
            } else {
                setTimeout(filterSignatures, 100);
            }
        })();
        </script>";
 
    }

}
