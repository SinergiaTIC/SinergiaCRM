
function getModuleVarList(module) {
      var dataString = 'moduloPlantilla='+ module;
      
      ajaxStatus.showStatus(SUGAR.language.get('app_strings', 'LBL_LOADING'));
      $.ajax({
         type: "POST",
         url: "index.php?module=DHA_PlantillasDocumentos&action=modulevarlist",
         data: dataString,
         dataType: "html",
         cache: false,
         success: function(data) {
            $("#VarList_tabs").html(data);   
         }
      });
      ajaxStatus.hideStatus();
}

$("#modulo").change(function () {
   var module = $("select option:selected").val();
   $("#VarList_tabs").text(module);
   getModuleVarList(module);
});


