<?PHP

// Declarations for all stages.
// (Construction of all Stage objects.)

include_once($relPath.'v_site.inc');
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

new Round(
    'P1',
    _('Proofreading Round 1'),
    array(),
    'IMMEDIATE',
    _("The page-texts are the output from OCR software and have not been looked at."),
    'proofreading_guidelines.php',
    array(
        0 => '#FFE4B5', // mocassin
        1 => '#FFF8DC', // cornsilk
    ),
    array()
);

// -----------------------------------------------------------------------------

new Round(
    'P2',
    _('Proofreading Round 2'),
    array( 'P1' => 400, 'F1' => 50, 'days since reg' => 91 ), // 'P1' => 1000, 3 months after rollout
    'REQ-HUMAN',
        // "10 random P1 pages proofed within the last week will be reviewed.
        // If 2 or fewer proofing errors are found, approval will be given."
    _("The page-texts have already been proofread once, but now need to be examined <b>closely</b> for small errors that may have been missed."),
    'proofreading_guidelines.php',
    array(
        0 => '#DDA0DD', // plum
        1 => '#D8BFD8', // thistle
    ),
    array( 'P1' )
);

// -----------------------------------------------------------------------------

new Round(
    'F1',
    _('Formatting Round 1'),
    array( 'R*+P1' => 300, 'days since reg' => 21, 'quiz' => 1 ),
    'REQ-AUTO',
    _("The page-texts have already been proofread twice, but now need to be formatted with markup which may be specific to the project."),
    'document.php',
    array(
        0 => '#FFE4B5', // mocassin
        1 => '#FFF8DC', // cornsilk
    ),
    array()
);

// -----------------------------------------------------------------------------

new Round(
    'F2',
    _('Formatting Round 2'),
    array( 'F1' => 400, 'days since reg' => 91 ), // 'F1' => 1000, 3 months after rollout
    'REQ-HUMAN', // "peer approval"
    _("The page-texts in this round need to be carefully checked to remove any remaining formatting or proofreading errors."),
    'document.php',
    array(
        0 => '#DDA0DD', // plum
        1 => '#D8BFD8', // thistle
    ),
    array( 'F1' )
);

// ---------------------------

// After creating all rounds:

define('MAX_NUM_PAGE_EDITING_ROUNDS', 4);
assert( $n_rounds == MAX_NUM_PAGE_EDITING_ROUNDS );

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

$someone_maintains_the_PP_faq =
    sprintf( _("%s (<a href='%s'>%s</a>) maintains our <a href='%s'>Post Processing FAQ</a>."),
        'Julie Barkley',
        'http://www.pgdp.net/phpBB2/privmsg.php?mode=post&u=1674',
        'juliebarkley',
        "$code_url/faq/post_proof.php" );

new Pool(
    'PP',
    _('Post-Processing'),
    array( 'F1' => 400 ),
    'REQ-AUTO',
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
        _("The books listed below have already gone through two rounds of proofreading on this site and they now need to be massaged into a final e-text."),
        _("Once you have checked out and downloaded a book it will remain checked out to you until you check it back in."),
        _("When you have finished your work on the book, select <i>Upload for Verification</i> from the drop-down list for that project."),
        _("If you have several files to submit for a single project (say a text and HTML version), zip them up together first."),
        "</p>",

        "<p>",
        "<b>" . _("First Time Here?") . "</b>",
        $someone_maintains_the_PP_faq,
        _("Please read the FAQ as it covers all the steps needed to post process an e-text."),
        _("Select an easy work to get started on (usually fiction with a low page count is a good starter book; projects whose manager is BEGIN make excellent first projects for a new post processor)."),
        sprintf( _("Check out the <a href='%s'>Post Processing Forum</a> to post all your questions."), $post_processing_forum_url ),
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
    _('Before a PPer has submitted a final e-text, they can optionally make it available for Smooth Reading. Anyone can volunteer to Smooth Read a text, which is basically just reading through it and marking possible errors before returning it to the Post Processor.'),
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
    _('Once a PPer has submitted a final e-text, it needs to be checked by a PPVer before it is posted to PG.'),
    'ppv.php',
    array(
        '#99FFFF', // "harshflourolightblue"
        '#EAF7F7', // "paledarkskyblue"
    ),

    PROJ_POST_SECOND_CHECKED_OUT,
    PROJ_POST_SECOND_AVAILABLE,

    _("Post Processor"),
    'postproofer',

    array(
        "<p>",
        _("As an experienced volunteer, you have access to do verification of texts that have been Post Processed already, if you wish."),
        "<font color='red' size=4>",
        sprintf( _("Make sure you read the <b>new</b> <a href='%s'>Post Processing Verification Guidelines</a> and use the <a href='%s'>PPV Report Card</a> for each project you PPV."),
            "$code_url/faq/ppv.php",
            "$code_url/faq/ppv_report.txt" ),
        "</font>",
        "</p>",

        "<p>",
        $someone_maintains_the_PP_faq,
        sprintf( _("As always, the <a href='%s'>Post Processing Forum</a> is available for any of your questions."),
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
