/* eslint no-unused-vars: "off" */

function makeUrl(locator, params = {}, hash = "") {
    let url = new URL(locator);
    url.search = new URLSearchParams(params);
    url.hash = hash;
    return url;
}

function actionButton(label, title = "") {
    const button = document.createElement("button");
    button.classList.add("margin_a");
    button.type = "button";
    button.innerText = label;
    button.title = title;
    return button;
}

function makeCheckBox() {
    const checkBox = document.createElement("input");
    checkBox.type = "checkbox";
    return checkBox;
}

function makeLabel(contents) {
    const label = document.createElement("label");
    label.classList.add("no_wrap", "margin_a");
    label.append(...contents);
    return label;
}

function hide(element) {
    element.style.display = "none";
}

function show(element) {
    element.style.display = "";
}

function makeRadio(name) {
    const radio = document.createElement("input");
    radio.type = "radio";
    radio.name = name;
    return radio;
}
