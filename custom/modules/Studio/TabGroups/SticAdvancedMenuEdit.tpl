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
<div id="stic-menu">

	<h2>{$MOD.LBL_STIC_MENU_CONFIGURE_TITLE}</h2>
	<small>{$MOD.LBL_STIC_MENU_INFO}</small>

	<div id="menu-buttons" class="btn-group btn-group-justified" role="group">
	<div class="row">

			<button id="save-menu" type="button" class="btn btn-md btn-default ">{$MOD.LBL_STIC_MENU_SAVE}
				<span class="glyphicon glyphicon-ok text-success"></span>
			</button>


			<button id="restore-menu" type="button"
				class="btn btn-md btn-default ">{$MOD.LBL_STIC_MENU_RESTORE}</button>

			
			{* 
			The button to switch to the old menu remains hidden, but all the necessary logic to activate it
   			in the context of SuiteCRM is maintained if needed
   			<button class="btn btn-link" id="legacy-menu">{$MOD.LBL_STIC_MENU_CHANGE_TO_LEGACY}</button> 
			*}



		</div>
		<div class="panel panel-default col-md-7">
			<div id="menu-options">
				<div class="col-xs-4 text-center">
					<div class="checkbox">
						<label>
							<input id="stic_advanced_menu_icons" type="checkbox"
								{if $sticAdvancedMenuIcons}checked{/if}>
						</label>
					</div>
					{$MOD.LBL_STIC_MENU_ICONS}
				</div>
				<div class="col-xs-4 text-center">
					<div class="checkbox">
						<label>
							<input id="stic_advanced_menu_all" type="checkbox" {if $sticAdvancedMenuAll}checked{/if}>
						</label>
					</div>
					{$MOD.LBL_STIC_MENU_ALL}
				</div>
			</div>
			<div class="panel-body">
				<div id="stic-menu-manager">
				</div>

			</div>
		</div>
		<div class="col-md-1"></div>
		<div class="panel panel-primary col-md-4">
			<div class="panel-heading">
				<h3 class="panel-title">{$MOD.LBL_STIC_MENU_HIDDEN_MODULES}</h3>
			</div>
			<div class="panel-body">
				<div id="hidden-modules">
				</div>
			</div>
		</div>

	</div>
</div>