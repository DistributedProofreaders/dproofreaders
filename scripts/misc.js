
function sprintf(string, p1) {
    "use strict";
    return string.replace("%s", p1);
}

// like php bin2hex()
function encodeWord(word) {
    "use strict";
    var result = "";
    var i;
    for (i = 0; i < word.length; i += 1) {
        result += word.charCodeAt(i).toString(16);
    }
    return result;
}

function escapeHtml(text) {
    var map = {
        "&": "&amp;",
        "<": "&lt;",
        ">": "&gt;",
        "\"": "&quot;",
        "'": "&#039;"
    };

    return text.replace(/[&<>"']/g, function (m) {
        return map[m];
    });
}
