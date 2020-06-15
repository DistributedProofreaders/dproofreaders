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

    // assert issue exists and start, length, type, code and subText correct
    function issueTest(index, start, length, code, type, subText = "") {
        // put code as message in first one
        let issueExists = (issArray.length > index);
        assert.ok(issueExists, code);
        if(issueExists) {
            let issue = issArray[index];
            assert.strictEqual(issue.code, code);
            assert.strictEqual(issue.start, start);
            assert.strictEqual(issue.len, length);
            assert.strictEqual(issue.type, type);
            assert.strictEqual(issue.subText, subText);
        }
    }

    // assert there are no issues
    function noIssueTest(description) {
        assert.strictEqual(issArray.length, 0, description);
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

    // bold inside italic, ok
    text = "<i>ab <b>cd</b> ef</i>";
    issArray = analyse(text, configuration);
    noIssueTest("bold inside italic");

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
    noIssueTest("underline enabled");

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
    noIssueTest("* before footnote without reference");

    // character before out-of-line tag
    text = "x/#\nabc\n#/";
    issArray = analyse(text, configuration);
    issueTest(0, 1, 2, "charBefore", 1);

    // non-blank line before Illustration etc.
    text = "abc\n[Illustration]";
    issArray = analyse(text, configuration);
    issueTest(0, 4, 13, "blankBefore", 1);

    // non-blank line after Illustration <tb> etc.
    text = "abc\n\n<tb>\ndef";
    issArray = analyse(text, configuration);
    issueTest(0, 5, 4, "blankAfter", 1, "&lt;tb&gt;");

    // end of no-wrap after illustration ok
    text = "/*\nabc\n\n[Illustration ab]\n*/";
    issArray = analyse(text, configuration);
    noIssueTest("end of no-wrap after illustration");

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
    noIssueTest("block-quote inside block-quote");

    // character after out-of-line tag
    text = "/*\nabc\n*/ x";
    issArray = analyse(text, configuration);
    issueTest(0, 7, 2, "charAfter", 1, "*/");

    // character following Illustration Sidenote <tb> etc.
    text = "[Sidenote ] x";
    issArray = analyse(text, configuration);
    issueTest(0, 10, 1, "charAfter", 0, "Sidenote");

    // user note following Illustration Sidenote <tb> etc. ok
    text = "[Sidenote ] [** note]";
    issArray = analyse(text, configuration);
    noIssueTest("user note following Illustration Sidenote <tb> etc.");

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

    // new line before end tag
    text = "as<sc>Df\n</sc>";
    issArray = analyse(text, configuration);
    issueTest(0, 9, 5, "nlBeforeEnd", 1);

    // space before end tag
    text = "as<f>Df </f>";
    issArray = analyse(text, configuration);
    issueTest(0, 7, 1, "spaceBeforeEnd", 1);

    // entirely bold heading
    text = "ab\n\n\n<b>cd</b>\n\nef";
    issArray = analyse(text, configuration);
    issueTest(0, 5, 3, "noBold", 1);

    // small cap text with no capitals
    text = "<sc>abcd</sc>";
    issArray = analyse(text, configuration);
    issueTest(0, 0, 4, "scNoCap", 1);

    // small cap text with capitals only in comment, possible issue
    text = "<sc>abcd[** ABCD]</sc>";
    issArray = analyse(text, configuration);
    issueTest(0, 4, 1, "scNoCap", 0);

    // word character or ,.;: before start tag, possible issue
    text = "a;<i>df</i>";
    issArray = analyse(text, configuration);
    issueTest(0, 1, 1, "charBeforeStart", 0);

    // word character or ,.;: before start tag, possible issue
    // test for non-basic-latin character
    text = "aÀ<i>df</i>";
    issArray = analyse(text, configuration);
    issueTest(0, 1, 1, "charBeforeStart", 0);

    // - before start tag, ok
    text = "as-<i>df</i>";
    issArray = analyse(text, configuration);
    noIssueTest("- before start tag");

    // word character after end tag, possible issue
    text = "<i>df</i>x";
    issArray = analyse(text, configuration);
    issueTest(0, 9, 1, "charAfterEnd", 0);

    // ,;: before end tag, possible issue
    text = "<i>df;</i> abc";
    issArray = analyse(text, configuration);
    issueTest(0, 5, 1, "puncBEnd", 0);

    // ,;: before end tag, ok at end of text
    text = "<i>df;</i>";
    issArray = analyse(text, configuration);
    noIssueTest("puncBEnd at eot");

    // user note missing ]
    text = "[** abc";
    issArray = analyse(text, configuration);
    issueTest(0, 0, 3, "noCloseBrack", 1);

    // Footnote etc. missing ]
    text = "*[Footnote: ab\ncd";
    issArray = analyse(text, configuration);
    issueTest(0, 0, 10, "noCloseBrack", 0);

    // Footnote id must be letter or number
    text = "[Footnote *: ab\ncd]";
    issArray = analyse(text, configuration);
    issueTest(0, 0, 9, "footnoteId", 0);

    // Footnote anchor must not be *
    text = "[*]";
    issArray = analyse(text, configuration);
    issueTest(0, 0, 3, "starAnchor", 0);

    // no Footnote corresponding to anchor
    text = "[A][B]\n\n[Footnote A: abc]";
    issArray = analyse(text, configuration);
    issueTest(0, 3, 3, "noFootnote", 0);

    // no anchor corresponding to Footnote
    text = "xyz\n\n[Footnote A: abc]";
    issArray = analyse(text, configuration);
    issueTest(0, 5, 9, "noAnchor", 0);

    // a colon must be present
    text = "xyz\n\n[Footnote A abc]";
    issArray = analyse(text, configuration);
    issueTest(0, 5, 9, "noColon", 0);

    // colon must immediately follow continuation Footnote
    text = "xyz\n\n*[Footnote : abc]";
    issArray = analyse(text, configuration);
    issueTest(0, 6, 9, "colonNext", 0);

    // space must immediately follow Footnote
    text = "xyz\n\n[Footnote: abc]";
    issArray = analyse(text, configuration);
    issueTest(0, 5, 9, "spaceNext", 0);

    // duplicated footnote id
    text = "xyz[A]\n\n[Footnote A: abc]\n\n[Footnote A: def]";
    issArray = analyse(text, configuration);
    issueTest(0, 27, 9, "dupNote", 0);

    // a continuation footnote should precede other footnotes
    text = "xyz[A]\n\n[Footnote A: abc]\n\n*[Footnote: def]";
    issArray = analyse(text, configuration);
    issueTest(0, 28, 9, "continueFirst", 0);

    // pair of inline tags with no content
    text = "<g></g>";
    issArray = analyse(text, configuration);
    issueTest(1, 0, 3, "emptyTag", 1);
    issueTest(0, 3, 4, "emptyTag", 1);

    // duplicated footnote id
    text = "abc[A]\ndef[A]\nghi[A]\njkl[B]\n\n[Footnote A: abc]\n\n[Footnote B: mno]";
    issArray = analyse(text, configuration);
    console.log(issArray);
    issueTest(2, 3, 3, "multipleAnchors", 0);
    issueTest(1, 10, 3, "multipleAnchors", 0);
    issueTest(0, 17, 3, "multipleAnchors", 0);
});
