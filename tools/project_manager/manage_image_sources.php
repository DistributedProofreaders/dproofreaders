<?php
$relPath = '../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'send_mail.inc');
include_once($relPath.'metarefresh.inc');
include_once($relPath.'Settings.inc');
include_once($relPath.'User.inc');

require_login();

$page_url = "$code_url/tools/project_manager/manage_image_sources.php?";

$action = get_enumerated_param(
    $_REQUEST,
    'action',
    'show_sources',
    ['show_sources', 'add_source', 'update_oneshot']
);

$can_edit = user_is_image_sources_manager();

// Action 'update_oneshot' is used as a target for form submit buttons. The
// desired action is based on the submit button's name. Here we do the action
// and set $action to 'show_sources' to display the list all in one page load.
if ($action == 'update_oneshot') {
    if (isset($_REQUEST['edit'])) {
        $action = 'edit_source';
    } elseif (isset($_REQUEST['enable'])) {
        $source = new ImageSource($_REQUEST['source']);
        $source->enable();
        $action = 'show_sources';
    } elseif (isset($_REQUEST['disable'])) {
        $source = new ImageSource($_REQUEST['source']);
        $source->disable();
        $action = 'show_sources';
    } elseif (isset($_REQUEST['approve'])) {
        $source = new ImageSource($_REQUEST['source']);
        $source->approve();
        $action = 'show_sources';
    } elseif (isset($_REQUEST['save_edits'])) {
        // This handles both edits to existing sources, and the creation of new sources
        $new_code_name = rtrim(ltrim($_REQUEST["code_name"]));

        $errmsgs = '';

        // Required fields
        if (strlen($_REQUEST['display_name']) < 1) {
            $errmsgs .= _("A value for Display Name is required. Please enter one.") . "<br>";
        }

        if (strlen($_REQUEST['full_name']) < 1) {
            $errmsgs .= _("A value for Full Name is required. Please enter one.") . "<br>";
        }

        if (strlen($new_code_name) < 1) {
            $errmsgs .= _("A value for Image Source ID is required. Please enter one.") . "<br>";
        }

        $source = new ImageSource(array_get($_REQUEST, 'code_name', null));

        if ($source->code_name && !isset($_REQUEST['editing'])) {
            $errmsgs .= sprintf(_('An Image Source with this ID already exists. If you
            wish to edit the details of an existing source, please contact %s.
            Otherwise, choose a different ID for this source.'), $db_requests_email_addr) . "<br>";
        }

        if ($errmsgs) {
            output_header('', NO_STATSBAR);
            echo "<p class='error'><br>" . $errmsgs . "</p>";
            $source->show_edit_form();
            die;
        }

        $source->save_from_post();
        if ($can_edit) {
            $action = 'show_sources';
        } else {
            output_header('', NO_STATSBAR);
            if ($source->new_source) {
                $source->log_request_for_approval($pguser);
            }
            echo "<p>";
            echo _("Your proposal has been successfully recorded. You will be
                notified by email once it has been approved.");
            echo "</p>";
        }
    }
}

if ($action == 'show_sources') {
    // The more detailed listing of Image Sources is only available
    // to managers.
    if (!$can_edit) {
        metarefresh(0, "$code_url/tools/project_manager/show_image_sources.php");
    }

    output_header(_('List Image Sources'), NO_STATSBAR);

    show_is_toolbar($action);

    $sql = "SELECT code_name FROM image_sources ORDER BY display_name ASC";
    $result = DPDatabase::query($sql);

    echo "<br>";
    echo "<table class='image_source'>";
    echo "<tr>";
    echo "<th>" . _("ID") . "</th>";
    echo "<th>" . _("Display Name") . "</th>";
    echo "<th>" . _("Full Name") . "</th>";
    echo "<th>" . _("Status") . "</th>";
    echo "<th>" . _("Store Images") . "</th>";
    echo "<th>" . _("Publish Images") . "</th>";
    echo "<th>" . _("Source shown to") . "</th>";
    echo "</tr>";

    $count = 0;
    while ([$source_name] = mysqli_fetch_row($result)) {
        $count++;
        $source = new ImageSource($source_name);
        $source->show_listing_row($count);
    }

    echo "</table>";
    echo "<br>";
} elseif ($action == 'edit_source') {
    $source = new ImageSource($_REQUEST['source']);
    output_header(sprintf(_("Editing %s"), $source->display_name), NO_STATSBAR);
    show_is_toolbar($action);
    $source->show_edit_form();
} elseif ($action == 'add_source') {
    $title = $can_edit ? _('Add a new Image Source') : _('Propose a new Image Source');
    output_header($title, NO_STATSBAR);
    show_is_toolbar($action);
    $blank = new ImageSource(null);
    $blank->show_edit_form();
}


// ----------------------------------------------------------------------------

class ImageSource
{
    public function __construct($code_name = null)
    {
        $this->new_source = true;
        $this->code_name = null;

        if (!is_null($code_name)) {
            $sql = sprintf("
                SELECT *
                FROM image_sources
                WHERE code_name = '%s'
                ", DPDatabase::escape($code_name));
            $result = DPDatabase::query($sql);
            $source_fields = mysqli_fetch_assoc($result);

            if ($source_fields) {
                foreach ($source_fields as $field => $value) {
                    $this->$field = $value;
                }

                $this->new_source = false;
            }
        }
    }

    public function show_listing_row($count)
    {
        global $page_url;
        $sid = html_safe($this->code_name);
        $asid = attr_safe($this->code_name);

        if ($count % 2 == "1") {
            $row_class = "o";
        } else {
            $row_class = "e";
        }

        // calculate how many rows this listing will have so we can span them
        // for some columns
        $listing_rows = 2;
        if ($this->public_comment) {
            $listing_rows++;
        }
        if ($this->internal_comment) {
            $listing_rows++;
        }

        echo "<tr class='$row_class'>";
        echo "<td rowspan='$listing_rows' class='center-align'>";
        echo html_safe($this->code_name);
        echo "<br>\n";
        echo "<form method='post' action='$page_url#$asid'>";
        echo "  <input type='hidden' name='action' value='update_oneshot'>\n";
        echo "  <input type='hidden' name='source' value='$sid'>\n";
        $this->show_buttons();
        echo "</form>";
        echo "</td>";

        echo "<td><a name='$asid' id='$asid'></a>";
        echo html_safe($this->display_name);
        echo "</td>";

        echo "<td>";
        echo "<span style='font-size: large'>" . html_safe($this->full_name) . "</span><br>";
        if ($this->url == "") {
            echo "<span class='error'>" . _("Missing URL") . "</span>";
        } else {
            echo make_link($this->url, $this->url);
        }
        echo "</td>";

        echo $this->_get_status_cell($this->is_active);
        echo "<td class='center-align'>" . $this->_may_maynot_unknown($this->ok_keep_images) . "</td>";
        echo "<td class='center-align'>" . $this->_may_maynot_unknown($this->ok_show_images) . "</td>";
        echo "<td class='center-align'>" . $this->_showto($this->info_page_visibility) . "</td>";
        echo "</tr>";

        echo "<tr class='$row_class'>";
        echo "<td colspan='6'>";
        echo "<b>" . _("Credits Line (no URLs)") . ": </b>";
        echo html_safe($this->credit);
        echo "</td></tr>";

        if ($this->public_comment) {
            echo "<tr class='$row_class'>";
            echo "<td colspan='6'>";
            echo "<b>" . _("Description (public comments)") . ": </b>";
            echo html_safe($this->public_comment);
            echo "</td></tr>";
        }

        if ($this->internal_comment) {
            echo "<tr class='$row_class'>";
            echo "<td colspan='6'>";
            echo "<b>" . _("Notes (internal comments)") . ": </b>";
            echo html_safe($this->internal_comment);
            echo "</td></tr>";
        }
    }

    public function show_buttons()
    {
        echo "<input type='submit' name='edit' value='".attr_safe(_('Edit'))."'> ";
        echo "<br>\n";
        switch ($this->is_active) {
            case '-1':
                echo "<input type='submit' name='approve' value='".attr_safe(_('Approve'))."'> ";
                break;
            case '0':
                echo "<input type='submit' name='enable' value='".attr_safe(_('Enable'))."'> ";
                break;
            case '1':
                echo "<input type='submit' name='disable' value='".attr_safe(_('Disable'))."'> ";
                break;
        }
    }

    public function show_edit_form()
    {
        global $page_url;
        echo "<form method='post' action='$page_url&amp;action=update_oneshot#$this->code_name'>\n";
        echo "<table class='image_source'>\n";

        if ($this->new_source) {
            $this->_show_edit_row('code_name', _('Image Source ID'), false, 10, true);
        } else {
            echo "<input type='hidden' name='editing' value='true'>" .
                "<input type='hidden' name='code_name' value='" . attr_safe($this->code_name) ."'>";
            $this->_show_summary_row(_('Image Source ID'), $this->code_name);
        }
        $this->_show_edit_row('display_name', _('Display Name'), false, 30, true);
        $this->_show_edit_row('full_name', _('Full Name'), false, 100, true);
        $this->_show_edit_row('url', _('Website'), false, 200);
        $this->_show_edit_row('credit', _('Credits Line (no URLs)'), true, 200);
        $this->_show_edit_permissions_row();
        $this->_show_edit_row('public_comment', _('Description (public comments)'), true, 255);
        $this->_show_edit_row('internal_comment', _('Notes (internal comments)'), true);

        echo "<tr><td colspan='2' class='center-align'>
            <input type='submit' name='save_edits' value='".attr_safe(_('Save'))."'>
            </td></tr></table></form>\n";
    }

    public function _show_edit_row($field, $label, $textarea = false, $maxlength = null, $required = false)
    {
        $value = $this->new_source
            ? (empty($_REQUEST[$field]) ? '' : $_REQUEST[$field])
            : $this->$field;

        $required_attr = $required ? 'required' : '';

        $maxlength_attr = is_null($maxlength) ? '' : "maxlength='$maxlength'";
        if ($textarea) {
            $value = html_safe($value);
            $editing = "<textarea cols='60' rows='6' name='$field' $maxlength_attr $required_attr>$value</textarea>";
        } else {
            $value = attr_safe($value);
            $editing = "<input type='text' name='$field' size='60' value='$value' $maxlength_attr $required_attr>";
        }
        echo "  <tr>" .
            "<th class='label'>$label</th>" .
            "<td>$editing</td>" .
            "</tr>\n";
    }

    public function _show_edit_permissions_row()
    {
        global $site_abbreviation;
        $cols = [
            ['field' => 'ok_keep_images', 'label' => _('Images may be stored')],
            ['field' => 'ok_show_images', 'label' => _('Images may be published')],

        ];

        $editing = '';

        foreach ($cols as $col) {
            $field = $col['field'];
            $existing_value = $this->new_source
                ? (empty($_REQUEST[$field]) ? '-1' : $_REQUEST[$field])
                : $this->$field;

            $editing .= "$col[label]: ";
            $editing .= "<select name='$col[field]'>";
            foreach (['1' => _('Yes'), '0' => _('No'), '-1' => _('Unknown')] as $val => $opt) {
                $editing .= "<option value='$val' " .
                    ($existing_value == $val ? 'selected' : '') .
                    ">$opt</option>";
            }
            $editing .= "</select><br>";
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

        $editing .= _("Visibility on Info Page: ");
        $editing .= "<select name='" . attr_safe($field) . "'>";

        foreach ([
            // TRANSLATORS: IS = image source
            '0' => _('IS Managers Only'),
            // TRANSLATORS: PMs = project managers
            '1' => _('Also PMs'),
            // TRANSLATORS: %s is the site abbreviation
            '2' => sprintf(_("All %s Users"), $site_abbreviation),
            '3' => _('Publicly Visible'), ] as $val => $opt) {
            {
                $editing .= "<option value='$val' " .
                    ($existing_value == $val ? 'selected' : '') .
                    ">$opt</option>";
            }
        }
        $editing .= "</select><br>";

        $this->_show_summary_row(_('Permissions'), $editing, false);
    }

    public function save_from_post()
    {
        global $can_edit;

        $std_fields = [
            'display_name', 'full_name', 'credit',
            'ok_keep_images', 'ok_show_images', 'info_page_visibility',
            'public_comment', 'internal_comment', ];
        foreach ($std_fields as $field) {
            $this->$field = $_POST[$field];
        }

        // If the URL has no scheme, prepend http:// (unless it's empty)
        // An empty URL is possible when a source consists of physically
        // donated material in quantity and specific credit is required.
        if ($_POST['url'] == "") {
            $this->url = "";
        } else {
            $this->url = strpos($_POST['url'], '://') ? $_POST['url'] : 'http://'.$_POST['url'];
        }

        $this->code_name = strtoupper($_POST['code_name']);

        if ($this->new_source) {
            // If the user is an Image Sources Manager, then the new source
            // should default to disabled. If not, the source should default
            // to pending approval.
            $this->is_active = $can_edit ? '0' : '-1';
            // New sources shouldn't be shown on the public version of the
            // info page until they are approved.
            $this->info_page_visibility = '1' ;
        }

        $sql = sprintf(
            "
            REPLACE INTO image_sources
            SET
                code_name = LEFT('%s', 10),
                display_name = LEFT('%s', 30),
                full_name = LEFT('%s', 100),
                url = LEFT('%s', 200),
                credit = LEFT('%s', 200),
                ok_keep_images = %d,
                ok_show_images = %d,
                info_page_visibility = %d,
                public_comment = LEFT('%s', 255),
                internal_comment = '%s',
                is_active = %d
            ",
            DPDatabase::escape($this->code_name),
            DPDatabase::escape($this->display_name),
            DPDatabase::escape($this->full_name),
            DPDatabase::escape($this->url),
            DPDatabase::escape($this->credit),
            DPDatabase::escape($this->ok_keep_images),
            DPDatabase::escape($this->ok_show_images),
            DPDatabase::escape($this->info_page_visibility),
            DPDatabase::escape($this->public_comment),
            DPDatabase::escape($this->internal_comment),
            $this->is_active
        );
        DPDatabase::query($sql);
    }

    public function enable()
    {
        $this->_set_field('is_active', 1);
    }

    public function disable()
    {
        $this->_set_field('is_active', 0);
    }


    public function approve()
    {
        global $pguser, $site_abbreviation, $site_name;
        $this->_set_field('is_active', 1);
        $this->_set_field('info_page_visibility', 1);

        $notify_users = Settings::get_users_with_setting(
            'is_approval_notify',
            $this->code_name
        );

        foreach ($notify_users as $username) {
            $user = new User($username);

            $userSettings = & Settings::get_Settings($username);
            $userSettings->remove_value('is_approval_notify', $this->code_name);

            $subject = sprintf(_('%1$s: Image Source %2$s has been approved!'), $site_abbreviation, $this->display_name);

            $body = "Hello $username,\n\n" .
                "The Image Source that you proposed, $this->display_name, has been\n".
                "approved by $pguser. You can select it, and apply it to projects, from\n".
                "your project manager's page.\n";

            send_mail($user->email, $subject, $body);
        }
    }

    public function _set_field($field, $value)
    {
        $sql = sprintf(
            "
            UPDATE image_sources
            SET $field = '%s'
            WHERE code_name = '%s'
            ",
            DPDatabase::escape($value),
            DPDatabase::escape($this->code_name)
        );
        DPDatabase::query($sql);
        $this->$field = $value;
    }


    public function _show_summary_row($label, $value, $htmlspecialchars = true)
    {
        echo "  <tr>" .
            "<th class='label'>$label</th>" .
            "<td>" . ($htmlspecialchars ? html_safe($value) : $value) . "</td>" .
            "</tr>\n";
    }

    public function _get_status_cell($status, $class = '')
    {
        switch ($status) {
            case 1:
                $middle = _('Enabled');
                $open = "<td class='enabled{$class}'>";
                break;
            case 0:
                $middle = _('Disabled');
                $open = "<td class='disabled{$class}'>";
                break;
            case -1:
                $middle = _('Pending Approval');
                $open = "<td class='pending{$class}'>";
                break;
        }

        return $open . $middle . '</td>';
    }

    public function _may_maynot_unknown($value)
    {
        if ($value != '-1') {
            return ($value ? _('may') : _('may not'));
        } else {
            return "unknown";
        }
    }

    public function _showto($show_to)
    {
        global $site_abbreviation;
        switch ($show_to) {
            case '0':
                $to_whom = _("Image Managers Only");
                break;
            case '1':
                $to_whom = _("Project Managers");
                break;
            case '2':
                $to_whom = sprintf(_("Any %s User"), $site_abbreviation);
                break;
            case '3':
                $to_whom = _("All Users and Visitors");
                break;
        }
        return $to_whom;
    }

    public function log_request_for_approval($requestor_username)
    {
        global $general_help_email_addr,$image_sources_manager_addr,$code_url,$site_abbreviation,$site_name;

        $userSettings = & Settings::get_Settings($requestor_username);
        $userSettings->add_value('is_approval_notify', $this->code_name);

        $subject = sprintf(_('%s: New Image Source proposed'), $site_abbreviation)." : ".$this->display_name;

        $body = "Hello,\n\nYou are receiving this email because\n".
        "you are listed as an Image Sources manager at the $site_name\n".
        "site. If this is an error, please contact <$general_help_email_addr>.\n\n".
        "$requestor_username has proposed that $this->display_name be added\n".
        "to the list of Image Sources. To edit or approve this Image Source,\n".
        "visit\n    $code_url/tools/project_manager/manage_image_sources.php?action=show_sources#$this->code_name".
        "\n";

        send_mail($image_sources_manager_addr, $subject, $body);
    }
}

// ----------------------------------------------------------------------------

function make_link($url, $label)
{
    $start = substr($url, 0, 3);
    if ($start == 'htt') {
        return "<a href='$url'>$label</a>";
    } else {
        return "<a href='http://$url'>$label</a>";
    }
}

function show_is_toolbar($action)
{
    $pages = [
        'add_source' => _('Add New Source'),
        'show_sources' => _('List All Image Sources'),
    ];

    $toolbar_items = [];
    foreach ($pages as $new_action => $label) {
        if ($action == $new_action) {
            $item = "<b>$label</b>";
        } else {
            $item = "<a href='?action=$new_action'>$label</a>";
        }

        $toolbar_items[] = $item;
    }

    echo "<p class='center-align'>" . implode(" | ", $toolbar_items) . "</p>";
}
