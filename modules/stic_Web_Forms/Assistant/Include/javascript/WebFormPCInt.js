/**
 * File for the unconditional validation of bank accounts, for use when the CRM Setting GENERAL_IBAN_VALIDATION is set to 0 at the time of generating the form.
 * @returns {Boolean}
 */
function validateIBAN() {
  // If the type of payment is not direct debit, the IBAN must not be validated
  if (document.getElementById("stic_Payment_Commitments___payment_method").value == "direct_debit") {
    var bankAccount = document.getElementById("stic_Payment_Commitments___bank_account");
    bankAccount.value = bankAccount.value.toUpperCase();
    if (bankAccount == null) {
      // If there is no account number it will give error
      return false;
    }
  }
  return true;
}
