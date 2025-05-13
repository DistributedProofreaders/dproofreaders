/* global translations */
import gettext from "../node_modules/gettext.js/dist/gettext.esm.js";

export var translate = gettext();

var loadTranslations = function () {
    console.debug("Loading translations");
    if (typeof translations !== "undefined") {
        Object.entries(translations).forEach(([key, value]) => {
            var headers = value[""];
            delete value[""];
            translate.setMessages("messages", key, value, headers["plural-forms"]);
        });
    } else {
        console.debug("No translations found.");
    }
};

window.addEventListener("DOMContentLoaded", () => {
    loadTranslations();
});

export default translate;
