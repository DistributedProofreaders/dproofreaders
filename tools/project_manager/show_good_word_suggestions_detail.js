/*global $ pageBrowserData imageControl pageChanger pageTitle splitControl showGoodWordSuggestionsDetail */

$(function () {
    let STORAGE_KEY = 'show_good_word_suggestions_detail';
    var hiddenLayout = function() {
        return $("<input>", {type: 'hidden', name: 'layout', value: showGoodWordSuggestionsDetail.layout});
    };
    var hiddenCutoff = function() {
        return $("<input>", {type: 'hidden', name: 'timeCutoff', value: showGoodWordSuggestionsDetail.timeCutoff});
    };
    var hiddenWord = function() {
        return $("<input>", {type: 'hidden', name: 'word', value: showGoodWordSuggestionsDetail.word});
    };
    var hiddenProjectId = function() {
        return $("<input>", {type: 'hidden', name: 'projectid', value: showGoodWordSuggestionsDetail.projectid});
    };
    let splitPercent = parseFloat(localStorage.getItem(STORAGE_KEY));
    if(!splitPercent) {
        // if not defined or 0, set the value to 30%.
        splitPercent = 30;
    }
    let mainSplit = splitControl("#show_good_word_suggestions_detail_container", {
        splitVertical: showGoodWordSuggestionsDetail.layout === 'vertical',
        splitPercent: splitPercent,
    });
    mainSplit.reLayout();
    mainSplit.dragEnd.add(function (percent) {
        localStorage.setItem(STORAGE_KEY, percent);
    });
    let topDiv = $("#page-browser");
    // the non-scrolling area which will contain any error message and the form with controls
    let fixHead = $("<div>", {class: 'fixed-box control-pane'});
    topDiv.append(fixHead);

    if(pageBrowserData.errorMessage) {
        fixHead.append($("<p>", {class: 'error'}).append(pageBrowserData.errorMessage));
    }

    let pageControlForm = $("<form>", {method: "get"});
    pageControlForm.append(hiddenProjectId());
    pageControlForm.append(hiddenLayout());
    pageControlForm.append(hiddenCutoff());
    pageControlForm.append(hiddenWord());
    pageControlForm.prop("class", "inline");
    pageControlForm.append(pageChanger(pageControlForm));
    fixHead.append(pageControlForm);

    // if a page is given show its name in title and display it in
    // scrollable area with controls and a button to change project.
    pageTitle();

    let stretchDiv = $("<div>", {class: 'stretch-box'});
    topDiv.append(stretchDiv);

    let theImageControl = imageControl();
    fixHead.prepend(theImageControl.controls);
    stretchDiv.addClass("overflow-auto image-pane").append(theImageControl.image);
    theImageControl.setZoom();
});
