var formHasAlreadyBeenSent = false;
/**
 * Form submission function
 * @param form form to be sent
 */
function submitForm(form) {
  grecaptcha.execute('<SITE_KEY>', {}).then(function (token) {
    // Agregamos el token al campo del formulario
    const recaptchaInput = document.createElement("input");
    recaptchaInput.type = "hidden";
    recaptchaInput.name = "g-recaptcha-response";
    recaptchaInput.value = token;
    recaptchaForm = document.querySelector("#WebToLeadForm");
    recaptchaForm.appendChild(recaptchaInput);

    if (checkFields() && checkFormSize()) {
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
  });
  return false;
}