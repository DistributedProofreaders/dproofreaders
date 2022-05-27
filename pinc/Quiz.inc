<?php
include_once($relPath."Stage.inc");
include_once($relPath."stages.inc");
include_once($relPath.'misc.inc'); // get_enumerated_param
include_once($relPath.'CharSuites.inc'); // CharSuiteSet

// for groups of quizzes that go together
class QuizLevel
{
    public static $map_quiz_level_id_to_QuizLevel = [];

    public function __construct($level_id, $level_name, $activity_type, $info, $quizzes)
    {
        $this->level_id = $level_id;
        $this->level_name = $level_name;         // name for the category of quizzes
        $this->activity_type = $activity_type;   // "proof" or "format"
        $this->info = $info;                     // description or other info the user should know
        $this->quizzes = $quizzes;               // the individual quizzes that the level contains

        foreach ($quizzes as $quiz) {
            $quiz->activity_type = $activity_type;
        }

        assert(!isset(QuizLevel::$map_quiz_level_id_to_QuizLevel[$level_id]));
        QuizLevel::$map_quiz_level_id_to_QuizLevel[$level_id] = & $this;
    }
}

class Quiz
{
    public static $valid_quiz_page_ids = [];
    public static $map_quiz_page_id_to_Quiz = [];
    public static $map_quiz_id_to_Quiz = [];

    public function __construct($id, $name, $short_name, $description, $thread, $pages, $pass_requirements)
    {
        $this->id = $id;
        $this->name = $name;               // appears in the top of the quiz's table
        $this->short_name = $short_name;   // appears in the righthand frame during the quiz
        $this->description = $description;
        $this->thread = $thread;           // forum thread for quiz questions & comments, if other than the default
        $this->pages = $pages;
        $this->pass_requirements = $pass_requirements;
        // Currently supported pass_requirements:
        // ['maximum_age'] => time_in_seconds:
        //     Passes recorded longer than time_in_seconds ago are not valid.

        assert(!isset(Quiz::$map_quiz_id_to_Quiz[$id]));
        Quiz::$map_quiz_id_to_Quiz[$id] = & $this;

        foreach (array_keys($pages) as $quiz_page_id) {
            assert(!in_array($quiz_page_id, self::$valid_quiz_page_ids));
            self::$valid_quiz_page_ids[] = $quiz_page_id;

            assert(!isset(self::$map_quiz_page_id_to_Quiz[$quiz_page_id]));
            self::$map_quiz_page_id_to_Quiz[$quiz_page_id] = & $this;
        }
    }

    // Get an array of quiz pages and the date the user passed them. The date
    // will be null if the user did not attempt or pass the page (because we
    // don't store non-passed attempts, we can't distinguish notpassed from
    // not attempted).
    private function get_user_page_passes($username, $use_pass_requirements = true)
    {
        foreach (array_keys($this->pages) as $quiz_page_id) {
            $pages_required_results[$quiz_page_id] = null;
        }

        $sql = sprintf("
            SELECT quiz_page, date
            FROM quiz_passes
            WHERE username = '%s'
                AND quiz_page IN (%s)
                AND result = 'pass'
            ORDER BY date
        ", DPDatabase::escape($username),
            surround_and_join(array_keys($this->pages), '"', '"', ","));
        $result = DPDatabase::query($sql);

        while ($attempt = mysqli_fetch_object($result)) {
            $pages_required_results[$attempt->quiz_page] = $attempt->date;

            if ($use_pass_requirements && isset($this->pass_requirements['maximum_age'])) {
                if ((time() - $attempt->date) > $this->pass_requirements['maximum_age']) {
                    $pages_required_results[$attempt->quiz_page] = null;
                }
            }
        }

        return $pages_required_results;
    }

    public function user_has_passed($username)
    {
        $pages_required_results = $this->get_user_page_passes($username);

        // If a user has passed the quiz, the $pages_required_results array should
        // have only pagename => timestamp rows. If any "null" values remain,
        // the user has not passed all the pages required, or the result of that
        // page is invalid (e.g. too old).
        if (array_search(null, $pages_required_results) !== false) {
            return false;
        } else {
            return true;
        }
    }

    public function show_results_table($username)
    {
        global $code_url;
        global $code_dir;

        if (!empty($username)) {
            // 3 extra columns of user-specific info
            $n_columns = 5;
        } else {
            $n_columns = 2;
        }

        // This assumes that the access minima quiz strings are names
        // 'quiz/X' where X is some quiz ID (eg 'f_only', 'p_mod2', 'p_greek')
        $stage_required_for = [];
        global $Stage_for_id_;
        foreach ($Stage_for_id_ as $stage_id => $stage) {
            if (array_key_exists("quiz/$this->id", $stage->access_minima)) {
                $stage_required_for[$stage_id] = $stage;
            }
        }

        echo "<h3 id='$this->id'>$this->name</h3>";

        // if this quiz is required for a stage that the user is now eligible
        // for, output a callout
        if (count($stage_required_for) && $username == User::current_username()) {
            foreach ($stage_required_for as $stage_id => $stage) {
                $uao = $stage->user_access($username);
                // if the user can't currently access it but they have satisfied
                // all of the requirements to do so, point them in the right direction
                if (!$uao->can_access && $uao->all_minima_satisfied) {
                    echo "<div class='callout'>";
                    echo "<p>";
                    echo sprintf(_("You've completed the quiz requirements to work in %s!"), $stage_id);

                    echo " ";

                    echo sprintf(_("Continue to the <a href='%1\$s'>%2\$s page</a>."), "$code_url/$stage->relative_url#Entrance_Requirements", $stage_id);
                    echo "</p>";
                    echo "</div>";
                    echo "<br>";
                }
            }
        }

        echo "<table class='themed theme_striped'>";

        if ($this->description != "" || count($stage_required_for) > 0) {
            echo "
                <tr><td colspan='$n_columns'>";
            if (count($stage_required_for) > 0) {
                echo "<p>" . _("Passing this quiz is required prior to working in:") . " ";
                $stage_links = [];
                foreach ($stage_required_for as $stage_id => $stage) {
                    $stage_links[] = "<a href='$code_url/$stage->relative_url'>$stage_id</a>";
                }
                echo implode(", ", $stage_links);
                echo "</p>";
            }
            echo "<p>$this->description</p>";
            echo "</td></tr>";
        }

        $pages_required_results = $this->get_user_page_passes($username, false);
        $page_number = 0;
        $pages = $this->pages;
        foreach ($pages as $quiz_page_id => $desc) {
            $page_number += 1;
            $page_label = sprintf(_('Page %d'), $page_number);
            echo "
                <tr><td><b>$page_label</b>: $desc</td>";
            echo "<td>";
            $tutfile = "$code_dir/quiz/tuts/tut_$quiz_page_id.php";
            if (file_exists($tutfile)) {
                echo "<a href='tuts/tut_$quiz_page_id.php'>" . _("Tutorial") . "</a> | ";
            }
            echo "<a href='generic/main.php?quiz_page_id=$quiz_page_id'>" . _("Quiz Page") . "</a></td>";
            if (!empty($username)) {
                $passed = $pages_required_results[$quiz_page_id] != null;
                $text = $passed ? _("Passed") : _("Not passed");
                $class = $passed ? 'quiz-passed' : 'quiz-not-passed';
                echo "<td class='$class'>$text</td>";

                $date = $pages_required_results[$quiz_page_id] ? $pages_required_results[$quiz_page_id] : 0;
                $text = ($date != 0) ? strftime("%d-%b-%y", $date) : _("Not attempted");
                $max = $this->pass_requirements['maximum_age'];
                $date_ok = ((time() - $date) < $max) || empty($max);
                $class = $date_ok ? 'quiz-date-ok' : 'quiz-date-not-ok';
                echo "<td class='$class'>$text</td>";

                if ($passed && $date_ok) {
                    $text = "<span class='x-large' title='" . attr_safe(_("Quiz page passed!")) . "'>✓</span>";
                    $class = 'quiz-ok';
                } else {
                    $text = "<span class='x-large' title='" . attr_safe(_("Quiz page not passed!")) . "'>✗</span>";
                    $class = 'quiz-not-ok';
                }
                echo "<td class='$class'>$text</td>";
            }
            echo "</tr>";
        }
        if (!empty($username)) {
            $total_pass = $this->user_has_passed($username);
            $text = $total_pass ? _("Quiz passed") : _("Quiz not passed");
            $class = $total_pass ? 'quiz-ok' : 'quiz-not-ok';
            echo "
                <tr><td colspan='$n_columns' class='$class center-align bold'>$text</td></tr>";
        }
        echo "</table>\n";
    }
}

class QuizCharSuites
{
    use CharSuiteSet;

    public function get_charsuites()
    {
        $charsuites[] = CharSuites::get('basic-latin');
        $charsuites[] = CharSuites::get('extended-european-latin-b');
        return $charsuites;
    }
}

// -----------------------------------------------------------------------------

function get_quiz_id_param($arr, $key)
// If $arr[$key] is defined and is a valid quiz id, return it.
// Otherwise, die with an error message.
{
    return get_enumerated_param($arr, $key, null, array_keys(Quiz::$map_quiz_id_to_Quiz));
}

function get_quiz_page_id_param($arr, $key)
// If $arr[$key] is defined and is a valid quiz page id, return it.
// Otherwise, die with an error message.
{
    return get_enumerated_param($arr, $key, null, Quiz::$valid_quiz_page_ids);
}

// -----------------------------------------------------------------------------

function get_Quiz_with_id($quiz_id)
{
    return Quiz::$map_quiz_id_to_Quiz[$quiz_id];
}

function get_Quiz_containing_page($quiz_page_id)
{
    return Quiz::$map_quiz_page_id_to_Quiz[$quiz_page_id];
}

// -----------------------------------------------------------------------------

function record_quiz_attempt($username, $quiz_page_id, $result)
{
    $sql = sprintf("
        SELECT *
        FROM quiz_passes
        WHERE username = '%s' AND quiz_page = '%s'
    ", DPDatabase::escape($username), DPDatabase::escape($quiz_page_id));
    $res = DPDatabase::query($sql);

    if (mysqli_num_rows($res) > 0) {
        // The user has already passed this page; update the timestamp
        $sql = sprintf("
            UPDATE quiz_passes
            SET date = %d
            WHERE username = '%s' AND quiz_page = '%s'
        ", time(),
            DPDatabase::escape($username),
            DPDatabase::escape($quiz_page_id)
        );
        DPDatabase::query($sql);
    } else {
        $sql = sprintf("
            INSERT INTO quiz_passes
            VALUES ('%s', %d, '%s', '%s')
        ", DPDatabase::escape($username),
            time(),
            DPDatabase::escape($quiz_page_id),
            DPDatabase::escape($result)
        );
        DPDatabase::query($sql);
    }
}
