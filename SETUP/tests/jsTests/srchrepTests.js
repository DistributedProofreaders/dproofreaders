/* global $ QUnit srchrep */

let originalOpener = opener;
QUnit.module("srchrep tests", {
    beforeEach: function() {
        // eslint-disable-next-line camelcase
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
    window.opener = { parent: { docRef: { editform: { text_data: {
        value: '<i>value</i>.',
    } } } } };
    $('#search').val('<i>value</i>.');
    $('#replace').val('value.');
    srchrep.doReplace();
    assert.strictEqual(
        window.opener.parent.docRef.editform.text_data.value,
        'value.',
        'Search replace replaces all instances.');
    assert.notOk($('#undo').prop('disabled'), 'Undo should be enabled.');
});

QUnit.test("replaces -- instances", function(assert) {
    window.opener = { parent: { docRef: { editform: { text_data: {
        value: 'brave -- new.',
    } } } } };
    $('#search').val('--');
    $('#replace').val('-');
    srchrep.doReplace();
    assert.strictEqual(
        window.opener.parent.docRef.editform.text_data.value,
        'brave - new.',
        'Search replace replaces all instances.');
    assert.notOk($('#undo').prop('disabled'), 'Undo should be enabled.');
});

QUnit.test("replaces with $ correctly", function(assert) {
    window.opener = { parent: { docRef: { editform: { text_data: {
        value: 'brave -- new.',
    } } } } };
    $('#search').val('--');
    $('#replace').val('$');
    srchrep.doReplace();
    assert.strictEqual(
        window.opener.parent.docRef.editform.text_data.value,
        'brave $ new.',
        'Search replace replaces all instances.');
    assert.notOk($('#undo').prop('disabled'), 'Undo should be enabled.');
});

QUnit.test("supports regular expressions", function(assert) {
    window.opener = { parent: { docRef: { editform: { text_data: {
        value: 'Example search text.',
    } } } } };
    $('#search').val('s.*ch');
    $('#replace').val('');
    $('#is_regex').prop('checked', true);
    srchrep.doReplace();
    assert.strictEqual(
        window.opener.parent.docRef.editform.text_data.value,
        'Example  text.',
        'Search replace replaces all instances with regular expressions.');
    assert.notOk($('#undo').prop('disabled'), 'Undo should be enabled.');
});

QUnit.test("restore saved text undoes replace", function(assert) {
    window.opener = { parent: { docRef: { editform: { text_data: {
        value: 'Example search text.',
    } } } } };
    $('#search').val('search');
    $('#replace').val('sch');
    srchrep.doReplace();
    assert.strictEqual(
        window.opener.parent.docRef.editform.text_data.value,
        'Example sch text.',
        'Search replace replaces all instances.');
    assert.notOk($('#undo').prop('disabled'), 'Undo should be enabled.');
    srchrep.restoreSavedText();
    assert.strictEqual(
        window.opener.parent.docRef.editform.text_data.value,
        'Example search text.',
        'undo restores original text.');
    assert.ok($('#undo').prop('disabled'), 'Undo should be enabled.');
});

QUnit.test("supports \\n for newlines as replace value", function(assert) {
    window.opener = { parent: { docRef: { editform: { text_data: {
        value: 'Example search -- and replace search text <i>value</i>.',
    } } } } };
    $('#search').val('search');
    $('#replace').val('s\\n');
    srchrep.doReplace();
    assert.strictEqual(
        window.opener.parent.docRef.editform.text_data.value,
        'Example s\r\n -- and replace s\r\n text <i>value</i>.',
        'Search replace replaces all instances with regular expressions.');
    assert.notOk($('#undo').prop('disabled'), 'Undo should be enabled.');
});
