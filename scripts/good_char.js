/* global $ makeGoodCharRegex validCharacterPattern */
/* exported goodChar */

var goodChar;
$(function () {
    // need to define this after the page has loaded so validCharacterPattern
    // is available
    goodChar = makeGoodCharRegex(validCharacterPattern);
});
