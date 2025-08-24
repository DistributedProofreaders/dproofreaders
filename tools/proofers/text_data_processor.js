window.addEventListener("DOMContentLoaded", function () {
    // By specifying the string adjustment value in "adjust" we don't need to
    // calculate the character (grapheme) length which is challenging until
    // we can use Intl.Segmenter in very modern browsers.
    // https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Intl/Segmenter
    const conversionMap = {
        "‘": {
            replace: "'",
        },
        "“": {
            replace: '"',
        },
        "’": {
            replace: "'",
        },
        "”": {
            replace: '"',
        },
        "—": {
            replace: "--",
            adjust: 1,
        },
        "…": {
            replace: "...",
            adjust: 2,
        },
    };

    document.getElementById("text_data").addEventListener("input", (event) => {
        const textDataElement = event.target;
        let selectionEnd = textDataElement.selectionEnd;
        textDataElement.value = [...textDataElement.value]
            .map((character) => {
                if (conversionMap[character]) {
                    const replace = conversionMap[character].replace;
                    selectionEnd += conversionMap[character].adjust || 0;
                    return replace;
                } else {
                    return character;
                }
            })
            .join("");
        textDataElement.selectionStart = selectionEnd;
        textDataElement.selectionEnd = selectionEnd;
    });
});
