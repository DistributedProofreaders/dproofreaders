/* global $ QUnit splitControl */

var codeUrl = '../../..';

QUnit.module("splitControl tests", {
    beforeEach: function() {
        $(document.body).append(`
        <div id='split-test'>
          <div style='display: flex;flex-direction: column;height: 100vh;column-flex'>
            <div id='container' style='flex: auto'>
              <div style='overflow: auto'>text 1</div>
              <div style='overflow: auto'>text 2</div>
            </div>
          </div>
        </div>`);
    },
    afterEach: function() {
        $('#split-test').remove();
    },
});

QUnit.test("splitter defaults config values", function(assert) {
    let mainSplit = splitControl("#container");
    mainSplit.reLayout();

    const dragBar = $($('#container div').get(1));
    assert.strictEqual(dragBar.css('cursor'), 'ew-resize', 'verify drag bar has east west resize for vertical split');
});

QUnit.test("vertical split test draws east west drag bar", function(assert) {
    let mainSplit = splitControl("#container", {splitVertical: true});
    mainSplit.reLayout();

    assert.strictEqual($($('#container div').get(1))
        .css('cursor'), 'ew-resize', 'verify drag bar has east west resize for vertical split');
});

QUnit.test("horizontal split test draws north south drag bar", function(assert) {
    let mainSplit = splitControl("#container", {splitVertical: false});
    mainSplit.reLayout();

    assert.strictEqual($($('#container div').get(1))
        .css('cursor'), 'ns-resize', 'verify drag bar has north south resize for horizontal split');
});
