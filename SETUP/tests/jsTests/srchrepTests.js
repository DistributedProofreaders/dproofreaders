/* global $ QUnit srchrep */

let originalOpener = opener;
QUnit.module("srchrep tests", hooks => {
    const setTextValue = value => {
        // eslint-disable-next-line camelcase
        window.opener = { parent: { docRef: { editform: { text_data: {
            value,
        } } } } };
    };

    hooks.beforeEach(() => {
        $(document.body).append(`
        <form id='srchrep-form'>
          <input type="text" id='search'>
          <input type="text" id='replace'>
          <input type="button" id='undo' disabled>
          <input type="checkbox" name="is_regex" id='is_regex'>
        </form>`);
    });

    hooks.afterEach(() => {
        $('#srchrep-form').remove();
        window.opener = originalOpener;
    });

    QUnit.test("replaces all instances", function(assert) {
        setTextValue('<i>value</i>.');
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
        setTextValue('brave -- new.');
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
        setTextValue('brave -- new.');
        $('#search').val('--');
        $('#replace').val('$');
        srchrep.doReplace();
        assert.strictEqual(
            window.opener.parent.docRef.editform.text_data.value,
            'brave $ new.',
            'Search replace replaces all instances.');
        assert.notOk($('#undo').prop('disabled'), 'Undo should be enabled.');
    });

    QUnit.test("replaces with regular expression groups correctly", function(assert) {
        setTextValue('brave -- new.');
        $('#search').val('(brave) -- (new)');
        $('#replace').val('$2 __ $1');
        $('#is_regex').prop('checked', true);
        srchrep.doReplace();
        assert.strictEqual(
            window.opener.parent.docRef.editform.text_data.value,
            'new __ brave.',
            'Search replace replaces all instances.');
        assert.notOk($('#undo').prop('disabled'), 'Undo should be enabled.');
    });

    QUnit.test("supports regular expressions", function(assert) {
        setTextValue('Example search text.');
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
        setTextValue('Example search text.');
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
        setTextValue('Example search -- and replace search text <i>value</i>.');
        $('#search').val('search');
        $('#replace').val('s\\n');
        srchrep.doReplace();
        assert.strictEqual(
            window.opener.parent.docRef.editform.text_data.value,
            'Example s\r\n -- and replace s\r\n text <i>value</i>.',
            'Search replace replaces all instances with regular expressions.');
        assert.notOk($('#undo').prop('disabled'), 'Undo should be enabled.');
    });
});
