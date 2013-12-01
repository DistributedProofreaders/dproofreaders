<?php
// This file creates quiz objects for each quiz with data in CVS
include_once($relPath."Quiz.inc");
include_once($relPath."../quiz/generic/quiz_defaults.inc"); // $old_texts_url etc


// quiz levels:

new QuizLevel(
    "BPQL",
    _("Basic Proofreading Quizzes and Tutorials"),
    "proof",
    "<p>" . _("This section is designed as an overview of the most important and frequently used <a href='../faq/proofreading_guidelines.php'>proofreading guidelines</a>. The pages cover items that you are likely to see when proofreading BEGINNERS ONLY and EASY projects.") .
        "</p>\n<p>" .
        _("The \"extra practice\" pages do not cover any additional guidelines beyond the standard five pages; they are included to allow you to try the same skills on a new page.") .
        "</p>",
    array('BPQ1','BPQ2')
);

new QuizLevel(
    "MPQL",
    _("Moderate Proofreading Quizzes and Tutorials"),
    "proof",
    "<p>" . _("This section covers other aspects of the <a href='../faq/proofreading_guidelines.php'>proofreading guidelines</a>, beyond what is in the Basic section. The pages include items that you are likely to see when proofreading Average projects.") . "</p>",
    array('MPQ1','MPQ2')
);

new QuizLevel(
    "SPQL",
    _("Specialized Proofreading Quizzes and Tutorials"),
    "proof",
    "<p>" . _("This section covers topics that are not addressed in the <a href='../faq/proofreading_guidelines.php'>proofreading guidelines</a>. The pages include items that you are likely to see when proofreading HARD (and some Average) projects.") .
        "</p>\n<p>" .
        _("If you are not yet familiar with a topic, you can learn about it by using the tutorial mode or following the \"Information\" link for that topic.") .
        "</p>",
    array('OPQ','GPQ','OSPQ')
);

new QuizLevel(
    "FQL",
    _("Formatting Quizzes"),
    "format",
    "<p>" . _("The formatting quizzes cover topics addressed in the <a href='../faq/document.php'>formatting guidelines</a>.") . "</p>",
    array('FQ')
);


// individual quizzes:

new Quiz(
    "FQ",
    _("Formatting Quiz"),
    _("Formatting Quiz"),
    _("This quiz covers the corrections you should make in the formatting rounds&mdash;<b>not</b> the proofreading rounds. There's no need to do this quiz until you are ready to work in F1, and in fact it may confuse you if you try it earlier."),
    "", // **fixme** Add the url for the new formatting quiz feedback thread, once it exists
    array(
        _('Page one: Italics, bold, and small caps') => 'formatting1',
        _('Page two: Section headings, hyphens and em-dashes') => 'formatting2',
        _('Page three: Chapter headings and thought breaks') => 'formatting3',
        _('Page four: Poetry, blockquotes and lists') => 'formatting4',
        _('Page five: Illustrations and footnotes') => 'formatting5'),
    array(
        'maximum_age' => 15778463) // 6 months
);


new Quiz(
    "BPQ1",
    _("Basic Proofreading Quiz and Tutorial"),
    _("Basic Proofreading Quiz"),
    "",
    "",
    array(
        _('<b>Page 1</b>: Page headers/footers, end-of-line hyphenation') => 'p_basic_1',
        _('<b>Page 2</b>: Paragraph spacing, end-of-page hyphenation, dashes') => 'p_basic_2',
        _('<b>Page 3</b>: Chapter headers, ellipses, bold text, small caps') => 'p_basic_3',
        _('<b>Page 4</b>: Punctuation, illustrations, footnotes, italics') => 'p_basic_4',
        _('<b>Page 5</b>: Poetry, font size changes') => 'p_basic_5'),
    array(
        'maximum_age' => 15778463) // 6 months
);


new Quiz(
    "BPQ2",
    _("Basic Proofreading Quiz, Extra Practice"),
    _("Basic Proofreading Quiz"),
    "",
    "",
    array(
        _('<b>Page 6</b>') => 'p_basicx_1',
        _('<b>Page 7</b>') => 'p_basicx_2'),
    array(
        'maximum_age' => 15778463) // 6 months
);


new Quiz(
    "MPQ1",
    _("Moderate Proofreading Quiz and Tutorial, Part 1"),
    _("Moderate Proofreading Quiz"),
    "",
    "",
    array(
        _('<b>Page 1</b>: Superscripts') => 'p_mod1_1',
        _('<b>Page 2</b>: End-of-line hyphenation, contractions, dashes') => 'p_mod1_2',
        _('<b>Page 3</b>: Accented characters, fractions') => 'p_mod1_3',
        _('<b>Page 4</b>: OCR problems (1-l-I, 0-O)') => 'p_mod1_4',
        _('<b>Page 5</b>: Footnotes') => 'p_mod1_5'),
    array(
        'maximum_age' => 15778463) // 6 months
);


new Quiz(
    "MPQ2",
    _("Moderate Proofreading Quiz and Tutorial, Part 2"),
    _("Moderate Proofreading Quiz"),
    "",
    "",
    array(
        _('<b>Page 6</b>: Sidenotes, multiple columns') => 'p_mod2_1',
        _('<b>Page 7</b>: Subscripts') => 'p_mod2_2',
        _('<b>Page 8</b>: Non-ASCII characters') => 'p_mod2_3',
        _('<b>Page 9</b>: Quote marks on each line') => 'p_mod2_4',
        _('<b>Page 10</b>: Plays') => 'p_mod2_5'),
    array(
        'maximum_age' => 15778463) // 6 months
);


new Quiz(
    "OPQ",
    _("Old Texts Quiz and Tutorial"),
    _("Old Texts Quiz"),
    "<a href='$old_texts_url'>" . _("Information") . "</a>",
    "",
    array(
        _('<b>Page 1</b>: Long s, decorative ligatures, catchwords') => 'p_old_1',
        _('<b>Page 2</b>: Usage of u/v and i/j, ampersands') => 'p_old_2',
        _('<b>Page 3</b>: Nasal abbreviations, blackletter') => 'p_old_3',),
    array(
        'maximum_age' => 15778463) // 6 months
);


new Quiz(
    "GPQ",
    _("Greek Transliteration Quiz and Tutorial"),
    _("Greek Transliteration Quiz"),
    "<p><a href='$Greek_translit_url'>" . _("Information") . "</a><br>" .
        _("Note: Since quiz messages are generated automatically, in some situations the feedback may not correspond to what you have done. This is particularly true for Greek transliteration, so if the message doesn't make sense or isn't helpful please use the forum link provided in the error message to get feedback.") . "</p>",
    "", // **fixme** insert Greek quiz feedback thread url, once it exists
    array(
        _('<b>Page 1</b>: Basic transliteration') => 'p_greek_1',
        _('<b>Page 2</b>: Upsilon transliteration, diacritical marks') => 'p_greek_2',
        _('<b>Page 3</b>: Gamma transliteration, diacritical marks') => 'p_greek_3',
        _('<b>Page 4</b>: Greek punctuation') => 'p_greek_4',
        _('<b>Page 5</b>: Double letters, variant forms') => 'p_greek_5'),
    array(
        'maximum_age' => 15778463) // 6 months
);


new Quiz(
    "OSPQ",
    _("Other Specialized Quizzes and Tutorials"),
    _("Other Specialized Topics"),
    "",
    "",
    array(
        sprintf(_('Ligatures, page 1 (<a href="%s">Information</a>)'),$ae_oe_ligatures_url) => 'p_aeoe_1',
        sprintf(_('Ligatures, page 2 (<a href="%s">Information</a>)'),$ae_oe_ligatures_url) => 'p_aeoe_2',
        sprintf(_('Thorn (<a href="%s">Information</a>)'),$thorn_url) => 'p_thorn',
        sprintf(_('Fraktur (<a href="%s">Information</a>)'),$fraktur_url) => 'p_fraktur',),
    array(
        'maximum_age' => 15778463) // 6 months
);

// vim: sw=4 ts=4 expandtab
