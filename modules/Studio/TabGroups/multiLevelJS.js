$(document).ready(function() {
  var dat = [{
      label: 'Node 1',
      children: [{
          label: 'Child 1'
      }, {
          label: 'Child 2'
      }]
  }, {
      label: 'Node 2',
      children: [{
          label: 'Child 3'
      }]
  }];
console.log(dat);
console.log(data[0]);
  $(function() {
    console.log("tree");
    $("#tree1").tree({
      data: data[0]
    });
  });
});
