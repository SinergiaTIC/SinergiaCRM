/**
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2018 SalesAgility Ltd.
 *
 * SinergiaCRM is a work developed by SinergiaTIC Association, based on SuiteCRM.
 * Copyright (C) 2013 - 2023 SinergiaTIC Association
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
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
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * You can contact SinergiaTIC Association at email address info@sinergiacrm.org.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo, "Supercharged by SuiteCRM" logo and “Nonprofitized by SinergiaCRM” logo.
 * If the display of the logos is not reasonably feasible for technical reasons,
 * the Appropriate Legal Notices must display the words "Powered by SugarCRM",
 * "Supercharged by SuiteCRM" and “Nonprofitized by SinergiaCRM”.
 */var aclviewer=function(){var lastDisplay='';return{view:function(role_id,role_module){YAHOO.util.Connect.asyncRequest('POST','index.php',{'success':aclviewer.display,'failure':aclviewer.failed},'module=ACLRoles&action=EditRole&record='+role_id+'&category_name='+role_module);ajaxStatus.showStatus(SUGAR.language.get('app_strings','LBL_REQUEST_PROCESSED'));},save:function(form_name){var formObject=document.getElementById(form_name);YAHOO.util.Connect.setForm(formObject);YAHOO.util.Connect.asyncRequest('POST','index.php',{'success':aclviewer.postSave,'failure':aclviewer.failed});aclviewer.showMessageACLRoles(SUGAR.language.get("app_strings","LBL_SAVING")+"...");},postSave:function(o){SUGAR.util.globalEval(o.responseText);aclviewer.view(result['role_id'],result['module']);aclviewer.hideMessageACLRoles();aclviewer.showMessageACLRoles(SUGAR.language.get("app_strings","LBL_REQUEST_PROCESSED"));setTimeout(()=>{aclviewer.hideMessageACLRoles();},2000);},display:function(o){aclviewer.lastDisplay='';ajaxStatus.flashStatus('Done');document.getElementById('category_data').innerHTML=o.responseText;},failed:function(){ajax.flashStatus('Could Not Connect');},toggleDisplay:function(id){if(aclviewer.lastDisplay!=''&&typeof(aclviewer.lastDisplay)!='undefined'){aclviewer.hideDisplay(aclviewer.lastDisplay);}
if(aclviewer.lastDisplay!=id){aclviewer.showDisplay(id);aclviewer.lastDisplay=id;}else{aclviewer.lastDisplay='';}},hideDisplay:function(id){document.getElementById(id).style.display='none';document.getElementById(id+'link').style.display='';},showDisplay:function(id){document.getElementById(id).style.display='';document.getElementById(id+'link').style.display='none';},showMessageACLRoles:function(message){SUGAR.ajaxUI.loadingPanel=new YAHOO.widget.Panel("ajaxloading",{width:"240px",close:false,draggable:false,constraintoviewport:false,modal:true,visible:false});SUGAR.ajaxUI.loadingPanel.setBody('<div class="acl-saving-message"><b>'+message+'</b></div>');SUGAR.ajaxUI.loadingPanel.render(document.body);SUGAR.ajaxUI.loadingPanel.show();var panelElement=document.getElementById('ajaxloading');if(panelElement){panelElement.style.position='fixed';panelElement.style.top='200px';panelElement.style.left='50%';panelElement.style.transform='translateX(-50%)';panelElement.style.zIndex='99999';}
var maskElement=document.querySelector('.mask');if(maskElement){maskElement.style.zIndex='99998';}},hideMessageACLRoles:function(){SUGAR.ajaxUI.loadingPanel.hide();},filterCategories:function(filterValue){var filter=filterValue.toUpperCase();var table=document.querySelector('#acl-scroll-wrapper table');var rows=table.getElementsByTagName('tr');var hasFilter=filterValue.trim()!=='';var clearButton=document.getElementById('clearFilter');var visibleCount=0;var totalCount=0;if(typeof(Storage)!=="undefined"){localStorage.setItem('aclRolesCategoryFilter',filterValue);}
clearButton.style.display=hasFilter?'inline':'none';for(var i=0;i<rows.length;i++){var row=rows[i];if(row.id==='ACLEditView_Access_Header'){continue;}
if(row.id&&row.id.indexOf('ACLEditView_Access_')===0){totalCount++;var categoryCell=row.querySelector('td[id$="_category"]');if(categoryCell){var categoryText=categoryCell.textContent||categoryCell.innerText;if(categoryText.toUpperCase().indexOf(filter)>-1){row.style.display='';visibleCount++;}else{row.style.display='none';}}}}},clearCategoryFilter:function(){var filterInput=document.getElementById('categoryFilter');if(filterInput){filterInput.value='';aclviewer.filterCategories('');}},restoreFilter:function(){if(typeof(Storage)!=="undefined"){var savedFilter=localStorage.getItem('aclRolesCategoryFilter');if(savedFilter){var filterInput=document.getElementById('categoryFilter');if(filterInput){filterInput.value=savedFilter;aclviewer.filterCategories(savedFilter);}}}}};}();(function(){var originalDisplay=aclviewer.display;aclviewer.display=function(o){originalDisplay(o);setTimeout(function(){aclviewer.restoreFilter();},50);setTimeout(function(){aclviewer.restoreFilter();},200);};window.addEventListener('load',function(){aclviewer.restoreFilter();});window.addEventListener('DOMContentLoaded',function(){aclviewer.restoreFilter();});setTimeout(function(){aclviewer.restoreFilter();},100);setTimeout(function(){aclviewer.restoreFilter();},300);})();