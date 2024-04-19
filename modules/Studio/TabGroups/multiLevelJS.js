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
    $("#tree1").tree({
      data: data[0],
      dragAndDrop: true,
      saveState: true
    //   autoOpen: true
    });
  });
});
