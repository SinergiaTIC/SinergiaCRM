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
<script type="text/javascript" src="{sugar_getjspath file='custom/modules/Studio/TabGroups/SticAdvancedMenuEdit.js'}"></script>
<div id="stic-menu">

	<h2>{$MOD.LBL_STIC_MENU_CONFIGURE_TITLE}</h2>
	<small>{$MOD.LBL_STIC_MENU_INFO}</small>

	<div class="row">
		<div class="panel panel-default col-md-7">
			<div id="menu-buttons" class="row">
				<div class="col-xs-4">
					<button id="save-menu" type="button"
						class="btn btn-md btn-default btn-block">{$MOD.LBL_STIC_MENU_SAVE}
						<span class="glyphicon glyphicon-ok text-success"></span>
					</button>
				</div>
				<div class="col-xs-4">
					<button id="restore-menu" type="button"
						class="btn btn-md btn-default btn-block">{$MOD.LBL_STIC_MENU_RESTORE}</button>
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