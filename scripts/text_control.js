/*global $ */
// this operates the font face, font size and wrap controls
$(function () {
    var fontFaceSelector = document.getElementById("fntFace");
    var fontSizeSelector = document.getElementById("fntSize");
    var wrapCheck = document.getElementById("wrap");

    function setFontFace() {
        $("#text_data").css("font-family", fontFaceSelector.value);
    }

    var fontFaceIndex = localStorage.getItem("font_face_index");
    if(fontFaceIndex) {
        fontFaceSelector.selectedIndex = fontFaceIndex;
    }
    setFontFace();

    $(fontFaceSelector).change(function () {
        setFontFace();
        localStorage.setItem("font_face_index", fontFaceSelector.selectedIndex);
    });

    function setFontSize() {
        $("#text_data").css("font-size", fontSizeSelector.value);
    }

    var fontSizeIndex = localStorage.getItem("font_size_index");
    if(fontSizeIndex) {
        fontSizeSelector.selectedIndex = fontSizeIndex;
    }
    setFontSize();

    $(fontSizeSelector).change(function () {
        setFontSize();
        localStorage.setItem("font_size_index", fontSizeSelector.selectedIndex);
    });

    function setWrap() {
        $("#text_data").attr('wrap', wrapCheck.checked ? 'soft' : 'off');
    }

    var textWrap = JSON.parse(localStorage.getItem("text_wrap"));
    if(!textWrap) {
        textWrap = false;
    }
    wrapCheck.checked = textWrap;
    setWrap();

    $(wrapCheck).change(function () {
        localStorage.setItem("text_wrap", JSON.stringify(wrapCheck.checked));
        setWrap();
    });
});
