$(document).ready(function() {
  $(function() {
    // Create js menu
    $("#stic-menu-manager").jstree({
      core: {
        data: menu[0],
        check_callback: true,
        themes: {
          icons: false,
          dots: true
        }
      },
      plugins: ["dnd", "wholerow", "contextmenu"],
      contextmenu: {
        items: function($node) {
          var tree = $("#stic-menu-manager").jstree(true);
          return {
            Create: {
              separator_before: false,
              separator_after: true,
              label: "<i class='glyphicon glyphicon-plus'></i> Crear",
              action: function(obj) {
                $node = tree.create_node($node);
                tree.edit($node);
              }
            },
            Rename: {
              separator_before: false,
              separator_after: true,
              label: "<i class='glyphicon glyphicon-pencil'></i> Renombrar",
              action: function(obj) {
                tree.edit($node);
              }
            },
            Delete: {
              separator_before: false,
              separator_after: false,
              label: "<i class='glyphicon glyphicon-remove'></i> Eliminar",
              action: function(obj) {
                if (tree.is_selected($node)) {
                  tree.delete_node(tree.get_selected());
                } else {
                  tree.delete_node($node);
                }
              }
            }
          };
        }
      }
    });

    $("#hidden-modules").jstree({
      core: {
        data: allModules[0],
        themes: {
          icons: false
        },
        check_callback: true
      },
      plugins: ["dnd", "wholerow", "search", "unique"]
    });
  });

  $("#restore-menu").on("click", function() {
    if (confirm("¿Restaurar el menu por defecto de SinergiaCRM?") == false) {
      return;
    }

    // Define los datos que enviarás en la petición
    var dataToSend = {
      manageMode: "restore" // Reemplaza con el modo de guardar adecuado
    };

    // Realiza la petición AJAX
    $.ajax({
      url: location.href.slice(0, location.href.indexOf(location.search)),
      type: "POST",
      data: {
        module: "Studio",
        action: "SticManageTabs",
        ...dataToSend
      },
      success: function(response) {
        console.log("Respuesta recibida:", response);
        location.reload(true);
        // setTimeout(function() {
          
          
        //   location.reload(true);
        // }, 100);
      },
      error: function(xhr, status, error) {
        console.error("Error en la petición:", status, error);
      }
    });
  });

  // Button command
  $("#save-menu").on("click", function() {
    var $cleanMenu = $("#stic-menu-manager").jstree(true).get_json().map(filterNodes);

    // Define los datos que enviarás en la petición
    var dataToSend = {
      menuJson: JSON.stringify($cleanMenu), // Reemplaza con el valor real que deseas enviar
      manageMode: "save" // Reemplaza con el modo de guardar adecuado
    };

    // Realiza la petición AJAX
    $.ajax({
      url: location.href.slice(0, location.href.indexOf(location.search)),
      type: "POST",
      data: {
        module: "Studio",
        action: "SticManageTabs",
        ...dataToSend
      },
      success: function(response) {
        console.log("Respuesta recibida:", response);
        location.reload(true);
      },
      error: function(xhr, status, error) {
        console.error("Error en la petición:", status, error);
      }
    });

   

   

  });

  // Cambiar el texto y cambiar el id
  $("#stic-menu-manager").on("rename_node.jstree", function(e, data) {
    var tree = $("#stic-menu-manager").jstree(true);
    var newId = data.text.replace(/\s+/g, "_");

    // Cambiar el ID directamente
    tree.set_id(data.node, newId);

    // Imprimir el ID actual del nodo para verificar
    console.log("Nuevo ID establecido:", newId);
    console.log("ID actual del nodo:", data.node.id); // Verificar el ID después de intentar cambiarlo
  });

  $("#stic-menu-manager").on("rename_node.jstree", handleTreeChanges);
  $("#stic-menu-manager").on("move_node.jstree", handleTreeChanges);
  $("#stic-menu-manager").on("delete_node.jstree", handleTreeChanges);
  $("#stic-menu-manager").on("create_node.jstree", handleTreeChanges);

  
  


  setTimeout(() => {
    $('#saved-notice').fadeOut(3000);
  }, 3000);
});

function filterNodes(node) {
  // Verificar si el nodo es un objeto
  if (typeof node === "object" && node !== null) {
    // Filtrar las claves del objeto
    const keys = Object.keys(node);
    // Iterar sobre las claves y eliminar aquellas que no sean "id" o "children"
    for (const key of keys) {
      if (key !== "id" && key !== "children") {
        delete node[key];
      } else {
        // Si la clave es "children", iterar sobre los hijos y filtrarlos también
        if (key === "children") {
          node[key] = node[key].map(filterNodes);
          // Si children es un array vacío, eliminar la clave "children"
          if (node[key].length === 0) {
            delete node[key];
          }
        }
      }
    }
  }
  return node;
}

function handleTreeChanges(e, data) {
  console.log("Se ha modificado el árbol:", data);
  // Aquí puedes añadir cualquier lógica adicional que necesites ejecutar
  $("#save-menu .glyphicon")
    .addClass("glyphicon-save")
    .removeClass("glyphicon-ok")
    .addClass("text-danger")
    .removeClass("text-success");
}
