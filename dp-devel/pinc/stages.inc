<?PHP

// Declarations for all stages.
// (Construction of all Stage objects.)

include_once($relPath.'site_vars.php');
include_once($relPath.'project_states.inc');
include_once($relPath.'Stage.inc');
include_once($relPath.'RoundDescriptor.inc');
include_once($relPath.'Pool.inc');

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

if ($site_supports_metadata)
{

new Stage(
    'CA',
    _('Clearance Approvals'),
    array(),
    'REQ-HUMAN',
    '',
    _('A qualified person must confirm that the work is in the public domain.'),
    NULL,
    array(
        'a',
        'b',
    ),
    "tools/site_admin/proj_approvals.php"
);

}

// -----------------------------------------------------------------------------

if ($site_supports_metadata)
{

new Stage(
    'MD',
    _('Metadata Collection'),
    array(),
    'REQ-HUMAN',
    '',
    _('We collect various bits of metadata for each page in a project.'),
    NULL,
    array(
        'a',
        'b',
    ),
    "tools/proofers/md_available.php"
);

}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

$pi_tools_for_P = array(
    'popup_links'  => array('search_and_replace','greek_transliterator','hieroglyph_transliterator'),
    'tool_buttons' => array('remove_markup','upper_case','title_case','lower_case'),
    'tool_links'   => array('greek','note','brackets','blank_page'),
);
$pi_tools_for_F = array(
    'popup_links'  => 'ALL',
    'tool_buttons' => 'ALL',
    'tool_links'   => 'ALL',
);

new Round(
    'P1',
    _('Proofreading Round 1'),
    array(),
    'IMMEDIATE',
    '',
    _("The page-texts are the output from OCR software and need to have the text carefully compared to the image."),
    'proofreading_guidelines.php',
    array(
        0 => '#FFE4B5', // mocassin
        1 => '#FFF8DC', // cornsilk
    ),
    $pi_tools_for_P,
    array(),
    array(
             0 => _('Novice'),
            25 => _('Proofreading Pupil'),
           100 => _('Proofreading Apprentice'),
           500 => _('Proofreading Scholar'),
          1000 => _('Proofreading Prodigy'),
          2500 => _('Prefect of Proofreaders'),
          5000 => _('Proofreading Graduate'),
         10000 => _('Proofreading Alumnus'),
         20000 => _('Fellow of Proofreading'),
         30000 => _('Doctor of Proofreading'),
         40000 => _('Proofreading Don'),
         50000 => _('Dean of Proofreading'),
         60000 => _('Proofreading Proctor'),
         70000 => _('Principal Proofreader'),
         80000 => _('Master Proofreader'),
         90000 => _('Prefect of Proofreaders'),
         99000 => _('Supervising Proofreader'),
        100000 => _('Proofreading Professor'),
        110000 => _('Peer of Proofreading'),
        120000 => _('Doyen of Proofreading'),
        130000 => _('Proofreading Chancellor'),
        140000 => _('Proofreading Primate'),
        150000 => _('Paramount Proofreader'),
    )
);

// -----------------------------------------------------------------------------

new Round(
    'P2',
    _('Proofreading Round 2'),
    array( 'R*+P1' => 300, 'days since reg' => 21, 'quiz/P' => 1 ),
    'REQ-AUTO',
    '',
    _("The page-texts have already been proofread, and now need to have the text spellchecked and carefully compared to the image."),
    'proofreading_guidelines.php',
    array(
        0 => '#FFE4B5', // mocassin
        1 => '#FFF8DC', // cornsilk
    ),
    $pi_tools_for_P,
    array( 'P1' ),
    array(
             0 => _('Precise Proofreader'),
            25 => _('Picky Proofreader'),
           100 => _('Painstaking Proofreader'),
           500 => _('Punctilious Proofreader'),
          1000 => _('Persnickety Proofreader'),
          2500 => _('Particular Proofreader'),
          5000 => _('Proficient Proofreader'),
         10000 => _('Proper Proofreader'),
         20000 => _('Prudent Proofreader'),
         30000 => _('Proofreading Personage'),
         40000 => _('Proofreading Poppet'),
         50000 => _('Plighted Proofreader'),
         60000 => _('Proofreading Proctor'),
         70000 => _('Principal Proofreader'),
         80000 => _('Prime Proofreader'),
         90000 => _('Primal Proofreader'),
         99000 => _('Proofreading Personality'),
        100000 => _('Proofreading Professional'),
        110000 => _('Peerless Proofreader'),
        120000 => _('Plighted Proofreader'),
        130000 => _('Paraproofreader'),
        140000 => _('Proofreading Panjandrum'),
        150000 => _('Perfectionist Proofreader'),
    )
);

// -----------------------------------------------------------------------------

new Round(
    'P3',
    _('Proofreading Round 3'),
    array( 'P1+P2' => 400, 'P2' => 50, 'F1' => 50, 'days since reg' => 42, 'quiz/P' => 1 ),
    'REQ-HUMAN',
    _("Once you have met the requirements and requested access to P3, an evaluator will check over at least 50 of your recent P2 pages and if they've been proofed to P3 standards, you'll be granted access."),
    _("The page-texts have already been proofread, but now need to be examined <b>closely</b> for small errors that may have been missed."),
    'proofreading_guidelines.php',
    array(
        0 => '#DDA0DD', // plum
        1 => '#D8BFD8', // thistle
    ),
    $pi_tools_for_P,
    array( 'P1', 'P2' ),
    array(
             0 => _('Specialist Proofreader'),
            25 => _('Precious Proofreader'),
           100 => _('Prized Proofreader'),
           500 => _('Premiere Proofreader'),
          1000 => _('Proofreading Perfectionist'),
          2500 => _('Pillar of Proofreading'),
          5000 => _('Proofreading Purist'),
         10000 => _('Proofreader of Precision'),
         20000 => _('Archetypal Proofreader'),
         30000 => _('Proofreading Nonpareil'),
         40000 => _('Paradigmatic Proofreader'),
         50000 => _('Preeminent Proofreader'),
         60000 => _('Prime Proofreader'),
         70000 => _('Proofreader of Plenariness'),
         80000 => _('Perpetual Proofreader'),
         90000 => _('Prefect of Proofreaders'),
         99000 => _('Impeccable Proofreader'),
        100000 => _('Proofreader of Persistence'),
        110000 => _('Patent Proofreader'),
        120000 => _('Proofreading Philosopher'),
        130000 => _('Patron of Proofreaders'),
        140000 => _('Proofreading Partner'),
        150000 => _('Pioneer of Proofreaders'),
    )
);

// -----------------------------------------------------------------------------

new Round(
    'F1',
    _('Formatting Round 1'),
    array( 'R*+P1' => 300, 'days since reg' => 21, 'quiz/F' => 1 ),
    'REQ-AUTO',
    '',
    _("The page-texts have already been proofread, but now need to be formatted with markup which may be specific to the project."),
    'document.php',
    array(
        0 => '#FFE4B5', // mocassin
        1 => '#FFF8DC', // cornsilk
    ),
    $pi_tools_for_F,
    array(),
    array(
             0 => _('Formatting Neophyte'),
            25 => _('Formatting Intern'),
           100 => _('Journeyman Formatter'),
           500 => _('Crafter of Texts'),
          1000 => _('Detailer of Books'),
          2500 => _('Fastidious Formatter'),
          5000 => _('Foremost Formatter'),
         10000 => _('Fine Formatter'),
         20000 => _('Flamboyant Formatter'),
         30000 => _('Fabulous Formatter'),
         40000 => _('Upgrader of Texts'),
         50000 => _('Famous Formatter'),
         60000 => _('Indefatigible Formatter'),
         70000 => _('Finisher of Texts'),
         80000 => _('Formatter of Choice'),
         90000 => _('Capital Formatter'),
         99000 => _('Formatter with Flair'),
        100000 => _('Formatter of Finesse'),
        110000 => _('Formatter with Forte'),
        120000 => _('First-Class Formatter'),
        130000 => _('Formatter of Favour'),
        140000 => _('Formatter of Refinement'),
        150000 => _('Flawless Formatter'),
    )
);

// -----------------------------------------------------------------------------

new Round(
    'F2',
    _('Formatting Round 2'),
    array( 'F1' => 400, 'days since reg' => 91 ), // 'F1' => 1000, 3 months after rollout
    'REQ-HUMAN', // "peer approval"
    _("Once you have met the above requirements and requested access to F2, an evaluator will check over at least 150 of your recent F1 pages and if they've been proofed to F2 standards, you'll be granted access."),
    _("The page-texts in this round need to be carefully checked to remove any remaining formatting or proofreading errors."),
    'document.php',
    array(
        0 => '#DDA0DD', // plum
        1 => '#D8BFD8', // thistle
    ),
    $pi_tools_for_F,
    array( 'F1' ),
    array(
             0 => _('Refurbisher of Texts'),
            25 => _('Sprucer of Texts'),
           100 => _('Formatter Savant'),
           500 => _('Formatting Wunderkind'),
          1000 => _('Elite Formatter'),
          2500 => _('Polisher of Texts'),
          5000 => _('Formatting Artiste'),
         10000 => _('Cultivator of Texts'),
         20000 => _('Formatter of Enrichment'),
         30000 => _('Designing Formatter'),
         40000 => _('Formatting Artisan'),
         50000 => _('Formatting Afficiando'),
         60000 => _('Guru of Formatters'),
         70000 => _('Formatting Familiar'),
         80000 => _('Formatting Virtuoso'),
         90000 => _('Formatter of Excellence'),
         99000 => _('Exquisite Formatter'),
        100000 => _('Elite Formatter'),
        110000 => _('Formatting Genius'),
        120000 => _('Formatter of Fine Feats'),
        130000 => _('Harmoniser of Texts'),
        140000 => _('Formatting Architect'),
        150000 => _('Preserver of Texts'),
    )
);

// ---------------------------

// After creating all rounds:

define('MAX_NUM_PAGE_EDITING_ROUNDS', 5);
assert( $n_rounds == MAX_NUM_PAGE_EDITING_ROUNDS );

declare_mentoring_pair( 'P1', 'P2' );

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

$someone_maintains_the_PP_faq =
    sprintf( _("%s (<a href='%s'>%s</a>) maintains our <a href='%s'>Post-Processing FAQ</a>."),
        'Julie Barkley',
        'http://www.pgdp.net/phpBB2/privmsg.php?mode=post&u=1674',
        'juliebarkley',
        "$code_url/faq/post_proof.php" );

new Pool(
    'PP',
    _('Post-Processing'),
    array( 'F1' => 400 ),
    'REQ-AUTO',
    '',
    _('After going through various rounds of proofreading and formatting, the books need to be massaged into a final e-text.'),
    'post_proof.php',
    array(
        '#cccccc',
        '#ffffff'
    ),

    PROJ_POST_FIRST_CHECKED_OUT,
    PROJ_POST_FIRST_AVAILABLE,

    _("Manager"),
    'username',

    array(
        "<p>",
        _("Each book listed below has gone through multiple rounds of proofreading and formatting, and now needs to be massaged into a final e-text."),
        _("Once you have checked out and downloaded a book it will remain checked out to you until you check it back in."),
        _("When you have finished your work on the book, select <i>Upload for Verification</i> from the drop-down list for that project."),
        _("If you have several files to submit for a single project (say a text and HTML version), zip them up together first."),
        "</p>",

        "<p>",
        "<b>" . _("First Time Here?") . "</b>",
        _("Please read the FAQ as it covers all the steps needed to post-process an e-text."),
        _("Select an easy work to get started on (usually fiction with a low page count is a good starter book; projects whose manager is BEGIN make excellent first projects for a new post-processor)."),
        sprintf( _("Check out the <a href='%s'>Post-Processing Forum</a> to post all your questions."), $post_processing_forum_url ),
        _("If nothing interests you right now, check back later and there will be more!"),
        "</p>",
    )
);

// -----------------------------------------------------------------------------

new Stage(
    'SR',
    _('Smooth Reading'),
    array(),
    'IMMEDIATE',
    '',
    _('Before a PPer has submitted a final e-text, they can optionally make it available for Smooth Reading. Anyone can volunteer to Smooth Read a text, which is basically just reading through it and marking possible errors before returning it to the Post-Processor.'),
    NULL,
    array(
        '#CCFFCC',
        '#CCFF99',
    ),
    "tools/post_proofers/smooth_reading.php"
);

// -----------------------------------------------------------------------------

new Pool(
    'PPV',
    _('Post-Processing Verification'),
    array(),
    'NOREQ', // "Peer approval. Also gives F2 access."
    '',
    _('Once a PPer has submitted a final e-text, it needs to be checked by a PPVer before it is posted to PG.'),
    'ppv.php',
    array(
        '#99FFFF', // "harshflourolightblue"
        '#EAF7F7', // "paledarkskyblue"
    ),

    PROJ_POST_SECOND_CHECKED_OUT,
    PROJ_POST_SECOND_AVAILABLE,

    _("Post-Processor"),
    'postproofer',

    array(
        "<p>",
        _("In this pool, experienced volunteers verify texts that have already been Post-Processed, and mentor new Post-Processors."),
        "<font color='red' size=4>",
        sprintf( _("Before working in this pool, make sure you read the <b>new</b> <a href='%s'>Post-Processing Verification Guidelines</a> and use the <a href='%s'>PPV Report Card</a> for each project you PPV."),
            "$code_url/faq/ppv.php",
            "$code_url/faq/ppv_report.txt" ),
        "</font>",
        "</p>",

        "<p>",
        sprintf( _("As always, the <a href='%s'>Post-Processing Forum</a> is available for any of your questions."),
            $post_processing_forum_url ),
        "</p>",
    )
);

// -----------------------------------------------------------------------------

if ( $site_supports_corrections_after_posting )
{

new Pool(
    'CR',
    _('Corrections Review'),
    array( 'F1' => 400 ),
    'REQ-AUTO',
    '',
    _('Readers can submit corrections. We need to review them and possibly revise the text.'), 
    NULL,
    array(
        '#e0e8dd',
        '#ffffff',
    ),

    PROJ_CORRECT_CHECKED_OUT,
    PROJ_CORRECT_AVAILABLE,

    _("Editor"),
    'correctedby',

    array(
        "<p>",
        _("The books listed below have already been posted to Project Gutenberg, but a reader has found errors and submitted a corrected text."),
        _("We need you to review the corrections to see if they're valid."),
        _("Once you have checked out and downloaded a book it will remain checked out to you until you check it back in."),
        _("When you have finished your work on the book, send the book to Project Gutenberg to be posted."),
        "</p>",
    )
);

}

// -----------------------------------------------------------------------------

// vim: sw=4 ts=4 expandtab
?>
