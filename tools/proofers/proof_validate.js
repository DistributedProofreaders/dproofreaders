/*global $ validateText standardInterface switchConfirm revertConfirm */

$(function () {
    // special handling for certain buttons
    // switch layout - validate before confirm
    $("#button4").click(function(event) {
        if(validateText() && confirm(switchConfirm)) {
            return;
        } else {
            event.preventDefault();
        }
    });

    // revert to original - validate before confirm
    $("#button8").click(function(event) {
        if(validateText() && confirm(revertConfirm)) {
            return;
        } else {
            event.preventDefault();
        }
    });

    // word check -- for standard interface:
    // Direct the (text-only) spellcheck doc to 'textframe'
    // (rather than 'proofframe', the statically defined target).
    $("#button10").click(function(event) {
        if(!validateText()) {
            event.preventDefault();
            return;
        }
        if(standardInterface) {
            document.getElementById('editform').target = 'textframe';
        }
    });

    // this applies to "Save as 'Done'", "Save as 'In Progress'"
    // and "Save as 'Done' & Proofread Next Page"
    $(".check_button").click(function(event) {
        if(!validateText()) {
            event.preventDefault();
        }
    });
});
