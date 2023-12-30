<?php
$relPath = './../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'metarefresh.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'special_colors.inc');

require_login();

if (!user_is_a_sitemanager()) {
    die(_("You are not authorized to invoke this script."));
}

$page_url = "$code_url/tools/site_admin/manage_special_days.php";

$action = get_enumerated_param(
    $_REQUEST,
    'action',
    'show_specials',
    ['show_specials', 'add_special', 'update_oneshot']
);

// Action 'update_oneshot' is used as a target for form submit buttons. The
// desired action is based on the submit button's name. Here we do the action
// and set $action to 'show_specials' to display the list all in one page load.
if ($action == 'update_oneshot') {
    global $page_url;
    if (isset($_POST['edit'])) {
        $action = 'edit_source';
    } elseif (isset($_POST['save_edits'])) {
        // This handles both edits to existing special days, and the creation of new ones

        $errmsgs = '';

        $spec_code = trim($_POST['spec_code']);

        $source = new SpecialDay($spec_code);

        if (!isset($_POST['editing'])) {
            // we are creating a new special day
            if (!$source->new_source) {
                $errmsgs .= _('A Special Day with this ID already exists. Please choose a different ID for this event.') . "<br>";
            } else {
                if ((strlen($spec_code) < 1) || (preg_match("/[^A-Za-z0-9. -]/", $spec_code) === 1)) {
                    $errmsgs .= _("Please enter a Special Day Code. Allowable characters are alphanumeric characters and the period, hyphen or space.") . "<br>";
                } elseif ((startswithnocase($spec_code, "Otherday")) || (startswithnocase($spec_code, "Birthday"))) {
                    $errmsgs .= _('The ID cannot start with "Otherday" or "Birthday".') . "<br>";
                }
            }
        }

        // validate the numeric fields
        $numeric_fields = [
            "open_month" => _("Open Month"),
            "open_day" => _("Open Day"),
            "close_month" => _("Close Month"),
            "close_day" => _("Close Day"),
        ];
        foreach ($numeric_fields as $field => $string) {
            if ($_POST[$field] == '' || !ctype_digit($_POST[$field])) {
                $errmsgs .= sprintf(
                    _("Field %s does not contain a valid number."),
                    $string
                ) . "<br>";
            }
        }

        // validate the URLs
        if ($_POST['info_url'] && !startswith($_POST['info_url'], "http")) {
            $errmsgs .= _("Info URL is not a valid URL -- ensure it starts with http:// or https://.") . "<br>";
        }

        if ($_POST['image_url'] && !startswith($_POST['image_url'], "http")) {
            $errmsgs .= _("Image URL is not a valid URL -- ensure it starts with http:// or https://.") . "<br>";
        }

        $source->save_from_post();

        // Redisplay at the entry just added or edited
        metarefresh(0, "$page_url#" . urlencode($spec_code));
    }
}

if ($action == 'show_specials') {
    $title = _('Manage Special Days');
    output_header($title, NO_STATSBAR);
    echo "<h1>", html_safe($title), "</h1>\n";

    show_sd_toolbar($action);

    $sql = "
        SELECT spec_code
        FROM special_days
        ORDER BY open_month, open_day
    ";
    $result = DPDatabase::query($sql);
    echo "<br>\n\n";
    echo "<table class='list_special_days'>\n";
    $count = 0;
    $current_month = -1;
    while ([$source_name] = mysqli_fetch_row($result)) {
        $count++;
        $source = new SpecialDay($source_name);
        $current_month = $source->show_listing_row($count, $current_month);
    }
    echo "</table>";
    echo "<br>";
} elseif ($action == 'edit_source') {
    $source = new SpecialDay($_POST['source']);
    $headertext = sprintf(_("Editing Special Day: %s"), $source->display_name);
    output_header($headertext, NO_STATSBAR);
    show_sd_toolbar($action);
    echo "<h1>" . $headertext . "</h1>\n";
    $source->show_edit_form();
} elseif ($action == 'add_special') {
    $headertext = _('Add a new Special Day');
    output_header($headertext, NO_STATSBAR);
    show_sd_toolbar($action);
    echo "<h1>" . $headertext . "</h1>\n";
    $blank = new SpecialDay(null);
    $blank->show_edit_form();
}

// ----------------------------------------------------------------------------

class SpecialDay
{
    public function __construct($spec_code = null)
    {
        $this->new_source = true;
        $this->enable = 0;

        if (!is_null($spec_code)) {
            $sql = sprintf(
                "
                SELECT *
                FROM special_days
                WHERE spec_code = '%s'
                ",
                DPDatabase::escape($spec_code)
            );
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

    public function show_listing_row($count, $current_month)
    {
        global $page_url;
        $sid = html_safe($this->spec_code);
        $usid = urlencode($sid);

        if ($count % 2 == 1) {
            $row_class = "o";
        } else {
            $row_class = "e";
        }

        // Calculate how many rows this listing will have so we can span them
        // for some columns
        $listing_rows = 4;
        if ($this->date_changes) {
            $listing_rows++;
        }

        // Output a new month header when listing month differs from previous one
        if (($current_month < 0) && ($current_month != $this->open_month)) {
            echo "<tr class='month'><td colspan='9'><h2>";
            echo _("Undated Entries") ."</h2></td></tr>";
            output_table_headers();
        } elseif ($this->open_month != $current_month) {
            echo "<tr class='month'><td colspan='9'><h2>";
            echo icu_date("MMMM", mktime(0, 0, 0, $this->open_month, 10));
            echo "</h2></td></tr>";
            output_table_headers();
        }

        echo "\n\n<tr class='$row_class' id='$usid'>";
        echo "<td class='codecell' rowspan='$listing_rows'>";
        echo $sid . "\n";
        echo "<form method='post' action='$page_url#$usid '>\n";
        echo "  <input type='hidden' name='action' value='update_oneshot'>\n";
        echo "  <input type='hidden' name='source' value='$sid'>\n";
        $this->show_buttons();
        echo "</form>\n";
        echo "</td>\n";
        echo "<td>" . html_safe($this->display_name) . "</td>";
        [$style, $cell] = get_special_day_cell_parts((array)$this);
        echo "<td style='$style'>$cell</td>";
        echo "<td>" . $this->color . "</td>\n";
        echo $this->_get_status_cell($this->enable, ' pb') . "\n";
        echo "<td class='right'>" . $this->open_month . "</td>";
        echo "<td class='right'>" . $this->open_day . "</td>";
        echo "<td class='right'>" . $this->close_month . "</td>";
        echo "<td class='right'>" . $this->close_day . "</td>";
        echo "</tr>\n";

        echo "<tr class='$row_class'>";
        echo "<th class='right'>" . _("Info URL") . ":</th><td colspan='7'>" . make_link($this->info_url, $this->info_url) . "</td>";
        echo "</tr>";
        echo "<tr class='$row_class'>";
        echo "<th class='right'>" . _("Image URL") . ":</th><td colspan='7'>" . make_link($this->image_url, $this->image_url) . "</td>";
        echo "</tr>\n";

        if ($this->date_changes) {
            echo "<tr class='$row_class'>";
            echo "<th class='right'>" . _("Date Changes") . ":</th><td colspan='7'>" . html_safe($this->date_changes) . "</td>";
            echo "</tr>\n";
        }

        echo "<tr class='$row_class'>";
        echo "<th class='right'>" . _("Comments") . ":</th><td colspan='7'>" . html_safe($this->comment) . "</td>";
        echo "</tr>\n";

        return($this->open_month);
    }

    public function show_buttons()
    {
        echo "<input type='submit' name='edit' value='".attr_safe(_('Edit'))."'>\n";
    }

    public function show_edit_form()
    {
        global $page_url;
        echo "<form method='post' action='$page_url'>
        <input type='hidden' name='action' value='update_oneshot'>\n";


        if ($this->new_source) {
            echo "<table class='edit_special_day'>";
            $this->_show_edit_row('spec_code', _('Special Day ID'), 'text', 20);
        } else {
            echo "<input type='hidden' name='editing' value='true'>" .
                "<input type='hidden' name='spec_code' value='" . attr_safe($this->spec_code) ."'>
                <table class='edit_special_day'>";
            $this->_show_summary_row(_('Special Day ID'), $this->spec_code);
        }
        $this->_show_edit_row('display_name', _('Display Name'), 'text', 80);
        $this->_show_edit_row('symbol', _('Symbol'), 'text', 4);
        echo "  <tr><th class='label'>Enable</th><td><input type='checkbox' name='enable'";
        if ($this->enable) {
            echo " value='1' checked";
        }
        echo "></td></tr>\n";
        $this->_show_edit_row('comment', _('Comment'), 'textarea');
        $this->_show_edit_row('color', _('Color'), 'text', 8);
        $this->_show_edit_row('open_month', _('Open Month'), 'number', null, 1, 12);
        $this->_show_edit_row('open_day', _('Open Day'), 'number', null, 1, 31);
        $this->_show_edit_row('close_month', _('Close Month'), 'number', null, 1, 12);
        $this->_show_edit_row('close_day', _('Close Day'), 'number', null, 1, 31);
        $this->_show_edit_row('date_changes', _('Date Changes'));
        $this->_show_edit_row('info_url', _('Info URL'));
        $this->_show_edit_row('image_url', _('Image URL'));

        echo "<tr><td colspan='2' style='text-align:center;'>
            <input type='submit' name='save_edits' value='".attr_safe(_('Save'))."'>
            </td></tr></table>\n</form>\n";
    }

    public function _show_edit_row($field, $label, $type = 'text', $maxlength = null, $min = null, $max = null)
    {
        $value = $this->new_source
            ? (empty($_POST[$field]) ? '' : $_POST[$field])
            : $this->$field;

        $value = html_safe($value);

        if ($type == "textarea") {
            $editing = "<textarea style='width: 40em; height: 5em' name='$field'>$value</textarea>";
        } elseif ($type == "text") {
            $maxlength_attr = is_null($maxlength) ? '' : "maxlength='$maxlength'";
            $editing = "<input type='text' style='width: 40em' name='$field' value='$value' $maxlength_attr>";
        } elseif ($type == "number") {
            $min_attr = is_null($min) ? '' : "min='$min'";
            $max_attr = is_null($max) ? '' : "max='$max'";
            $editing = "<input type='number' style='width: 4em' name='$field' size='60' value='$value' $min_attr $max_attr>";
        }
        $addl_data = '';
        if ($field == "symbol") {
            $addl_data = "<br>" . sprintf(_("See the full list of emojis in <a href='%s'>the Unicode standard</a>."), "http://unicode.org/emoji/charts/full-emoji-list.html");
        } elseif ($field == "spec_code") {
            $addl_data = "<br>" . _("This string is used as the primary key in the DB and cannot be changed after created.");
        }

        echo "  <tr>" .
            "<th class='label'>$label</th>" .
            "<td>$editing$addl_data</td>" .
            "</tr>\n";
    }

    public function save_from_post()
    {
        global $errmsgs;
        $std_fields = ['display_name', 'enable', 'comment',
            'color', 'open_day', 'open_month', 'close_day',
            'close_month', 'date_changes', 'symbol', ];
        $std_fields_sql = [];
        foreach ($std_fields as $field) {
            switch ($field) {
                case 'enable':
                    if (!isset($_POST[$field])) {
                        $this->$field = 0;
                    } else {
                        $this->$field = 1;
                    }
                    break;
                default:
                    $this->$field = $_POST[$field];
            }
            $std_fields_sql[] = set_col_str($field, $this->$field);
        }
        $std_fields_sql = join(",\n", $std_fields_sql);

        if ($this->new_source) {
            $this->spec_code = $_POST['spec_code'];
        }

        // Set URLs separately. If the URL has no scheme, prepend http:// (but only if not empty)
        $this->info_url = $_POST['info_url'];
        if ($this->info_url != '') {
            $this->info_url = strpos($_POST['info_url'], '://') ? $_POST['info_url'] : 'http://'.$_POST['info_url'];
        }

        $this->image_url = $_POST['image_url'];
        if ($this->image_url != '') {
            $this->image_url = strpos($_POST['image_url'], '://') ? $_POST['image_url'] : 'http://'.$_POST['image_url'];
        }

        if ($errmsgs) {
            output_header('', NO_STATSBAR);
            echo "<p class='error bold'>" . $errmsgs . "</p>";
            $this->show_edit_form();
            die;
        }

        $setters = join(", ", [
            set_col_str("spec_code", $this->spec_code),
            set_col_str("info_url", $this->info_url),
            set_col_str("image_url", $this->image_url),
        ]);
        $sql = "
            REPLACE INTO special_days
            SET
                $std_fields_sql,
                $setters
        ";
        DPDatabase::query($sql);
    }

    public function _set_field($field, $value)
    {
        $sql = sprintf(
            "
            UPDATE special_days
            SET $field = '%s'
            WHERE spec_code = '%s'
            ",
            DPDatabase::escape($value),
            DPDatabase::escape($this->spec_code)
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
            default:
                $middle = _('Invalid');
                $open = "<td class='disabled{$class}'>";
                break;
        }
        return $open . $middle . '</td>';
    }
}

// ----------------------------------------------------------------------------

function make_link($url, $label)
{
    if (!$url) {
        return '';
    }
    $start = substr($url, 0, 3);
    $label = html_safe($label);
    if ($start != 'htt') {
        $url = "http://" . $url;
    }
    return "<a href='". attr_safe($url). "'>$label</a>";
}

function show_sd_toolbar($action)
{
    $pages = [
        'add_special' => _('Add New Special Day'),
        'show_specials' => _('List All Special Days'),
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

    echo "<p style='text-align: center; margin: 5px 0 5px 0;'>" . implode(" | ", $toolbar_items) . "</p>";
}

function output_table_headers()
{
    echo "<tr>";
    echo "<th class='headers'>" . _("Special Day Code") . "</th>";
    echo "<th class='headers'>" . _("Display Name") . "</th>";
    echo "<th class='headers'>" . _("Symbol") . "</th>";
    echo "<th class='headers'>" . _("Color") . "</th>";
    echo "<th class='headers'>" . _("Enable") . "</th>";
    echo "<th class='headers'>" . _("Open Month") . "</th>";
    echo "<th class='headers'>" . _("Open Day") . "</th>";
    echo "<th class='headers'>" . _("Close Month") . "</th>";
    echo "<th class='headers'>" . _("Close Day") . "</th>";
    echo "</tr>";
}
