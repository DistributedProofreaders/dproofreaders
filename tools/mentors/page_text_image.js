/*global $ initSplit mainSplit */

// this starts the text splitter bar and adjusts it if its container is resized
$(function () {
    var textSplit = initSplit("text_pane", 0, 50, 30, 2);
    mainSplit.reSize.add(textSplit.reLayout);
});
