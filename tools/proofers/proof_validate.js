/*global validateText standardInterface switchConfirm revertConfirm */

window.addEventListener('DOMContentLoaded', function() {
    // special handling for certain buttons
    // switch layout - validate before confirm
    document.getElementById("button4").addEventListener("click", function(event) {
        if(validateText() && confirm(switchConfirm)) {
            return;
        } else {
            event.preventDefault();
        }
    });

    // revert to original - validate before confirm
    if (document.getElementById("button8")) {
        document.getElementById("button8").addEventListener("click", function(event) {
            if(validateText() && confirm(revertConfirm)) {
                return;
            } else {
                event.preventDefault();
            }
        });
    }

    // word check -- for standard interface:
    // Direct the (text-only) spellcheck doc to 'textframe'
    // (rather than 'proofframe', the statically defined target).
    document.getElementById("button10").addEventListener("click", function(event) {
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
    document.querySelectorAll(".check_button").forEach(checkButton => {
        checkButton.addEventListener("click", function(event) {
            if(!validateText()) {
                event.preventDefault();
            }
        });
    });

    const conversionMap = {
        '‘': '\'',
        '“': '"',
        '’': '\'',
        '”': '"',
        '—': '-'
    };

    document.getElementById('text_data').addEventListener('input', (event) => {
        event.target.value = [...event.target.value].map(character => {
            return conversionMap[character] || character;
        }).join('');
    });
});
