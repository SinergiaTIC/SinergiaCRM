// // Access configuration examples:
// window.alpineComponent.formConfig
// window.alpineComponent.bean.base_module

function wizardForm(readOnly) {
  return {
    step: 1,
    totalSteps: 4,
    steps: [],
    stepTitle: "",

    isReadOnly: readOnly,

    bean: STIC.record || {},

    // {
    //  data_blocks: [{ name, path, text, editable_text, order, fixed_order, module, required, is_relation, parent_data_block
    //                  fields: [{ name, label, required, required_in_form, type, type_in_form, subtype_in_form,
    //                             show_in_form, value_type, value, value_text, validations: [{ type }]
    //                          }],
    //                  duplicate_detection: {fields: [<field_name>], on_duplicate}
    //               }],
    //   flows: [{ name,
    //             actions: [{ order, action_name,
    //                         params: [{name, source, value}]
    //                      }]
    //          }],
    //   layout: [{ type, name,
    //              display: {type, title},
    //              elements: [{ type, block, name }]
    //           }]
    // }

    // DataBlocks: [{
    //   name, text, editable_text, order, fixed_order, module, required,
    //   fields: [{ name, label, required, required_in_form, type, type_in_form, subtype_in_form,
    //              show_in_form, value_type, value, value_text }],
    //   duplicate_detection: {fields: [<field_name>], on_duplicate},
    //   relationships: [{ is_source, source_name, source_text, source_data_block_name, source_field_name, dest_data_block_name }]

    // }]
    formConfig: {},
    formConfig_json() {
      return JSON.stringify(this.formConfig);
    },

    appListStrings: SUGAR.language.languages.app_list_strings,

    // [moduleName: [name: ModuleName, icon: StudioIcon, text: TranslatedModuleName, textSingular: TranslatedModuleNameSingular]]
    enabledStudioModules: STIC.enabledStudioModules,

    // [name: ModuleName, text: TranslatedModuleName, textSingular: TranslatedModuleNameSingular]
    enabledModules: STIC.enabledModules,

    step1: {},
    step2: {
      // RelatedModule: {
      //   id, name, text, isRelation, moduleDestName, moduleDestText, moduleSourceName, moduleSourceText, path, pathText,
      //   fields: [name: {name, text, type, required, options, inViews}],
      //   relationships: [name: {name, text, fieldName, relationship, moduleName, moduleText}]
      // }
      treeSelectedRelatedModule: {},
      treeShowAllModules: false,
      relationshipsInDataBlocks: [],
      modulesInDataBlocks: [],
    },
    step3: {},
    step4: {},

    async init() {
      // Set Context accessible
      window.alpineComponent = this;

      // Set config object
      // IEPA!! this.bean?.configuration està amb \\&quot;...
      // this.formConfig = JSON.parse(this.bean?.configuration || "{}");
      this.initializeFormConfig();

      // Load current Step
      this.loadStep();
    },

    initializeFormConfig() {
      if (!("data_blocks" in this.formConfig)) {
        this.formConfig.data_blocks = [];
      }
      // Detached DataBlock
      if (this.formConfig.data_blocks.length == 0) {
        let dataBlock = newDataBlock("_Detached", translate("LBL_DATABLOCK_DETACHED"));
        dataBlock.editable_text = false;
        dataBlock.order = 0;
        dataBlock.fixed_order = true;
        dataBlock.required = true;
        this.formConfig.data_blocks.push(dataBlock);
      }

      if (!("flows" in this.formConfig)) {
        this.formConfig.flows = [];
      }
      // Default flows
      if (this.formConfig.flows.length === 0) {
        // { name, text, actions: [{ order, action_name, params: [{name, source, value}] }
        this.formConfig.flows.push({
          name: "Main",
          text: translate("LBL_FLOW_MAIN"),
          actions: [],
        });
        this.formConfig.flows.push({
          name: "OnError",
          text: translate("LBL_FLOW_ONERROR"),
          actions: [],
        });
      }

      if (!("layout" in this.formConfig)) {
        this.formConfig.layout = [];
      }
    },

    async loadStep() {
      if (this.step <= this.totalSteps && this.steps.length < this.step + 1) {
        this.steps[this.step] = await (
          await fetch(`modules/stic_Advanced_Web_Forms/wizard/steps/step${this.step}.html`)
        ).text();
      }
      this.stepTitle = translate("LBL_WIZARD_TITLE_STEP" + this.step);
      document.getElementById("wizard-step-container").innerHTML = this.steps[this.step];
      $("#wizard-section-title").text(this.stepTitle + " (" + this.step + "/" + this.totalSteps + ")");
    },

    enablePrevStep() {
      return this.step > 1;
    },
    enableNextStep() {
      return this.step < this.totalSteps;
    },
    nextStep() {
      if (this.enableNextStep()) {
        let allOk = true;
        document.querySelectorAll("#wizard-step-container form").forEach(function (f) {
          allOk &= f.reportValidity();
        });
        if (allOk) {
          this.step++;
          this.autoSave();
          this.loadStep();
        }
      }
    },
    prevStep() {
      if (this.enablePrevStep()) {
        this.step--;
        this.loadStep();
      }
    },

    async autoSave() {
      const response = await fetch("index.php?module=stic_Advanced_Web_Forms&action=saveDraft", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ bean: this.bean, config: this.formConfig_json(), step: this.step }),
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
          this.bean.id = data.id;
        }
      }
    },

    finish() {
      if (!this.isReadOnly) {
        fetch("index.php?module=stic_Advanced_Web_Forms&action=finalizeConfig", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ beanData: this.beanData, config: this.config, step: this.step }),
        }).then(() => location.reload());
      }
    },
  };
}

function translate(label) {
  return (
    SUGAR.language.languages.stic_Advanced_Web_Forms[label] ?? SUGAR.language.languages.app_strings[label] ?? label
  );
}

function set_wizard_assigned_user(popup_reply_data) {
  window.alpineComponent.bean.assigned_user_id = popup_reply_data.name_to_value_array.assigned_user_id;
  window.alpineComponent.bean.assigned_user_name = popup_reply_data.name_to_value_array.assigned_user_name;
}

// Global variable to store the jstree instance.
var jstreeInstance = null;

function initializeModuleTree($tree) {
  // Destroy existing jstree instance and clear the container.
  if (jstreeInstance) {
    jstreeInstance.destroy();
  }
  if ($tree == null || $tree.length == 0) {
    return;
  }
  $tree.empty();

  let rootNodes = [];
  // First node: base_module
  let baseModuleName = window.alpineComponent.bean.base_module;
  let baseModuleText = window.alpineComponent.enabledModules[baseModuleName].textSingular;
  rootNodes.push({
    id: baseModuleName,
    text: getTreeNodeText(baseModuleName, baseModuleName, baseModuleText),
    children: true, // It CAN have children
    state: { opened: false }, // Ensures the root node is initially closed.
    data: {
      isRelation: false,
      relationName: "",
      relationText: "",
      moduleName: baseModuleName,
      moduleText: baseModuleText,
      path: [baseModuleName],
      pathText: [baseModuleText],
    },
  });

  for (const [moduleName, module] of Object.entries(window.alpineComponent.enabledStudioModules)) {
    if (moduleName == baseModuleName) {
      continue;
    }
    rootNodes.push({
      id: moduleName,
      text: getTreeNodeText(moduleName, moduleName, module.textSingular),
      children: true, // It CAN have children
      state: { opened: false }, // Ensures the root node is initially closed.
      data: {
        isRelation: false,
        relationName: "",
        relationText: "",
        moduleName: moduleName,
        moduleText: module.textSingular,
        path: [moduleName],
        pathText: [module.textSingular],
      },
    });
  }

  // Initialize jstree on the designated div.
  $tree
    .jstree({
      core: {
        data: function (node, cb) {
          // If node.id is '#', Jstree is asking for first-level nodes (tree root).
          if (node.id === "#") {
            //cb.call(this, [rootNode]);
            cb.call(this, rootNodes);
          } else {
            // An existing node has been expanded, and Jstree is asking for its children.
            let moduleInfo = getModuleInformation(node.data?.moduleName);
            if (!moduleInfo) {
              return;
            }
            let childrenNodes = [];
            // Convert the object of related modules to an array of Jstree nodes.
            for (let key in moduleInfo.relationships) {
              let moduleName = moduleInfo.relationships[key].moduleName;
              let moduleText = moduleInfo.relationships[key].moduleText;
              let relationText = moduleInfo.relationships[key].text;

              // Skip fields related to disabled modules
              if (!window.alpineComponent.enabledModules.hasOwnProperty(moduleName)) {
                continue;
              }

              let newPath = [...node.data.path];
              newPath.push(key);

              let newPathText = [...node.data.pathText];
              newPathText.push(relationText);

              let isLoop = node.data.path.includes(moduleName);
              let nodeId = node.id + "-" + key;
              childrenNodes.push({
                id: nodeId,
                text: getTreeNodeText(nodeId, moduleName, moduleText, key, relationText),
                children: !isLoop,
                data: {
                  isRelation: true,
                  relationName: key,
                  relationText: relationText,
                  moduleName: moduleName,
                  moduleText: moduleText,
                  path: newPath,
                  pathText: newPathText,
                },
              });
            }
            childrenNodes.sort(function (a, b) {
              if (a.data.pathText > b.data.pathText) return 1;
              if (a.data.pathText < b.data.pathText) return -1;
              if (a.text > b.text) return 1;
              if (a.text < b.text) return -1;
              return 0;
            });
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
      jstreeInstance = $("#jstree-container").jstree(true);
      treeToggleAllModules();

      // Ensure base module is in DataBlock
      let baseModuleName = window.alpineComponent.bean.base_module;
      let baseDataBlock = addDataBlockByTreeNode(jstreeInstance.get_node(baseModuleName), false);
      baseDataBlock.required = true;

      $("#jstree-loading").hide();
      $(this).show();
    })
    .on("select_node.jstree", function (e, data) {
      window.alpineComponent.step2.treeSelectedRelatedModule = getRelatedModuleByTreeNode(data.node);
    });
}

function getTreeNodeText(nodeId, moduleName, moduleText, relationName = "", relationText = "") {
  if (relationName != "" && relationText == "") {
    relationText = moduleText;
  }

  let text = relationName == "" ? `${moduleText}` : `${relationText} <sup>(${moduleText})</sup>`;
  let checkSelected =
    relationName == ""
      ? `step2.modulesInDataBlocks.findIndex((m) => m == '${moduleName}') != -1`
      : `step2.relationshipsInDataBlocks.findIndex((r) => r.name == '${relationName}') != -1`;

  let html = `
  <div class="stic-tree-item" :class="{ selected: ${checkSelected} }">
    ${text}
    <template x-if="!(${checkSelected})">
      <button type='button' class='btn btn-sm ms-3 p-0 ps-2 pe-2' @click="addDataBlockByTreeNode(jstreeInstance.get_node('${nodeId}'));">+</button>
    </template>
  </div>
  `;
  return html;
}

// RelatedModule: {
//   id, name, text, isRelation, moduleDestName, moduleDestText, moduleSourceName, moduleSourceText, path, pathText,
//   fields: [name: {name, text, type, required, options, inViews}],
//   relationships: [name: {name, text, fieldName, relationship, moduleName, moduleText}]
// }
function getRelatedModuleByTreeNode(node) {
  let moduleInfo = getModuleInformation(node.data.moduleName);
  let name = "";
  let text = "";
  let moduleSourceName = "";
  let moduleSourceText = "";
  let moduleDestName = "";
  let moduleDestText = "";
  if (node.data.isRelation) {
    name = node.data.relationName;
    text = node.data.relationText;
    let i = node.data.path.length - 2;
    moduleSourceName = node.data.path[i];
    moduleSourceText = node.data.pathText[i];
    moduleDestName = node.data.moduleName;
    moduleDestText = node.data.moduleText;
  } else {
    name = node.data.moduleName;
    text = node.data.moduleText;
    moduleSourceName = node.data.moduleName;
    moduleSourceText = node.data.moduleName;
    moduleDestName = node.data.moduleName;
    moduleDestText = node.data.moduleText;
  }

  return {
    id: node.id,
    name: name,
    text: text,
    isRelation: node.data.isRelation,
    moduleDestName: moduleDestName,
    moduleDestText: moduleDestText,
    moduleSourceName: moduleSourceName,
    moduleSourceText: moduleSourceText,
    path: node.data.path,
    pathText: node.data.pathText,
    fields: moduleInfo.fields,
    relationships: moduleInfo.relationships,
  };
}

// Global variable to store cached modules information
// module: {
//           name, text,
//           fields: [name: {name, text, type, required, options, inViews}],
//           relationships: [name: {name, text, fieldName, relationship, moduleName, moduleText}]
//         }
var cachedModules = {};
function getModuleInformation(moduleName) {
  // Do not get info of not enabled modules
  if (!moduleName || !window.alpineComponent.enabledModules.hasOwnProperty(moduleName)) {
    return null;
  }

  if (!cachedModules.hasOwnProperty(moduleName)) {
    $.ajax({
      url: "index.php", //location.href.slice(0, location.href.indexOf(location.search)),
      type: "POST",
      async: false,
      dataType: "json",
      data: {
        module: "stic_Advanced_Web_Forms",
        action: "getModuleInformation",
        getmodule: moduleName,
      },
      success: function (response) {
        cachedModules[moduleName] = response;
      },
      error: function (xhr, status, error) {
        console.error("Error retrieving Information for module: '" + moduleName + "'", status, error);
      },
    });
  }

  if (cachedModules.hasOwnProperty(moduleName)) {
    return cachedModules[moduleName];
  }

  return null;
}
// [{name, text, type, required, options, inViews}]
function getModuleFields(moduleName) {
  return Object.values(getModuleInformation(moduleName).fields);
}

function treeToggleAllModules(treeShowAllModules) {
  if (treeShowAllModules) {
    jstreeInstance.show_all();
  } else {
    // Hide all root nodes except window.alpineComponent.bean.base_module
    const rootNodes = jstreeInstance.get_json("#", { flat: false });
    rootNodes.forEach((node) => {
      if (node.id !== window.alpineComponent.bean.base_module) {
        jstreeInstance.hide_node(node.id);
      }
    });
  }
}

function addDataBlockByTreeNode(node) {
  // Ensure parent node is added
  let parentDataBlock = null;
  let parentRelatedModule = null;
  if (node.data.path.length > 1) {
    let parentNode = jstreeInstance.get_node(node.parent);
    parentDataBlock = addDataBlockByTreeNode(parentNode);
    parentRelatedModule = getRelatedModuleByTreeNode(parentNode);
  }

  // RelatedModule: {
  //   id, name, text, isRelation, moduleDestName, moduleDestText, moduleSourceName, moduleSourceText, path, pathText,
  //   fields: [name: {name, text, type, required, options, inViews}],
  //   relationships: [name: {name, text, fieldName, relationship, moduleName, moduleText}]
  // }
  let relatedModule = getRelatedModuleByTreeNode(node);

  // DataBlocks: [{
  //   name, text, editable_text, order, fixed_order, module, required(),
  //   fields: [{ name, label, required, required_in_form, type, type_in_form, subtype_in_form,
  //              show_in_form, value_type, value, value_text }],
  //   duplicate_detection: {fields: [<field_name>], on_duplicate},
  //   relationships: [{ is_source, source_name, source_text, source_data_block_name, source_field_name, dest_data_block_name }]
  // }]
  let dataBlocks = window.alpineComponent.formConfig.data_blocks;

  // Only a DataBlock per Module
  let name = relatedModule.moduleDestName;
  let text = relatedModule.moduleDestText;
  let dataBlockIndex = dataBlocks.findIndex((d) => d.name === name);
  let dataBlock = null;

  if (dataBlockIndex != -1) {
    dataBlock = dataBlocks[dataBlockIndex];
  } else {
    let found = false;
    let index = 0;

    // Check new DataBlock Text
    let newText = text;
    do {
      newText = index > 0 ? `${text} (${index})` : text;
      found = dataBlocks.findIndex((d) => d.text === newText) != -1;
    } while (found);
    text = newText;

    // Get required fields
    let initialFields = [];
    let checkFields = [];
    for (const [key, value] of Object.entries(relatedModule.fields)) {
      if (value.required ?? false) {
        initialFields.push(convertRelatedFieldToDataBlockField(value));
        checkFields.push(key);
      }
    }

    // Insert dataBlock to array
    dataBlock = newDataBlock(name, text);
    dataBlock.module = relatedModule.moduleDestName;
    dataBlock.fields = initialFields;
    dataBlock.duplicate_detection.fields = checkFields;

    dataBlocks.push(dataBlock);

    // Add module in modulesInDataBlocks
    if (window.alpineComponent.step2.modulesInDataBlocks.findIndex((m) => m == dataBlock.module) == -1) {
      window.alpineComponent.step2.modulesInDataBlocks.push(dataBlock.module);
    }
  }

  // Add Relation information to DataBlocks and Field in Parent
  if (relatedModule.isRelation && parentDataBlock != null && parentRelatedModule != null) {
    // Find relationship (defined in parent)
    let relationshipInfo = parentRelatedModule.relationships[relatedModule.name];

    let relationshipSource = {
      is_source: true,
      source_name: relationshipInfo.name,
      source_text: relationshipInfo.text,
      source_data_block_name: parentDataBlock.name,
      source_field_name: relationshipInfo.fieldName,
      dest_data_block_name: name,
    };
    let relationshipDest = {
      is_source: false,
      source_name: relationshipInfo.name,
      source_text: relationshipInfo.text,
      source_data_block_name: parentDataBlock.name,
      source_field_name: relationshipInfo.fieldName,
      dest_data_block_name: name,
    };

    // Add relation in parent side
    let index = parentDataBlock.relationships.findIndex((d) => d.name === relationshipSource.name);
    if (index == -1) {
      parentDataBlock.relationships.push(relationshipSource);
    }

    // Add relation in dest side
    index = dataBlock.relationships.findIndex((d) => d.name === relationshipDest.name);
    if (index == -1) {
      dataBlock.relationships.push(relationshipDest);
    }

    // Add Field in parent side
    let relField = convertRelatedFieldToDataBlockField(parentRelatedModule.fields[relationshipInfo.fieldName]);
    relField.required = true;
    relField.value_type = "dataBlock"; // IEPA!! Les relacions poden ser dataBlock o id
    relField.value = name;
    relField.value_text = text;
    relField.show_in_form = false;

    // Find field in current fields
    index = parentDataBlock.fields.findIndex((field) => field.name === relationshipSource.source_field_name);
    if (index !== -1) {
      parentDataBlock.fields[index] = Object.assign(parentDataBlock.fields[index], relField);
    } else {
      parentDataBlock.fields.push(relField);
    }

    // Add relation in relationshipsInDataBlocks
    if (
      window.alpineComponent.step2.relationshipsInDataBlocks.findIndex((r) => r.name == relationshipInfo.name) == -1
    ) {
      window.alpineComponent.step2.relationshipsInDataBlocks.push(relationshipInfo);
    }
  }

  return dataBlock;
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
        let moduleDef = getModuleInformation(dataBlockOrig.module);
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
