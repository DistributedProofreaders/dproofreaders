/*global $ textUrl changeWatcher */
$(function () {

    var pageSelector = document.getElementById("page-select");
    var roundSelector = document.getElementById("round");

    function loadText(data) {
        $("#text_data").text(data);
    }

    function showText() {
        $.getJSON(textUrl + "&page=" + pageSelector.value + "&text_column=" + roundSelector.value, "", loadText)
            .fail(function(jqxhr, textStatus, error) {
                $("#text_data").text(textStatus + " " + error);
            });
    }

    changeWatcher.newPage.add(showText);

    showText();

    $(roundSelector).change(showText);
});
