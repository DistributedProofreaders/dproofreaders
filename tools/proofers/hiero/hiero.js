/* exported add */

function add(glyph) {
    let hierobox = document.getElementsByName('hierobox')[0];
    let text = hierobox.value;
    let lastc = text.charCodeAt(text.length - 1);
    let sep;
    if((lastc >= 48 && lastc <= 57) || (lastc >= 65 && lastc <= 90) || (lastc >= 97 && lastc <= 122)) {
        sep = '-';
    } else {
        sep = '';
    }

    hierobox.value += sep + glyph;
}