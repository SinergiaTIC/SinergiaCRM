function wizardForm(readOnly) {
  return {
    step: 1,
    totalSteps: 4,
    steps: [],
    stepTitle: "",

    isReadOnly: readOnly,

    bean: STIC.record || {},
    configDataBlocks: {},

    appListStrings: SUGAR.language.languages.app_list_strings,

    // [moduleName: [name: ModuleName, icon: StudioIcon, text: TranslatedModuleName]]
    enabledStudioModules: STIC.enabledStudioModules,

    // [name: ModuleName, text: TranslatedModuleName]
    enabledModules: STIC.enabledModules,

    step1: {},
    step2: {
      // RelatedModule: {
      //   id, text, isRelation, relationName, relationText, moduleName, moduleText, path, pathText,
      //   fields: [name: {name, text, type, required, options, inViews}],
      //   relationships: [name: {name, text, fieldName, relationship, moduleName, moduleText}]
      // }
      treeSelectedRelatedModule: {},
      treeShowAllModules: false,
      showDetailsData: false,
      detailsActivePanel: 'panel1',
      detailsFieldHideNotInView: false, 
      detailsFieldHideNotRequired: false,
    },
    step3: {},
    step4: {},

    async init() {
      // Set Context accessible
      window.alpineComponent = this;

      // Split configs
      let allConfig = JSON.parse(this.bean?.config_json || "{}");
      if (allConfig.hasOwnProperty("dataBlocks")) {
        this.configDataBlocks = allConfig.dataBlocks;
      }

      // Load current Step
      this.loadStep();
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

    autoSave() {
      // Join Configs
      let config = {
        dataBlocks: this.configDataBlocks,
      };
      fetch("index.php?module=stic_Advanced_Web_Forms&action=saveDraft", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ bean: this.bean, config: config, step: this.step }),
      });
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
    text: baseModuleText,
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
      text: module.text,
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
              childrenNodes.push({
                id: node.id + "-" + key,
                text: relationText + " <sup>(" + moduleText + ")</sup>",
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
      plugins: ["wholerow"], // "contextmenu"
    })
    .on("ready.jstree", function () {
      // Store the jstree instance.
      jstreeInstance = $("#jstree-container").jstree(true);
      treeToggleAllModules();

      $("#jstree-loading").hide();
      $(this).show();
    })
    .on("select_node.jstree", function (e, data) {
      let moduleInfo = getModuleInformation(data.node.data.moduleName);
      let name = "";
      let text = "";
      let moduleSourceName = "";
      let moduleSourceText = "";
      let moduleDestName = "";
      let moduleDestText = "";
      if (data.node.data.isRelation) {
        name = data.node.data.relationName;
        text = data.node.data.relationText;
        let i = data.node.data.path.length - 2;
        moduleSourceName = data.node.data.path[i];
        moduleSourceText = data.node.data.pathText[i];
        moduleDestName = data.node.data.moduleName;
        moduleDestText = data.node.data.moduleText;
      } else {
        name = data.node.data.moduleName;
        text = data.node.data.moduleText;
      }

      window.alpineComponent.step2.treeSelectedRelatedModule = {
        id: data.node.id,
        name: name,
        text: text,
        isRelation: data.node.data.isRelation,
        moduleDestName: moduleDestName,
        moduleDestText: moduleDestText,
        moduleSourceName: moduleSourceName,
        moduleSourceText: moduleSourceText,
        path: data.node.data.path,
        pathText: data.node.data.pathText,
        fields: moduleInfo.fields,
        relationships: moduleInfo.relationships,
      };
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
