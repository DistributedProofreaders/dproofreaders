<?php
include_once($relPath."connect.inc");
include_once($relPath."site_vars.php");
include_once($relPath."Stage.inc");
include_once($relPath."stages.inc");
include_once($relPath.'misc.inc'); // get_enumerated_param

// for groups of quizzes that go together
class QuizLevel
{
    function QuizLevel($level_id,$level_name,$activity_type,$info,$quizzes)
    {
        $this->level_id = $level_id;
        $this->level_name = $level_name;         // name for the category of quizzes
        $this->activity_type = $activity_type;   // "proof" or "format"
        $this->info = $info;                     // description or other info the user should know
        $this->quizzes = $quizzes;               // the individual quizzes that the level contains

        global $map_quiz_level_id_to_QuizLevel;
        assert(!isset($map_quiz_level_id_to_QuizLevel[$level_id]));
        $map_quiz_level_id_to_QuizLevel[$level_id] =& $this;
    }
}

class Quiz
{
    static $valid_quiz_page_ids = array();

    function Quiz($id,$name,$short_name,$descrption,$thread,$pages, $pass_requirements)
    {
        $this->id = $id;
        $this->name = $name;               // appears in the top of the quiz's table
        $this->short_name = $short_name;   // appears in the righthand frame during the quiz
        $this->description = $descrption;
        $this->thread = $thread;           // forum thread for quiz questions & comments, if other than the default
        $this->pages = $pages;
        $this->pass_requirements = $pass_requirements;
        // Currently supported pass_requirements:
        // ['maximum_age'] => time_in_seconds:
        //     Passes recorded longer than time_in_seconds ago are not valid.

        global $map_quiz_id_to_Quiz;
        assert(!isset($map_quiz_id_to_Quiz[$id]));
        $map_quiz_id_to_Quiz[$id] =& $this;

        foreach ($pages as $quiz_page_id)
        {
           assert(!in_array($quiz_page_id, self::$valid_quiz_page_ids));
           self::$valid_quiz_page_ids[] = $quiz_page_id;
        }
    }

    function user_has_passed($username)
    {
        foreach ($this->pages as $quiz_page_id)
        {
            // false would be more logical than string "no",
            // but you can't array_search on a boolean.
            $pages_required_results[$quiz_page_id] = "no";
        }

        $result = mysql_query("
            SELECT * FROM quiz_passes
            WHERE username='$username'
        ");
        while ($attempt = mysql_fetch_object($result))
        {
            if($attempt->result != "pass") continue;
            if(array_key_exists($attempt->quiz_page,$pages_required_results))
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
        if (array_search("no",$pages_required_results) !== false)
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
        echo "
            <p><table border='0' cellpadding='2' style='border:2px solid black;'>
            <tr><th class='q' colspan='5'>$this->name</th></tr>";

        // This assumes that the access minima quiz strings are names
        // 'quiz/X' where X is some quiz ID (eg 'FQ', 'MPQ2', 'GPQ')
        $stage_required_for = array();
        global $Stage_for_id_;
        foreach($Stage_for_id_ as $stage_id => $stage)
        {
            if (array_key_exists("quiz/$this->id", $stage->access_minima))
                $stage_required_for[] = $stage_id;
        }

        if ($this->description != "" || count($stage_required_for) > 0)
        {
            echo "
                <tr><td colspan='5' class='info'>";
            if (count($stage_required_for) > 0)
            {
                echo "<p>" . _("Passing this quiz is required for:") . " ";
                echo implode(", ", $stage_required_for);
                echo "</p>"; 
            }
            echo "$this->description";
            echo "</td></tr>";
        }

        $pages = $this->pages;
        foreach ($pages as $desc => $quiz_page_id)
        {
            echo "
                <tr><td>$desc</td>";
            echo "<td>";
            $tutfile = "$code_dir/quiz/tuts/tut_$quiz_page_id.php";
            if (file_exists($tutfile))
            {
                echo "<a href='tuts/tut_$quiz_page_id.php'>" . _("Tutorial") . "</a> | ";
            }
            echo "<a href='generic/main.php?type=$quiz_page_id&quiz_id=$this->id'>" . _("Quiz") . "</a></td>";
            if (!empty($username))
            {
                $passed = user_has_passed_quiz_page($username, $quiz_page_id);
                $text = $passed ? _("Passed") : _("Not passed");
                $bgcolor = $passed ? '#ccffcc' : '#ffcccc';
                echo "<td bgcolor='$bgcolor'>$text</td>";
                $date = get_last_attempt_date_for_quiz_page($username, $quiz_page_id);
                $text = ($date != 0) ? strftime("%d-%b-%y",$date) : _("Not attempted");
                $max = $this->pass_requirements['maximum_age'];
                $date_ok = ((time() - $date) < $max) || empty($max);
                $bgcolor = $date_ok ? '#ccffcc' : '#ffcccc';
                echo "<td bgcolor='$bgcolor'>$text</td>";
                $text = ($passed && $date_ok) ?	"<img src='$code_url/quiz/tick.png' alt='Tick!' title='Quiz page passed!' width='25' height='24' />"
                    : "<img src='$code_url/quiz/cross.png' alt='Cross' title='Quiz page not passed' width='22' height='25'  />";
                $bgcolor = ($passed && $date_ok) ? '#88ff88' : '#ff8888';
                echo "<td bgcolor='$bgcolor'>$text</td>";
            }
            else
            {
                echo "<td /><td /><td />";
            }
            echo "</tr>";
        }
        if (!empty($username))
        {
            $total_pass = $this->user_has_passed($username);
            $text = $total_pass ? _("Quiz passed") : _("Quiz not passed");
            $bgcolor = $total_pass ? '#88ff88' : '#ff8888';
            echo "
                <tr><td colspan='5'  bgcolor='$bgcolor' style='font-weight: bold;text-align:center;'>$text</td></tr>";
        }
        echo "</table></p>\n";
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

// -----------------------------------------------------------------------------

function record_quiz_attempt($username, $quiz_page_id, $result)
{
    new dbConnect();
    $res = mysql_query("
        SELECT * FROM quiz_passes
        WHERE username='$username' AND quiz_page='$quiz_page_id'
    ");
    $now = time();
    if (mysql_num_rows($res) > 0)
    {
        // The user has already passed this page; update the timestamp
        mysql_query("
            UPDATE quiz_passes SET date= '$now'
            WHERE username='$username' AND quiz_page='$quiz_page_id'
        ");
    }
    else
    {
        mysql_query("
            INSERT INTO quiz_passes
            VALUES('$username','$now','$quiz_page_id','$result')
        ");
    }
}

function user_has_passed_quiz_page($username, $quiz_page_id, $pass_requirements = array())
{
    // This function could fairly easily lookup the quizzes to see what $pass_requirements should be,
    // but by calling it without that argument, it's possible to tell the user *why* they haven't passed.
    $res = mysql_query("
        SELECT * FROM quiz_passes
        WHERE username='$username' AND quiz_page='$quiz_page_id'
    ");
    if (mysql_num_rows($res) == 0)
        return false;
    if (isset($pass_requirements['minimum_age']))
    {
        $value = mysql_result($res,0,"date");
        if ((time() - $value) > $this->pass_requirements['maximum_age'])
            return false;
    }
    return true;
}

function get_last_attempt_date_for_quiz_page($username, $quiz_page_id)
{
    $res = mysql_query("
        SELECT date FROM quiz_passes
        WHERE username='$username' AND quiz_page='$quiz_page_id'
    ");
    if (mysql_num_rows($res) == 0)
        return false;

    $value = mysql_result($res,0,"date");

    return $value;
}

function get_activity_type_for_quiz ($quiz_id)
{
    global $map_quiz_level_id_to_QuizLevel;
    foreach ($map_quiz_level_id_to_QuizLevel as $quiz_level_id => $quiz_level)
    {
        foreach ($quiz_level->quizzes as $quiz)
        {
            if ($quiz->id == $quiz_id)
            {
                return $quiz_level->activity_type;
            }
        }
    }
}

// vim: sw=4 ts=4 expandtab
