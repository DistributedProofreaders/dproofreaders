<?php

// Declarations for all stages.
// (Construction of all Stage objects.)

include_once($relPath.'site_vars.php');
include_once($relPath.'project_states.inc');
include_once($relPath.'Stage.inc');
include_once($relPath.'RoundDescriptor.inc');
include_once($relPath.'Pool.inc');
include_once($relPath.'User.inc');
include_once($relPath.'forum_interface.inc'); // get_url_to_view_forum()
include_once($relPath.'misc.inc'); // str_contains startswith
include_once($relPath.'access_change_emails.inc'); // pp_access_change_email()

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

if ($site_supports_metadata)
{

new Stage(
    'CA',
    _('Clearance Approvals'),
    array(),
    'REQ-HUMAN',
    '',
    NULL, /* access_change_callback */
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
    NULL, /* access_change_callback */
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

// $pi_tools_for_P and $pi_tools_for_F are handy helper variables that define
// what proofreading tools are shown in the toolbox in the proofreading
// interface for the proofreading (P) and the formatting (F) rounds. These are
// passed into the Round() constructors below. The names refer to ToolButtons
// and PopupLink objects defined in pinc/ProofreadingToolbox.inc.

$pi_tools_for_P = array(
    'popup_links'  => array('search_and_replace','greek_transliterator','hieroglyph_transliterator'),
    'tool_buttons' => array('remove_markup','upper_case','title_case','lower_case'),
    'tool_links'   => array('greek','note','brackets','braces','blank_page'),
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
    NULL, /* access_change_callback */
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
    array( 'P1' => 300, 'days since reg' => 21, 'quiz/p_mod1' => 1 ),
    'REQ-AUTO',
    '',
    NULL, /* access_change_callback */
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
    array( 'P2' => 150, 'F1' => 50, 'days since reg' => 42, 'quiz/p_mod2' => 1 ),
    'REQ-HUMAN',
    _("Once you have met the requirements and requested access to P3, an evaluator will check over at least 50 of your recent P2 pages that have been completed in P3. If you proofread them to P3 standards, you'll be granted access."),
    NULL, /* access_change_callback */
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
    array( 'P1' => 300, 'days since reg' => 21, 'quiz/f_only' => 1 ),
    'REQ-AUTO',
    '',
    NULL, /* access_change_callback */
    _("The page-texts have already been proofread, but now need to be formatted with markup which may be specific to the project."),
    'formatting_guidelines.php',
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
         60000 => _('Indefatigable Formatter'),
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
    _("Once you have met the requirements and requested access to F2, an evaluator will check over at least 150 of your recent F1 pages that have been completed in F2. If they've been formatted to F2 standards, you'll be granted access."),
    NULL, /* access_change_callback */
    _("The page-texts in this round need to be carefully checked to remove any remaining formatting errors."),
    'formatting_guidelines.php',
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
         50000 => _('Formatting Aficionado'),
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

// ---------------------------

declare_mentoring_pair( 'P1', 'P2' );

// -----------------------------------------------------------------------------

// Some of the DP code assumes that the site has been configured with an
// "Entry-Level Round" (ELR): a single round where new users start out and
// become familiar with the site.  In particular, the page-tallies from this
// round are distinguished from others in that they are (deemed to be)
// a fair indicator of the user's experience.

// This particular assignment assumes that the ELR is the first round
// defined in this file. This is probably correct, but your site is
// welcome to use a different round as your ELR.
$ELR_round = get_Round_for_round_number(1);

// On the other hand, if your site doesn't have a distinguished entry-level
// round (e.g., everyone can work in multiple rounds from their first day),
// then you may have to do some hacking (sorry).

// -----------------------------------------------------------------------------

function can_user_get_pages_in_project( $username, $project, $round )
// If the given user is allowed to get a new page
// of the given project in the given round
// (via "Start Proofreading" or "Save and Do Another"),
// return NULL.
// Otherwise, return a string explaining why not.
//
// You can assume that this function will be called only if:
// -- the current user has general access to the round, and
// -- the project is available in the round,
// so this function doesn't have to check for either of those.
//
// It also doesn't need to check whether any of the project's pages
// are actually available.
{
    // Projects with difficulty='beginner' are treated differently in
    // various ways.
    if ( $project->difficulty == 'beginner' )
    {
        if ( $round->is_a_mentee_round() )
        {
            if ( !user_can_work_on_beginner_pages_in_round($round) )
            {
                return
                    _("You have reached your quota of pages from 'Beginners Only' projects in this round.")
                    . " "
                    . _("Perhaps you could try working on an EASY project.");
            }

            if (user_is_a_sitemanager() || user_is_proj_facilitator())
            {
                // User is exempt from N-pages-per-beginner-project restriction.
            }
            else
            {
                // When a beginner project is in a mentored round,
                // any given user can work on at most N pages
                // from that project in that round.

                // (For a while, N was 11 for all beginner projects.
                // Then we reduced it to 6 for English beginner projects.)
                $max_n_pages_per_project = ($project->language == 'English' ? 6 : 11);

                $result = mysqli_query(DPDatabase::get_connection(), "
                    SELECT COUNT(*) as pagesdone
                    FROM {$project->projectid}
                    WHERE {$round->user_column_name} = '$username'
                "); 
                $row = mysqli_fetch_assoc($result);
                if ($row["pagesdone"] >= $max_n_pages_per_project)
                {
                    return
                        _("You have reached your quota of pages from this 'Beginners Only' project, but you're welcome to work on a different one.")
                        . " "
                        . _("Or you could try working on an EASY project.");
                }
            }
        }
        else if ( $round->is_a_mentor_round() )
        {
            if ( !user_can_work_on_beginner_pages_in_round($round) )
            {
                return _("You do not have access to difficulty='beginner' (Mentors Only) projects in this round.");
            }
        }
        else
        {
            // In other rounds, difficulty='beginner' projects aren't handled specially.
        }
    }

    // In P1, English and French projects, for a while after their release,
    // are reserved for inexperienced users.
    $n_days_of_reserve = get_reserve_length($project, $round);
    if ($n_days_of_reserve > 0)
    {
        $page_tally_threshold = 500;
        $days_on_site_threshold = 21;

        // TRANSLATORS: This is a strftime-formatted string for the date with year and time
        $datetime_format = _("%A, %B %e, %Y at %X");

        $t_release = $project->modifieddate;
        $t_generally_available =
            $t_release + $n_days_of_reserve * 24 * 60 * 60;
        $t_now = time();

        // echo "t_release = $t_release<br>\n";
        // echo "t_generally_available = $t_generally_available<br>\n";
        // echo "t_now = $t_now<br>\n";
        if ( $t_now < $t_generally_available )
        {
            // The project is still in its "reserved" period.
            // It is available only to inexperienced users plus a few others.

            if ( $project->can_be_managed_by_user($username) )
            {
                // The current user is an SA, PF, or the project's PM,
                // and is not subject to this restriction.
            }
            else
            {
                // Is this user an inexperienced user?

                $user = new User($username);
                $days_on_site = ($t_now - $user->date_created) / 86400;
                // echo "days_on_site = $days_on_site<br>\n";

                $P1_page_tally = user_get_ELR_page_tally($username);
                // echo "P1_page_tally = $P1_page_tally<br>\n";

                if ( $days_on_site < $days_on_site_threshold or $P1_page_tally < $page_tally_threshold )
                {
                    // Yes, inexperienced.
                    // So not subject to this restriction
                    // (and so, allowed to proofread, unless there's some other problem).
                }
                else
                {
                    // No, experienced.
                    // So cannot proofread this project yet.
                    return
                        sprintf(
                            _('This project is currently reserved for proofreaders who joined less than %1$d days ago or have done less than %2$s P1 pages.'),
                            $days_on_site_threshold,
                            $page_tally_threshold
                        )
                        . "<br>" .
                        sprintf(
                            _('It will become generally available %s.'),
                            strftime( $datetime_format, $t_generally_available )
                        )
                    ;
                }
            }
        }
        else
        {
            // The project has finished its "reserved" period.
            // It is available to all (subject to other restrictions).
        }
    }

    // None of the above restrictions apply.
    return NULL;
}

function get_reserve_length($project, $round)
// Should $project be temporarily reserved for inexperienced users
// after becoming available in $round?
// If so, return the length of the reserve in days.
// If not, return 0.
{
    // We only reserve-for-newbies in P1.
    if ($round->id != 'P1') return 0;

    // Missing page(s) projects are exempted from being reserved
    // because we want them to catch up to their "parent" project
    // as quickly as possible.
    if (str_contains($project->nameofwork, '[missing page')) return 0;

    // *->P1 projects are exempted, because they were already reserved
    // for inexperienced users on their first pass through P1.
    if (str_contains($project->nameofwork, '->P1}')) return 0;

    if ($project->language == 'English' || $project->language == 'French')
    {
        // All 'special day' projects are exempted.
        if ($project->special_code != '') return 0;

        // Otherwise, reserve for 3 days.
        return 3;
    }

    // Anything not covered above
    // (i.e., language other than 'English' or 'French')
    // is not reserved.
    return 0;
}

// -------------------------------------------------------------------

function round_project_listing_presort( $round )
// On each round page, the order of the project list can be adjusted by
// clicking on column headers. If you want certain criteria to take precedence
// over such sorts, have this function return a string expressing those criteria
// as one or more ordering specifications (such as would appear in an ORDER BY
// clause in SQL), referring to columns of the projects table.
//
// For instance, to have beginner projects always appear at the top of
// every round's project list, this function could return
//     "(difficulty = 'beginner') DESC"
// or
//     "IF( difficulty = 'beginner', 1, 2 )"
// or
//     "CASE difficulty WHEN 'beginner' THEN 1 ELSE 2 END"
//
// If you don't want any criteria to take precedence over column-sorts,
// return NULL or the empty string.
{
    if(is_proofreading_round($round))
    {
        return "
            (difficulty = 'beginner') DESC,
            (nameofwork LIKE '%Newcomers Only%' OR nameofwork LIKE '%Rapid Review%') DESC
        ";
    }
    else if(is_formatting_round($round))
    {
        return "
            (
                nameofwork LIKE '%Fast Formatting Feedback%' OR
                nameofwork LIKE '%F1 Apprentice%' OR
                nameofwork LIKE '%F2 Qual%'
            ) DESC
        ";
    }
    else
    {
        assert( FALSE );
    }
}

function is_proofreading_round($round)
{
    return startswith($round->id, 'P');
}

function is_formatting_round($round)
{
    return startswith($round->id, 'F');
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

new Pool(
    'PP',
    _('Post-Processing'),
    array( 'F1' => 400 ),
    'REQ-AUTO',
    '',
    "pp_access_change_email", /* access_change_callback */
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
        "<b>" . _("First Time Here?") . "</b>",
        sprintf( _("Please read the <a href='%s'>Post-Processing FAQ</a> as it covers all the steps needed to post-process an e-text."),
        "$code_url/faq/post_proof.php"),
        _("Select an easy work to get started on (usually fiction with a low page count is a good starter book; projects whose manager is BEGIN make excellent first projects for a new post-processor)."),
        sprintf( _("Check out the <a href='%s'>Post-Processing Forum</a> to post all your questions."), get_url_to_view_forum($post_processing_forum_idx) ),
        _("If nothing interests you right now, check back later and there will be more!"),
        "</p>",

        "<p>",
        _("Each book listed below has gone through multiple rounds of proofreading and formatting, and now needs to be massaged into a final e-text."),
        _("Once you have checked out and downloaded a book it will remain checked out to you until you check it back in."),
        _("When you have finished your work on the book, select <i>Upload for Verification</i> from the drop-down list for that project."),
        _("If you have several files to submit for a single project (say a text and HTML version), zip them up together first."),
        "</p>",
    )
);

function echo_postcomments_instructions()
// If a project is checked out for PP,
// and the post-processor is viewing the project page,
// then the page will have an editable text area
// entitled "Post-Processor's Comments".
// This function is called between the title and the text area,
// and should give (site-specific) instructions to the post-processor
// on how to use (or not use) the postcomments field.
{
    global $wiki_url;

    echo '<p>' .
        _("You can use this text area to enter comments on how you're
        doing with the post-processing, both to keep track for yourself
        and so that we will know that there's still work in progress.")
        . '</p>';
    echo '<p>' . sprintf(
        // TRANSLATORS: PM = project manager
        _("If you find that some proofing images are missing or unreadable,
        or that illustration images are missing or inadequate,
        first let the PM know what you've found (or not found).
        If you don't hear back from the PM within a reasonable amount of time,
        let the <a href='%s'>db-req</a> squirrels know about the issues
        so we can get the project fixed."),
        "$wiki_url/Db-req")
        . '</p>';
    echo '<p>'
        . _("Note that your old comments will be replaced by those you enter here.")
        . '</p>';
}

// -----------------------------------------------------------------------------

new Stage(
    'SR',
    _('Smooth Reading'),
    array(),
    'IMMEDIATE',
    '',
    NULL, /* access_change_callback */
    _('Before a final e-text is posted to PG, it can be optionally uploaded for Smooth Reading. Anyone can volunteer to Smooth Read a text, which involves reading through the text for smoothness, marking possible errors and returning it to the PPer.'),
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
    NULL, /* access_change_callback */
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
        sprintf( _("<b>Before working in this pool</b>, please make sure you read the <a href='%s'>Post-Processing Verification Guidelines</a>."),
            "$code_url/faq/ppv.php"),
        sprintf( _("The PPV reporting form is <a href='%s'>here</a>."),
            "$code_url/tools/post_proofers/ppv_report.php"),
        "</p>",

        "<p>",
        sprintf( _("As always, the <a href='%s'>Post-Processing Forum</a> is available for any of your questions."),
            get_url_to_view_forum($post_processing_forum_idx) ),
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
    NULL, /* access_change_callback */
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

new Activity(
    'DU',
    _('Uploading to Project Gutenberg'),
    array(),
    'NOREQ',
    'OOGA',
    NULL /* access_change_callback */
);

new Activity(
    "P2_mentor",
    sprintf( _("Mentoring in round %s"), 'P2' ),
    array(),
    'NOREQ',
    '',
    NULL /* access_change_callback */
);

// vim: sw=4 ts=4 expandtab
?>
