$(document).ready(function() {
  //   var dat = [{
  //       label: 'Node 1',
  //       children: [{
  //           label: 'Child 1'
  //       }, {
  //           label: 'Child 2'
  //       }]
  //   }, {
  //       label: 'Node 2',
  //       children: [{
  //           label: 'Child 3'
  //       }]
  //   }];

  $(function() {
    console.log("tree");
    // $("#tree1").tree({
    //   data: data[0],
    //   dragAndDrop: true,
    //   saveState: true
    // //   autoOpen: true
    // });
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
});
