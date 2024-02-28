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
var sticCV_Record_Field_Content = class sticCV_Record_Field_Content extends sticCV_Element_Label {
    constructor (customView, $fieldElement, fieldName) {
        super(customView, $fieldElement.children('[field="'+fieldName+'"]'));

        this.fieldName = fieldName;
        this.type = this.$element.attr("type");
        
        switch(this.type) {
            case "bool":
                this.$editor = this.$element.find("[type='checkbox']:input");
                break;
            case "enum":
            case "multienum":
                this.$editor = this.$element.find("select");
                break;
            case "datetimecombo":
                this.$editor = this.$element.find("input,select");
                break;
            default:
                this.$editor = this.$element.find("input");
                break;
        }
        this.$buttons = this.$element.find("button");
        this.$items = this.$element.find(".items");
        this.$fieldText = this.$element.find(".sugar_field");
        this.$readonlyLabel = this.$element.find(".stic-ReadonlyInput");
    }

    color(color="") {
        sticCVUtils.color(this.$editor, this.customView, color);
        sticCVUtils.color(this.$items, this.customView, color);
        sticCVUtils.color(this.$readonlyLabel, this.customView, color);
        return super.color(color);
    }
    background(color="") {
        sticCVUtils.background(this.$editor, this.customView, color);
        sticCVUtils.background(this.$items, this.customView, color);
        sticCVUtils.background(this.$readonlyLabel, this.customView, color);
        if (this.customView.view == "detailview" || this.type=="radioenum") {
            super.background(color);
        }
        return this;
    }

    readonly(readonly=true) {
        return sticCVUtils.readonly(this, readonly);
    }
    inline_edit(inline_edit=true) {
       return sticCVUtils.inline_edit(this, inline_edit);
    }
    value(newValue) {
        return sticCVUtils.value(this, newValue);
    }

    text(newText){
        if(this.customView.view == "editview" || this.customView.view == "quickcreate"){
            if(this.type=="enum" || this.type=="currency_id" || this.type=="dynamicenum" || this.type=="multienum") {
                return sticCVUtils.text(this.$editor.find("option:selected"), this.customView, newText);
            } else if(this.type=="radioenum") {
                return sticCVUtils.text(this.$editor.parent().find("[type='radio']:checked").parent(), this.customView, newText);
            } else if(this.type=="bool") {
                if(this.value()) {
                    return "☒";
                } else {
                    return "☐";
                }
            } else if(this.$editor.prop("type")=="text" || this.$editor.prop("type")=="textarea") {
                return this.value();
            }
        }
        return super.text(newText);
    }

    showEditor(show=true){
        sticCVUtils.show(this.$editor, this.customView, show);
        sticCVUtils.show(this.$items, this.customView, show);
        if(this.type=="radioenum"){
            sticCVUtils.show(this.$editor.parent(), this.customView, show);
        } 
    }
    showReadOnlyLabel(show=true){
        if(this.type!="image" && this.type!="html") {
            if(show) {
                if (this.$readonlyLabel.length==0) {
                    this.$element.prepend('<p class="stic-ReadonlyInput" style="display:none"></p>');
                    this.$readonlyLabel = this.$element.find(".stic-ReadonlyInput");
                    // Update label when value is changed
                    var self = this;
                    this.onChange(function() {
                        self.$readonlyLabel.text(self.text());
                    });
                }
            }
            sticCVUtils.show(this.$readonlyLabel, this.customView, show);
            this.change();
        }
    }
    is_readonly(){
        return this.$readonlyLabel.length!=0 && this.$readonlyLabel.css('display')!='none';
    }


    applyActionWithValue(actionName, value) { 
        var result = super.applyActionWithValue(actionName, value);
        if(result!== false) {
            return result;
        }
        switch(actionName){
            case "fixed_value": return this.value(value);
            case "readonly": return this.readonly(value);
            case "inline": return this.inline_edit(value); 
            case "required": return this.required(value);
        }
        return false;
    }

    onChange(callback) {
        return sticCVUtils.onChange(this.$editor, callback) || super.onChange(callback);
    }
    change() {
        return ticCVUtils.change(this.$editor) || super.change();
    }

    checkCondition(condition) {
        switch(condition.operator) {
            case 'Equal_To':
                if(this.type=="relate"){
                    return this.value().split('|')[0]==condition.value.split('|')[0];
                } else {
                    return this.value()==condition.value;
                }
            case 'Not_Equal_To':
                if(this.type=="relate"){
                    return this.value().split('|')[0]!=condition.value.split('|')[0];
                } else {
                    return this.value()!=condition.value;
                }
            case 'Greater_Than':
                return this.value()>condition.value;
            case 'Less_Than':
                return this.value()<condition.value;
            case 'Greater_Than_or_Equal_To':
                return this.value()>=condition.value;
            case 'Less_Than_or_Equal_To':
                return this.value()<=condition.value;
            case 'Contains':
                if(this.type=="multienum"){
                    var valueArray = this.value().split(',');
                    var conditionValueArray = condition.value.split(',');
                    for(var conditionValue of conditionValueArray){
                        if(conditionValue[0]=='^'){ 
                            conditionValue = conditionValue.substring(1, conditionValue.length-1);
                        }
                        if(!valueArray.includes(conditionValue)){
                            return false;
                       }
                    }
                    return true;
                } else {
                    return (this.value()??"").includes(condition.value);
                }
            case 'Not_Contains':
                if(this.type=="multienum"){
                    var valueArray = this.value().split(',');
                    var conditionValueArray = condition.value.split(',');
                    for(var conditionValue of conditionValueArray){
                        if(conditionValue[0]=='^'){ 
                            conditionValue = conditionValue.substring(1, conditionValue.length-1);
                        }
                        if(valueArray.includes(conditionValue)){
                            return false;
                       }
                    }
                    return true;
                } else {
                    return !(this.value()??"").includes(condition.value);
                }
            case 'Starts_With':
                return (this.value()??"").startsWith(condition.value);
            case 'Not_Starts_With':
                return !(this.value()??"").startsWith(condition.value);
            case 'Ends_With':
                return (this.value()??"").endsWith(condition.value);
            case 'Not_Ends_With':
                return !(this.value()??"").endsWith(condition.value);
            case 'is_null':
                return (this.value()??"").split('|')[0]=="";
            case 'is_not_null':
                return (this.value()??"").split('|')[0]!="";
        }
        return false;
    }
}


