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
        if($elem.length==0) return;
        var visible = ($elem.css('display') != 'none');
        if(show===true||show==="1"||show===1) {
            if(!visible) {
                $elem.show();
                customView?.addUndoFunction(function() { $elem.hide(); });
            }
        } else {
            if(visible) {
                $elem.hide();
                customView?.addUndoFunction(function() { $elem.show(); });
            }
        }
    }
    static color($elem, customView=null, color="") {
        if($elem.length==0) return;
        $elem.css("color", color);
        customView?.addUndoFunction(function() { $elem.css('color', ''); });
    }
    static background($elem, customView=null, color="") { 
        if($elem.length==0) return;
        $elem.css("background-color", color); 
        customView?.addUndoFunction(function() { $elem.css("background-color", ''); });
    }
    static bold($elem, customView=null, bold=true) {
        if($elem.length==0) return;
        if(bold===true||bold==="1"||bold===1) {
            $elem.css('font-weight', 'bold');
            customView?.addUndoFunction(function() { $elem.css('font-weight', ''); });
        } else {
            $elem.css('font-weight', 'normal');
            customView?.addUndoFunction(function() { $elem.css('font-weight', ''); });
        }
    }
    static italic($elem, customView=null, italic=true) {
        if($elem.length==0) return;
        if(italic===true||italic==="1"||italic===1) {
            $elem.css('font-style', 'italic');
            customView?.addUndoFunction(function() { $elem.css('font-style', ''); });
        } else {
            $elem.css('font-style', 'normal');
            customView?.addUndoFunction(function() { $elem.css('font-style', ''); });
        }
    }
    static underline($elem, customView=null, underline=true) {
        if($elem.length==0) return;
        if(underline===true||underline==="1"||underline===1) {
            $elem.css('text-decoration', 'underline');
            customView?.addUndoFunction(function() { $elem.css('text-decoration', ''); });
        } else {
            $elem.css('text-decoration', 'none');
            customView?.addUndoFunction(function() { $elem.css('text-decoration', ''); });
        }
    }
    static style($elem, customView=null, style="") {
        if($elem.length==0) return;
        var oldStyle = $elem.attr('style');
        $elem.css(style);
        customView?.addUndoFunction(function() { $elem.attr('style', oldStyle); });
    }
    static frame($elem, customView=null, frame=true){
        if($elem.length==0) return;
        if(frame===true||frame==="1"||frame===1) {
            $elem.css({"border-color": "orangered", "border-style": "dashed"});
            customView?.addUndoFunction(function() { $elem.css({"border-color": "", "border-style": ""}); });
        } else {
            $elem.css({"border-color": "", "border-style": ""});
            customView?.addUndoFunction(function() { $elem.css({"border-color": "", "border-style": ""}); });
        }
    }
    static text($elem, customView=null, newText) {
        if($elem.length==0) return "";
        var oldText = $elem.text();
        if(newText===undefined || newText!=oldText) {
            return oldText;
        }
        var text = $elem.text(newText);
        customView?.addUndoFunction(function() { $elem.text(oldText); });

        return text;
    }

    static value(fieldContent, newValue) {
        sticCVUtils.setValue(fieldContent, newValue);
        return sticCVUtils.getValue(fieldContent);
    }

    static getValue(fieldContent) {
        var $elem = fieldContent.$editor;
        if($elem.length==0) {
            $elem = fieldContent.$element;
        }
        var typeArray = fieldContent.type.split('|');
        switch (typeArray[0]) {
            case "radioenum":
                var $radio = $elem.parent().find("[type='radio']:checked");
                if($radio.length!=0) {
                    return $radio.val();
                } else if(typeArray.length>1) {
                    return sticCVUtils.getListValueFromLabel(typeArray[1], trim($elem.text()));
                }
                break;
            case "multienum":
                var valueArray = $elem.map(function(){return $(this).val();}).get();
                return valueArray[valueArray.length-1];
            case "bool":
                return $elem.prop("checked");
        }
        return $elem.val();
    }
    static setValue(fieldContent, newValue) {
        if(newValue===undefined) return;

        var oldValue = sticCVUtils.getValue(fieldContent);
        if(newValue!=oldValue) {
            // Set new value
            var $elem = fieldContent.$editor;
            if($elem.length==0) {
                $elem = fieldContent.$element;
            }
            var typeArray = fieldContent.type.split('|');
            var customView = fieldContent.customView;

            switch (typeArray[0]) {
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

    static onChange($elem, callback) {
        if($elem.length==0) return false;
        $elem.on("change paste keyup", function() { callback();});
        return true;
    }
    static change($elem) {
        if($elem.length==0) return false;
        $elem.change();
        return true;
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