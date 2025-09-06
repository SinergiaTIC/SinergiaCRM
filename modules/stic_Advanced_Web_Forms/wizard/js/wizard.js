function wizardForm(readOnly) {
  return {
    step: 1,
    totalSteps: 4,
    steps: [],
    stepTitle: "",

    isReadOnly: readOnly,

    bean: STIC.record || {},

    // {
    //  data_blocks: [{ name, text, editable_text, order, fixed_order, module, required,
    //                  fields: [{ name, label, required, required_in_form,
    //                             validations: [{ type }] ???
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
    formConfig: {},
    formConfig_json() {
      return JSON.stringify(this.formConfig);
    },

    appListStrings: SUGAR.language.languages.app_list_strings,

    // [moduleName: [name: ModuleName, icon: StudioIcon, text: TranslatedModuleName]]
    enabledStudioModules: STIC.enabledStudioModules,

    // [name: ModuleName, text: TranslatedModuleName]
    enabledModules: STIC.enabledModules,

    step1: {},
    step2: {
      // RelatedModule: {
      //   name, text, isRelation, moduleDestName, moduleDestText, moduleSourceName, moduleSourceText, path, pathText,
      //   fields: [name: {name, text, type, required, options, inViews}],
      //   relationships: [name: {name, text, fieldName, relationship, moduleName, moduleText}]
      // }
      treeSelectedRelatedModule: {},
      treeShowAllModules: false,
      showDetailsData: false,
      detailsActivePanel: "panel1",
      detailsFieldHideNotInView: false,
      detailsFieldHideNotRequired: false,
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
        // DataBlocks: [{
        //   name, text, editable_text, order, fixed_order, module, required,
        //   fields: [{ name, label, required, required_in_form, validations: [{ type }] }],
        //   duplicate_detection: {fields: [<field_name>], on_duplicate}
        // }]
        this.formConfig.data_blocks.push({
          name: "_Detached",
          text: translate("LBL_DATABLOCK_DETACHED"),
          editable_text: false,
          order: 0,
          fixed_order: true,
          module: "",
          required: true,
          fields: [],
          duplicate_detection: { fields: [], on_duplicate: "skip" },
        });
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
  let baseModuleText = window.alpineComponent.enabledModules[baseModuleName].text;
  rootNodes.push({
    id: baseModuleName,
    text: getTreeNodeText(baseModuleName, baseModuleText),
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
      text: getTreeNodeText(moduleName, module.text),
      children: true, // It CAN have children
      state: { opened: false }, // Ensures the root node is initially closed.
      data: {
        isRelation: false,
        relationName: "",
        relationText: "",
        moduleName: moduleName,
        moduleText: module.text,
        path: [moduleName],
        pathText: [module.text],
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
                text: getTreeNodeText(nodeId, moduleText, relationText),
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

      $("#jstree-loading").hide();
      $(this).show();
    })
    .on("select_node.jstree", function (e, data) {
      window.alpineComponent.step2.treeSelectedRelatedModule = getRelatedModuleByTreeNode(data.node);
    });
  // // Debugging events for load/open operations (commented out for production).
  // .on('after_open.jstree', function(e, data) {
  //     // console.log("Node OPENED:", data.node.id, "Children loaded:", data.node.children ? data.node.children.length : 0);
  // })
  // .on('load_node.jstree', function(e, data) {
  //     // console.log("Node LOADED (data obtained):", data.node.id, "Children obtained:", data.node.children ? data.node.children.length : 0);
  //     if (data.node.children && data.node.children.length === 0 && data.node.id !== '#') {
  //         // console.warn("Node loaded without children. Arrow might disappear.");
  //     }
  // });
}

function getTreeNodeText(nodeId, moduleText, relationText = "") {
  var str = "";
  if (relationText == "") {
    // Is a module
    str += moduleText;
  } else {
    // Is a relationship
    str += `${relationText}<sup>(${moduleText})</sup>`;
  }
  str += `<button type='button' class='btn btn-sm ms-3 p-0 ps-2 pe-2' @click="addDataBlockByTreeNode(jstreeInstance.get_node('${nodeId}'));">+</button>`;

  return str;
}

// RelatedModule: {
//   name, text, isRelation, moduleDestName, moduleDestText, moduleSourceName, moduleSourceText, path, pathText,
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
  // RelatedModule: {
  //   name, text, isRelation, moduleDestName, moduleDestText, moduleSourceName, moduleSourceText, path, pathText,
  //   fields: [name: {name, text, type, required, options, inViews}],
  //   relationships: [name: {name, text, fieldName, relationship, moduleName, moduleText}]
  // }
  let relatedModule = getRelatedModuleByTreeNode(node);

  // DataBlocks: [{
  //   name, text, editable_text, order, fixed_order, module, required,
  //   fields: [{ name, label, required, required_in_form, validations: [{ type }] }],
  //   duplicate_detection: {fields: [<field_name>], on_duplicate}
  // }]
  let dataBlocks = window.alpineComponent.formConfig.data_blocks;

  let found = false;
  let index = 0;

  // Check Name
  let name = relatedModule.path.join(":");
  let newName = name;
  do {
    newName = index > 0 ? `${name}(${index})` : name;
    for (let i = 0; i < dataBlocks.length; i++) {
      found = dataBlocks[i].name == newName;
      if (found) {
        index++;
        break;
      }
    }
  } while (found);
  name = newName;

  found = false;
  index = 0;

  // Check Text
  let text = relatedModule.text;
  let newText = text;
  do {
    newText = index > 0 ? `${text} (${index})` : text;
    for (let i = 0; i < dataBlocks.length; i++) {
      found = dataBlocks[i].text == newText;
      if (found) {
        index++;
        break;
      }
    }
  } while (found);
  text = newText;

  // Get required fields
  let initialFields = [];
  let checkFields = [];
  for (const [key, value] of Object.entries(relatedModule.fields)) {
    if (value.required ?? false) {
      initialFields.push({
        name: key,
        label: value.text,
        required: true,
        required_in_form: true,
      });
      checkFields.push(key);
    }
  }

  dataBlocks.push({
    name: name,
    text: text,
    editable_text: true,
    order: dataBlocks.length,
    fixed_order: false,
    module: relatedModule.isRelation ? relatedModule.moduleDestName : relatedModule.name,
    required: false,
    fields: initialFields,
    duplicate_detection: {
      fields: checkFields,
      on_duplicate: "enrich",
    },
  });
}
