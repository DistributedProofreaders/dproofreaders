<?php
include_once($relPath."Stage.inc");
include_once($relPath."stages.inc");
include_once($relPath.'misc.inc'); // get_enumerated_param
include_once($relPath.'CharSuites.inc'); // CharSuiteSet

// for groups of quizzes that go together
class QuizLevel
{
    function __construct($level_id, $level_name, $activity_type, $info, $quizzes)
    {
        $this->level_id = $level_id;
        $this->level_name = $level_name;         // name for the category of quizzes
        $this->activity_type = $activity_type;   // "proof" or "format"
        $this->info = $info;                     // description or other info the user should know
        $this->quizzes = $quizzes;               // the individual quizzes that the level contains

        foreach ($quizzes as $quiz)
        {
            $quiz->activity_type = $activity_type;
        }

        global $map_quiz_level_id_to_QuizLevel;
        assert(!isset($map_quiz_level_id_to_QuizLevel[$level_id]));
        $map_quiz_level_id_to_QuizLevel[$level_id] =& $this;
    }
}

class Quiz
{
    static $valid_quiz_page_ids = array();
    static $map_quiz_page_id_to_Quiz = array();

    function __construct($id, $name, $short_name, $description, $thread, $pages, $pass_requirements)
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

        global $map_quiz_id_to_Quiz;
        assert(!isset($map_quiz_id_to_Quiz[$id]));
        $map_quiz_id_to_Quiz[$id] =& $this;

        foreach (array_keys($pages) as $quiz_page_id)
        {
           assert(!in_array($quiz_page_id, self::$valid_quiz_page_ids));
           self::$valid_quiz_page_ids[] = $quiz_page_id;

           assert(!isset(self::$map_quiz_page_id_to_Quiz[$quiz_page_id]));
           self::$map_quiz_page_id_to_Quiz[$quiz_page_id] =& $this;
        }
    }

    function user_has_passed($username)
    {
        foreach (array_keys($this->pages) as $quiz_page_id)
        {
            // false would be more logical than string "no",
            // but you can't array_search on a boolean.
            $pages_required_results[$quiz_page_id] = "no";
        }

        $sql = sprintf("
            SELECT *
            FROM quiz_passes
            WHERE username = '%s'
        ", DPDatabase::escape($username));
        $result = DPDatabase::query($sql);
        while ($attempt = mysqli_fetch_object($result))
        {
            if($attempt->result != "pass") continue;
            if(array_key_exists($attempt->quiz_page, $pages_required_results))
            {
                $pages_required_results[$attempt->quiz_page] = $attempt->date;
            }
            else
            {
                //This quiz page isn't relevant to this quiz.
                continue;
            }
        }

        if (isset($this->pass_requirements['maximum_age']))
        {
            foreach ($pages_required_results as $quiz_page_id => $value)
            {
                if($value == "no") continue; // The user hasn't passed the quiz anyway.
                if ((time() - $value) > $this->pass_requirements['maximum_age'])
                {
                    $pages_required_results[$quiz_page_id] = "no";
                }
            }
        }

        // At this point, if a user has passed the quiz, the $pages_required_results array should
        // have only pagename => timestamp  rows. If any "no" values remain, the user has not passed
        // all the pages required, or the result of that page is invalid (e.g. too old).
        if (array_search("no", $pages_required_results) !== false)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    function show_results_table($username)
    {
        global $code_url;
        global $code_dir;

        if (!empty($username))
            $n_columns = 5; // 3 extra columns of user-specific info
        else
            $n_columns = 2;

        // This assumes that the access minima quiz strings are names
        // 'quiz/X' where X is some quiz ID (eg 'f_only', 'p_mod2', 'p_greek')
        $stage_required_for = array();
        global $Stage_for_id_;
        foreach($Stage_for_id_ as $stage_id => $stage)
        {
            if (array_key_exists("quiz/$this->id", $stage->access_minima))
                $stage_required_for[$stage_id] = $stage;
        }

        echo "<h3 id='$this->id'>$this->name</h3>";

        // if this quiz is required for a stage that the user is now eligible
        // for, output a callout
        if(count($stage_required_for) && $username == User::current_username())
        {
            foreach($stage_required_for as $stage_id => $stage)
            {
                $uao = $stage->user_access($username);
                // if the user can't currently access it but they have satisfied
                // all of the requirements to do so, point them in the right direction
                if(!$uao->can_access && $uao->all_minima_satisfied)
                {
                    echo "<div class='callout'>";
                    echo "<div class='calloutheader'>";
                    echo _("You can now work in $stage_id!");
                    echo "</div>";

                    echo "<p>";
                    echo sprintf(_("You've satisfied all requirements to work in %s!"), $stage_id);

                    echo " ";

                    echo sprintf(_("Continue to the <a href='%1\$s'>%2\$s page</a> to access this activity and get started."), "$code_url/$stage->relative_url#Entrance_Requirements", $stage_id);
                    echo "</p>";
                    echo "</div>";
                    echo "<br>";
                }
            }
        }

        echo "<table class='themed theme_striped'>";

        if ($this->description != "" || count($stage_required_for) > 0)
        {
            echo "
                <tr><td colspan='$n_columns'>";
            if (count($stage_required_for) > 0)
            {
                echo "<p>" . _("Passing this quiz is required prior to working in:") . " ";
                $stage_links = [];
                foreach($stage_required_for as $stage_id => $stage)
                {
                    $stage_links[] = "<a href='$code_url/$stage->relative_url'>$stage_id</a>";
                }
                echo implode(", ", $stage_links);
                echo "</p>"; 
            }
            echo "<p>$this->description</p>";
            echo "</td></tr>";
        }

        $page_number = 0;
        $pages = $this->pages;
        foreach ($pages as $quiz_page_id => $desc)
        {
            $page_number += 1;
            $page_label = sprintf(_('Page %d'), $page_number);
            echo "
                <tr><td><b>$page_label</b>: $desc</td>";
            echo "<td>";
            $tutfile = "$code_dir/quiz/tuts/tut_$quiz_page_id.php";
            if (file_exists($tutfile))
            {
                echo "<a href='tuts/tut_$quiz_page_id.php'>" . _("Tutorial") . "</a> | ";
            }
            echo "<a href='generic/main.php?quiz_page_id=$quiz_page_id'>" . _("Quiz Page") . "</a></td>";
            if (!empty($username))
            {
                $passed = user_has_passed_quiz_page($username, $quiz_page_id);
                $text = $passed ? _("Passed") : _("Not passed");
                $class = $passed ? 'quiz-passed' : 'quiz-not-passed';
                echo "<td class='$class'>$text</td>";

                $date = get_last_attempt_date_for_quiz_page($username, $quiz_page_id);
                $text = ($date != 0) ? strftime("%d-%b-%y", $date) : _("Not attempted");
                $max = $this->pass_requirements['maximum_age'];
                $date_ok = ((time() - $date) < $max) || empty($max);
                $class = $date_ok ? 'quiz-date-ok' : 'quiz-date-not-ok';
                echo "<td class='$class'>$text</td>";

                if($passed && $date_ok)
                {
                    $text = "<span class='x-large' title='" . attr_safe(_("Quiz page passed!")) . "'>✓</span>";
                    $class = 'quiz-ok';
                }
                else
                {
                    $text = "<span class='x-large' title='" . attr_safe(_("Quiz page not passed!")) . "'>✗</span>";
                    $class = 'quiz-not-ok';
                }
                echo "<td class='$class'>$text</td>";
            }
            echo "</tr>";
        }
        if (!empty($username))
        {
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
    global $map_quiz_id_to_Quiz;
    return get_enumerated_param($arr, $key, NULL, array_keys($map_quiz_id_to_Quiz));
}

function get_quiz_page_id_param($arr, $key)
// If $arr[$key] is defined and is a valid quiz page id, return it.
// Otherwise, die with an error message.
{
    return get_enumerated_param($arr, $key, NULL, Quiz::$valid_quiz_page_ids);
}

// -----------------------------------------------------------------------------

function get_Quiz_with_id($quiz_id)
{
    global $map_quiz_id_to_Quiz;
    return $map_quiz_id_to_Quiz[$quiz_id];
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

    if (mysqli_num_rows($res) > 0)
    {
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
    }
    else
    {
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

function user_has_passed_quiz_page($username, $quiz_page_id, $pass_requirements = array())
{
    // This function could fairly easily lookup the quizzes to see what $pass_requirements should be,
    // but by calling it without that argument, it's possible to tell the user *why* they haven't passed.
    $sql = sprintf("
        SELECT *
        FROM quiz_passes
        WHERE username = '%s' AND quiz_page = '%s'
    ", DPDatabase::escape($username), DPDatabase::escape($quiz_page_id));
    $res = DPDatabase::query($sql);
    $row = mysqli_fetch_assoc($res);
    if(!$row)
        return false;

    if (isset($pass_requirements['minimum_age']))
    {
        if ((time() - $row["date"]) > $this->pass_requirements['maximum_age'])
            return false;
    }
    return true;
}

function get_last_attempt_date_for_quiz_page($username, $quiz_page_id)
{
    $sql = sprintf("
        SELECT *
        FROM quiz_passes
        WHERE username = '%s' AND quiz_page = '%s'
    ", DPDatabase::escape($username), DPDatabase::escape($quiz_page_id));
    $res = DPDatabase::query($sql);
    $row = mysqli_fetch_assoc($res);
    if(!$row)
        return false;

    return $row["date"];
}

// vim: sw=4 ts=4 expandtab
