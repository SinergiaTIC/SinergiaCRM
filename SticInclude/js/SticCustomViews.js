/**
 * This file contains logic and functions needed to manage custom views behaviour
 *
 */
const CustomView  = {
    editview: {
        field: (fieldName) => new CustomViewField("editview", fieldName),
    },
    detailview: {
        field: (fieldName) => new CustomViewField("detailview", fieldName),
    },
}

class CustomViewField {
    constructor (view, fieldName) {
        this.view = view;
        this.fieldName = fieldName;
    };
    input() {
        if (this.view=="editview") {
            return new CustomViewDivEditInput(this, this.row().element.children('[field="'+this.fieldName+'"]'));
        }
        if (this.view=="detailview") {
            return new CustomViewDivDetailInput(this, this.row().element.children('[field="'+this.fieldName+'"]'));
        }
    }
    label() {
        return new CustomViewDivLabel(this, this.row().element.children('.label'));
    }
    row() {
        return new CustomViewDiv(this, $('*[data-field="'+this.fieldName+'"]'));
    }
    show(show = true) {
        return this.row().show(show);
    }
    hide() {
        return this.row().hide();
    }
}
class CustomViewDiv {
    constructor (field, element){
        this.field = field;
        this.element = element;
    }
    show(show=true) {
        if(show) {
            this.element.show();
        } else {
            this.hide();
        }
        return this;
    }
    hide() {
        return this.show(false);
    }
}
class CustomViewDivLabel extends CustomViewDiv {
    constructor (field, element){
        super(field, element);
    }
    color(color) {
        this.element.css("color", color);
        return this;
    }
    background(color) {
        this.element.css("background-color", color);
        return this;
    }
}
class CustomViewDivEditInput extends CustomViewDivLabel {
    constructor (field, element){
        super(field, element);
        this.editor = this.element.find(":input");
        this.items = this.element.find(".items");
    }
    color(color) {
        this.editor.css("color", color);
        this.items.css("color", color);
        return super.color(color);
    }
    background(color) {
        this.editor.css("background-color", color);
        this.items.css("background-color", color);
        return this;
    }
}
class CustomViewDivDetailInput extends CustomViewDivLabel {
    constructor (field, element){
        super(field, element);
    }
}