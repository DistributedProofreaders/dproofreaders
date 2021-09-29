/* global QUnit analyse processExMath findClose */

QUnit.module("Format preview test", function() {
    let configuration = {
        suppress: {},
        allowUnderline: false,
    };

    let underlineConfig = {
        suppress: {},
        allowUnderline: true,
    };

    let allowCharBeforeStartConfig = {
        suppress: {charBeforeStart: true},
        allowUnderline: false,
    };

    let noSideNoteBlankConfig = {
        suppress: {sideNoteBlank: true},
        allowUnderline: false,
    };

    let mathConfig = {
        suppress: {},
        allowMathPreview: true
    };

    let text;
    let issArray;

    // assert issue exists and start, length, type, code and subText correct
    function issueTest(assert, index, start, length, code, type, subText = "") {
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
    function noIssueTest(assert) {
        assert.strictEqual(issArray.length, 0);
    }

    QUnit.test("missing inline start tag", function (assert) {
        text = "df</b> ab";
        issArray = analyse(text, configuration).issues;
        issueTest(assert, 0, 2, 4, "noStartTag", 1);
    });

    QUnit.test("missing out-of-line start tag", function (assert) {
        text = "abc\n#/";
        issArray = analyse(text, configuration).issues;
        issueTest(assert, 0, 4, 2, "noStartTag", 1);
    });

    QUnit.test("missing inline end tag", function (assert) {
        text = "the <i>df";
        issArray = analyse(text, configuration).issues;
        issueTest(assert, 0, 4, 3, "noEndTag", 1);
    });

    QUnit.test("missing out-of-line end tag", function (assert) {
        text = "abc\n\n/*\ndf";
        issArray = analyse(text, configuration).issues;
        issueTest(assert, 0, 5, 2, "noEndTag", 1);
    });

    QUnit.test("no inline end tag in the paragraph", function (assert) {
        text = "the <i>df\n\nnew paragraph</b> ab";
        issArray = analyse(text, configuration).issues;
        issueTest(assert, 0, 4, 3, "noEndTagInPara", 1);
        issueTest(assert, 1, 24, 4, "noStartTag", 1);
    });

    QUnit.test("mismatched tags", function (assert) {
        text = "the <i>df</b> ab";
        issArray = analyse(text, configuration).issues;
        issueTest(assert, 0, 4, 3, "misMatchTag", 1);
        issueTest(assert, 1, 9, 4, "misMatchTag", 1);
    });

    QUnit.test("bold inside italic, ok", function (assert) {
        text = "<i>ab <b>cd</b> ef</i>";
        issArray = analyse(text, configuration).issues;
        noIssueTest(assert);
    });

    QUnit.test("nested tag", function (assert) {
        text = "<i>ab <b>cd <i>ef</i> gh</b> ij</i>";
        issArray = analyse(text, configuration).issues;
        issueTest(assert, 0, 12, 3, "nestedTag", 1);
    });

    QUnit.test("unrecognised tag, u not enabled", function (assert) {
        text = "ab <u>cd</u>";
        issArray = analyse(text, configuration).issues;
        issueTest(assert, 0, 3, 3, "unRecTag", 0);
        issueTest(assert, 1, 8, 4, "unRecTag", 0);
    });

    QUnit.test("u tag enabled", function (assert) {
        text = "ab <u>cd</u>";
        issArray = analyse(text, underlineConfig).issues;
        noIssueTest(assert);
    });

    QUnit.test("character before footnote", function (assert) {
        text = "xy[1]\n\nz[Footnote 1: ab]";
        issArray = analyse(text, configuration).issues;
        issueTest(assert, 0, 8, 9, "charBefore", 1);
    });

    QUnit.test("* before footnote without reference ok", function (assert) {
        text = "*[Footnote: ab]";
        issArray = analyse(text, configuration).issues;
        noIssueTest(assert);
    });

    QUnit.test("character before out-of-line tag", function (assert) {
        text = "x/#\nabc\n#/";
        issArray = analyse(text, configuration).issues;
        issueTest(assert, 0, 1, 2, "charBefore", 1);
    });

    QUnit.test("non-blank line before Illustration etc.", function (assert) {
        text = "abc\n[Illustration]";
        issArray = analyse(text, configuration).issues;
        issueTest(assert, 0, 4, 13, "blankBefore", 1);
    });

    QUnit.test("blank then note before Illustration etc.", function (assert) {
        text = "abc\n\n[** note]\n[Illustration]";
        issArray = analyse(text, configuration).issues;
        noIssueTest(assert);
    });

    QUnit.test("non-blank line before Sidenote", function (assert) {
        text = "abc\n[Sidenote: abc]";
        issArray = analyse(text, configuration).issues;
        issueTest(assert, 0, 4, 9, "blankBefore", 1);
    });

    QUnit.test("non-blank line before Sidenote suppressed", function (assert) {
        text = "abc\n[Sidenote: abc]";
        issArray = analyse(text, noSideNoteBlankConfig).issues;
        noIssueTest(assert);
    });

    QUnit.test("non-blank line after Illustration <tb> etc.", function (assert) {
        text = "abc\n\n<tb>\ndef";
        issArray = analyse(text, configuration).issues;
        issueTest(assert, 0, 5, 4, "blankAfter", 1, "&lt;tb&gt;");
    });

    QUnit.test("end of no-wrap after illustration ok", function (assert) {
        text = "/*\nabc\n\n[Illustration ab]\n*/";
        issArray = analyse(text, configuration).issues;
        noIssueTest(assert);
    });

    QUnit.test("no-wrap inside no-wrap", function (assert) {
        text = "/*\nabc\n\n/*\ndef\n*/\n*/";
        issArray = analyse(text, configuration).issues;
        issueTest(assert, 0, 8, 2, "NWinNW", 1);
    });

    QUnit.test("block-quote inside no-wrap", function (assert) {
        text = "/*\nabc\n\n/#\ndef\n#/\n*/";
        issArray = analyse(text, configuration).issues;
        issueTest(assert, 0, 8, 2, "BQinNW", 1);
    });

    QUnit.test("block-quote inside block-quote ok", function (assert) {
        text = "/#\nabc\n\n/#\ndef\n#/\n#/";
        issArray = analyse(text, configuration).issues;
        noIssueTest(assert);
    });

    QUnit.test("character after out-of-line tag", function (assert) {
        text = "/*\nabc\n*/ x";
        issArray = analyse(text, configuration).issues;
        issueTest(assert, 0, 7, 2, "charAfter", 1, "*/");
    });

    QUnit.test("character following Illustration Sidenote <tb> etc.", function (assert) {
        text = "[Sidenote ] x";
        issArray = analyse(text, configuration).issues;
        issueTest(assert, 0, 10, 1, "charAfter", 0, "Sidenote");
    });

    QUnit.test("user note following Illustration Sidenote <tb> etc. ok", function (assert) {
        text = "[Sidenote ] [** note]";
        issArray = analyse(text, configuration).issues;
        noIssueTest(assert);
    });

    QUnit.test("non-blank line before out-of-line start tag", function (assert) {
        text = "x\n/*\nabc\n*/";
        issArray = analyse(text, configuration).issues;
        issueTest(assert, 0, 2, 2, "OolPrev", 1);
    });

    QUnit.test("non-blank line after out-of-line start tag", function (assert) {
        text = "/*\nabc\n*/\nx";
        issArray = analyse(text, configuration).issues;
        issueTest(assert, 0, 7, 2, "OolNext", 1);
    });

    QUnit.test("only 1, 2 or 4 blank lines", function (assert) {
        text = "\n\n\nabc\n\n\n\n\ndef\n\n\n\n\n\nghi";
        issArray = analyse(text, configuration).issues;
        issueTest(assert, 0, 3, 1, "blankLines124", 1);
        issueTest(assert, 1, 20, 1, "blankLines124", 1);
    });

    QUnit.test("4 blank lines with note", function (assert) {
        text = "\n\n\n[** note]\n\nabc";
        issArray = analyse(text, configuration).issues;
        noIssueTest(assert);
    });

    QUnit.test(",.;:!? after start tag", function (assert) {
        text = "as <i>,df</i>";
        issArray = analyse(text, configuration).issues;
        issueTest(assert, 0, 6, 1, "puncAfterStart", 0);
    });

    QUnit.test("space after start tag", function (assert) {
        text = "as <i> df</i>";
        issArray = analyse(text, configuration).issues;
        issueTest(assert, 0, 6, 1, "spaceAfterStart", 1);
    });

    QUnit.test("new line after start tag", function (assert) {
        text = "as <sc>\nDf</sc>";
        issArray = analyse(text, configuration).issues;
        issueTest(assert, 0, 3, 4, "nlAfterStart", 1);
    });

    QUnit.test("new line before end tag", function (assert) {
        text = "as <sc>Df\n</sc>";
        issArray = analyse(text, configuration).issues;
        issueTest(assert, 0, 10, 5, "nlBeforeEnd", 1);
    });

    QUnit.test("space before end tag", function (assert) {
        text = "as <f>Df </f>";
        issArray = analyse(text, configuration).issues;
        issueTest(assert, 0, 8, 1, "spaceBeforeEnd", 1);
    });

    QUnit.test("entirely bold heading", function (assert) {
        text = "ab\n\n\n<b>cd</b>\n\nef";
        issArray = analyse(text, configuration).issues;
        issueTest(assert, 0, 5, 3, "noBold", 1);
    });

    QUnit.test("small cap text with no capitals", function (assert) {
        text = "<sc>abcd</sc>";
        issArray = analyse(text, configuration).issues;
        issueTest(assert, 0, 0, 4, "scNoCap", 1);
    });

    QUnit.test("small cap fragment with no capitals, possible issue", function (assert) {
        text = "<sc>*abcd</sc>";
        issArray = analyse(text, configuration).issues;
        issueTest(assert, 0, 4, 1, "scNoCap", 0);
    });

    QUnit.test("word character or ,.;: before start tag, possible issue", function (assert) {
        text = "a;<i>df</i>";
        issArray = analyse(text, configuration).issues;
        issueTest(assert, 0, 1, 1, "charBeforeStart", 0);
    });

    QUnit.test("number before start tag, ok", function (assert) {
        text = "It cost 9<i>l.</i> 4<i>s.</i> 1<i>d.</i>";
        issArray = analyse(text, configuration).issues;
        noIssueTest(assert);
    });

    QUnit.test("Allow character before start tag", function (assert) {
        text = "a;<i>df</i>";
        issArray = analyse(text, allowCharBeforeStartConfig).issues;
        noIssueTest(assert);
    });

    QUnit.test("non-basic-latin character before start tag", function (assert) {
        text = "aÀ<i>df</i>";
        issArray = analyse(text, configuration).issues;
        issueTest(assert, 0, 1, 1, "charBeforeStart", 0);
    });

    QUnit.test("- before start tag, ok", function (assert) {
        text = "as-<i>df</i>";
        issArray = analyse(text, configuration).issues;
        noIssueTest(assert);
    });

    QUnit.test("word character after end tag, possible issue", function (assert) {
        text = "<i>df</i>éx";
        issArray = analyse(text, configuration).issues;
        issueTest(assert, 0, 9, 1, "charAfterEnd", 0);
    });

    QUnit.test(",;: before end tag, possible issue", function (assert) {
        text = "<i>df;</i> abc";
        issArray = analyse(text, configuration).issues;
        issueTest(assert, 0, 5, 1, "puncBEnd", 0);
    });

    QUnit.test(",;: before end tag, ok at end of text", function (assert) {
        text = "<i>df;</i>";
        issArray = analyse(text, configuration).issues;
        noIssueTest(assert);
    });

    QUnit.test("multi-line user note", function (assert) {
        text = "[** abcd\nefgh]";
        issArray = analyse(text, configuration).issues;
        noIssueTest(assert);
    });

    QUnit.test("user note missing ]", function (assert) {
        text = "[** abc";
        issArray = analyse(text, configuration).issues;
        issueTest(assert, 0, 0, 3, "noCloseBrack", 1);
    });

    QUnit.test("Footnote etc. missing ]", function (assert) {
        text = "*[Footnote: ab\ncd";
        issArray = analyse(text, configuration).issues;
        issueTest(assert, 0, 0, 10, "noCloseBrack", 0);
    });

    QUnit.test("Footnote id must be letter or number", function (assert) {
        text = "[Footnote *: ab\ncd]";
        issArray = analyse(text, configuration).issues;
        issueTest(assert, 0, 0, 9, "footnoteId", 0);
    });

    QUnit.test("Footnote anchor must not be *", function (assert) {
        text = "[*]";
        issArray = analyse(text, configuration).issues;
        issueTest(assert, 0, 0, 3, "starAnchor", 0);
    });

    QUnit.test("no Footnote corresponding to anchor", function (assert) {
        text = "[A][B]\n\n[Footnote A: abc]";
        issArray = analyse(text, configuration).issues;
        issueTest(assert, 0, 3, 3, "noFootnote", 0);
    });

    QUnit.test("no anchor corresponding to Footnote", function (assert) {
        text = "xyz\n\n[Footnote A: abc]";
        issArray = analyse(text, configuration).issues;
        issueTest(assert, 0, 5, 9, "noAnchor", 0);
    });

    QUnit.test("a colon must be present", function (assert) {
        text = "xyz\n\n[Footnote A abc]";
        issArray = analyse(text, configuration).issues;
        issueTest(assert, 0, 5, 9, "noColon", 0);
    });

    QUnit.test("colon must immediately follow continuation Footnote", function (assert) {
        text = "xyz\n\n*[Footnote : abc]";
        issArray = analyse(text, configuration).issues;
        issueTest(assert, 0, 6, 9, "colonNext", 0);
    });

    QUnit.test("space must immediately follow Footnote", function (assert) {
        text = "xyz\n\n[Footnote: abc]";
        issArray = analyse(text, configuration).issues;
        issueTest(assert, 0, 5, 9, "spaceNext", 0);
    });

    QUnit.test("duplicated footnote id", function (assert) {
        text = "xyz[A]\n\n[Footnote A: abc]\n\n[Footnote A: def]";
        issArray = analyse(text, configuration).issues;
        issueTest(assert, 0, 27, 9, "dupNote", 0);
    });

    QUnit.test("a continuation footnote should precede other footnotes", function (assert) {
        text = "xyz[A]\n\n[Footnote A: abc]\n\n*[Footnote: def]";
        issArray = analyse(text, configuration).issues;
        issueTest(assert, 0, 28, 9, "continueFirst", 0);
    });

    QUnit.test("pair of inline tags with no content", function (assert) {
        text = "<g></g>";
        issArray = analyse(text, configuration).issues;
        issueTest(assert, 0, 0, 3, "emptyTag", 1);
        issueTest(assert, 1, 3, 4, "emptyTag", 1);
    });

    QUnit.test("multiple footnote anchors", function (assert) {
        text = "abc[A]\ndef[A]\nghi[A]\njkl[B]\n\n[Footnote A: abc]\n\n[Footnote B: mno]";
        issArray = analyse(text, configuration).issues;
        issueTest(assert, 0, 3, 3, "multipleAnchors", 0);
        issueTest(assert, 1, 10, 3, "multipleAnchors", 0);
        issueTest(assert, 2, 17, 3, "multipleAnchors", 0);
    });

    QUnit.test("Overlapping markup - both possible issues", function (assert) {
        text = "ab <i>cd</i>e<b>fg</b> h";
        issArray = analyse(text, configuration).issues;
        issueTest(assert, 0, 12, 1, "charAfterEnd", 0);
    });

    QUnit.test("Overlapping markup - issue + possible issue", function (assert) {
        text = "ab\n\n\n\nc<i>de</i> fg";
        issArray = analyse(text, configuration).issues;
        issueTest(assert, 0, 6, 1, "blankLines124", 1);
    });

    QUnit.test("missing maths start tag in non-math mode", function (assert) {
        text = "e=mc^2\\]";
        issArray = analyse(text, configuration).issues;
        noIssueTest(assert);
    });

    QUnit.test("missing maths start tag", function (assert) {
        text = "e=mc^2\\]";
        issArray = analyse(text, mathConfig).issues;
        issueTest(assert, 0, 6, 2, "noStartTag", 1);
    });

    QUnit.test("mismatched maths tags", function (assert) {
        text = "\\(e=mc^2\\]";
        issArray = analyse(text, mathConfig).issues;
        issueTest(assert, 0, 0, 2, "misMatchTag", 1);
        issueTest(assert, 1, 8, 2, "misMatchTag", 1);
    });

    QUnit.test("missing maths end tag", function (assert) {
        text = "\\[e=mc^2\\(";
        issArray = analyse(text, mathConfig).issues;
        issueTest(assert, 0, 0, 2, "noEndTag", 1);
        issueTest(assert, 1, 8, 2, "noEndTag", 1);
    });

    QUnit.test("process outside math markup", function (assert) {
        let text = "abc\\[d\nef\\]ghi\\(jkl\\)mno";
        function toUpper(txt) {
            return txt.toUpperCase();
        }
        let procText = processExMath(text, toUpper, true);
        assert.strictEqual(procText, "ABC\\[d\nef\\]GHI\\(jkl\\)MNO");
        procText = processExMath(text, toUpper, false);
        assert.strictEqual(procText, "ABC\\[D\nEF\\]GHI\\(JKL\\)MNO");
    });

    QUnit.test("Find unmatched closing bracket", function (assert) {
        let text = "xy]za[b[c]d]ef]";
        assert.strictEqual(findClose(text, 3), 14);
    });

    QUnit.test("Check proofers note removed", function (assert) {
        let text = "xy]za[** b[c]d]ef";
        let procText = analyse(text, configuration).text;
        assert.strictEqual(procText, "xy]zaef");
    });

});
