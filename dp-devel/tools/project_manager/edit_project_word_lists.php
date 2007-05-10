<?
$relPath="./../../pinc/";
include_once($relPath.'site_vars.php');
include_once($relPath.'dp_main.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'links.inc');
include_once('edit_common.inc');
include_once($relPath.'wordcheck_engine.inc');
include_once($relPath.'metarefresh.inc');
include_once($relPath.'project_edit.inc');

$return = array_get($_REQUEST,"return","$code_url/tools/project_manager/projectmgr.php");

if ( !user_is_PM() )
{
    die('permission denied');
}

$pwlh = new ProjectWordListHolder;

$errors = array();

if (isset($_POST['saveAndProject']) || isset($_POST['saveAndPM']) || isset($_POST['save']) )
{
    $errors = $pwlh->set_from_post();
    if (count($errors)==0)
    {
        $errors = $pwlh->save_to_files();
        if (count($errors)==0)
        {
            if (isset($_POST['saveAndProject']))
            {
                metarefresh(0, "$code_url/project.php?id=$pwlh->projectid", _("Save and Go To Project"), "");
                exit;
            }
            elseif (isset($_POST['saveAndPM']))
            {
                metarefresh(0, "$code_url/tools/project_manager/projectmgr.php", _("Save and Go To PM Page"), "");
                exit;
            }
        }
        elseif (isset($_POST['save']))
        {
            // No errors, but fall through.
        }
    }
    else
    {
        // Errors.
        // fall through
    }
} elseif(isset($_POST['quit'])) {
    // if return is empty for whatever reason take them to
    // the PM page
    if(empty($return))
        $return="$code_url/tools/project_manager/projectmgr.php";

    // do the redirect
    metarefresh(0, $return, _("Quit without Saving"), "");
    exit;
} elseif(isset($_POST['reload'])) {
    // fall through
}

if(count($errors)==0)
    $pwlh->set_from_db();

$page_title = _("Edit Project Word Lists");

$no_stats=1;
theme($page_title, "header");
echo "<h1>$page_title</h1>\n";

foreach($errors as $error) {
    echo "<p class='error'>$error</p>";
}

$pwlh->show_form();

theme("", "footer");

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

class ProjectWordListHolder
{
    // load data from database
    function set_from_db()
    {
        $projectid = $_REQUEST['projectid'];
        if ( $projectid == '' )
        {
            return array(_("parameter 'projectid' is empty"));
        }

        $ucep_result = user_can_edit_project($projectid);
        // we only let people clone projects that they can edit, so this
        // is valid whether they are cloning or editing
        if ( $ucep_result == PROJECT_DOES_NOT_EXIST )
        {
            return array(_("parameter 'projectid' is invalid: no such project").": '$projectid'");
        }
        else if ( $ucep_result == USER_CANNOT_EDIT_PROJECT )
        {
            return array(_("you are not allowed to edit this project").": '$projectid'");
        }
        else if ( $ucep_result == USER_CAN_EDIT_PROJECT )
        {
            // fine
        }
        else
        {
            return array(_("unexpected return value from user_can_edit_project") . ": '$ucep_result'");
        }

        $res = mysql_query("SELECT * FROM projects WHERE projectid = '$projectid'");
        if (mysql_num_rows($res) == 0)
        {
            return array(_("parameter 'projectid' is invalid") . ": '$projectid'");
        }

        $ar = mysql_fetch_array($res);

        $this->projectid        = $projectid;
        $this->nameofwork       = $ar['nameofwork'];
        $this->projectmanager   = $ar['username'];
        $this->authorsname      = $ar['authorsname'];
        $this->language         = $ar['language'];
        $this->checkedoutby     = $ar['checkedoutby'];

        mysql_free_result($res);


        $gwl_object = get_project_word_file($this->projectid,"good");
        $this->gwl_timestamp = $gwl_object->mod_time;
        $bwl_object = get_project_word_file($this->projectid,"bad");
        $this->bwl_timestamp = $bwl_object->mod_time;
        $good_words = load_project_good_words($this->projectid);
        $bad_words=load_project_bad_words($this->projectid);

        if ( is_string($good_words) )
        {
            array_push($errors,$good_words);
            $this->good_words = '';
        }
        else
        {
            $this->good_words = implode("\n", $good_words);
        }

        if ( is_string($bad_words) )
        {
            array_push($errors,$bad_words);
            $this->bad_words = '';
        }
        else
        {
            $this->bad_words = implode("\n", $bad_words);
        }

        return $errors;
    }

    function set_from_post()
    {
        if ( get_magic_quotes_gpc() )
        {
            // Values in $_POST come with backslashes added.
            // We want the fields of $this to be unescaped strings,
            // so we strip the slashes.
            $_POST = array_map('stripslashes', $_POST);
        }

        if ( isset($_POST['projectid']) )
        {
            $this->projectid = $_POST['projectid'];

            $ucep_result = user_can_edit_project($this->projectid);
            if ( $ucep_result == PROJECT_DOES_NOT_EXIST )
            {
                return array(_("parameter 'projectid' is invalid: no such project").": '$this->projectid'");
            }
            else if ( $ucep_result == USER_CANNOT_EDIT_PROJECT )
            {
                return array(_("you are not allowed to edit this project").": '$this->projectid'");
            }
            else if ( $ucep_result == USER_CAN_EDIT_PROJECT )
            {
                // fine
            }
            else
            {
                return array(_("unexpected return value from user_can_edit_project") . ": '$ucep_result'");
            }
        }

        $this->projectid        = @$_POST['projectid'];
        $this->good_words       = @$_POST['good_words'];
        $this->bad_words        = @$_POST['bad_words'];
        $this->gwl_timestamp    = @$_POST['gwl_timestamp'];
        $this->bwl_timestamp    = @$_POST['bwl_timestamp'];

        return array();
    }


    // -------------------------------------------------------------------------

    function save_to_files()
    {
        global $projects_dir, $uploads_dir, $pguser;

        $messages = array();

        // first, check to see if the good or bad word list
        // has changed out from beneath us
        $gwl_object = get_project_word_file($this->projectid,"good");
        $bwl_object = get_project_word_file($this->projectid,"bad");
        $current_gwl_timestamp = $gwl_object->mod_time;
        $current_bwl_timestamp = $bwl_object->mod_time;

        if($current_gwl_timestamp != $this->gwl_timestamp) {
            $error = sprintf(_("The Good Words List was changed by another process during your edit session. Your changes have not been saved to prevent data loss. View the %s and merge your changes manually. If you want the superset of both lists, simply append the contents of the Good Words List to that within the Good Words edit box below - the server will remove any duplicates. Saving this page again will override this message."),new_window_link($gwl_object->abs_url,_("Good Words List")));
            array_push($messages,$error);
            $this->gwl_timestamp = $current_gwl_timestamp;
        } else {
            // everything looks good, save the changes
            $good_words = explode("[lf]",str_replace(array("\r","\n"),array('',"[lf]"),$this->good_words));
            save_project_good_words($this->projectid, $good_words);
        }

        if($current_bwl_timestamp != $this->bwl_timestamp) {
            $error = sprintf(_("The Bad Words List was changed by another process during your edit session. Your changes have not been saved to prevent data loss. View the %s and merge your changes manually. If you want the superset of both lists, simply append the contents of the Bad Words List to that within the Bad Words edit box below - the server will remove any duplicates. Saving this page again will override this message."),new_window_link($bwl_object->abs_url,_("Bad Words List")));
            array_push($messages,$error);
            $this->bwl_timestamp = $current_bwl_timestamp;
        } else {
            // everything looks good, save the changes
            $bad_words = explode("[lf]",str_replace(array("\r","\n"),array('',"[lf]"),$this->bad_words));
            save_project_bad_words($this->projectid, $bad_words);
        }

        
        return $messages;
    }

    // =========================================================================

    function show_form()
    {
        global $theme;

        $this->echo_stylesheet();

        echo "<form method='post' enctype='multipart/form-data' action='".$_SERVER['PHP_SELF']."'>";

        $this->show_hidden_controls();

        echo "<center>";
        echo "<table class='wordlisttable'>";

        $this->show_visible_controls();

        echo "<tr>";
        echo   "<td class='label' colspan='2' align='center'>";
        echo     "<input type='submit' name='saveAndPM' value='"._("Save and Go To PM Page")."'>";
        echo     "<input type='submit' name='saveAndProject' value='"._("Save and Go To Project")."'>";
        echo     "<input type='submit' name='save' value='"._("Save")."'>";
        echo     "<input type='submit' name='quit' value='"._("Quit Without Saving")."'>";
        echo     "<input type='submit' name='reload' value='"._("Refresh Word Lists")."'>";
        echo   "</td>";
        echo "</tr>\n";

        echo "</table>";
        echo "</center>";
        echo "</form>";
        echo "\n";
    }

    // -------------------------------------------------------------------------

    function echo_stylesheet() {
?>
    <style type="text/css">
    table.wordlisttable {
        padding: 5px;
        border-collapse: collapse;
        width: 90%;
    }
    table.wordlisttable td {
        border: thin solid #000;
    }
    table.wordlisttable td.label {
        background-color: #CCC;
        font-weight: bold;
    }
    table.wordlisttable .mono { font-family: monospace; }
    table.wordlisttable textarea { width: 100%; }
    </style>
<?
    }

    function show_hidden_controls()
    {
        global $return;

        echo "<input type='hidden' name='projectid' value='$this->projectid'>";
        echo "<input type='hidden' name='gwl_timestamp' value='$this->gwl_timestamp'>";
        echo "<input type='hidden' name='bwl_timestamp' value='$this->bwl_timestamp'>";
        echo "<input type='hidden' name='return' value='$return'>";
    }


function show_visible_controls() {
    $goodWordData = encodeFormValue($this->good_words);
    $badWordData = encodeFormValue($this->bad_words);

    $fields=array(
        "projectid" => _("Project ID"),
        "nameofwork" => _("Name of Work"),
        "authorsname" => _("Author's Name"),
        "projectmanager" => _("Project Manager"),
        "checkedoutby" => _("Post-Processor"),
        "language" => _("Language")
    );

    foreach($fields as $field => $label) {
        echo "<tr>";
        echo "<td class='label'>$label</td>";
        echo "<td>" . $this->$field . "</td>";
        echo "</tr>";
    }

    echo "<tr>";
    echo "<td class='label'>" . _("WordCheck Statistics") . "</td>";
    echo "<td>" . new_window_link("show_project_wordcheck_stats.php?projectid=$this->projectid",_("Show WordCheck flagged word statistics")) . "</td>";
    echo "</tr>";

    echo "<tr>";
    echo "<td class='label' align='center' colspan='2'>";
    echo _("Project Dictionary - Word Lists");
    echo "</td>";
    echo "</tr>";

    echo "<td colspan='2'>";

    echo "<table width='100%'>";
    echo "<tr>";
    echo "<td class='label' align='center'>" . _("Good Words") . "</td>";
    echo "<td class='label' align='center'>" . _("Bad Words") . "</td>";
    echo "</tr>";

    echo "<tr>";
    echo "<td align='center' valign='top' width='50%'>";
    echo "<textarea class='mono' name='good_words' cols='40' rows='20'>$goodWordData</textarea>";

    echo "<p>";
    echo "<b>" . _("Words that WordCheck would currently flag:") . "</b><br>";
    echo new_window_link(
        "show_current_flagged_words.php?projectid=$this->projectid",
        _("Display results")
    );
    echo " | ";
    echo "<a href='show_current_flagged_words.php?projectid=$this->projectid&amp;format=file'>" . _("Download results") . "</a>";
    echo "</p>";

    $f = get_project_word_file($this->projectid, 'good_suggs');
    if ($f->size > 0)
    {
        echo "<p>";
        echo "<b>" . _("Suggestions from proofers:") . "</b><br>";
        echo new_window_link(
            "show_good_word_suggestions.php?projectid=$this->projectid",
            _("Display results")
        );
        echo " | ";
        echo "<a href='show_good_word_suggestions.php?projectid=$this->projectid&amp;timeCutoff=0&amp;format=file'>" . _("Download results") . "</a>";
        echo "</p>";
    }

    echo "</td>";

    echo "<td align='center' valign='top' width='50%'>";
    echo "<textarea class='mono' name='bad_words' cols='40' rows='20'>$badWordData</textarea>";

    // see if the site has Possible Bad Word files
    // for this project's languages before showing the link
    $languages = array_unique(array_values(get_project_languages($this->projectid)));
    $show_links = false;
    foreach ( $languages as $language ) {
        $langcode3 = langcode3_for_langname( $language );
        $fileObject = get_site_word_file( $langcode3, "possible_bad" );
        $show_links = $show_links || ($fileObject->size > 0);
    }

    if($show_links) {
        echo "<p>";
        echo "<b>" . _("Words in the site's Possible Bad Words file:") . "</b><br>";
        echo new_window_link(
            "show_project_possible_bad_words.php?projectid=$this->projectid",
            _("Display results")
        );
        echo " | ";
        echo "<a href='show_project_possible_bad_words.php?projectid=$this->projectid&amp;format=file'>" . _("Download results") . "</a>";
        echo "</p>";
    }
// commenting this code out until the linked page is ready for checkin
/*
    echo "<p>";
    echo "<b>" . _("Possible stealth-scannos:") . "</b><br>";
    echo new_window_link(
        "show_project_stealth_scannos.php?projectid=$this->projectid",
        _("Display results")
    );
    echo " | ";
    echo "<a href='show_project_stealth_scannos.php?projectid=$this->projectid&amp;format=file'>" . _("Download results") . "</a>";
    echo "</p>";
*/

    echo "</td>";
    echo "</tr>";
    echo "</table>";

    echo "</td>";
    echo "</tr>";

    echo "<tr>";
    echo "<td colspan='2' align='center'>";

    echo sprintf(
        _("See the %s for more information on word lists."),
        new_window_link( "../../faq/wordcheck-faq.php", _("WordCheck FAQ") )
    );

    echo "</td>";
    echo "</tr>";

    echo "<tr>";
    echo "<td class='label'>" . _("Project Information") . "</td>";
    echo "<td>" . new_window_link( "editproject.php?action=edit&amp;project=$this->projectid", _("Edit project information") ) . "</td>";
    echo "</tr>";

    // -------------------------------------------------------------------------

}

}

// vim: sw=4 ts=4 expandtab
?>
