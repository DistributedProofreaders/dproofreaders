<?php

// Declarations for all stages.
// (Construction of all Stage objects.)

include_once($relPath.'site_vars.php');
include_once($relPath.'project_states.inc');
include_once($relPath.'Stage.inc');
include_once($relPath.'Round.inc');
include_once($relPath.'Pool.inc');
include_once($relPath.'User.inc');
include_once($relPath.'forum_interface.inc'); // get_url_to_view_forum()

class Acccessor
{
    public function add($object)
    {
        $this->objects[$object->id] = $object;
    }

    public function get_all()
    {
        return $this->objects;
    }
}

construct_activities();

function construct_activities()
{
    global $rounds, $stages, $activities, $pools, $project_states, $ACCESS_CRITERIA, $code_url, $post_processing_forum_idx, $ELR_round;

    // this is used in noncvs
    global $n_rounds;

    $pi_tools_for_P = [
        'popup_links' => ['search_and_replace', 'greek_transliterator', 'hieroglyph_transliterator'],
        'tool_buttons' => ['remove_markup', 'upper_case', 'title_case', 'lower_case'],
        'tool_links' => ['greek', 'note', 'brackets', 'braces', 'blank_page'],
    ];
    $pi_tools_for_F = [
        'popup_links' => 'ALL',
        'tool_buttons' => 'ALL',
        'tool_links' => 'ALL',
    ];

    $activities = new Acccessor();

    $n_rounds = 0;
    $activities->add(new Round(
        ++$n_rounds,
        'P1',
        _('Proofreading Round 1'),
        [],
        'IMMEDIATE',
        '',
        null, // access_change_callback
        _("The page-texts are the output from OCR software and need to have the text carefully compared to the image."),
        'proofreading_guidelines.php',
        $pi_tools_for_P,
        null, // daily_page_limit
        [],
        [
            0 => _('Novice'),
            25 => _('Proofreading Pupil'),
            100 => _('Proofreading Apprentice'),
            500 => _('Proofreading Scholar'),
            1000 => _('Proofreading Prodigy'),
            2500 => _('Proofreading Mastermind'),
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
        ]
    ));

    // -----------------------------------------------------------------------------

    $activities->add(new Round(
        ++$n_rounds,
        'P2',
        _('Proofreading Round 2'),
        ['P1' => 300, 'days since reg' => 21, 'quiz/p_basic' => 1, 'quiz/p_mod1' => 1],
        'REQ-AUTO',
        '',
        null, // access_change_callback
        _("The page-texts have already been proofread, and now need to have the text WordChecked and carefully compared to the image."),
        'proofreading_guidelines.php',
        $pi_tools_for_P,
        null, // daily_page_limit
        ['P1'],
        [
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
            120000 => _('Perspicacious Proofreader'),
            130000 => _('Paraproofreader'),
            140000 => _('Proofreading Panjandrum'),
            150000 => _('Perfectionist Proofreader'),
        ]
    ));

    // -----------------------------------------------------------------------------

    $activities->add(new Round(
        ++$n_rounds,
        'P3',
        _('Proofreading Round 3'),
        ['P2' => 150, 'F1' => 50, 'days since reg' => 42, 'quiz/p_basic' => 1, 'quiz/p_mod1' => 1, 'quiz/p_mod2' => 1],
        'REQ-HUMAN',
        _("Once you have met the requirements and requested access to P3, an evaluator will check over at least 50 of your recent P2 pages that have been completed in P3. If you proofread them to P3 standards, you'll be granted access."),
        null, // access_change_callback
        _("The page-texts have already been proofread, but now need to be examined <b>closely</b> for small errors that may have been missed."),
        'proofreading_guidelines.php',
        $pi_tools_for_P,
        null, // daily_page_limit
        ['P1', 'P2'],
        [
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
        ]
    ));

    // -----------------------------------------------------------------------------

    $activities->add(new Round(
        ++$n_rounds,
        'F1',
        _('Formatting Round 1'),
        ['P1' => 300, 'days since reg' => 21, 'quiz/f_only' => 1],
        'REQ-AUTO',
        '',
        null, // access_change_callback
        _("The page-texts have already been proofread, but now need to be formatted with markup which may be specific to the project."),
        'formatting_guidelines.php',
        $pi_tools_for_F,
        null, // daily_page_limit
        [],
        [
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
        ]
    ));

    // -----------------------------------------------------------------------------

    $activities->add(new Round(
        ++$n_rounds,
        'F2',
        _('Formatting Round 2'),
        ['F1' => 400, 'days since reg' => 91], // 'F1' => 1000, 3 months after rollout
        'REQ-HUMAN', // "peer approval"
        _("Once you have met the requirements and requested access to F2, an evaluator will check over at least 150 of your recent F1 pages that have been completed in F2. If they've been formatted to F2 standards, you'll be granted access."),
        null, // access_change_callback
        _("The page-texts in this round need to be carefully checked to remove any remaining formatting errors."),
        'formatting_guidelines.php',
        $pi_tools_for_F,
        null, // daily_page_limit
        ['F1'],
        [
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
            100000 => _('Formatting Specialist'),
            110000 => _('Formatting Genius'),
            120000 => _('Formatter of Fine Feats'),
            130000 => _('Harmoniser of Texts'),
            140000 => _('Formatting Architect'),
            150000 => _('Preserver of Texts'),
        ]
    ));

    // After creating all rounds:
    define('MAX_NUM_PAGE_EDITING_ROUNDS', 5);
    assert($n_rounds == MAX_NUM_PAGE_EDITING_ROUNDS);

    $activities->add(new Pool(
        'PP',
        _('Post-Processing'),
        ['F1' => 400],
        'REQ-AUTO',
        '',
        null, // access_change_callback
        _('After going through various rounds of proofreading and formatting, the books need to be massaged into a final e-text.'),
        'post_proof.php',
        _("Manager"),
        'username',
        [
            "<p>",
            "<b>" . _("First Time Here?") . "</b>",
            sprintf(
                _("Please read the <a href='%s'>Post-Processing FAQ</a> as it covers all the steps needed to post-process an e-text."),
                "$code_url/faq/post_proof.php"
            ),
            _("Select an easy work to get started on (usually fiction with a low page count is a good starter book; projects whose manager is BEGIN make excellent first projects for a new post-processor)."),
            sprintf(_("Check out the <a href='%s'>Post-Processing Forum</a> to post all your questions."), get_url_to_view_forum($post_processing_forum_idx)),
            _("If nothing interests you right now, check back later and there will be more!"),
            "</p>",

            "<p>",
            _("Each book listed below has gone through multiple rounds of proofreading and formatting, and now needs to be massaged into a final e-text."),
            _("Once you have checked out and downloaded a book it will remain checked out to you until you check it back in."),
            _("When you have finished your work on the book, select <i>Upload for Verification</i> from the drop-down list for that project."),
            _("If you have several files to submit for a single project (say a text and HTML version), zip them up together first."),
            "</p>",
        ]
    ));

    $activities->add(new Pool(
        'PPV',
        _('Post-Processing Verification'),
        [],
        'NOREQ', // "Peer approval. Also gives F2 access."
        '',
        null, // access_change_callback
        _('Once a PPer has submitted a final e-text, it needs to be checked by a PPVer before it is posted to PG.'),
        'ppv.php',
        _("Post-Processor"),
        'postproofer',
        [
            "<p>",
            _("In this pool, experienced volunteers verify texts that have already been Post-Processed, and mentor new Post-Processors."),
            sprintf(
                _("<b>Before working in this pool</b>, please make sure you read the <a href='%s'>Post-Processing Verification Guidelines</a>."),
                "$code_url/faq/ppv.php"
            ),
            sprintf(
                _("The PPV reporting form is <a href='%s'>here</a>."),
                "$code_url/tools/post_proofers/ppv_report.php"
            ),
            "</p>",

            "<p>",
            sprintf(
                _("As always, the <a href='%s'>Post-Processing Forum</a> is available for any of your questions."),
                get_url_to_view_forum($post_processing_forum_idx)
            ),
            "</p>",
        ]
    ));

    $activities->add(new Stage(
        'SR',
        _('Smooth Reading'),
        [],
        'IMMEDIATE',
        '',
        null, // access_change_callback
        _('Before a final e-text is posted to PG, it can be optionally uploaded for Smooth Reading. Anyone can volunteer to Smooth Read a text, which involves reading through the text for smoothness, marking possible errors and returning it to the PPer.'),
        null,
        "tools/post_proofers/smooth_reading.php"
    ));

    $activities->add(new Activity(
        'DU',
        _('Uploading to Project Gutenberg'),
        [],
        'NOREQ',
        'OOGA',
        null // access_change_callback
    ));

    $activities->add(new Activity(
        "P2_mentor",
        sprintf(_("Mentoring in round %s"), 'P2'),
        [],
        'NOREQ',
        '',
        null // access_change_callback
    ));

    // now construct various access tables
    $stages = new Acccessor();
    $rounds = new Acccessor();
    $pools = new Acccessor();
    foreach($activities->get_all() as $activity) {
        foreach($activity->get_access_criteria() as $key => $value) {
            $ACCESS_CRITERIA[$key] = $value;
        }
        if($activity instanceof Pool) {
            $pools->add($activity);
        }
        if($activity instanceof Stage) {
            $stages->add($activity);
        }
        if($activity instanceof Round) {
            $round = $activity;
            $rounds->add($round);
            $rounds->round_for_number[$round->round_number] = $round;
            foreach($round->get_page_states() as $page_state) {
                // e.g. $activities->round_for_page_state["P1.page_avail"] = $P1;
                $rounds->round_for_page_state[$page_state] = $round;
                // e.g. $activities->page_states[0] = "P1.page_avail"
                $rounds->page_states[] = $page_state;
            }
        }
    }

    $project_states = new ProjectStates($rounds->get_all(), $pools->get_all());

    // XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

    declare_mentoring_pair('P1', 'P2');

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
}

// these can next be made member functions
// used as $rounds->get_Round_for_page_state($state) etc.
function get_Round_for_page_state($page_state)
{
    global $rounds;
    return $rounds->round_for_page_state[$page_state] ?? null;
}

function get_Round_for_round_number($round_number)
{
    global $rounds;
    return $rounds->round_for_number[$round_number] ?? null;
}

function get_Round_for_round_id($round_id)
{
    global $rounds;
    return $rounds->objects[$round_id] ?? null;
}

function get_Round_for_text_column_name($text_column_name)
{
    global $rounds;
    foreach ($rounds->objects as $round_id => $round) {
        if ($round->text_column_name == $text_column_name) {
            return $round;
        }
    }
    return null;
}

function get_Stage_for_id($id)
{
    global $stages;
    $stage = $stages->objects[$id] ?? null;
    if (null !== $stage) {
        return $stage;
    } else {
        die("There is no stage with id='$id'.");
    }
}

function get_Pool_for_id($id)
{
    global $pools;
    return $pools->objects[$id] ?? null;
}

// aliases for compatibility with existing code
global $PAGE_STATES_IN_ORDER, $Round_for_round_id_, $Round_for_round_number_, $Round_for_page_state_,
$Activity_for_id_, $Pool_for_id_, $Stage_for_id_;

global $rounds, $stages, $activities, $pools, $project_states;

$PAGE_STATES_IN_ORDER = $rounds->page_states;
$Round_for_round_id_ = $rounds->objects;
$Round_for_round_number_ = $rounds->round_for_number;
$Round_for_page_state_ = $rounds->round_for_page_state;
$Activity_for_id_ = $activities->objects;
$Stage_for_id_ = $stages->objects;
$Pool_for_id_ = $pools->objects;

global $PROJECT_STATES_IN_ORDER, $project_state_phase_, $project_states;

$PROJECT_STATES_IN_ORDER = $project_states->states_in_order;
$project_state_phase_ = $project_states->project_state_phase;
