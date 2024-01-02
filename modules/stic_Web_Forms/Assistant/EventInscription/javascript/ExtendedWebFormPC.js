/**
 * Overload the data validation function to include the validation of the payment methods fields
 */
(function() {
  var tmpCheckFields = checkFields;
  checkFields = function() {
    if (tmpCheckFields) {
      return tmpCheckFields() && validateIBAN();
    }
    return validateIBAN();
  };
})();

/**
 * Adapt the form based on the value of the payment method
 */
function adaptPaymentMethod(oPaymentMethod) {
  // Retrieve the html element of payment method
  var vPaymentMethod = oPaymentMethod.options[oPaymentMethod.selectedIndex].value;

  // If the payment method is a direct debit, it shows the account number field and marks it as required.
  if (vPaymentMethod == "direct_debit") {
    showField("stic_Payment_Commitments___bank_account");
    addRequired("stic_Payment_Commitments___bank_account");
  } else {
    hideField("stic_Payment_Commitments___bank_account");
    removeRequired("stic_Payment_Commitments___bank_account");
  }
  oPaymentMethod.prev_value = vPaymentMethod;
}
