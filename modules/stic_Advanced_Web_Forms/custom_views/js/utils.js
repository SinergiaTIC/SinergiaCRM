class utils {
  /**
   * Translate current label to current user language
   * @param {string} label The label to be translated
   * @returns Translated label
   */
  static translate(label) {
    return (
      SUGAR.language.languages.stic_Advanced_Web_Forms[label] ?? SUGAR.language.languages.app_strings[label] ?? label
    );
  }

  /**
   * Translate current label to current user language to put it in a label for a field
   * @param {string} label The label to be translated
   * @returns {string} Translated label
   */
  static translateForFieldLabel(label) {
    let translated = utils.translate(label).trim();
    return utils.toFieldLabelText(translated);
  }

  /**
   * Ensures the text hs : at the end, to put in a label for a field
   * @param {string} text 
   * @returns  {string}
   */
  static toFieldLabelText(text) {
    if (text && !text.endsWith(":")) {
      text += ":";
    }
    return text;
  }

  /**
   * Decode string with html entities (&quot; &lbrace; ...)
   * @param {string} string The string with HTML entities
   * @returns {string} Decoded string
   */
  static decodeHTMLString(string) {
    const parser = new DOMParser();
    const doc = parser.parseFromString(`<!doctype html><body>${string}`, "text/html");
    return doc.body.textContent;
  }

  /**
   * Gets the defined list in app_list_strings
   * @param {string} listName The list name
   * @param {bool} asString If the list must be returned as the name of the list
   * @returns The list or the entire list name
   */
  static getList(listName, asString = false) {
    if (asString) {
      return `SUGAR.language.languages.app_list_strings.${listName}`;
    }
    return Object.entries(SUGAR.language.languages.app_list_strings[listName]).map(([k, v]) => ({ id: k, text: v }));
  }

  /**
   * Get options for a field
   * @param {FieldInformation} fieldInfo 
   * @param {boolean} [asString=false] 
   * @returns array or name of options [id, text]
   */
  static getFieldOptions(fieldInfo, asString=false) {
    if (fieldInfo == undefined) {
      return asString ? "" : [];
    }
    if (fieldInfo.type == "bool") {
      return utils.getList("stic_boolean_list", asString);
    }
    if (fieldInfo.options && (fieldInfo.type == "enum" || fieldInfo.type == "multienum")) {
      return utils.getList(fieldInfo.options, asString);
    }
    return asString ? "" : [];
  }

  static _cachedModules = {};
  /**
   * Retrieves fields and relationships of given Module
   * @param {string} moduleName The name of the module
   * @returns Module Information
   * Result: [name, text, textSingular, inStudio, icon, fields:[Field], relationships:[Relationship]]
   *   Field: {
   *     name, text, type, required, options, inViews
   *   }
   *   Relationship: {
   *     name, text, module_orig, field_orig, relationship, module_dest
   *   }
   */
  static getModuleInformation(moduleName) {
    // Do not get info of not enabled modules
    if (!moduleName || !STIC.enabledModules.hasOwnProperty(moduleName)) {
      return null;
    }

    if (!utils._cachedModules.hasOwnProperty(moduleName)) {
      $.ajax({
        url: "index.php",
        type: "POST",
        async: false,
        dataType: "json",
        data: {
          module: "stic_Advanced_Web_Forms",
          action: "getModuleInformation",
          getmodule: moduleName,
          getavailablemodules: JSON.stringify(STIC.enabledModules),
        },
        success: function (response) {
          utils._cachedModules[moduleName] = response;
        },
        error: function (xhr, status, error) {
          console.error(
            "Error retrieving Information for module: '" + moduleName + "'",
            status,
            error,
            xhr.responseText
          );
        },
      });
    }

    if (utils._cachedModules.hasOwnProperty(moduleName)) {
      return utils._cachedModules[moduleName];
    }

    return null;
  }

  static newId(prefix = "") {
    return prefix + Date.now().toString(36) + Math.random().toString(36).substring(2);
  }
}
