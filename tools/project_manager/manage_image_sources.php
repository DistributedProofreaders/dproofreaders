<?php
$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'dpsql.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'maybe_mail.inc');
include_once($relPath.'metarefresh.inc');
include_once($relPath.'misc.inc'); // get_enumerated_param()

require_login();

$theme_args['css_data'] = "
table.listing { border-collapse:collapse; }
table.listing td { border: 1px solid #999; }
table.listing tr.e { background-color: white; }
table.listing tr.o { background-color: #ddd; }
table.listing td { padding: 5px; }
table.listing td.enabled { background-color: #9f9; text-align: center; }
table.listing td.disabled { background-color: #ddd; text-align: center; }
table.listing td.pending { background-color: #ff8; text-align: center; }
table.listing td.center { text-align: center; }
form { padding: 0; margin: 0; }

table.source { width:75%; border-collapse:collapse;
    margin-left: auto; margin-right: auto; }
table.source td { border:1px solid black; padding:5px; }
table.source td.pa {width:30%; font-weight:bold; }";

$page_url = "$code_url/tools/project_manager/manage_image_sources.php?";

$action = get_enumerated_param($_REQUEST, 'action', 'show_sources',
              array('show_sources','add_source','update_oneshot')
          );

$can_edit = user_is_image_sources_manager();

// Action 'update_oneshot' is used as a target for form submit buttons. The
// desired action is based on the submit button's name. Here we do the action
// and set $action to 'show_sources' to display the list all in one page load.
if ($action == 'update_oneshot')
{
    if (isset($_REQUEST['edit']))
    {
        $action = 'edit_source';
    }

    elseif (isset($_REQUEST['enable']))
    {
        $source = new ImageSource($_REQUEST['source']);
        $source->enable();
        $action='show_sources';
    }

    elseif (isset($_REQUEST['disable']))
    {
        $source = new ImageSource($_REQUEST['source']);
        $source->disable();
        $action='show_sources';
    }

    elseif (isset($_REQUEST['approve']))
    {
        $source = new ImageSource($_REQUEST['source']);
        $source->approve();
        $action='show_sources';
    }

    elseif (isset($_REQUEST['save_edits']))
    {
        # This handles both edits to existing sources, and the creation of new sources
        $source = new ImageSource;

        $errmsgs = '';

        $new_code_name = rtrim(ltrim($_REQUEST[code_name]));

        if (strlen($new_code_name) < 1) 
            $errmsgs .= _("A value for Image Source ID is required. Please enter one.") . " ";

        $result = mysql_query("SELECT COUNT(*)
            FROM image_sources
            WHERE code_name = '$new_code_name'");

        $new = (mysql_result($result,0) == 0);

        if (!$new)
            $source->ImageSource($_REQUEST['code_name']);

        if ( !$new && !isset($_REQUEST['editing']) )
        {
            $errmsgs .= sprintf(_('An Image Source with this ID already exists. If you
            wish to edit the details of an existing source, please contact %s.
            Otherwise, choose a different ID for this source.'),$db_requests_email_addr) . "<br />";
        }

        $source->save_from_post();
        if ($can_edit)
        {
            $action = 'show_sources';
        }
        else
        {
            output_header('', NO_STATSBAR);
                if ($new)
                    $source->log_request_for_approval($pguser);
                echo _("Your proposal has been successfully recorded. You will be
                    notified by email once it has been approved.");
        }

    }
}

if ($action == 'show_sources')
{
    // The more detailed listing of Image Sources is only available
    // to managers.
    if (!$can_edit)
        metarefresh(0,"$code_url/tools/project_manager/show_image_sources.php");

    output_header(_('List Image Sources'), NO_STATSBAR, $theme_args);

    show_is_toolbar();

    $result = mysql_query("SELECT code_name FROM image_sources ORDER BY display_name ASC");

    echo "<br />";
    echo "<table class='listing'>";
    echo "<tr>";
    echo "<th>" . _("ID") . "</th>";
    echo "<th>" . _("Display Name") . "</th>";
    echo "<th>" . _("Full Name") . "</th>";
    echo "<th>" . _("Status") . "</th>";
    echo "<th>" . _("Images<br>stored") . "</th>";
    echo "<th>" . _("Images<br>published") . "</th>";
    echo "<th>" . _("Source shown to") . "</th>";
    echo "</tr>";
    $count=0;
    while ( list($source_name) = mysql_fetch_row($result) )
    {
        $count++;

        $source = new ImageSource($source_name);
        $source->show_listing_row($count);
    }
    echo "</table>";
    echo "<br>";
}

elseif ($action == 'edit_source')
{
    $source = new ImageSource($_REQUEST['source']);
    output_header(sprintf(_("Editing %s"), $source->display_name), NO_STATSBAR, $theme_args);
    show_is_toolbar();
    $source->show_edit_form();
}

elseif ($action == 'add_source')
{
    $title = $can_edit ? _('Add a new Image Source') : _('Propose a new Image Source');
    output_header($title, NO_STATSBAR, $theme_args);
    show_is_toolbar();
    $blank = new ImageSource(null);
    $blank->show_edit_form();
}


// ----------------------------------------------------------------------------

class ImageSource
{

    function ImageSource($code_name = null)
    {
        if( !is_null($code_name) )
        {
            $result = mysql_query("SELECT *
                FROM image_sources
                WHERE code_name = '$code_name'");
            $source_fields = mysql_fetch_assoc($result);

            foreach ($source_fields as $field => $value)
            {
                $this->$field = $value;
            }
        }
        else
        {
            $this->new_source = true;
        }
    }

    function show_listing_row($count)
    {
        global $page_url;
        $sid = htmlspecialchars($this->code_name, ENT_QUOTES);

        if($count%2 == "1")
            $row_class = "o";
        else
            $row_class = "e";

        // calculate how many rows this listing will have so we can span them
        // for some columns
        $listing_rows = 2;
        if($this->public_comment)
            $listing_rows++;
        if($this->internal_comment)
            $listing_rows++;

        echo "<tr class='$row_class'>";
        echo "<td rowspan='$listing_rows'>" . htmlspecialchars($this->code_name) . "</td>";
        echo "<td><a name='$sid' id='$sid'></a>" . htmlspecialchars($this->display_name) . "</td>";
        echo "<td>" . make_link($this->url, $this->full_name) . "</td>";
        echo $this->_get_status_cell($this->is_active,' pb');
        echo "<td class='center'>" . $this->_may_maynot_unknown($this->ok_keep_images) . "</td>";
        echo "<td class='center'>" . $this->_may_maynot_unknown($this->ok_show_images) . "</td>";
        echo "<td>" . $this->_showto($this->info_page_visibility) . "</td>";
        echo "<td class='center' rowspan='$listing_rows'>";
        echo "<form method='post' action='$page_url#$sid'>";
        echo "  <input type='hidden' name='action' value='update_oneshot'>\n";
        echo "  <input type='hidden' name='source' value='$sid'>\n";
                $this->show_buttons();
        echo "</form>";
        echo "</td>";
        echo "</tr>";

        echo "<tr class='$row_class'>";
        echo "<td colspan='6'>" . sprintf(_("<b>Credits Line:</b> %s"), htmlspecialchars($this->credit)) . "</td>";
        echo "</tr>";

        if($this->public_comment)
        {
            echo "<tr class='$row_class'>";
            echo "<td colspan='6'>" . sprintf(_("<b>Comment (public):</b> %s"), htmlspecialchars($this->public_comment)) . "</td>";
            echo "</tr>";
        }

        if($this->internal_comment)
        {
            echo "<tr class='$row_class'>";
            echo "<td colspan='6'>" . sprintf(_("<b>Notes (internal):</b> %s"), htmlspecialchars($this->internal_comment)) . "</td>";
            echo "</tr>";
        }
    }

    function show_buttons()
    {
        echo "<input type='submit' name='edit' value='".attr_safe(_('Edit'))."' /> ";
        switch ($this->is_active)
        {
            case('-1'):
                echo "<input type='submit' name='approve' value='".attr_safe(_('Approve'))."' /> ";
                break;
            case('0'):
                echo "<input type='submit' name='enable' value='".attr_safe(_('Enable'))."' /> ";
                break;
            case('1'):
                echo "<input type='submit' name='disable' value='".attr_safe(_('Disable'))."' /> ";
                break;
        }
    }

    function show_edit_form()
    {
        global $page_url;
        echo "<table class='source'><form method='post'
            action='$page_url&amp;action=update_oneshot#$this->code_name'>\n";

        if($this->new_source)
        {
            $this->_show_edit_row('code_name',_('Image Source ID'),false,10);
        }
        else
        {
            echo "<input type='hidden' name='editing' value='true' />" .
                "<input type='hidden' name='code_name' value='" . htmlspecialchars($this->code_name, ENT_QUOTES) ."' />";
            $this->_show_summary_row(_('Image Source ID'),$this->code_name);
        }
        $this->_show_edit_row('display_name',_('Display Name'),false,30);
        $this->_show_edit_row('full_name',_('Full Name'));
        $this->_show_edit_row('url',_('Website'));
        $this->_show_edit_row('credit',_('Credits line'),true);
        $this->_show_edit_permissions_row();
        $this->_show_edit_row('public_comment',_('Comment (public)'),true);
        $this->_show_edit_row('internal_comment',_('Notes (internal)'),true);

        echo "<tr><td colspan='2' style='text-align:center;'>
            <input type='submit' name='save_edits' value='".attr_safe(_('Save'))."' />
            </td> </tr> </form> </table>\n\n";
    }

    function _show_edit_row($field, $label, $textarea = false, $maxlength = null)
    {

        $value = $this->new_source
            ? (empty($_REQUEST[$field]) ? '' : $_REQUEST[$field])
            : $this->$field;

        $value = htmlspecialchars($value,ENT_QUOTES);

        if ($textarea)
        {
            $editing = "<textarea cols='60' rows='6' name='$field'>$value</textarea>";
        }
        else
        {
            $maxlength_attr = is_null($maxlength) ? '' : "maxlength='$maxlength'";
            $editing = "<input type='text' name='$field' size='60' value='$value' $maxlength_attr />";
        }
        echo "  <tr>" .
            "<td class='pa'>$label</td>" .
            "<td class='pb'>$editing</td>" .
            "</tr>\n";
    }

    function _show_edit_permissions_row()
    {
        global $site_abbreviation;
        $cols = array(
            array('field' => 'ok_keep_images', 'label' => _('Images may be stored'), 'allow_unknown' => true),
            array('field' => 'ok_show_images', 'label' => _('Images may be published'), 'allow_unknown' => true),

            );

        $editing = '';

        foreach ($cols as $col)
        {

            $field = $col['field'];
            $existing_value = $this->new_source
                ? (empty($_REQUEST[$field]) ? '-1' : $_REQUEST[$field])
                : $this->$field;

            $editing .= "$col[label] <select name='$col[field]'>";
            foreach (array('1' => _('Yes'),'0' => _('No'),'-1' => _('Unknown')) as $val => $opt)
            {
                if (! (!$col['allow_unknown'] && $val == -1) )
                {
                $editing .= "<option value='$val' " .
                    ($existing_value == $val ? 'selected' :'') .
                    ">$opt</option>";
                }
            }
            $editing .= "</select><br />";
        }

        // info page visibility is more complicated
        //  0 = Image Source Managers and SAs
        //  1 = also any PM
        //  2 = also any logged-in user
        //  3 = anyone

            $field = 'info_page_visibility';
            $existing_value = $this->new_source
                ? (empty($_REQUEST[$field]) ? '2' : $_REQUEST[$field])
                : $this->$field;

            $editing .= _("Visibility on Info Page") . " <select name='$field'>";
            foreach (array(
                    '0' => _('IS Managers Only'),
                    '1' => _('Also PMs'),
                    // TRANSLATORS: %s is the site abbreviation
                    '2' => sprintf(_("All %s Users"), $site_abbreviation),
                    '3' => _('Publicly Visible')) 
                as $val => $opt)
            {
                {
                $editing .= "<option value='$val' " .
                    ($existing_value == $val ? 'selected' :'') .
                    ">$opt</option>";
                }
            }
            $editing .= "</select><br />";


        $this->_show_summary_row(_('Permissions'),$editing,false);
    }

    function save_from_post()
    {
        global $errmsgs,$can_edit,$new,$theme_args;
        $std_fields = array('display_name','full_name','credit',
                    'ok_keep_images','ok_show_images','info_page_visibility','public_comment','internal_comment');
        foreach ($std_fields as $field)
        {
            $this->$field = $_POST[$field];
            $std_fields_sql .= "$field = '{$this->$field}',\n";
        }

        // If the url has no scheme, prepend http://
        $this->url = strpos($_POST['url'],'://') ? $_POST['url'] : 'http://'.$_POST['url'];
        $this->code_name = strtoupper($_POST['code_name']);
        if ($new)
        {
            // If the user is an Image Sources manager, then the new source should
            // default to disabled. If not, the source should default to pending approval.
            $this->is_active = $can_edit ? '0' : '-1';
            // new sources shouldn't be shown on
            // the public version of the info page until they are approved.
             $this->info_page_visibility = '1' ;
        }

        if ($errmsgs)
        {
            output_header('', NO_STATSBAR, $theme_args);
            echo "<p style='font-weight: bold; color: red;'>" . $errmsgs . "</p>";
            $this->show_edit_form();
            die;
        }

        mysql_query("REPLACE INTO image_sources
            SET
                code_name = '$this->code_name',
                $std_fields_sql
                url = '$this->url',
                is_active = '$this->is_active'
        ") or die("Couldn't add/edit source: ".mysql_error());

    }

    function enable()
    {
        $this->_set_field('is_active',1);
    }

    function disable()
    {
        $this->_set_field('is_active',0);
    }


    function approve()
    {
        global $pguser, $site_abbreviation, $site_signoff, $site_name;
        $this->_set_field('is_active',1);
        $this->_set_field('info_page_visibility',1);

        $result = mysql_query("SELECT users.username,users.email
            FROM usersettings,users
            WHERE usersettings.setting = 'is_approval_notify'
            AND usersettings.value = '$this->code_name'
            AND usersettings.username = users.username");

        mysql_query("DELETE
            FROM usersettings
            WHERE setting = 'is_approval_notify'
            AND usersettings.value = '$this->code_name'") or die(mysql_error());

        list($username, $email) = mysql_fetch_row($result);

        $subject = sprintf(_('%s: Image Source %s has been approved!'),$site_abbreviation,$this->display_name);

        $body = "Hello $username,\n\n" .
            "This is a message from the $site_name website.\n\n".
            "The Image Source that you proposed, $this->display_name, has been\n".
            "approved by $pguser. You can select it, and apply it to projects, from\n".
            "your project manager's page.\n\n$site_signoff";

        maybe_mail($email,$subject,$body);
    }

    function _set_field($field,$value)
    {
        mysql_query("UPDATE image_sources
            SET $field = '$value'
            WHERE code_name = '$this->code_name'");
        $this->$field = $value;
    }


    function _show_summary_row($label,$value,$htmlspecialchars = true)
    {
        echo "  <tr>" .
            "<td class='pa'>$label</td>" .
            "<td class='pb'>" . ($htmlspecialchars ? htmlspecialchars($value) : $value ) . "</td>" .
            "</tr>\n";
    }

    function _get_status_cell($status,$class = '')
    {
        switch ($status)
        {
          case(1):
              $middle = _('Enabled');
              $open = "<td class='enabled{$class}'>";
              break;
          case(0):
              $middle = _('Disabled');
              $open = "<td class='disabled{$class}'>";
              break;
          case(-1):
              $middle = _('Pending approval');
              $open = "<td class='pending{$class}'>";
              break;
        }

        return $open . $middle . '</td>';
    }

    function _may_maynot_unknown($value)
    {
        if ($value != '-1')
        {
            return ( $value ? _('may') : _('may not') );
        }
        else
            return "unknown";
    }

    function _showto($show_to)
    {
        global $site_abbreviation;
        switch ($show_to)
        {
            case '0': $to_whom = _("Image Managers Only");
                break;
            case '1':  $to_whom = _("Project Managers");
                break;
            case '2':  $to_whom = sprintf(_("Any %s User"),$site_abbreviation);
                break;
            case '3':  $to_whom = _("All Users and Visitors");
                break;
        }
        return $to_whom;
    }

    function log_request_for_approval($requestor_username)
    {
        global $general_help_email_addr,$image_sources_manager_addr,$code_url,$site_abbreviation,$site_name,$site_signoff;

        mysql_query("INSERT INTO usersettings
            SET
                username = '$requestor_username',
                setting = 'is_approval_notify',
                value = '$this->code_name'") or die(mysql_error());

        $subject = sprintf(_('%s: New Image Source proposed'),$site_abbreviation)." : ".$this->display_name;

        $body = "Hello,\n\nYou are receiving this email because\n".
        "you are listed as an Image Sources manager at the $site_name\n".
        "site. If this is an error, please contact <$general_help_email_addr>.\n\n".
        "$requestor_username has proposed that $this->display_name be added\n".
        "to the list of Image Sources. To edit or approve this Image Source,\n".
        "visit\n    $code_url/tools/project_manager/manage_image_sources.php?action=show_sources#$this->code_name".
        "\n\n$site_signoff";

        maybe_mail($image_sources_manager_addr,$subject,$body);
    }

}

// ----------------------------------------------------------------------------

function make_link($url,$label)
{
    $start = substr($url,0,3);
    if ($start == 'htt')
    {
        return "<a href='$url'>$label</a>";
    }
    else
    {
        return "<a href='http://$url'>$label</a>";
    }
}

function show_is_toolbar()
{
    global $action;

    $pages = array(
        'add_source' => _('Add New Source'),
        'show_sources' => _('List All Image Sources')
    );
    echo "<p style='text-align: center; margin: 5px 0 5px 0;'>";
    foreach ($pages as $new_action => $label)
    {
        echo ($action == $new_action) ? "<b>" : "<a href='?action=$new_action'>";
        echo $label;
        echo ($action == $new_action) ? "</b>" : "</a>";

        if ( $label != end($pages) )
            echo " | ";
    }
    echo "</p>";
}

// vim: sw=4 ts=4 expandtab
?>
