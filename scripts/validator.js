import { ajax } from "./api.js";

export function makeValidator(projectId, quill) {
    // more difficult to do this by quill formatting because characters can be
    // more than one js 16-bit character.
    function drawValidate(invalidChars, text) {
        // split into unicode code points
        const allChars = text.split(/(?:)/u);
        const ops = [];
        let goodText = "";

        function appendGood() {
            if (goodText.length > 0) {
                ops.push({ insert: goodText });
                goodText = "";
            }
        }

        for (const char of allChars) {
            const title = invalidChars[char];
            if (title) {
                appendGood();
                ops.push({ insert: char, attributes: { title: title, background: "#ff99cc" } });
            } else {
                goodText += char;
            }
        }
        appendGood();
        quill.setContents({ ops: ops }, "silent");
    }

    async function enter() {
        const text = quill.getText();
        try {
            const data = await ajax("PUT", `v1/projects/${projectId}/validatetext`, {}, { text: text });
            drawValidate(data.invalid_chars, text);
        } catch (error) {
            alert(error.message);
        }
    }

    return {
        enter,
    };
}
