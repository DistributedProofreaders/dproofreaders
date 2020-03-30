/*global $ */
/* exported changeWatcher */

function watchPageChange() {
    var pageSelector = document.getElementById("page-select");
    var prevButton = document.getElementById("prev-button");
    var nextButton = document.getElementById("next-button");
    var newPage = $.Callbacks();

    function pageChange() {
        var currentIndex = pageSelector.selectedIndex;
        prevButton.disabled = (currentIndex <= 0);
        nextButton.disabled = (currentIndex >= (pageSelector.length - 1));
        newPage.fire();
    }

    $(pageSelector).change(function() {
        pageChange();
    });

    $(prevButton).click(function () {
        pageSelector.selectedIndex -= 1;
        pageChange();
    });

    $(nextButton).click(function () {
        pageSelector.selectedIndex += 1;
        pageChange();
    });

    pageChange();

    return {
        newPage: newPage,
    };
}

var changeWatcher;

$(function () {
    changeWatcher = watchPageChange();
//    $(".error").hide(10000);
});
