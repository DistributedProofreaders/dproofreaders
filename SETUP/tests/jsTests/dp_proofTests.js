/* global QUnit surroundSelection submitForm */

QUnit.module("dp_proof tests", function(hooks) {
    let setTestText = (text, start, end) => {
        window.docRef = {
            editform: {
                // eslint-disable-next-line camelcase
                text_data: document.createElement('textarea'),
            }
        };
        window.docRef.editform.text_data.value = text;
        window.docRef.editform.text_data.setSelectionRange(start, end, 'forward');
    };

    hooks.beforeEach(function() {
        window.inFace = 0;
    });

    hooks.afterEach(function() {
        delete window.docRef;
        delete window.inFace;
    });

    QUnit.test("submitForm prevents double submit", function (assert) {
        const done = assert.async();
        const form = document.createElement('form');
        const submitButton = document.createElement('input');
        let submitCount = 0;
        submitButton.type = 'submit';
        form.addEventListener('submit', (event) => {
            event.preventDefault();
            submitForm(event.target);
            submitCount++;
            return false;
        });
        form.appendChild(submitButton);
        document.body.appendChild(form);
        assert.false(submitButton.disabled, "button should be disabled");
        submitButton.click();
        setTimeout(() => {
            // simulate event loop for clicks
            submitButton.click(); // click second time
            setTimeout(() => {
                assert.true(submitButton.disabled, "button should be disabled");
                assert.strictEqual(submitCount, 1, "Form should've been submitted only once");
                done();
            }, 0);
        }, 0);
    });

    QUnit.test("surroundSelection surrounds selection", function (assert) {
        setTestText('hello', 0, 5);
        surroundSelection('<i>', '</i>');
        assert.strictEqual(window.docRef.editform.text_data.value, "<i>hello</i>", 'surrouned text with <i>');
    });

    QUnit.test("surroundSelection surrounds selection", function (assert) {
        setTestText('hello', 0, 5);
        surroundSelection('<i>', '</i>');
        assert.strictEqual(window.docRef.editform.text_data.value, "<i>hello</i>", 'surrouned text with <i>');
    });

    QUnit.test("surroundSelection with [** without selection", function (assert) {
        setTestText('hello', 0, 0);
        surroundSelection('[** ', ']');
        assert.strictEqual(window.docRef.editform.text_data.value, "[** ]hello", 'inserts [**');
    });

    QUnit.test("surroundSelection with [** duplicates selection for typos", function (assert) {
        setTestText('hello', 0, 5);
        surroundSelection('[** ', ']');
        assert.strictEqual(window.docRef.editform.text_data.value, "hello[** hello]", 'surrouned text with [**');
    });

    QUnit.test("surroundSelection with [Illustration: with selection", function (assert) {
        setTestText('hello', 0, 5);
        surroundSelection('[Illustration: ', ']');
        assert.strictEqual(window.docRef.editform.text_data.value, "[Illustration: hello]", 'surrouned text with [Illustration: ');
    });

    QUnit.test("surroundSelection with [Illustration: without selection", function (assert) {
        setTestText('hello', 0, 0);
        surroundSelection('[Illustration: ', ']');
        assert.strictEqual(window.docRef.editform.text_data.value, "[Illustration]hello", 'inserts text with [Illustration ');
    });

    QUnit.test("surroundSelection with [Illustration: without selection", function (assert) {
        setTestText('hello', 0, 0);
        surroundSelection('[Illustration: ', ']');
        assert.strictEqual(window.docRef.editform.text_data.value, "[Illustration]hello", 'inserts text with [Illustration ');
    });

    QUnit.test("surroundSelection with [Footnote #:  without selection", function (assert) {
        setTestText('hello', 0, 0);
        surroundSelection('[Footnote #: ', ']');
        assert.strictEqual(window.docRef.editform.text_data.value, "[Footnote]hello", 'inserts text with [Footnote]');
    });

    QUnit.test("surroundSelection with [Footnote #:  with selection", function (assert) {
        setTestText('hello', 0, 5);
        surroundSelection('[Footnote #: ', ']');
        assert.strictEqual(window.docRef.editform.text_data.value, "[Footnote: hello]", 'surrounds text with [Footnote: ]');
    });

    QUnit.test("surroundSelection with [Footnote #:  with selection and identifier", function (assert) {
        setTestText('a hello', 0, 7);
        surroundSelection('[Footnote #: ', ']');
        assert.strictEqual(window.docRef.editform.text_data.value, "[Footnote a: hello]", 'surrounds text with [Footnote: ]');
    });

    QUnit.test("surroundSelection with [Footnote #:  with selection and identifier", function (assert) {
        setTestText('1 hello', 0, 7);
        surroundSelection('[Footnote #: ', ']');
        assert.strictEqual(window.docRef.editform.text_data.value, "[Footnote 1: hello]", 'surrounds text with [Footnote: ]');
    });
});
