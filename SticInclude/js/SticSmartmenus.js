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

$(document).ready(function() {
  $("#stic-menu").smartmenus({
    subMenusSubOffsetX: 1,
    subMenusSubOffsetY: -8
  });

  // Actualizar el menú cuando se abra
  $("#stic-menu > li:first-child > a").on("click", function(e) {
    e.preventDefault();
    updateActionMenu();
  });

  // También actualizar el menú en la carga inicial
  updateActionMenu();
});

function buildActionMenu() {
  var $newActionMenu = $("<ul>");

  $("#actionMenuSidebar ul li.actionmenulinks").each(function() {
    var $originalLink = $(this).find("a");
    var $icon = $originalLink.find(".side-bar-action-icon span").clone();
    var linkText = $originalLink.find(".actionmenulink").text();

    var $newLink = $("<a>")
      .attr("href", $originalLink.attr("href"))
      .attr("data-action-name", $originalLink.data("action-name"));

    $newLink.append($icon).append(linkText);

    var $newListItem = $("<li>").append($newLink);
    $newActionMenu.append($newListItem);
  });

  return $newActionMenu;
}

// Función para actualizar el menú de acciones
function updateActionMenu() {
  var $newActionMenu = buildActionMenu();
  var $actionsArea = $("#stic-menu #actions-area");

  // Añadir el nuevo menú de acciones
  $actionsArea.prepend($newActionMenu.children());
}
