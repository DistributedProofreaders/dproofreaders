<?PHP

// Declarations for all stages.
// (Construction of all Stage objects.)

include_once($relPath.'v_site.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'Stage.inc');
include_once($relPath.'RoundDescriptor.inc');
include_once($relPath.'Pool.inc');

// -----------------------------------------------------------------------------

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
    _('After going through various rounds of proofreading and formatting, the books need to be massaged into a final e-text.'),
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

new Pool(
    'PPV',
    _('Post-Processing Verification'),
    NULL,
    _('Once a PPer has submitted a final e-text, it needs to be checked by a PPVer before it is posted to PG.'),
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

new Pool(
    'CR',
    _('Corrections Review'),
    array( 'F1' => 400 ),
    _('Readers can submit corrections. We need to review them and possibly revise the text.'), 
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

// -----------------------------------------------------------------------------

// vim: sw=4 ts=4 expandtab
?>
