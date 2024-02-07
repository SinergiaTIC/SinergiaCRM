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

var sticCustomView = class sticCustomView {
    constructor(view) {
        this.view = view;
    }

    field(fieldName) { return new CustomViewField(this, fieldName); }
    panel(panelName) { return new CustomViewPanel(this, panelName); }
    tab(tabIndex)    { return new CustomViewTab(this, tabIndex); }


    /**
     * Process the View customization
     * @param {*} jsonRules : The rules to apply in a string with json structure. The rules will be applied in order
     * json format for rules: list of customizations, each with conditions and actions. 
     *  [
     *   {
     *    conditions: [], 
     *    actions: [{type: tab_modification, element: 4, action: visible, value: 0, element_section: tab}],
     *   },
     *   {
     *    conditions: [{field: stic_referral_agent_c, operator: Equal_To, value: social_services}],
     *    actions: [{type: tab_modification, element: 4, action: visible, value: 1, element_section: tab}],
     *   },
     *  ]
     */
    processSticCustomView(jsonRules) {
        var customizations = JSON.parse(jsonRules);
        if(Array.isArray(customizations) && customizations.length) {
            var self = this;
            customizations.forEach(customization => {
                self.addCustomization(customization.conditions, customization.actions);
            });
        }
    }

    /**
     * Adds a customization: a group of Conditions to apply a list of actions
     */
    addCustomization(conditions, actions) {
        // Bind every change involved in condition set
        if(Array.isArray(conditions) && conditions.length) {
            var self = this;
            conditions.forEach(condition => {
                self.field(condition.field).onChange(function() { 
                    self.checkConditionsAndApplyActions(conditions, actions); 
                });
            });
        }
        // Check conditions with current values
        this.checkConditionsAndApplyActions(conditions, actions); 
    }

    /**
     * Applies an action defined in an object
     * Example:
     * {
     *  type: tab_modification,
     *  element: 4,
     *  action: visible,
     *  value: 0,
     *  element_section: tab,
     * },
     */
    applyAction(action) {
        switch(action.type) {
            case "field_modification": return this.field(action.element).applyAction(action);
            case "panel_modification": return this.panel(action.element).applyAction(action);
            case "tab_modification":   return this.tab(action.element).applyAction(action);
        }
    }
    
    /**
     * Check a condition defined in an object
     * Example:
     * {
     *  field: stic_referral_agent_c,
     *  operator: Equal_To
     *  value: social_services
     * }
     */
    checkCondition(condition) {
        return this.field(condition.field).checkCondition(condition);
    }

    /**
     * Checks all conditions in a list in order to apply all actions
     */
    checkConditionsAndApplyActions(conditions, actions) {
        var value = true;
        if(Array.isArray(conditions) && conditions.length) {
            var self = this;
            conditions.forEach(condition => value &&= self.checkCondition(condition));
        }
        if(value) {
            if(Array.isArray(actions) && actions.length) {
                var self = this;
                actions.forEach(action => self.applyAction(action));
            }
        }
    }
}

var sticCustomizeView = class sticCustomizeView {
    static editview = this.For("editview"); 
    static detailview = this.For("detailview");
    static quickcreate = this.For("quickcreate"); 

    static For(view) {
        return new sticCustomView(view);
    }
}

var CustomViewItemBase = class CustomViewItemBase {
    constructor (customView, itemName) {
        this.customView = customView;

        this.view = customView.view;
        this.itemName = itemName;
        
        switch(this.view) {
            case "detailview":  this.elementView = $(".detail-view"); break;
            case "editview":    this.elementView = $("#EditView"); break;
            case "quickcreate": this.elementView = $("#EditView_tabs"); break;
        }
        switch(this.view) {
            case "detailview":  this.form = null; break;
            case "editview":    this.form = this.elementView; break;
            case "quickcreate": this.form = this.elementView.parent(); break;
        }
    }

    applyAction(action) { return false; } // Abstract class
}

var CustomViewField = class CustomViewField extends CustomViewItemBase {
    constructor (customView, fieldName) {
        super(customView, fieldName);

        this.fieldName = fieldName;

        var rowElement = this.elementView.find('*[data-field="'+this.fieldName+'"]');
        switch(this.view) {
            case "detailview":  this.row = new CustomViewDivDetailRow(this, rowElement);; break;
            case "editview":    this.row = new CustomViewDivEditRow(this, rowElement); break;
            case "quickcreate": this.row = new CustomViewDivEditRow(this, rowElement); break;
        }
        
        this.label = new CustomViewDivLabel(this, this.row.element.children('.label'));
        
        var inputElement = this.row.element.children('[field="'+this.fieldName+'"]');
        switch(this.view) {
            case "detailview":  this.input = new CustomViewDivDetailInput(this, inputElement); break;
            case "editview":    this.input = new CustomViewDivEditInput(this, inputElement); break;
            case "quickcreate": this.input = new CustomViewDivEditInput(this, inputElement); break;
        }
    }
    show(show=true) { this.row.show(show); return this; }
    hide() { return this.show(false); }

    readonly(readonly=true) { this.input.readonly(readonly); return this; }

    mandatory(mandatory=true) {
        if(mandatory===true||mandatory==="1"||mandatory===1) {
            //IEPA!!
            // Type always text??!!!
            setRequiredStatus(this.fieldName, 'text',SUGAR.language.get('app_strings', 'ERR_MISSING_REQUIRED_FIELDS'));
        } else {
            setUnrequiredStatus(this.fieldName);
        }
        return this;
    }
    inline(inline=true) {
        //IEPA!!
        console.log("Inline not available. Requested:" + inline);
        return false;
    }
    value(newValue) { return this.input.value(newValue); }
    fixed_value(fixed_value) {
        var value = this.value(fixed_value);

        //IEPA!!
        console.log("Fixed_value not checked. Requested:" + fixed_value);
        return value;
    }

    applyAction(action) {
        switch(action.element_section){
            case "field_label": return this.label.applyAction(action);
            case "field_input": return this.input.applyAction(action);
            case "field": {
                switch(action.action){
                    case "visible": return this.show(action.value);
                    case "readonly": return this.readonly(action.value);
                    case "mandatory": return this.mandatory(action.value);
                    case "inline": return this.inline(action.value);
                    case "fixed_value": return this.fixed_value(action.value);
                }
            }
        }
        return false;
    }
    
    checkCondition(condition) {
        switch(condition.operator) {
            case 'Equal_To':
                return this.value()==condition.value;
            case 'Not_Equal_To':
                return this.value()!==condition.value;
            case 'Greater_Than':
                return this.value()>condition.value;
            case 'Less_Than':
                return this.value()<condition.value;
            case 'Greater_Than_or_Equal_To':
                return this.value()>=condition.value;
            case 'Less_Than_or_Equal_To':
                return this.value()<=condition.value;
            case 'Contains':
                return (this.value()??"").includes(condition.value);
            case 'Starts_With':
                return (this.value()??"").startsWith(condition.value);
            case 'Ends_With':
                return (this.value()??"").endsWith(condition.value);
            case 'is_null':
                return (this.value()??"")=="";
            case 'is_not_null':
                return (this.value()??"")!="";
        }
        return false;
    }

    onChange(callback) {
        this.input.onChange(callback);
    }
    change() {
        this.input.change();
    }
}
var CustomViewPanel = class CustomViewPanel extends CustomViewItemBase {
    constructor (customView, panelName) {
        super(customView, panelName);

        this.panelName = panelName;

        this.panel = new CustomViewDivBase(this, this.elementView.find('.panel-body[data-id="'+this.panelName+'"]').parent());
        this.header = new CustomViewDivHeader(this, this.panel.element.children('.panel-heading'));
    };

    show(show=true) { this.panel.show(show); return this; }
    hide() { return this.show(false); }

    applyAction(action) {
        switch(action.element_section){
            case "panel_header": return this.header.applyAction(action);
            case "panel": {
                switch(action.action){
                    case "visible": return this.show(action.value);
                }
            }
        }
        return false;
    }
}
var CustomViewTab = class CustomViewTab extends CustomViewItemBase {
    constructor (customView, tabIndex) {
        super(customView, tabIndex);

        this.tabIndex = tabIndex;

        this.header = new CustomViewDivLabel(this, this.elementView.find('[id=tab'+this.tabIndex+']'));
    };

    show(show=true) { this.header.show(show); return this; }
    hide() { return this.show(false); }

    applyAction(action) {
        switch(action.element_section){
            case "tab_header": return this.header.applyAction(action);
            case "tab": {
                switch(action.action){
                    case "visible": return this.show(action.value);
                }
            }
        }
        return false;
    }
}

var CustomViewDivBase = class CustomViewDivBase {
    constructor (item, element){
        this.item = item;
        this.element = element;
    }
    show(show=true) {
        if(show===true||show==="1"||show===1) {
            this.element.show();
        } else {
            this.element.hide();
        }
        return this;
    }
    hide() { return this.show(false); }

    applyAction(action) { return false; } // Abstract class
}

var CustomViewDivEditRow = class CustomViewDivEditRow extends CustomViewDivBase {
    constructor (item, element){
        super(item, element);
    }
}
var CustomViewDivDetailRow = class CustomViewDivDetailRow extends CustomViewDivBase {
    constructor (item, element){
        super(item, element);
    }
}
var CustomViewDivLabel = class CustomViewDivLabel extends CustomViewDivBase {
    constructor (item, element){
        super(item, element);
    }
    color(color="") { this.element.css("color", color); return this; }
    background(color="") { this.element.css("background-color", color); return this; }

    bold(bold=true) {
        if (bold===true||bold==="1"||bold===1) {
            this.element.css('font-weight', 'bold');
        } else {
            this.element.css('font-weight', 'normal');
        }
        return this;
    }
    italic(italic=true) {
        if (italic===true||italic==="1"||italic===1) {
            this.element.css('font-style', 'italic');
        } else {
            this.element.css('font-style', 'normal');
        }
        return this;
    }
    underline(underline=true) {
        if (underline===true||underline==="1"||underline===1) {
            this.element.css('text-decoration', 'underline');
        } else {
            this.element.css('text-decoration', 'normal');
        }
        return this;
    }
    text(newText){
        return this.element.text(newText);
    }
    value(newValue) {
        return null;
    }


    applyAction(action) {
        switch(action.action){
            case "visible": return this.show(action.value);
            case "color": return this.color(action.value);
            case "background": return this.background(action.value);
            case "bold": return this.bold(action.value);
            case "italic": return this.italic(action.value);
            case "underline": return this.underline(action.value);
        }
        return false;
    }

    onChange(callback) {
        this.element.on("change paste keyup", function() { callback();});
    }
    change() {
        this.element.change();
    }
}
var CustomViewDivEditInput = class CustomViewDivEditInput extends CustomViewDivLabel {
    constructor (item, element){
        super(item, element);
        this.editor = this.element.find(":input");
        this.option
        this.items = this.element.find(".items");
        this.labelValue = this.element.find(".stic-ReadonlyInput");
        this.type = this.element.attr("type"); 
    }
    value(newValue) {
        if(newValue!==undefined) {
            this.editor.val(newValue);
            this.change();
        }
        return this.editor.val();
    }
    text(newText){
        var text = this.editor.val();
        if(this.type=="enum" || this.type=="multienum"){
            text = this.editor.find("option:selected").text();
        }
        return text;
    }
    color(color="") {
        this.editor.css("color", color);
        this.items.css("color", color);
        this.labelValue.css("color", color);
        return super.color(color);
    }
    background(color="") {
        this.editor.css("background-color", color);
        this.items.css("background-color", color);
        this.labelValue.css("background-color", color);
        if (this.type=="radioenum") {
            super.background(color);
        }
        return this;
    }
    readonly(readonly=true) {
        if(readonly===true||readonly==="1"||readonly===1) {
            this.editor.hide();
            this.items.hide();
            if (this.labelValue.length==0) {
                this.element.append('<p class="stic-ReadonlyInput"></p>');
                this.labelValue = this.element.find(".stic-ReadonlyInput");
                // Update label when value is changed
                var self = this;
                this.editor.on("change paste keyup", function() {
                    self.labelValue.text(self.text());
                });
            }
            this.labelValue.show();
            this.editor.change();
        }
        else {
            this.labelValue.hide();
            this.editor.show();
            this.items.show();
        }
        return this;
    }

    onChange(callback) {
        this.editor.on("change paste keyup", function() { callback();});
    }
    change() {
        this.editor.change();
    }
}
var CustomViewDivDetailInput = class CustomViewDivDetailInput extends CustomViewDivLabel {
    constructor (item, element){
        super(item, element);
    }
}

var CustomViewDivHeader = class CustomViewDivHeader extends CustomViewDivLabel {
    constructor (item, element){
        super(item, element);
        this.anchor = this.element.find("a");
    }
    color(color="") {
        this.anchor.css("color", color);
        return this;
    }
    background(color="") {
        if (this.anchor.length>0) {
            this.anchor[0].style.setProperty("background-color", color, "important");
        }
        return this;
    }

}


