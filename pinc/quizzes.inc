<?php
// This file creates quiz objects for each quiz with data in CVS
include_once($relPath."Quiz.inc");
include_once($relPath."../quiz/generic/quiz_defaults.inc"); // $old_texts_url etc

define('SIX_MONTHS_IN_SECONDS', 15778463);

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

new QuizLevel(
    "P_BASIC",
    _("Basic Proofreading Quizzes and Tutorials"),
    "proof",
    "<p>" . _("This section is designed as an overview of the most important and frequently used <a href='../faq/proofreading_guidelines.php'>proofreading guidelines</a>. The pages cover items that you are likely to see when proofreading BEGINNERS ONLY and EASY projects.") .
        "</p>\n<p>" .
        _("These tutorials and quizzes only address the standard guidelines. Some projects have special instructions, particularly for books in non-English languages or with unusual features, so be sure to read the Project Comments carefully on each project you proofread.") .
        "</p>\n<p>" .
        _("The \"extra practice\" pages do not cover any additional guidelines beyond the standard five pages; they are included to allow you to try the same skills on a new page.") .
        "</p>",
    [

        new Quiz(
            "p_basic",
            _("Basic Proofreading Quiz and Tutorial"),
            _("Basic Proofreading Quiz"),
            "",
            "",
            [
                'p_basic_1' => _('Page headers/footers, end-of-line hyphenation'),
                'p_basic_2' => _('Paragraph spacing, end-of-page hyphenation, dashes'),
                'p_basic_3' => _('Chapter headers, ellipses, bold text, small caps'),
                'p_basic_4' => _('Punctuation, illustrations, footnotes, italics'),
                'p_basic_5' => _('Poetry, font size changes'),
            ],
            [
                'maximum_age' => SIX_MONTHS_IN_SECONDS,
            ]
        ),

        new Quiz(
            "p_basicx",
            _("Basic Proofreading Quiz, Extra Practice"),
            _("Basic Proofreading Quiz"),
            "",
            "",
            [
                'p_basicx_1' => '',
                'p_basicx_2' => '',
            ],
            [
                'maximum_age' => SIX_MONTHS_IN_SECONDS,
            ]
        ),

    ]
);

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

new QuizLevel(
    "P_MOD",
    _("Moderate Proofreading Quizzes and Tutorials"),
    "proof",
    "<p>" . _("This section covers other aspects of the <a href='../faq/proofreading_guidelines.php'>proofreading guidelines</a>, beyond what is in the Basic section. The pages include items that you are likely to see when proofreading Average projects.") . "</p>",
    [

        new Quiz(
            "p_mod1",
            _("Moderate Proofreading Quiz and Tutorial, Part 1"),
            _("Moderate Proofreading Quiz"),
            "",
            "",
            [
                'p_mod1_1' => _('Superscripts'),
                'p_mod1_2' => _('End-of-line hyphenation, contractions, dashes'),
                'p_mod1_3' => _('Accented characters, fractions'),
                'p_mod1_4' => _('OCR problems (1-l-I, 0-O)'),
                'p_mod1_5' => _('Footnotes'),
            ],
            [
                'maximum_age' => SIX_MONTHS_IN_SECONDS,
            ]
        ),

        new Quiz(
            "p_mod2",
            _("Moderate Proofreading Quiz and Tutorial, Part 2"),
            _("Moderate Proofreading Quiz"),
            "",
            "",
            [
                'p_mod2_1' => _('Sidenotes, multiple columns'),
                'p_mod2_2' => _('Subscripts'),
                'p_mod2_3' => _('Non-ASCII characters'),
                'p_mod2_4' => _('Quote marks on each line'),
                'p_mod2_5' => _('Plays'),
            ],
            [
                'maximum_age' => SIX_MONTHS_IN_SECONDS,
            ]
        ),

    ]
);

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

new QuizLevel(
    "P_SPECIAL",
    _("Specialized Proofreading Quizzes and Tutorials"),
    "proof",
    "<p>" . _("This section covers topics that are not addressed in the <a href='../faq/proofreading_guidelines.php'>proofreading guidelines</a>. The pages include items that you are likely to see when proofreading HARD (and some Average) projects.") .
        "</p>\n<p>" .
        _("The following quizzes are not required, but can be useful. If you are not yet familiar with a topic, you can learn about it by using the tutorial mode or following the \"Information\" link for that topic.") .
        "</p>",
    [

        new Quiz(
            "p_old",
            _("Old Texts Quiz and Tutorial"),
            _("Old Texts Quiz"),
            "<a href='$old_texts_url'>" . _("Information") . "</a>",
            "",
            [
                'p_old_1' => _('Long s, decorative ligatures, catchwords'),
                'p_old_2' => _('Usage of u/v and i/j, ampersands'),
                'p_old_3' => _('Nasal abbreviations, blackletter'),
            ],
            [
                'maximum_age' => SIX_MONTHS_IN_SECONDS,
            ]
        ),

        new Quiz(
            "p_greek",
            _("Greek Transliteration Quiz and Tutorial"),
            _("Greek Transliteration Quiz"),
            "<p><a href='$Greek_translit_url'>" . _("Information") . "</a><br>" .
                _("Note: Since quiz messages are generated automatically, in some situations the feedback may not correspond to what you have done. This is particularly true for Greek transliteration, so if the message doesn't make sense or isn't helpful please use the forum link provided in the error message to get feedback.") . "</p>",
            "", // **fixme** insert Greek quiz feedback thread url, once it exists
            [
                'p_greek_1' => _('Basic transliteration'),
                'p_greek_2' => _('Upsilon transliteration, diacritical marks'),
                'p_greek_3' => _('Gamma transliteration, diacritical marks'),
                'p_greek_4' => _('Greek punctuation'),
                'p_greek_5' => _('Double letters, variant forms'),
            ],
            [
                'maximum_age' => SIX_MONTHS_IN_SECONDS,
            ]
        ),

        new Quiz(
            "p_other",
            _("Other Specialized Quizzes and Tutorials"),
            _("Other Specialized Topics"),
            "",
            "",
            [
                'p_aeoe_1' => sprintf(_('Ligatures, page %1$d (<a href="%2$s">Information</a>)'), 1, $ae_oe_ligatures_url),
                'p_aeoe_2' => sprintf(_('Ligatures, page %1$d (<a href="%2$s">Information</a>)'), 2, $ae_oe_ligatures_url),
                'p_thorn' => sprintf(_('Thorn (<a href="%s">Information</a>)'), $thorn_url),
                'p_fraktur' => sprintf(_('Fraktur (<a href="%s">Information</a>)'), $fraktur_url),
            ],
            [
                'maximum_age' => SIX_MONTHS_IN_SECONDS,
            ]
        ),

    ]
);

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

new QuizLevel(
    "F_ONLY",
    _("Formatting Quizzes"),
    "format",
    "<p>" . _("The formatting quizzes cover topics addressed in the <a href='../faq/formatting_guidelines.php'>formatting guidelines</a>.") . "</p>",
    [

        new Quiz(
            "f_only",
            _("Formatting Quiz"),
            _("Formatting Quiz"),
            _("This quiz covers the corrections you should make in the formatting rounds&mdash;<b>not</b> the proofreading rounds. There's no need to do this quiz until you are ready to work in F1, and in fact it may confuse you if you try it earlier."),
            "$forums_url/viewtopic.php?t=47863",
            [
                'formatting1' => _('Italics, bold, and small caps'),
                'formatting2' => _('Section headings, hyphens and em-dashes'),
                'formatting3' => _('Chapter headings and thought breaks'),
                'formatting4' => _('Poetry, blockquotes and lists'),
                'formatting5' => _('Illustrations and footnotes'),
            ],
            [
                'maximum_age' => SIX_MONTHS_IN_SECONDS,
            ]
        ),

    ]
);

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
