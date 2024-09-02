{*
This file is part of SinergiaCRM.
SinergiaCRM is a work developed by SinergiaTIC Association, based on SuiteCRM.
Copyright (C) 2013 - 2023 SinergiaTIC Association
This program is free software; you can redistribute it and/or modify it under
the terms of the GNU Affero General Public License version 3 as published by the
Free Software Foundation.
This program is distributed in the hope that it will be useful, but WITHOUT
ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
details.
You should have received a copy of the GNU Affero General Public License along with
this program; if not, see http://www.gnu.org/licenses or write to the Free
Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
02110-1301 USA.
You can contact SinergiaTIC Association at email address info@sinergiacrm.org.

*}


{* JSTREE *}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>
<link rel="stylesheet" href="custom/modules/Studio/TabGroups/SticAdvancedMenuEdit.css" />

<script>
	var jsonMenu ='{$jsonMenu|escape:'javascript'}'
	var menu = [JSON.parse(jsonMenu)]

	var jsonAll ='{$jsonAll|escape:'javascript'}'
	var allModules = [JSON.parse(jsonAll)]
</script>
<script type="text/javascript" src="{sugar_getjspath file='custom/modules/Studio/TabGroups/SticAdvancedMenuEdit.js'}">
</script>

<div id="stic-menu" class="content">

    <div class="moduleTitle">
	    <h2>{$MOD.LBL_STIC_MENU_CONFIGURE_TITLE}</h2>
    </div>

	<div id="menu-buttons" class="btn-group btn-group-justified" role="group">
		<div class="row">

			<button id="save-menu" type="button" class="btn btn-md btn-default text-uppercase">{$MOD.LBL_STIC_MENU_SAVE}
				<span class="glyphicon glyphicon-ok text-success"></span>
			</button>

			<button id="restore-menu" type="button"
				class="btn btn-md btn-default text-uppercase">{$MOD.LBL_STIC_MENU_RESTORE}</button>

			{* 
			The button to switch to the old menu remains hidden, but all the necessary logic to activate it
   			in the context of SuiteCRM is maintained if needed
   			<button class="btn btn-link" id="legacy-menu">{$MOD.LBL_STIC_MENU_CHANGE_TO_LEGACY}</button> 
			*}

		</div>

        {* <div class="panel panel-default"> *}
		<div id="menu-options">
			<div class="col-xs-4 ">
				<div class="checkbox">
					<label>
						<input id="stic_advanced_menu_icons" type="checkbox" {if $sticAdvancedMenuIcons}checked{/if}>
					</label>
				</div>
				<strong>{$MOD.LBL_STIC_MENU_ICONS}</strong>
			</div>
			<div class="col-xs-4 ">
				<div class="checkbox">
					<label>
						<input id="stic_advanced_menu_all" type="checkbox" {if $sticAdvancedMenuAll}checked{/if}>
					</label>
				</div>
				<strong>{$MOD.LBL_STIC_MENU_ALL}</strong>
			</div>
		</div>
		{* </div> *}
	</div>
	
    <div class="panel col-md-8" id="enabled-modules">

		<div class="panel-heading">
			<h3 class="panel-title">{$MOD.LBL_STIC_MENU_ENABLED_INCLUDED}</h3>
		</div>
		
		<div class="panel-body col-md-8">
			<div id="stic-menu-manager">
			</div>
			<div id="menu-config-info">{$MOD.LBL_STIC_MENU_INFO}</div>
		</div>
	
    </div>

    <div class="panel panel-primary col-md-4" id="not-included-modules">
		<div class="panel-heading">
			<h3 class="panel-title">{$MOD.LBL_STIC_MENU_ENABLED_NOT_INCLUDED}</h3>
		</div>
		<div class="panel-body">
			<div id="hidden-modules">
			</div>
		</div>
	</div>

</div>
