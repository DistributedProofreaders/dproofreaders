/*global $ splitControl pageBrowse showGoodWordSuggestionsDetail */

$(function () {
    let STORAGE_KEY = 'show_good_word_suggestions_detail';
    // this is a function to get a function to show a file
    let getShowCurrentImageFile = null;

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

    let params = new URLSearchParams();
    params.set("mode", "image");
    params.set("project", showGoodWordSuggestionsDetail.projectid);
    params.set("simpleHeader", "true");

    $(".page-select").click( function () {
        let imageFile = this.dataset.value;
        let ShowCurrentImageFile;
        // getShowCurrentImageFile will be null the first time
        if(getShowCurrentImageFile && (ShowCurrentImageFile = getShowCurrentImageFile())) {
            // ShowCurrentImageFile could be null if ajax failed or slow
            ShowCurrentImageFile(imageFile);
        } else {
            params.set("imagefile", imageFile);
            // give a no-action function for replace params
            getShowCurrentImageFile = pageBrowse(params, function () {});
        }
    });
});
