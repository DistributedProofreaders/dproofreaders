/* global QUnit analyse */

QUnit.test("Format preview test", function( assert ) {
    let configuration = {
        suppress: {},
        allowUnderline: false,
    };

    let underlineConfig = {
        suppress: {},
        allowUnderline: true,
    };

    let text;
    let issArray;

    // assert start, length code and type correct for issue at index
    function issueTest(index, start, length, code, type) {
        let issue = issArray[index];
        assert.strictEqual(issue.start, start);
        assert.strictEqual(issue.len, length);
        assert.strictEqual(issue.code, code);
        assert.strictEqual(issue.type, type);
    }

    // missing inline start tag
    text = "df</b> ab";
    issArray = analyse(text, configuration);
    issueTest(0, 2, 4, "noStartTag", 1);

    // missing out-of-line start tag
    text = "abc\n#/";
    issArray = analyse(text, configuration);
    issueTest(0, 4, 2, "noStartTag", 1);

    // missing inline end tag
    text = "the <i>df";
    issArray = analyse(text, configuration);
    issueTest(0, 4, 3, "noEndTag", 1);

    // missing out-of-line end tag
    text = "abc\n\n/*\ndf";
    issArray = analyse(text, configuration);
    issueTest(0, 5, 2, "noEndTag", 1);

    // no inline end tag in the paragraph
    text = "the <i>df\n\nnew paragraph</b> ab";
    issArray = analyse(text, configuration);
    issueTest(1, 4, 3, "noEndTagInPara", 1);
    issueTest(0, 24, 4, "noStartTag", 1);

    // mismatched tags
    text = "the <i>df</b> ab";
    issArray = analyse(text, configuration);
    issueTest(1, 4, 3, "misMatchTag", 1);
    issueTest(0, 9, 4, "misMatchTag", 1);

    // - before start tag, ok
    text = "as-<i>df</i>";
    issArray = analyse(text, configuration);
    assert.strictEqual(issArray.length, 0);

    // bold inside italic, ok
    text = "<i>ab <b>cd</b> ef</i>";
    issArray = analyse(text, configuration);
    assert.strictEqual(issArray.length, 0);

    // nested tag
    text = "<i>ab <b>cd <i>ef</i> gh</b> ij</i>";
    issArray = analyse(text, configuration);
    issueTest(0, 12, 3, "nestedTag", 1);

    // unrecognised tag, u not enabled
    text = "ab <u>cd</u>";
    issArray = analyse(text, configuration);
    issueTest(1, 3, 1, "unRecTag", 0);
    issueTest(0, 8, 1, "unRecTag", 0);

    // u tag enabled
    text = "ab <u>cd</u>";
    issArray = analyse(text, underlineConfig);
    assert.strictEqual(issArray.length, 0);

    // tabulate character
    text = "ab\tcd";
    issArray = analyse(text, configuration);
    issueTest(0, 2, 1, "tabChar", 1);

    // character before footnote
    text = "xy[1]\n\nz[Footnote 1: ab]";
    issArray = analyse(text, configuration);
    issueTest(0, 8, 9, "charBefore", 1);

    // * before footnote without reference ok
    text = "*[Footnote: ab]";
    issArray = analyse(text, configuration);
    assert.strictEqual(issArray.length, 0);

    // character before out-of-line tag
    text = "x/#\nabc\n#/";
    issArray = analyse(text, configuration);
    issueTest(0, 1, 2, "charBefore", 1);

    // non-blank line before Illustration etc.
    text = "abc\n[Illustration]";
    issArray = analyse(text, configuration);
    issueTest(0, 4, 13, "blankBefore", 1);

    // non-blank line after Illustration etc.
    text = "abc\n\n<tb>\ndef";
    issArray = analyse(text, configuration);
    issueTest(0, 5, 4, "blankAfter", 1);
    assert.strictEqual(issArray[0].subText, "&lt;tb&gt;");

    // no-wrap inside no-wrap
    text = "/*\nabc\n\n/*\ndef\n*/\n*/";
    issArray = analyse(text, configuration);
    issueTest(0, 8, 2, "NWinNW", 1);

    // block-quote inside no-wrap
    text = "/*\nabc\n\n/#\ndef\n#/\n*/";
    issArray = analyse(text, configuration);
    issueTest(0, 8, 2, "BQinNW", 1);

    // block-quote inside block-quote ok
    text = "/#\nabc\n\n/#\ndef\n#/\n#/";
    issArray = analyse(text, configuration);
    assert.strictEqual(issArray.length, 0);

    // character after out-of-line tag
    text = "/*\nabc\n*/ x";
    issArray = analyse(text, configuration);
    issueTest(0, 7, 2, "charAfter", 1);
    assert.strictEqual(issArray[0].subText, "*/");

    // character following Illustration Sidenote <tb> etc.
    text = "[Sidenote ] x";
    issArray = analyse(text, configuration);
    issueTest(0, 10, 1, "charAfter", 0);
    assert.strictEqual(issArray[0].subText, "Sidenote");

    // user note following Illustration Sidenote <tb> etc. ok
    text = "[Sidenote ] [** note]";
    issArray = analyse(text, configuration);
    assert.strictEqual(issArray.length, 0);

    // non-blank line before out-of-line start tag
    text = "x\n/*\nabc\n*/";
    issArray = analyse(text, configuration);
    issueTest(0, 2, 2, "OolPrev", 1);

    // non-blank line after out-of-line start tag
    text = "/*\nabc\n*/\nx";
    issArray = analyse(text, configuration);
    issueTest(0, 7, 2, "OolNext", 1);

    // only 1, 2 or 4 blank lines
    text = "\n\n\nabc\n\n\n\n\ndef\n\n\n\n\n\nghi"
    issArray = analyse(text, configuration);
//    console.log(issArray);
    issueTest(0, 20, 1, "blankLines124", 1);
    issueTest(1, 3, 1, "blankLines124", 1);

    // ,.;:!\? after start tag
    text = "as<i>,df</i>";
    issArray = analyse(text, configuration);
    issueTest(0, 5, 1, "puncAfterStart", 0);

    // space after start tag
    text = "as<i> df</i>";
    issArray = analyse(text, configuration);
    issueTest(0, 5, 1, "spaceAfterStart", 1);

    // new line after start tag
    text = "as<sc>\nDf</sc>";
    issArray = analyse(text, configuration);
    issueTest(0, 2, 4, "nlAfterStart", 1);



    // character before start tag, possible issue
    text = "as<i>df</i>";
    issArray = analyse(text, configuration);
    issueTest(0, 1, 1, "charBeforeStart", 0);


});
