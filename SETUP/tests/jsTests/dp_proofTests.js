/* global QUnit surroundSelection */

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
