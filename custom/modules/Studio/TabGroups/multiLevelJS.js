$(document).ready(function() {
  $(function() {
    // Create js menu
    $("#menu-modules").jstree({
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
          var tree = $("#menu-modules").jstree(true);
          return {
            Create: {
              separator_before: false,
              separator_after: false,
              label: "<i class='glyphicon glyphicon-plus'></i>",
              action: function(obj) {
                $node = tree.create_node($node);
                tree.edit($node);
              }
            },
            Rename: {
              separator_before: false,
              separator_after: false,
              label: "<i class='glyphicon glyphicon-pencil'></i>",
              action: function(obj) {
                tree.edit($node);
              }
            }
            // "Delete" botón ha sido eliminado para no aparecer en el menú
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

  // Button command
  $("#save-menu").on("click", function() {
    var $cleanMenu = $("#menu-modules").jstree(true).get_json().map(filterNodes);

    // Define los datos que enviarás en la petición
    var dataToSend = {
      menuJson: JSON.stringify($cleanMenu), // Reemplaza con el valor real que deseas enviar
      saveMode: "algo" // Reemplaza con el modo de guardar adecuado
    };

    // Realiza la petición AJAX
    $.ajax({
      url: "http://localhost:2000/sinergiacrm/index.php",
      type: "POST",
      data: {
        module: "Studio",
        action: "SticSaveTabs",
        ...dataToSend
      },
      success: function(response) {
        console.log("Respuesta recibida:", response);
      },
      error: function(xhr, status, error) {
        console.error("Error en la petición:", status, error);
      }
    });
  });
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
