/* global QUnit add */

QUnit.module("Hiero tests", function(hooks) {
    let hierobox;
    hooks.beforeEach(function() {
        hierobox = document.createElement('textarea');
        hierobox.name = 'hierobox';
        document.body.appendChild(hierobox);
    });

    hooks.afterEach(function() {
        document.body.removeChild(hierobox);
    });

    QUnit.test("adds separator for last character numeric", function (assert) {
        hierobox.value = '9';
        add('glyph');
        assert.strictEqual(hierobox.value, '9-glyph', '- separator inserted for numbers');
    });

    QUnit.test("adds separator for last character upper case", function (assert) {
        hierobox.value = 'PGDP';
        add('glyph');
        assert.strictEqual(hierobox.value, 'PGDP-glyph', '- separator inserted for upper case');
    });

    QUnit.test("adds separator for last character lower case", function (assert) {
        hierobox.value = 'pgdp';
        add('glyph');
        assert.strictEqual(hierobox.value, 'pgdp-glyph', '- separator inserted for lower case');
    });

    QUnit.test("does not add separator for last character symbol", function (assert) {
        hierobox.value = '[pgdp]';
        add('glyph');
        assert.strictEqual(hierobox.value, '[pgdp]glyph', '- separator not inserted for symbol');
    });
});
