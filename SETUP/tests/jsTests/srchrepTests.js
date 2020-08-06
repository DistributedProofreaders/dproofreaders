/* global $ QUnit srchrep */

let originalOpener = opener;
QUnit.module("srchrep tests", {
    beforeEach: function() {
        // eslint-disable-next-line camelcase
        window.opener = { parent: { docRef: { editform: { text_data: {
            value: 'Example search -- and replace search text <i>value</i>.',
        } } } } };
        $(document.body).append(`
        <form id='srchrep-form'>
          <input type="text" id='search'>
          <input type="text" id='replace'>
          <input type="button" id='undo' disabled>
          <input type="checkbox" name="is_regex" id='is_regex'>
        </form>`);
    },
    afterEach: function() {
        $('#srchrep-form').remove();
        window.opener = originalOpener;
    },
});

QUnit.test("replaces all instances", function(assert) {
    $('#search').val('<i>value</i>.');
    $('#replace').val('value.');
    srchrep.doReplace();
    assert.strictEqual(
        window.opener.parent.docRef.editform.text_data.value,
        'Example search -- and replace search text value.',
        'Search replace replaces all instances.');
    assert.notOk($('#undo').prop('disabled'), 'Undo should be enabled.');
});

QUnit.test("replaces -- instances", function(assert) {
    $('#search').val('--');
    $('#replace').val('-');
    srchrep.doReplace();
    assert.strictEqual(
        window.opener.parent.docRef.editform.text_data.value,
        'Example search - and replace search text <i>value</i>.',
        'Search replace replaces all instances.');
    assert.notOk($('#undo').prop('disabled'), 'Undo should be enabled.');
});

QUnit.test("supports regular expressions", function(assert) {
    $('#search').val('s.*ch');
    $('#replace').val('');
    $('#is_regex').prop('checked', true);
    srchrep.doReplace();
    assert.strictEqual(
        window.opener.parent.docRef.editform.text_data.value,
        'Example  text <i>value</i>.',
        'Search replace replaces all instances with regular expressions.');
    assert.notOk($('#undo').prop('disabled'), 'Undo should be enabled.');
});

QUnit.test("restore saved text undoes replace", function(assert) {
    $('#search').val('search');
    $('#replace').val('sch');
    srchrep.doReplace();
    assert.strictEqual(
        window.opener.parent.docRef.editform.text_data.value,
        'Example sch -- and replace sch text <i>value</i>.',
        'Search replace replaces all instances.');
    assert.notOk($('#undo').prop('disabled'), 'Undo should be enabled.');
    srchrep.restoreSavedText();
    assert.strictEqual(
        window.opener.parent.docRef.editform.text_data.value,
        'Example search -- and replace search text <i>value</i>.',
        'undo restores original text.');
    assert.ok($('#undo').prop('disabled'), 'Undo should be enabled.');
});

QUnit.test("supports \\n for newlines as replace value", function(assert) {
    $('#search').val('search');
    $('#replace').val('s\\n');
    srchrep.doReplace();
    assert.strictEqual(
        window.opener.parent.docRef.editform.text_data.value,
        'Example s\r\n -- and replace s\r\n text <i>value</i>.',
        'Search replace replaces all instances with regular expressions.');
    assert.notOk($('#undo').prop('disabled'), 'Undo should be enabled.');
});
