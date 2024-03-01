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

var sticCVUtils = class sticCVUtils {
    static show($elem, customView=null, show=true) {
        show=(show===true||show==="1"||show===1);
        $elem.each(function(){
            if(show) {
                sticCVUtils.removeClass($(this), customView, "hidden");
            } else {
                sticCVUtils.addClass($(this), customView, "hidden");
            }
        });
    }
    static color($elem, customView=null, color="") {
        $elem.each(function(){
            $(this).css("color", color);
            var $self=$(this);
            customView?.addUndoFunction(function() { $self.css('color', ''); });
        });
    }
    static background($elem, customView=null, color="", important=false) { 
        $elem.each(function(){
            if(important) {
                if($(this).style==undefined) {
                    $(this).attr('style', 'background-color:' + color + ' !important');
                } else {
                    $(this).style.setProperty("background-color", color, "important");
                }
            } else {
                $(this).css("background-color", color); 
            }
            var $self=$(this);
            customView?.addUndoFunction(function() { $self.css("background-color", ''); });
        });
    }
    static bold($elem, customView=null, bold=true) {
        bold=(bold===true||bold==="1"||bold===1);
        $elem.each(function(){
            if(bold) {
                $(this).css('font-weight', 'bold');
                var $self=$(this);
                customView?.addUndoFunction(function() { $self.css('font-weight', ''); });
            } else {
                $(this).css('font-weight', 'normal');
                var $self=$(this);
                customView?.addUndoFunction(function() { $self.css('font-weight', ''); });
            }
        });
    }
    static italic($elem, customView=null, italic=true) {
        italic=(italic===true||italic==="1"||italic===1);
        $elem.each(function(){
            if(italic) {
                $(this).css('font-style', 'italic');
                var $self=$(this);
                customView?.addUndoFunction(function() { $self.css('font-style', ''); });
            } else {
                $(this).css('font-style', 'normal');
                var $self=$(this);
                customView?.addUndoFunction(function() { $self.css('font-style', ''); });
            }
        });
    }
    static underline($elem, customView=null, underline=true) {
        underline=(underline===true||underline==="1"||underline===1);
        $elem.each(function(){
            if(underline) {
                $(this).css('text-decoration', 'underline');
                var $self=$(this);
                customView?.addUndoFunction(function() { $self.css('text-decoration', ''); });
            } else {
                $(this).css('text-decoration', 'none');
                var $self=$(this);
                customView?.addUndoFunction(function() { $self.css('text-decoration', ''); });
            }
        });
    }
    static style($elem, customView=null, style="") {
        $elem.each(function(){
            var oldStyle = $(this).attr('style');
            if(oldStyle===undefined){
                oldStyle="";
            }
            $(this).css(style);
            var $self=$(this);
            customView?.addUndoFunction(function() { $self.attr('style', oldStyle); });
        });
    }
    static frame($elem, customView=null, frame=true){
        frame=(frame===true||frame==="1"||frame===1);
        $elem.each(function(){
            if(frame) {
                $(this).css({"border-color": "orangered", "border-style": "dashed"});
                var $self=$(this);
                customView?.addUndoFunction(function() { $self.css({"border-color": "", "border-style": ""}); });
            } else {
                $(this).css({"border-color": "", "border-style": ""});
                var $self=$(this);
                customView?.addUndoFunction(function() { $self.css({"border-color": "", "border-style": ""}); });
            }
        });
    }
    static text($elem, customView=null, newText) {
        var textArray=[];
        $elem.each(function(){
            var oldText = $(this).text();
            if(newText===undefined || newText!=oldText) {
                textArray.push(oldText);
            } else {
                textArray.push($(this).text(newText));
                var $self=$(this);
                customView?.addUndoFunction(function() { $self.text(oldText); });
            }
        });
        return textArray.join(", ");
    }
    static addClass($elem, customView=null, className){
        if(className=="") return;
        $elem.each(function(){
            if(!$(this).hasClass(className)) {
                $(this).addClass(className);
                var $self=$(this);
                customView?.addUndoFunction(function(){ $self.removeClass(className); })
            }
        })
    }
    static removeClass($elem, customView=null, className){
        if(className=="") return;
        $elem.each(function(){
            if($(this).hasClass(className)) {
                $(this).removeClass(className);
                var $self=$(this);
                customView?.addUndoFunction(function(){ $self.addClass(className); })
            }
        })
    }
    static value(fieldContent, newValue, value_list) {
        sticCVUtils.setValue(fieldContent, newValue, value_list);
        return sticCVUtils.getValue(fieldContent, value_list);
    }

    static getValue(fieldContent, value_list) {
        var $elem = fieldContent.$editor;
        if(fieldContent.customView.view == "detailview") { 
            $elem = fieldContent.$fieldText;
        }
        if($elem.length==0 || $elem.get(0).parentNode === null) {
            $elem = fieldContent.$element;
        }

        if(fieldContent.customView.view == "detailview") { 
            if(fieldContent.type=="relate"){
                return $elem.attr("data-id-value")+"|"+$elem.text().trim();
            } 
            var text = fieldContent.text();
            if(value_list!=undefined && value_list!=""){
                return sticCVUtils.getListValueFromLabel(value_list, text);
            }
            return text;
        }
        switch (fieldContent.type) {
            case "radioenum":
                var $radio = $elem.parent().find("[type='radio']:checked");
                if($radio.length!=0) {
                    return $radio.val();
                }
            case "multienum":
                return $elem.val().sort().join(",");
            case "bool":
                return $elem.prop("checked");
            case "datetimecombo":
                return $elem.last().val();
            case "date":
                return $elem.val()+" 00:00";
            case "relate":
                return $elem.eq(1).val()+"|"+$elem.eq(0).val();
        }
        return $elem.val();
    }

    static setValue(fieldContent, newValue) {
        if(newValue===undefined) return;
        if(fieldContent.customView.view == "detailview") return;

        var $elem = fieldContent.$editor;
        if($elem.length==0) {
            $elem = fieldContent.$element;
        }
        if(fieldContent.type=="multienum"){
            var newValueArray = [];
            for(var newValueSingle of newValue.split(',')){
                if(newValueSingle[0]=='^'){ 
                    newValueArray.push(newValueSingle.substring(1, newValueSingle.length-1));
                }
                else {
                    newValueArray.push(newValueSingle);
                }
            }
            newValue = newValueArray.sort().join(",");
        }

        var oldValue = sticCVUtils.getValue(fieldContent);
        if(newValue!=oldValue) {
            // Set new value
            var customView = fieldContent.customView;

            switch (fieldContent.type) {
                case "radioenum":
                    var $radio = $elem.parent().parent().find("[type='radio'][value='"+newValue+"']");
                    if($radio.length!=0) {
                        $radio.prop('checked', true);
                    } else {
                        $elem.val(newValue);
                    }
                    break;
                case "bool":
                    $elem.prop("checked", newValue);
                    break;
                case "multienum":
                    $elem.val(newValue.split(","));
                    break;
                case "datetimecombo":
                    var dateTimeArray = newValue.split(" ");
                    $elem.eq(0).val(dateTimeArray[0]);
                    var timeArray = dateTimeArray[1].split(":");
                    $elem.eq(1).val(timeArray[0]);
                    $elem.eq(2).val(timeArray[1]);
                    $elem.eq(3).val(newValue);
                    break;
                case "relate":
                    var idNameArray = newValue.split('|');
                    $elem.eq(0).val(idNameArray[1]);
                    $elem.eq(1).val(idNameArray[0]);
                    break;
                default:
                    $elem.val(newValue);
                    break;
            }

            // Unset value modified by user
            var attr = $elem.attr("data-changedByUser");
            if(typeof(attr==="undefined") || attr===false) {
                $elem.removeAttr("data-changedByUser");
            }
            sticCVUtils.change($elem);

            // Set last setted value by this Api to be undoed
            $elem.attr("data-lastChangeByApi", newValue);

            // Add undo function
            customView.addUndoFunction(function() { 
                var currentValue = sticCVUtils.value(fieldContent);

                // Check if the last value change with Api is processed
                var attrApi = $elem.attr("data-lastChangeByApi");
                if(typeof(attrApi!=="undefined") && attrApi!==false) {
                    // The last value change with Api, is the current value?
                    if(attrApi!=currentValue) {
                        // Set data is changed by User
                        $elem.attr("data-changedByUser", currentValue);
                    } else {
                        // Data is not changed by User
                        var attrUser = $elem.attr("data-changedByUser");
                        if(typeof(attrUser==="undefined") || attrUser===false) {
                            $elem.removeAttr("data-changedByUser");
                        }
                    }
                    // The last value change with Api is processed
                    $elem.removeAttr("data-lastChangeByApi");
                }
                // Undo only if last change is not made by user
                var attrUser = $elem.attr("data-changedByUser");
                if(typeof(attrUser==="undefined") || attrUser===false) {
                    sticCVUtils.value(fieldContent, oldValue);
                }
            }, true);
        }
    }

    static readonly(fieldContent, readonly=true) {
        readonly = (readonly===true||readonly==="1"||readonly===1);
        if(fieldContent.customView.view == "detailview"){
            return sticCVUtils.inline_edit(fieldContent, !readonly);
        }

        var oldReadonly = fieldContent.is_readonly();
        if(readonly!=oldReadonly) {
            fieldContent.showEditor(!readonly);
            fieldContent.showReadOnlyLabel(readonly);
        }
        return this;
    }

    static inline_edit(fieldContent, inline_edit=true) {
         //IEPA!!
         console.log("Inline not available. Requested:" + inline_edit);
         return false;
    }

    static required(field, required=true) {
        var oldRequired = sticCVUtils.getRequiredStatus(field);
        var newRequired = required===true||required==="1"||required===1;

        var customView = field.customView;
        if(newRequired) {
            addToValidate(customView.formName, field.name, field.content.type, true, SUGAR.language.get('app_strings', 'ERR_MISSING_REQUIRED_FIELDS'));
            field.header.$element.addClass("conditional-required");
        } else {
            removeFromValidate(customView.formName, field.name);
            field.header.$element.removeClass("conditional-required");
        }
        if(oldRequired!=newRequired) {
            customView.addUndoFunction(function() { sticCVUtils.required(field, oldRequired); });
        }
        return this;
    }
    static getRequiredStatus(field) {
        var validateFields = validate[field.customView.formName];
        for (i = 0; i < validateFields.length; i++) {
            // Array(name, type, required, msg);
            if (validateFields[i][0] == field.name) {
                return validateFields[i][2];
            }
        }
        return false;
    }

    static onChange($elem, callback, alsoInline=false) {
        $elem.each(function(){
            $(this).on("change paste keyup", callback);
            YAHOO.util.Event.on($(this)[0], 'change', callback);
            if(!$(this).is(":input")||alsoInline) {
                var observer = new MutationObserver(callback);
                observer.observe($(this)[0], {attributes:true, childList:true, subtree:true, characterData:true});
            }
        });
        return $elem.length>0;
    }
    static change($elem) {
        $elem.each(function(){
            $(this).change();
        });
        return $elem.length>0;
    }

    /**
     * Return key value for label in app_list_stringsName array
     *
     * @param String app_list_stringsName $app_list_string to search in
     * @param String label The label to be searched (in current language)
    */
    static getListValueFromLabel(app_list_stringsName, label) {
        var res;
        $.each(SUGAR.language.languages.app_list_strings[app_list_stringsName], function (l, k) {
            if (k == label) {
                res = l;
            }
        });
        return res;
    }
}