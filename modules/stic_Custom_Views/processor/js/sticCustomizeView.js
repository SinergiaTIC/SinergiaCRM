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
 * This file contains logic and functions needed to manage custom views behaviour
 *
 */

var sticCustomizeView = class sticCustomizeView {
    static editview()    { return sticCustomizeView.For("editview"); }
    static detailview()  { return sticCustomizeView.For("detailview"); }
    static quickcreate() { return sticCustomizeView.For("quickcreate"); }

    static For(view) { 
        sticCustomizeView._load_moment();

        switch(view) {
            case "detailview": 
                return new sticCV_View_Record_Detail(view);
            case "editview":
            case "quickcreate":
                return new sticCV_View_Record_Edit(view);
        }
    }

    static _load_moment() {
        if(typeof moment!=='undefined') return;
        
        var url = "include/javascript/moment.min.js";
        console.log("Loading script [" + url + "].");

        // Add the script tag to the head
        var head = document.getElementsByTagName("head")[0];
        var script = document.createElement("script");
        script.type = "text/javascript";
        script.src = url;

        // // Bind events to the callback function if exist
        // if (callback) {
        //     // There are several events for cross browser compatibility.
        //     script.onreadystatechange = callback;
        //     script.onload = callback;
        // }

        // Fire the loading
        head.appendChild(script);
    }


}