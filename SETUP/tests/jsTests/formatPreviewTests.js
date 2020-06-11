/*QUnit.test( "a basic test example", function( assert ) {
    var value = "hello";
    assert.equal( value, "hello", "We expect value to be hello" );
});*/

QUnit.test("Format preview test", function( assert ) {
    let inLineTags = "[ibfg]|sc";
    let suppress = {};
    let text;
    let issArray;

    // assert start, length code and type correct for issue at index
    function issueTest(index, start, length, code, type) {
        let issue = issArray[index];
        assert.deepEqual(issue.start, start);
        assert.deepEqual(issue.len, length);
        assert.deepEqual(issue.code, code);
        assert.deepEqual(issue.type, type);
    }

    // missing inline start tag
    text = "df</b> ab";
    issArray = analyse(text, inLineTags, suppress);
    issueTest(0, 2, 4, "noStartTag", 1);

    // missing inline end tag
    text = "the <i>df";
    issArray = analyse(text, inLineTags, suppress);
//    console.log(issArray);
    issueTest(0, 4, 3, "noEndTag", 1);

    // no inline end tag in the paragraph
    text = "the <i>df\n\nnew paragraph</b> ab";
    issArray = analyse(text, inLineTags, suppress);
//    console.log(issArray);
    issueTest(1, 4, 3, "noEndTagInPara", 1);
    issueTest(0, 24, 4, "noStartTag", 1);

    // mismatched tags
    text = "the <i>df</b> ab";
    issArray = analyse(text, inLineTags, suppress);
    issueTest(1, 4, 3, "misMatchTag", 1);
    issueTest(0, 9, 4, "misMatchTag", 1);

    // character before start tag, possible issue
    text = "as<i>df</i>";
    issArray = analyse(text, inLineTags, suppress);
    issueTest(0, 1, 1, "charBeforeStart", 0);

    // - before start tag, ok
    text = "as-<i>df</i>";
    issArray = analyse(text, inLineTags, suppress);
    assert.deepEqual(issArray.length, 0);

});
