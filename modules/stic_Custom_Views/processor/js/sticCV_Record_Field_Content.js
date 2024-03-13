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
    constructor (field, $fieldElement, fieldName) {
        super(field.customView, $fieldElement.children('[field="'+fieldName+'"]'));

        this.field = field;
        this.fieldName = fieldName;
        this.type = (this.$element.attr("type")??"").toLowerCase();
        
        switch(this.type) {
            case "bool":
                this.$editor = this.$element.find("[type='checkbox']:input");
                break;
            case "enum":
            case "dynamicenum":
            case "multienum":
            case "currency_id":
                this.$editor = this.$element.find("select");
                break;
            case "datetimecombo":
                this.$editor = this.$element.find("input,select");
                break;
            default:
                this.$editor = this.$element.find("input,textarea,select");
                break;
        }

        this.$buttons = this.$element.find("button");
        this.$items = this.$element.find(".items,table,option,label");
        this.$fieldText = this.$element.find(".sugar_field");

        this.$readonlyLabel = this.$element.parent().find(".stic-ReadonlyInput");
        if (this.$readonlyLabel.length==0 && this.$element.length>0) {
            var classes = this.$element.attr("class").replaceAll("hidden","");
            this.$element.parent().append('<div class="'+classes+' stic-ReadonlyInput hidden"></div>');
            this.$readonlyLabel = this.$element.parent().find(".stic-ReadonlyInput");
            this.$readonlyLabel.text(this.text());

            // Update label when value is changed
            var self = this;
            this.onChange(function() {
                self.$readonlyLabel.text(self.text());
            });
        }
    }

    readonly(readonly=true) { return this.applyAction({action: "readonly", value: readonly}); }

    inline_edit(inline_edit=true) { return this.applyAction({action: "inline", value: inline_edit}); }
    
    value(newValue, value_list) {
        return sticCVUtils.value(this, newValue, value_list);
    }
    _getValue(value_list){
        return this.value(undefined, value_list);
    }

    text(newText){
        if(this.customView.view == "editview" || this.customView.view == "quickcreate"){
            switch (this.type) {
                case "enum":
                case "dynamicenum":
                case "multienum":
                case "currency_id":
                    return sticCVUtils.text(this.$editor.find("option:selected"), this.customView);
                case "radioenum":
                    return sticCVUtils.text(this.$editor.parent().find("[type='radio']:checked").parent(), this.customView);
                case "bool":
                    return this.value()?"☒":"☐";
                case "relate":
                    return this.value().split("|").slice(-1)[0];
                default:
                    return this.value();
            }
        }
        var text = "";
        if(this.customView.view=="detailview") {
            text=this.$element.text().trim();
        } else {
            text=sticCVUtils.text(this.$element, this.customView);
        }
        
        return text;
    }

    is_readonly(){
        return this.$readonlyLabel.length!=0 && !this.$readonlyLabel.hasClass("hidden");
    }

    applyAction(action) {
        switch(action.action){
            case "color": 
                sticCVUtils.color(this.$editor, this.customView, action.value);
                sticCVUtils.color(this.$items, this.customView, action.value, true);
                sticCVUtils.color(this.$readonlyLabel, this.customView, action.value);
                sticCVUtils.color(this.$element, this.customView, action.value);
                return this;
            case "background": 
                sticCVUtils.background(this.$editor, this.customView, action.value);
                sticCVUtils.background(this.$items, this.customView, action.value);
                sticCVUtils.background(this.$readonlyLabel, this.customView, action.value);
                if (this.customView.view == "detailview" || this.type=="radioenum") {
                    sticCVUtils.background(this.$element, this.customView, action.value);
                }
                return this;
            case "bold": 
                sticCVUtils.bold(this.$editor, this.customView, action.value);
                sticCVUtils.bold(this.$items, this.customView, action.value);
                sticCVUtils.bold(this.$readonlyLabel, this.customView, action.value);
                sticCVUtils.bold(this.$element, this.customView, action.value);
                return this;
            case "italic": 
                sticCVUtils.italic(this.$editor, this.customView, action.value);
                sticCVUtils.italic(this.$items, this.customView, action.value);
                sticCVUtils.italic(this.$readonlyLabel, this.customView, action.value);
                sticCVUtils.italic(this.$element, this.customView, action.value);
                return this;
            case "underline": 
                sticCVUtils.underline(this.$editor, this.customView, action.value);
                sticCVUtils.underline(this.$items, this.customView, action.value);
                sticCVUtils.underline(this.$readonlyLabel, this.customView, action.value);
                sticCVUtils.underline(this.$element, this.customView, action.value);
                return this;
            case "readonly": 
                if(this.customView.view != "editview" && this.customView.view != "quickcreate"){
                    return false;
                }
                var readonly=sticCVUtils.isTrue(action.value);
                this.applyAction({action:"visible", value:!readonly});
                sticCVUtils.show(this.$readonlyLabel, this.customView, readonly);
                return this;
            case "inline": 
                if(this.customView.view != "detailview") {
                    return false;
                }
                sticCVUtils.inline_edit(this, action.value);
                return this; 
            case "fixed_value": 
                return this.value(action.value);
        }
        return super.applyAction(action);
    } 

    onChange(callback) {
        var alsoInline=(this.customView.view=="detailview");
        return sticCVUtils.onChange(this.$editor, callback, alsoInline) || super.onChange(callback, alsoInline);
    }
    change() {
        return sticCVUtils.change(this.$editor) || super.change();
    }

    checkCondition(condition) {
        var value_list = condition.value_list;
        if(this.type=="multienum") {
            condition.value=(condition.value??"").replaceAll("^", "").split(',').sort().join(",");
        }
        switch(condition.operator) {
            case 'Equal_To':
                if(this.type=="relate"){
                    var valueSplit = this._getValue(value_list).split('|');
                    if(this.customView.view=="detailview" && valueSplit[0]=="undefined") {
                        return valueSplit[1]==condition.value.split('|')[1];
                    } else {
                        return valueSplit[0]==condition.value.split('|')[0];
                    }
                } else {
                    return this._getValue(value_list)==condition.value;
                }
            case 'Not_Equal_To':
                if(this.type=="relate"){
                    return this._getValue(value_list).split('|')[0]!=condition.value.split('|')[0];
                } else {
                    return this._getValue(value_list)!=condition.value;
                }
            case 'Greater_Than':
                if(this.type=="date" || this.type=="datetime" || this.type=="datetimecombo") {
                    return moment(this._getValue(value_list), condition.date_format).isAfter(moment(condition.value, condition.date_format));
                } else {
                    return this._getValue(value_list)>condition.value;
                }
            case 'Less_Than':
                if(this.type=="date" || this.type=="datetime" || this.type=="datetimecombo") {
                    return moment(this._getValue(value_list), condition.date_format).isBefore(moment(condition.value, condition.date_format));
                } else {
                    return this._getValue(value_list)<condition.value;
                }
            case 'Greater_Than_or_Equal_To':
                if(this.type=="date" || this.type=="datetime" || this.type=="datetimecombo") {
                    return moment(this._getValue(value_list), condition.date_format).isSameOrAfter(moment(condition.value, condition.date_format));
                } else {
                    return this._getValue(value_list)>=condition.value;
                }
            case 'Less_Than_or_Equal_To':
                if(this.type=="date" || this.type=="datetime" || this.type=="datetimecombo") {
                    return moment(this._getValue(value_list), condition.date_format).isSameOrBefore(moment(condition.value, condition.date_format));
                } else {
                    return this._getValue(value_list)<=condition.value;
                }
            case 'Contains':
                if(this.type=="multienum"){
                    var valueArray = this._getValue(value_list).split(',');
                    var conditionValueArray = condition.value.split(',');
                    for(var conditionValue of conditionValueArray){
                        if(!valueArray.includes(conditionValue)){
                            return false;
                       }
                    }
                    return true;
                } else {
                    return (this._getValue(value_list)??"").includes(condition.value);
                }
            case 'Not_Contains':
                if(this.type=="multienum"){
                    var valueArray = this._getValue(value_list).split(',');
                    var conditionValueArray = condition.value.split(',');
                    for(var conditionValue of conditionValueArray){
                        if(valueArray.includes(conditionValue)){
                            return false;
                       }
                    }
                    return true;
                } else {
                    return !(this._getValue(value_list)??"").includes(condition.value);
                }
            case 'Starts_With':
                return (this._getValue(value_list)??"").startsWith(condition.value);
            case 'Not_Starts_With':
                return !(this._getValue(value_list)??"").startsWith(condition.value);
            case 'Ends_With':
                return (this._getValue(value_list)??"").endsWith(condition.value);
            case 'Not_Ends_With':
                return !(this._getValue(value_list)??"").endsWith(condition.value);
            case 'is_null':
                return (this._getValue(value_list)??"").split('|')[0]=="";
            case 'is_not_null':
                return (this._getValue(value_list)??"").split('|')[0]!="";
        }
        return false;
    }
}


