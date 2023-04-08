/* global $ QUnit AddProofer */

QUnit.module("addproofer tests", {
    beforeEach: function() {
        $(document.body).append(`
        <table id='add-proofer-table'>
          <tr>
            <td>
              <select name='referrer'>
                <option value='search' selected>Search Engine (Google, Bing, etc)</option>
                <option value='other'>Other</option>
              </select>
            </td>
          </tr>
          <tr id='referrer_details'>
            <td>
              <input
                type='text'
                name='referrer_details'
            </td>
          </tr>
        </table>`);
    },
    afterEach: function() {
        $('#add-proofer-table').remove();
    },
});

QUnit.test("Referrer details hidden if other not selected", function(assert) {
    var addProofer = new AddProofer();
    assert.ok(
        $('#referrer_details').is(':visible'),
        'Referrer details should be visible before form init.');
    addProofer.initForm();
    assert.notOk(
        $('#referrer_details').is(':visible'),
        'Referrer details should be hidden after form init.');
});

QUnit.test("Referrer details visible if other selected", function(assert) {
    var addProofer = new AddProofer();
    assert.ok(
        $('#referrer_details').is(':visible'),
        'Referrer details should be visible before form init.');
    addProofer.initForm();
    assert.notOk(
        $('#referrer_details').is(':visible'),
        'Referrer details should be hidden after form init.');
    document.querySelector('select[name=referrer]').value = 'other';
    document.querySelector('select[name=referrer]').dispatchEvent(new Event('change'));
    assert.ok(
        $('#referrer_details').is(':visible'),
        'Referrer details should be visible after other selection.');
});
