var formHasAlreadyBeenSent = false;
/**
 * Form submission function
 * @param form form to be sent
 */
function submitForm(form) {
  if (grecaptcha && grecaptcha.getResponse().length !== 0 && 
      checkFields() && checkFormSize()) {
    if (typeof validateCaptchaAndSubmit != "undefined") {
      validateCaptchaAndSubmit();
    } else {
      if (formHasAlreadyBeenSent != true) {
        formHasAlreadyBeenSent = true;
        form.submit();
      } else {
        console.log("Form is locked because it has already been sent.");
      }
    }
  }
  return false;
}