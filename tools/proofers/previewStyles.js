/* exported defaultStyles */

// these are the default values. If the user changes anything the new
// styles are saved in local storage and reloaded next time.
// the foreground and background colours for plain text, italic, bold,
// gesperrt, smallcaps, font change, other tags, highlighting issues
// and possible issues.
// An empty color string means use default color
var defaultStyles = {
    t: {bg: "#fffcf4", fg: "#000000"},
    i: {bg: "", fg: "#0000ff"},
    b: {bg: "", fg: "#c55a1b"},
    g: {bg: "", fg: "#8a2be2"},
    sc: {bg: "", fg: "#009700"},
    f: {bg: "", fg: "#ff0000"},
    u: {bg: "", fg: ""},
    etc: {bg: "#ffcaaf", fg: ""},
    err: {bg: "#ff0000", fg: ""},
    hlt: {bg: "#ceff09", fg: ""},
    blockquote: {bg: "#fecafe", fg: ""},
    nowrap: {bg: "#d1fcff", fg: ""},
    color: true, // colour the markup or not
    allowUnderline: false,
    defFontIndex: 0,
    suppress: {},
    initialViewMode: "no_tags",
    allowMathPreview: false
};
