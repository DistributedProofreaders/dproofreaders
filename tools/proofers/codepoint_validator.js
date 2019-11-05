/*global $ codePoints */

// this function is copied from dp_proof.js
// could put it in another file misc.js
function htmlSafe(str) {
    // Return a version of str that is safe to send as element-content
    // in an HTML document.
    // That is, make the following replacements:
    //    &  ->  &amp;
    //    <  ->  &lt;
    //    >  ->  &gt;
    // This should be equivalent to PHP's
    //     htmlspecialchars($str,ENT_NOQUOTES)
    return str
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;');
}

$(function () {
    function utf8Chr(codePoint) {
        return "\\u{" + codePoint.slice(2) + "}";
    }

    var charClass = "";
    codePoints.forEach(function (codePoint) {
        if(codePoint.indexOf("-") === -1) {
            // single code point
            charClass += utf8Chr(codePoint);
        } else {
            // range
            let code = codePoint.split("-");
            charClass += utf8Chr(code[0]) + "-" + utf8Chr(code[1]);
        }
    });
    var pattern = new RegExp("[^" + charClass + "]", "ug");

    $(".check_button").click(function(event) {
        var text = $("#text_data").val();
        text = text.normalize("NFC");
        $("#text_data").val(text);
        if(!pattern.test(text)) {
            // no bad characters found
            return;
        }
        var replacement = "<span class='bad-char'>$&</span>";
        var markedText = htmlSafe(text).replace(pattern , replacement);

        $("#checker").css("display", "flex");
        $("#proofdiv").hide();
        $("#check-text").html(markedText);
        event.preventDefault();
    });

    $("#cc-quit").click(function () {
        $("#checker").hide();
        $("#proofdiv").show();
    });
});
