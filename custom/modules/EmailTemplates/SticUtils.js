/* HEADER */
// Set module name
var module = "EmailTemplates";

/* VIEWS CUSTOM CODE */
switch (viewType()) {
    case "edit":
    case "quickcreate":
    case "popup":    
        $(document).ready(function() {
            function toggleHtmlVisibility() {
                var selectedValue = $("[name=type").val();
                
                if (selectedValue === 'sms') {
                    // $('#myDiv').hide();
                    $("#toggle_textonly").prop("checked", true);
                    $("#toggle_textonly").attr("disabled", true);
                    $("#text_only").val(1);
                    toggle_text_only();
                } else {
                    // $('#myDiv').show();
                    $("#toggle_textonly").prop("checked", false);
                    $("#toggle_textonly").attr("disabled", false);
                    $("#text_only").val(0);
                    toggle_text_only();
                }
                

            }

            // Call the function on page load
            toggleHtmlVisibility();

            // Call the function when the select value changes
            $("[name=type").change(toggleHtmlVisibility);
        });
  
      break;
  
    case "detail":
      // Set autofill mark beside field label
      break;
  
    case "list":
      break;
    default:
      break;
  }
  

