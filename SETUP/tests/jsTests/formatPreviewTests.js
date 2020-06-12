/* global QUnit analyse */

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
    issueTest(0, 4, 3, "noEndTag", 1);

    // no inline end tag in the paragraph
    text = "the <i>df\n\nnew paragraph</b> ab";
    issArray = analyse(text, inLineTags, suppress);
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

    // bold inside italic, ok
    text = "<i>ab <b>cd</b> ef</i>";
    issArray = analyse(text, inLineTags, suppress);
    assert.deepEqual(issArray.length, 0);

    // nested tag
    text = "<i>ab <b>cd <i>ef</i> gh</b> ij</i>";
    issArray = analyse(text, inLineTags, suppress);
    issueTest(0, 12, 3, "nestedTag", 1);

    // unrecognised tag, u not enabled
    text = "ab <u>cd</u>";
    issArray = analyse(text, inLineTags, suppress);
    issueTest(1, 3, 1, "unRecTag", 0);
    issueTest(0, 8, 1, "unRecTag", 0);

    // u tag enabled
    text = "ab <u>cd</u>";
    issArray = analyse(text, inLineTags + "|u", suppress);
    assert.deepEqual(issArray.length, 0);

    // tabulate character
    text = "ab\tcd";
    issArray = analyse(text, inLineTags, suppress);
    issueTest(0, 2, 1, "tabChar", 1);

    // character before footnote
    text = "xy[1]\n\nz[Footnote 1: ab]";
    issArray = analyse(text, inLineTags, suppress);
    issueTest(0, 8, 9, "charBefore", 1);

    // * before footnote without reference ok
    text = "*[Footnote: ab]";
    issArray = analyse(text, inLineTags, suppress);
    assert.deepEqual(issArray.length, 0);

    // character before out-of-line tag
    text = "x/#\nabc\n#/";
    issArray = analyse(text, inLineTags, suppress);
    issueTest(0, 1, 2, "charBefore", 1);

    // non-blank line before Illustration etc.
    text = "abc\n[Illustration]";
    issArray = analyse(text, inLineTags, suppress);
    issueTest(0, 4, 13, "blankBefore", 1);

    // character after out-of-line tag
    text = "/*\nabc\n*/ x";
    issArray = analyse(text, inLineTags, suppress);
    issueTest(0, 7, 2, "charAfter", 1);
    assert.deepEqual(issArray[0].subText, "*/");

    // character following Illustration Sidenote <tb> etc.
    text = "[Sidenote ] x";
    issArray = analyse(text, inLineTags, suppress);
    issueTest(0, 10, 1, "charAfter", 0);
    assert.deepEqual(issArray[0].subText, "Sidenote");

    // user note following Illustration Sidenote <tb> etc. ok
    text = "[Sidenote ] [** note]";
    issArray = analyse(text, inLineTags, suppress);
    assert.deepEqual(issArray.length, 0);

});
