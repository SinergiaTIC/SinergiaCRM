class AWF_DataBlock {
  constructor(data) {
    Object.assign(this, data);
  }


//   getFormattedName() {
//     return this.name.toUpperCase();
//   }
}

class AWF_Flow {
  constructor(data) {
    Object.assign(this, data);
  }

//   startFlow() {
//     console.log(`Starting flow: ${this.id}`);
//   }
}

class AWF_Layout {
  constructor(data) {
    Object.assign(this, data);
  }

//   render() {
//     console.log(`Rendering layout element: ${this.type}`);
//   }
}


class AWF_Configuration {
  constructor(jsonString) {
    if (typeof jsonString === "string") {
      this.loadFromJSON(jsonString);
    }
  }

  loadFromJSON(jsonString) {
    const config = JSON.parse(jsonString);
    this.data_blocks = config.data_blocks.map((data) => new AWF_DataBlock(data));
    this.flows = config.flows.map((data) => new AWF_Flow(data));
    this.layout = config.layout.map((data) => new AWF_Layout(data));
  }

  toJSON() {
    return {
      data_blocks: this.data_blocks,
      flows: this.flows,
      layout: this.layout,
    };
  }
  
  toJSONString() {
    return JSON.stringify(this.toJSON());
  }
}
