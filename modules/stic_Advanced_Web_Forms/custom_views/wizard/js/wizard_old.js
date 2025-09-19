function wizardForm(readOnly) {
  return {
    navigation: {
      step: 1,
      totalSteps: 4,
    },

    bean: STIC.record || {},
    formConfig: {},

    // [name, text, textSingular, inStudio, icon]
    step1: {},
    step2: {
      // RelatedModule: {
      //   id, name, text, isRelation, moduleDestName, moduleDestText, moduleSourceName, moduleSourceText, path, pathText,
      //   fields: [name: {name, text, type, required, options, inViews}],
      //   relationships: [name: {name, text, fieldName, relationship, moduleName, moduleText}]
      // }
      loadingTree: true,
      treeShowAllModules: false,
      treeSelectedData: null,

      // IEPA!!
      // TODO: Remove!!!
      relationshipsInDataBlocks: [],
      modulesInDataBlocks: [],
    },
    step3: {},
    step4: {},

    async initWizard() {
      // Set Context accessible
      window.alpineComponent = this;

      // Set config object
      let jsonString = "{}";
      if (this.bean?.configuration) {
        jsonString = utils.decodeHTMLString(this.bean.configuration);
      }
      this.formConfig = AWF_Configuration.fromJSON(jsonString);

      // Load current Step
      WizardNavigation.loadStep();
    },
  };
}
class WizardNavigation {
  static cacheSteps = [];

  static async loadStep() {
    const step = window.alpineComponent.navigation.step;
    const totalSteps = window.alpineComponent.navigation.totalSteps;

    if (step <= 0 || step > totalSteps) {
      return;
    }

    if (!(step in WizardNavigation.cacheSteps)) {
      WizardNavigation.cacheSteps[step] = await (
        await fetch(`modules/stic_Advanced_Web_Forms/custom_views/wizard/steps/step${step}.html`)
      ).text();
    }

    $("#wizard-section-title").text(utils.translate(`LBL_WIZARD_TITLE_STEP${step}`) + ` (${step}/${totalSteps})`);

    let $el = document.getElementById("wizard-step-container");
    $el.innerHTML = WizardNavigation.cacheSteps[step];

    // Initialize Alpine.js over new content
    Alpine.initTree($el);
  }

  static enabled(action) {
    const step = window.alpineComponent.navigation.step;
    const totalSteps = window.alpineComponent.navigation.totalSteps;

    if (action == "prev") {
      return step > 1;
    }
    if (action == "next") {
      return step < totalSteps;
    }
  }

  static prev() {
    if (WizardNavigation.enabled("prev")) {
      window.alpineComponent.navigation.step--;
      WizardNavigation.loadStep();
    }
  }

  static next() {
    if (WizardNavigation.enabled("next")) {
      let allOk = true;
      document.querySelectorAll("#wizard-step-container form").forEach(function (f) {
        allOk &= f.reportValidity();
      });
      if (allOk) {
        window.alpineComponent.navigation.step++;
        WizardNavigation.autoSave();
        WizardNavigation.loadStep();
      }
    }
  }

  static async autoSave() {
    const response = await fetch("index.php?module=stic_Advanced_Web_Forms&action=saveDraft", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        bean: window.alpineComponent.bean,
        config: window.alpineComponent.formConfig.toJSONString(),
        step: window.alpineComponent.navigation.step,
      }),
    });

    // Check for http errors
    if (response.ok) {
      // Read response body as text (SuiteCRM add html code to every action response)
      const responseText = await response.text();
      const lines = responseText.split("\n").filter((line) => line.trim() !== "");

      // Get the last line of the array: The json with the data from server
      const lastLine = lines[lines.length - 1];
      const data = JSON.parse(lastLine);

      if (data.success) {
        // Update local data
        window.alpineComponent.bean.id = data.id;
      }
    }
  }

  static finish() {
    if (!this.isReadOnly) {
      fetch("index.php?module=stic_Advanced_Web_Forms&action=finalizeConfig", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          bean: window.alpineComponent.bean,
          config: window.alpineComponent.formConfig.toJSONString(),
          step: window.alpineComponent.navigation.step,
        }),
      }).then(() => location.reload());
    }
  }
}

class TreeNavigator {
  // Global variable to store the jstree instance.
  static _jstreeInstance = null;

  static _getModuleNode(moduleName, isBaseModule = false) {
    let data = {
      nodeId: moduleName,
      parentNodeId: '',
      text: STIC.enabledModules[moduleName].textSingular,
      isBaseModule: isBaseModule,
      isRelationship: false,
      moduleName: moduleName,
      relationship: {},
      modules: [moduleName],
    };

    return {
      id: moduleName,
      text: TreeNavigator._getTreeNodeText(data),
      children: true, // It CAN have children
      state: { opened: false }, // Ensures the root node is initially closed.
      data: data,
    };
  }

  static _getRootNodes(baseModuleName) {
    let rootNodes = [];
    // First node: base_module
    let baseNode = TreeNavigator._getModuleNode(baseModuleName, true);
    rootNodes.push(baseNode);

    for (const [moduleName, module] of Object.entries(STIC.enabledModules)) {
      if (moduleName == baseModuleName) {
        continue;
      }
      rootNodes.push(TreeNavigator._getModuleNode(moduleName));
    }
    return rootNodes;
  }

  static _getRelationNode(node, relationship) {
    // Relationship: {name, text, module_orig, field_orig, relationship, module_dest}

    const parentNodeModuleName = node.data.moduleName;
    let moduleName = "";
    if (parentNodeModuleName == relationship.module_orig) {
      moduleName = relationship.module_dest;
    } else if (parentNodeModuleName == relationship.module_dest) {
      moduleName = relationship.module_orig;
    }

    let id = `${node.id}-${relationship.name}`;
    // let isLoop = id.includes(`-${relationship.name}-`) || node.data.modules.includes(moduleName);

    let data = {
      nodeId: id,
      parentNodeId: node.id,
      text: relationship.text,
      isBaseModule: false,
      isRelationship: true,
      moduleName: moduleName,
      relationship: relationship,
      modules: [...node.data.modules, moduleName],
    };

    return {
      id: id,
      text: TreeNavigator._getTreeNodeText(data),
      children: true, //!isLoop, // It CAN have children
      state: { opened: false }, // Ensures the root node is initially closed.
      data: data,
    };
  }
  static _getChildrenNodes(node) {
    /*
     * Result: [name, text, textSingular, inStudio, icon, fields:[Field], relationships:[Relationship]]
     *   Field: {
     *     name, text, type, required, options, inViews
     *   }
     *   Relationship: {
     *     name, text, module_orig, field_orig, relationship, module_dest
     *   }
     */
    let parentModule = utils.getModuleInformation(node.data.moduleName);
    let childrenNodes = [];
    for (let key in parentModule.relationships) {
      childrenNodes.push(TreeNavigator._getRelationNode(node, parentModule.relationships[key]));
    }
    childrenNodes.sort(function (a, b) {
      // if (a.data.pathText > b.data.pathText) return 1;
      // if (a.data.pathText < b.data.pathText) return -1;
      if (a.text > b.text) return 1;
      if (a.text < b.text) return -1;
      return 0;
    });

    return childrenNodes;
  }

  static _getTreeNodeText(data) {
    let html = "";
    let module = null;

    if (data.isRelationship) {
      if (data.moduleName == data.relationship.module_dest) {
        module = STIC.enabledModules[data.relationship.module_dest];
      } else {
        module = STIC.enabledModules[data.relationship.module_orig];
      }
      html += `${data.relationship.text} <sup>(${module.text})</sup>`;
    } else {
      module = STIC.enabledModules[data.moduleName];
      html += `${module.textSingular}`;
    }

    // html += `<button type='button' class='btn btn-sm ms-3 p-0 ps-2 pe-2' @click="DataBlocksHelper.addDataBlockFromTreeNode(TreeNavigator._jstreeInstance.get_node('${data.nodeId}').data);">+</button>`;

    return html;
  }

  static initializeModuleTree($tree) {
    // Destroy existing jstree instance and clear the container.
    if (TreeNavigator._jstreeInstance) {
      TreeNavigator._jstreeInstance.destroy();
    }

    if ($tree == null || $tree.length == 0) {
      return;
    }
    window.alpineComponent.step2.loadingTree = true;

    $tree.empty();

    // Initialize jstree on the designated div.
    $tree
      .jstree({
        core: {
          data: function (node, cb) {
            // If node.id is '#', Jstree is asking for first-level nodes (tree root).
            if (node.id === "#") {
              let rootNodes = TreeNavigator._getRootNodes(window.alpineComponent.bean.base_module);
              cb.call(this, rootNodes);
            } else {
              // An existing node has been expanded, and Jstree is asking for its children.
              let childrenNodes = TreeNavigator._getChildrenNodes(node);
              cb.call(this, childrenNodes);
            }
          },
          check_callback: true, // Allows modifying the tree (e.g., adding/removing nodes).
          themes: {
            icons: false,
            dots: true,
          },
        },
        plugins: ["wholerow"], //"contextmenu"
      })
      .on("ready.jstree", function () {
        // Store the jstree instance.
        TreeNavigator._jstreeInstance = $("#jstree-container").jstree(true);
        TreeNavigator.toggleAllModules();

        window.alpineComponent.step2.treeSelectedData = null;
        // Ensure base module is in DataBlock
        // let baseModuleName = window.alpineComponent.bean.base_module;
        // let baseDataBlock = addDataBlockByTreeNode(TreeNavigator._jstreeInstance.get_node(baseModuleName), false);
        // baseDataBlock.required = true;

        TreeNavigator._jstreeInstance.select_node(window.alpineComponent.bean.base_module);
        window.alpineComponent.step2.loadingTree = false;
      })
      .on("select_node.jstree", function (e, data) {
        window.alpineComponent.step2.treeSelectedData = data.node.data;
      });
  }

  static toggleAllModules(treeShowAllModules) {
    if (!TreeNavigator._jstreeInstance) {
      return;
    }
    if (treeShowAllModules) {
      TreeNavigator._jstreeInstance.show_all();
    } else {
      // Hide all root nodes except window.alpineComponent.bean.base_module
      const rootNodes = TreeNavigator._jstreeInstance.get_json("#", { flat: false });
      rootNodes.forEach((node) => {
        if (!node.data.isBaseModule) {
          TreeNavigator._jstreeInstance.hide_node(node.id);
        }
      });
    }
  }
}

class DataBlocksHelper {
  static addDataBlockFromTreeNode(data, force = false) {
    // Ensure parent node is added
    if (data.parentNodeId != '') {
      DataBlocksHelper.addDataBlockFromTreeNode(TreeNavigator._jstreeInstance.get_node(data.parentNodeId).data);
    }

    if (!data.isRelationship) {
      window.alpineComponent.formConfig.addDataBlockModule(data.moduleName, force);
    }
    else {
      window.alpineComponent.formConfig.addDataBlockRelationship(data.relationship, force);
    }
  }

  static getTitleText(data) {
    let title = ``;
    // if (data.isRelationship) {
    //   // name, text, module_orig, field_orig, relationship, module_dest
    //   STIC.enabledModules[moduleName].textSingular
    //   title += `${data.text} (${utils.trans})`

    // } else {

    // }
    return title;
  }
}

// Access configuration examples:
// window.alpineComponent.formConfig
// window.alpineComponent.bean.base_module

function set_wizard_assigned_user(popup_reply_data) {
  window.alpineComponent.bean.assigned_user_id = popup_reply_data.name_to_value_array.assigned_user_id;
  window.alpineComponent.bean.assigned_user_name = popup_reply_data.name_to_value_array.assigned_user_name;
}

// [{name, text, type, required, options, inViews}]
function getModuleFields(moduleName) {
  return Object.values(utils.getModuleInformation(moduleName).fields);
}


function deleteDataBlock(indexToDelete) {
  // DataBlocks: [{
  //   name, text, editable_text, order, fixed_order, module, required(),
  //   fields: [{ name, label, required, required_in_form, type, type_in_form, subtype_in_form,
  //              show_in_form, value_type, value, value_text }],
  //   duplicate_detection: {fields: [<field_name>], on_duplicate},
  //   relationships: [{ is_source, source_name, source_text, source_data_block_name, source_field_name, dest_data_block_name }]
  // }]
  let dataBlocks = window.alpineComponent.formConfig.data_blocks;
  let dataBlock = dataBlocks[indexToDelete];
  let index = -1;

  if (dataBlock.required()) {
    return false;
  }
  for (let i = 0; i < dataBlock.relationships.length; i++) {
    // There are no "is_source" relationships: Is destination
    // Find Origin DataBlock
    index = dataBlocks.findIndex((d) => d.name == dataBlock.relationships[i].source_data_block_name);
    if (index != -1) {
      let dataBlockOrig = dataBlocks[index];
      let relationshipName = dataBlock.relationships[i].source_name;

      // Remove relationship in origin
      index = dataBlockOrig.relationships.findIndex((r) => r.source_name == dataBlock.relationships[i].source_name);
      if (index != -1) {
        dataBlockOrig.relationships.splice(index, 1);
      }

      // Find Field
      index = dataBlockOrig.fields.findIndex((f) => f.name === dataBlock.relationships[i].source_field_name);
      if (index != -1) {
        // module: {
        //           name, text,
        //           fields: [name: {name, text, type, required, options, inViews}],
        //           relationships: [name: {name, text, fieldName, relationship, moduleName, moduleText}]
        //         }
        let fieldOrig = dataBlockOrig.fields[index];
        let moduleDef = utils.getModuleInformation(dataBlockOrig.module);
        let fieldDef = moduleDef.fields[fieldOrig.name];
        if (fieldDef.required) {
          // Is required: Do not delete Field, reset values
          fieldOrig.value_type = "";
          fieldOrig.value = "";
          fieldOrig.value_text = "";
          fieldOrig.show_in_form = true;
        } else {
          // Is not required: Can be deleted
          dataBlockOrig.fields.splice(index, 1);
        }
      }

      // Remove relation from relationshipsInDataBlocks
      index = window.alpineComponent.step2.relationshipsInDataBlocks.findIndex((r) => r.name == relationshipName);
      if (index != -1) {
        window.alpineComponent.step2.relationshipsInDataBlocks.splice(index, 1);
      }
    }
  }
  dataBlocks.splice(indexToDelete, 1);

  // Remove module from modulesInDataBlocks
  index = window.alpineComponent.step2.modulesInDataBlocks.findIndex((m) => m == dataBlock.module);
  if (index != -1) {
    window.alpineComponent.step2.modulesInDataBlocks.splice(index, 1);
  }

  return true;
}

function convertRelatedFieldToDataBlockField(relatedField) {
  // DataBlocks: [{
  //   name, text, editable_text, order, fixed_order, module, required(),
  //   fields: [{ name, label, required, required_in_form, type, type_in_form, subtype_in_form,
  //              show_in_form, value_type, value, value_text }],
  //   duplicate_detection: {fields: [<field_name>], on_duplicate},
  //   relationships: [{ is_source, source_name, source_text, source_data_block_name, source_field_name, dest_data_block_name }]
  // }]
  let dataField = {
    name: relatedField.name,
    label: relatedField.text,
    required: relatedField.required ?? false,
    required_in_form: relatedField.required ?? false,
    type: relatedField.type,
    type_in_form: "",
    subtype_in_form: "",
    show_in_form: relatedField.required ?? false,
    value_type: "",
    value: "",
    value_text: "",
  };
  return dataField;
}

function newDataBlock(name, text) {
  // DataBlocks: [{
  //   name, text, editable_text, order, fixed_order, module, required(),
  //   fields: [{ name, label, required, required_in_form, type, type_in_form, subtype_in_form,
  //              show_in_form, value_type, value, value_text }],
  //   duplicate_detection: {fields: [<field_name>], on_duplicate},
  //   relationships: [{ is_source, source_name, source_text, source_data_block_name, source_field_name, dest_data_block_name }]
  // }]
  return {
    name: name,
    text: text,
    editable_text: true,
    order: window.alpineComponent.formConfig.data_blocks.length,
    fixed_order: false,
    module: "",
    required() {
      return (
        this.module &&
        this.module != "" &&
        this.module != window.alpineComponent.bean.base_module &&
        this.relationships &&
        this.relationships.findIndex((r) => r.is_source && r.dest_data_block_name != r.source_data_block_name) !== -1
      );
    },
    fields: [],
    duplicate_detection: {
      fields: [],
      on_duplicate: "enrich",
    },
    relationships: [],
  };
}
