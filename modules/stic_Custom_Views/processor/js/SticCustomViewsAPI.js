/**
 * This file contains logic and functions needed to manage custom views behaviour
 *
 */
var sticCustomView  = {
    editview: {
        field: (fieldName) => new CustomViewField("editview", fieldName),
        panel: (panelName) => new CustomViewPanel("editview", panelName),
        tab: (tabIndex) => new CustomViewTab("editview", tabIndex),
    },
    detailview: {
        field: (fieldName) => new CustomViewField("detailview", fieldName),
        panel: (panelName) => new CustomViewPanel("detailview", panelName),
        tab: (tabIndex) => new CustomViewTab("detailview", tabIndex),
    },
}
var CustomViewItem = class CustomViewItem {
    constructor (view, itemName) {
        this.view = view;
        this.itemName = itemName;
        if (this.view=="detailview"){
            this.elementView = $(".detail-view");
        }
        if (this.view=="editview"){
            this.elementView = $("#EditView");
            if(!this.elementView.length) {
                this.elementView = $("#EditView_tabs"); // Include QuickCreate view
            }
        }
    };
}
var CustomViewField = class CustomViewField extends CustomViewItem {
    constructor (view, fieldName) {
        super(view, fieldName);
        this.fieldName = fieldName;
    };
    input() {
        if (this.view=="detailview") {
            return new CustomViewDivDetailInput(this, this.row().element.children('[field="'+this.fieldName+'"]'));
        }
        if (this.view=="editview") {
            return new CustomViewDivEditInput(this, this.row().element.children('[field="'+this.fieldName+'"]'));
        }
    }
    label() {
        return new CustomViewDivLabel(this, this.row().element.children('.label'));
    }
    row() {
        return new CustomViewDiv(this, this.elementView.find('*[data-field="'+this.fieldName+'"]'));
    }
    show(show = true) {
        return this.row().show(show);
    }
    hide() {
        return this.row().hide();
    }
    readonly(readonly=true) {
        return this.input().readonly(readonly);
    }
}
var CustomViewDiv = class CustomViewDiv {
    constructor (item, element){
        this.item = item;
        this.element = element;
    }
    show(show=true) {
        if(show) {
            this.element.show();
        } else {
            this.element.hide();
        }
        return this;
    }
    hide() {
        return this.show(false);
    }
}
var CustomViewDivLabel = class CustomViewDivLabel extends CustomViewDiv {
    constructor (item, element){
        super(item, element);
    }
    color(color="") {
        this.element.css("color", color);
        return this;
    }
    background(color="") {
        this.element.css("background-color", color);
        return this;
    }
    bold(bold=true) {
        if (bold) {
            this.element.css('font-weight', 'bold');
        } else {
            this.element.css('font-weight', 'normal');
        }
        return this;
    }
    italic(italic=true) {
        if (italic) {
            this.element.css('font-style', 'italic');
        } else {
            this.element.css('font-style', 'normal');
        }
        return this;
    }
    underline(underline=true) {
        if (underline) {
            this.element.css('text-decoration', 'underline');
        } else {
            this.element.css('text-decoration', 'normal');
        }
        return this;
    }
}
var CustomViewDivEditInput = class CustomViewDivEditInput extends CustomViewDivLabel {
    constructor (item, element){
        super(item, element);
        this.editor = this.element.find(":input");
        this.option
        this.items = this.element.find(".items");
        this.labelValue = this.element.find(".stic-ReadonlyInput");
        this.type = this.element.attr("type"); 
    }
    text(){
        var text = this.editor.val();
        if(this.type=="enum" || this.type=="multienum"){
            text = this.editor.find("option:selected").text();
        }
        return text;
    }
    color(color="") {
        this.editor.css("color", color);
        this.items.css("color", color);
        this.labelValue.css("color", color);
        return super.color(color);
    }
    background(color="") {
        this.editor.css("background-color", color);
        this.items.css("background-color", color);
        this.labelValue.css("background-color", color);
        if (this.type=="radioenum") {
            super.background(color);
        }
        return this;
    }
    readonly(readonly=true) {
        if(readonly) {
            this.editor.hide();
            this.items.hide();
            if (this.labelValue.length==0) {
                this.element.append('<p class="stic-ReadonlyInput"></p>');
                this.labelValue = this.element.find(".stic-ReadonlyInput");
                // Update label when value is changed
                var self = this;
                this.editor.on("change paste keyup", function() {
                    self.labelValue.text(self.text());
                });
            }
            this.labelValue.show();
            this.editor.change();
        }
        else {
            this.labelValue.hide();
            this.editor.show();
            this.items.show();
        }
        return this;
    }
}
var CustomViewDivDetailInput = class CustomViewDivDetailInput extends CustomViewDivLabel {
    constructor (item, element){
        super(item, element);
    }
}

var CustomViewPanel = class CustomViewPanel extends CustomViewItem {
    constructor (view, panelName) {
        super(view, panelName);
        this.panelName = panelName;
    };
    panel() {
        return new CustomViewDiv(this, this.elementView.find('.panel-body[data-id="'+this.panelName+'"]').parent());
    }
    header() {
        return new CustomViewDivHeader(this, this.panel().element.children('.panel-heading'));
    }
    show(show = true) {
        return this.panel().show(show);
    }
    hide() {
        return this.panel().hide();
    }
}
var CustomViewDivHeader = class CustomViewDivHeader extends CustomViewDivLabel {
    constructor (item, element){
        super(item, element);
        this.anchor = this.element.find("a");
    }
    color(color="") {
        this.anchor.css("color", color);
        return this;
    }
    background(color="") {
        if (this.anchor.length>0) {
            this.anchor[0].style.setProperty("background-color", color, "important");
        }
        return this;
    }

}

var CustomViewTab = class CustomViewTab extends CustomViewItem {
    constructor (view, tabIndex) {
        super(view, tabIndex);
        this.tabIndex = tabIndex;
    };
    header() {
        return new CustomViewDivLabel(this, this.elementView.find('[id=tab'+this.tabIndex+']'));
    }
    show(show = true) {
        return this.header().show(show);
    }
    hide() {
        return this.header().hide();
    }
}

