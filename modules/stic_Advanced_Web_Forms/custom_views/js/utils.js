class utils {
  static translate(label) {
    debugger;
    return (
      SUGAR.language.languages.stic_Advanced_Web_Forms[label] ?? SUGAR.language.languages.app_strings[label] ?? label
    );
  }

}
