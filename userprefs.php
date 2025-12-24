<?php
$relPath = "./pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'metarefresh.inc');
include_once($relPath.'prefs_options.inc');
include_once($relPath.'languages.inc'); // bilingual_name()
include_once($relPath.'theme.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'Settings.inc');
include_once($relPath.'pophelp.inc');
include_once($relPath.'forum_interface.inc'); // get_forum_user_details(), get_url_to_edit_profile()
include_once($relPath.'User.inc');
include_once($relPath.'Project.inc');

require_login();

// The url the user viewed immediately before coming to the preferences.
// Not all browsers provide this, though.
// If the user came to userprefs.php by entering the URL manually,
// $origin will be uninitialized, in which case it could be set
// to ".../userprefs.php..." at the next calls. Avoid this by setting
// the fallback origin to the Activity Hub.
$origin = $_REQUEST["origin"] ?? "";
if (empty($origin)) {
    if (array_key_exists('HTTP_REFERER', $_SERVER)
        && !str_starts_with($_SERVER['HTTP_REFERER'], "$code_url/userprefs.php")) {
        $origin = $_SERVER['HTTP_REFERER'];
    } else {
        $origin = "$code_url/activity_hub.php";
    }
} else {
    $origin = urldecode($origin);
}
// From now on, keep the value of $origin through the browsing of tabs, saving prefs, etc.

// Define the available tabs.
// The indexes of the array are used elsewhere in this script.
$tabs = [
    0 => [
        "label" => _('General'),
    ],
];
if (user_is_PM()) {
    $tabs[1] = [
        "label" => _('Project managing'),
    ];
}

$selected_tab = get_integer_param($_REQUEST, "tab", 0, 0, max(array_keys($tabs)));

$user = User::load_current();
$userSettings = & Settings::get_Settings($user->username);

//just a way to get them back to someplace on quit button
if (isset($_POST["quitnc"])) {
    metarefresh(0, $origin, _("Quit"));
}

if (($_POST["insertdb"] ?? "") != "") {
    // one of the tabs was displayed and now it has been posted
    // determine which and let that tab save 'itself'.

    validate_csrf_token();

    if ($selected_tab == 0) {
        save_general_tab($user);
    } elseif ($selected_tab == 1) {
        save_pm_tab($user);
    }

    if (isset($_POST["saveAndQuit"])) {
        // Quit immediately after saving
        metarefresh(0, $origin, _("Quit"));
    } else {
        // Show the same tab that was just saved
        $url = "$code_url/userprefs.php?tab=$selected_tab&amp;origin=" . urlencode($origin);
        metarefresh(0, $url, _('Saving preferences'), _('Reloading current tab....'));
    }
}

// header, start of table, form, etc. common to all tabs
$header = _("Personal Preferences");
$theme_extra_args["js_files"] = [
    "$code_url/scripts/userprefs.js",
    "$code_url/scripts/pophelp.js",
];
$theme_extra_args["js_data"] = "
    var popHelpData = " . get_pophelp_json("prefs") . ";
";

set_csrf_token();
output_header($header, NO_STATSBAR, $theme_extra_args);
echo "<h1>$header</h1>";

output_tab_bar($tabs, $selected_tab, "tab", "origin=" . urlencode($origin));

echo "<p>" . _("Hover over a setting to get more information about it.") . "</p>";

echo "<form action='userprefs.php' method='post'>";

// Output CSRF token
echo_csrf_token_form_input();

echo "<input type='hidden' name='tab' value='$selected_tab'>";
// Keep remembering the URL from which the preferences where entered.
echo "<input type='hidden' name='origin' value='".attr_safe($origin)."'>\n";
echo "<input type='hidden' name='insertdb' value='true'>";

echo "<table class='preferences'>";

// display one of the tabs

if ($selected_tab == 1 && user_is_PM()) {
    echo_pm_tab($user);
} else { // $selected _tab == 0 OR someone tried to access e.g. the PM-tab without being a PM.
    echo_general_tab($user);
}

echo "</table></form>\n";
echo "<br>";

// End main code. Functions below.
// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX


function echo_general_tab(User $user): void
{
    global $userSettings;

    $options = get_locale_translation_selection_options();

    $u_intlang_options[""] = BROWSER_DEFAULT_STR;
    $u_intlang_options = array_merge($u_intlang_options, $options);

    $i_stats_privacy = [
        PRIVACY_ANONYMOUS => _("Anonymous"),
        PRIVACY_PRIVATE => _("Private"),
    ];

    echo "<tr>\n";
    show_preference(
        _('Name'),
        'real_name',
        'name',
        $user->real_name,
        'textfield',
        ['100%', 'required', '']
        // About 98% of pgdp.net's users have length(real_name) <= 20
    );
    show_preference(
        _('Interface Language'),
        'u_intlang',
        'intlang',
        $user->u_intlang,
        'dropdown',
        $u_intlang_options
    );
    echo "</tr>\n";

    // Check for DP/forum email mismatch, warn user if not the same
    $bb_user_info = get_forum_user_details($user->username);
    $email_warning = '';
    if ($bb_user_info["email"] != $user->email) {
        $edit_url = get_url_to_edit_profile();
        $email_warning = "<p><b>".sprintf(_("Warning: The email in your <a href='%s'>forum profile</a> is different."), $edit_url)."</b><br>";
        $email_warning .= _("Please update if necessary to ensure you receive messages as intended.")."</p>\n";
    }

    echo "<tr>\n";
    show_preference(
        _('E-mail'),
        'email',
        'email',
        $user->email,
        'emailfield',
        ['100%', 'required', $email_warning]
    );
    $theme_options = [];
    $result = DPDatabase::query("SELECT * FROM themes");
    while ($row = mysqli_fetch_array($result)) {
        $option_value = $row['unixname'];
        $option_label = $row['name'];
        $theme_options[$option_value] = $option_label;
    }
    show_preference(
        _('Theme'),
        'i_theme',
        'theme',
        $user->i_theme,
        'dropdown',
        $theme_options
    );
    echo "</tr>\n";

    echo "<tr>\n";
    show_preference(
        _('E-mail Updates'),
        'email_updates',
        'updates',
        $user->email_updates,
        'radio_group',
        [1 => _("Yes"), 0 => _("No")]
    );
    $navbar_options = [
        0 => _("Always collapse"),
        1 => _("Auto collapse"),
        2 => _("Never collapse"),
    ];
    show_preference(
        _('My Activities Menu'),
        'navbar_activity_menu',
        'navbar_activity_menu',
        $user->navbar_activity_menu,
        'dropdown',
        $navbar_options
    );
    echo "</tr>\n";

    echo "<tr>\n";
    show_link_as_if_preference(
        _('Password'),
        'password',
        get_reset_password_url(),
        _("Reset Password")
    );
    show_preference(
        _('Statistics Bar Alignment'),
        'u_align',
        'align',
        $user->u_align,
        'radio_group',
        [1 => _("Left"), 0 => _("Right")]
    );
    echo "</tr>\n";

    echo "<tr>\n";
    show_preference(
        _('Statistics'),
        'u_privacy',
        'privacy',
        $user->u_privacy,
        'dropdown',
        $i_stats_privacy
    );
    show_preference(
        _('Show Rank Neighbors'),
        'u_neigh',
        'neighbors',
        $user->u_neigh,
        'dropdown',
        get_rank_neighbor_options()
    );
    echo "</tr>\n";

    echo "<tr>\n";
    show_preference(
        _('Credits Wanted'),
        null,
        'creditswanted',
        null,
        'credits_wanted_adhoc',
        null
    );
    // About 'show'/'hide': It seems better to present to the user the option
    // 'show', rather than 'hide' since 'hide: no' seems double-negated (to me).
    $show_special_colors = !$userSettings->get_boolean('hide_special_colors');
    show_preference(
        _('Show Special Colors'),
        'show_special_colors',
        'showspecialcolors',
        ($show_special_colors ? 'yes' : 'no'),
        'dropdown',
        ['yes' => _('Yes'), 'no' => _('No')]
    );
    echo "</tr>\n";

    echo "<tr>\n";
    show_preference(
        _('Credit Name'),
        null,
        'creditname',
        null,
        'credit_name_adhoc',
        null
    );

    foreach (get_project_detail_levels() as $level => $label) {
        // We don't allow users to select level 1 and 4 because:
        // * Level 1 doesn't include Start Proofreading and will confuse people
        // * Level 4 includes the page detail which is resource intensive
        //   and can produce a Very Large Page (>35MB)
        if ($level == 1 || $level == 4) {
            continue;
        }

        $page_detail_options[$level] = sprintf("%s (%d)", $label, $level);
    }
    show_preference(
        _('Default Project Detail Level'),
        'project_detail',
        'project_detail',
        $userSettings->get_value('project_detail', 2),
        'dropdown',
        $page_detail_options
    );
    echo "</tr>\n";

    echo_bottom_button_row();
}

function save_general_tab(User $user): void
{
    global $userSettings;

    // set users values
    $input_string_fields = ["real_name", "email", "email_updates", "i_theme", "u_intlang"];
    $input_numeric_fields = ["u_align", "u_neigh", "u_privacy", "navbar_activity_menu"];

    // pull only specific data out of $_POST
    $data = [];
    foreach (array_merge($input_string_fields, $input_numeric_fields) as $field) {
        $data[$field] = $_POST[$field];
    }

    $update_string = create_mysql_update_string($data, $input_string_fields);

    $users_query = "
        UPDATE users
        SET $update_string
        WHERE u_id = $user->u_id
    ";
    DPDatabase::query($users_query);

    // Opt-out of credits when Content-Providing (deprecated), Image Preparing,
    // Text Preparing, Project-Managing and/or Post-Processing.
    $userSettings->set_boolean('cp_anonymous', !isset($_POST["cp_credit"]));
    $userSettings->set_boolean('ip_anonymous', !isset($_POST["ip_credit"]));
    $userSettings->set_boolean('tp_anonymous', !isset($_POST["tp_credit"]));
    $userSettings->set_boolean('pm_anonymous', !isset($_POST["pm_credit"]));
    $userSettings->set_boolean('pp_anonymous', !isset($_POST["pp_credit"]));
    // Credit Real Name, Username or Other (specify)
    $userSettings->set_value('credit_name', $_POST["credit_name"]);
    if (isset($_POST["credit_other"])) {
        $userSettings->set_value('credit_other', $_POST["credit_other"]);
    }

    $userSettings->set_boolean('hide_special_colors', $_POST["show_special_colors"] == 'no');

    $userSettings->set_value('project_detail', $_POST["project_detail"]);
}

function echo_pm_tab(User $user): void
{
    global $userSettings;

    $pm_view_options = [
        "user_all" => _("All Projects"),
        "user_avail" => _("Available Projects"),
        "user_active" => _("Active Projects"),
        "blank" => _("Basic Page"),
    ];

    echo "<tr>\n";
    show_preference(
        // TRANSLATORS: PM = project manager
        _('Default PM Page'),
        'pm_view',
        'pmdefault',
        $userSettings->get_value('pm_view', "user_all"),
        'dropdown',
        $pm_view_options
    );
    echo "</tr>\n";

    $auto_proj_thread = $userSettings->get_boolean('auto_proj_thread');

    echo "<tr>\n";
    show_preference(
        _('Automatically watch your project threads'),
        'auto_proj_thread',
        'auto_thread',
        ($auto_proj_thread ? 'yes' : 'no'),
        'dropdown',
        ['yes' => _('Yes'), 'no' => _('No')]
    );
    echo "</tr>\n";

    $send_to_post = $userSettings->get_boolean('send_to_post');

    echo "<tr>\n";
    show_preference(
        _('Automatically send your projects to the post-processing pool'),
        'send_to_post',
        'pmto_post',
        ($send_to_post ? 'yes' : 'no'),
        'dropdown',
        ['yes' => _('Yes'), 'no' => _('No')]
    );
    echo "</tr>\n";

    echo_bottom_button_row();
}

function save_pm_tab(User $user): void
{
    global $userSettings;

    // persist the default view for the PM's page

    $userSettings->set_value('pm_view', $_POST["pm_view"]);

    // remember if the PM wants to be automatically signed up for email notifications of
    // replies made to their project threads

    $userSettings->set_boolean('auto_proj_thread', $_POST["auto_proj_thread"] == 'yes');

    // remember if the PM wants to have their projects automatically assigned
    // to them for PP

    $userSettings->set_boolean('send_to_post', $_POST["send_to_post"] == 'yes');
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function echo_bottom_button_row(): void
{
    echo "<tr><td colspan='6' class='center-align'>";
    echo "<input type='submit' value='".attr_safe(_("Save Preferences"))."' name='change'> &nbsp;";
    echo "<input type='submit' value='".attr_safe(_("Save Preferences and Quit"))."' name='saveAndQuit'> &nbsp;";
    echo "<input type='submit' value='".attr_safe(_("Quit"))."' name='quitnc'>";
    echo "</td></tr>\n";
}

// ---------------------------------------------------------

/**
 * Shows a single user preference of a specific type (e.g. dropdown or font selection)
 *
 * @param mixed $current_value
 * @param mixed $extras
 */
function show_preference(
    string $label,
    ?string $field_name,
    string $pophelp_name,
    $current_value,
    string $type,
    // Extras is untyped as it varies based on $type.
    $extras
): void {
    th_label($label, $pophelp_name);

    echo "<td>";
    // This is a bit sneaky, calling a function via a non-static name.
    // (Be careful if you want to rename a function whose name starts with '_show_'.)
    $function_name = '_show_' . $type;
    $function_name($field_name, $current_value, $extras);
    echo "</td>";
}

// ---------------------------------------------------------

function _show_credits_wanted_adhoc(): void
{
    global $userSettings;

    $cp_credit_checked = $userSettings->get_boolean('cp_anonymous') ? '' : 'checked ';
    $ip_credit_checked = $userSettings->get_boolean('ip_anonymous') ? '' : 'checked ';
    $tp_credit_checked = $userSettings->get_boolean('tp_anonymous') ? '' : 'checked ';
    $pm_credit_checked = $userSettings->get_boolean('pm_anonymous') ? '' : 'checked ';
    $pp_credit_checked = $userSettings->get_boolean('pp_anonymous') ? '' : 'checked ';

    echo "<input type='checkbox' name='cp_credit' class='credit_box' value='yes' $cp_credit_checked> CP\n";
    echo "<input type='checkbox' name='ip_credit' class='credit_box' value='yes' $ip_credit_checked> IP\n";
    echo "<input type='checkbox' name='tp_credit' class='credit_box' value='yes' $tp_credit_checked> TP\n";
    if (user_is_PM()) {
        echo "<input type='checkbox' name='pm_credit' class='credit_box' value='yes' $pm_credit_checked> PM\n";
    }
    echo "<input type='checkbox' name='pp_credit' class='credit_box' value='yes' $pp_credit_checked> PP\n";

    echo "<br>";
    echo "<button type='button' class='lean_button' id='check_all'>Check all</button>";
    echo " | ";
    echo "<button type='button' class='lean_button' id='un_check_all'>Uncheck all</button>";
}

// ---------------------------------------------------------

// Handles 'credit_name' and 'credit_other'.
function _show_credit_name_adhoc(): void
{
    global $userSettings;

    $credit_options = [
        'real_name' => _('Real Name'),
        'username' => _('Username'),
        'other' => _('Other') . ':',
    ];

    $credit_name_value = $userSettings->get_value('credit_name', 'real_name');

    _show_dropdown('credit_name', $credit_name_value, $credit_options);

    $credit_other_value = attr_safe($userSettings->get_value('credit_other', ''));
    echo " <input type='text' name='credit_other' id='credit_other' value='$credit_other_value'>\n";
}

// ---------------------------------------------------------

/** @param array<string|int, string> $options */
function _show_dropdown(string $field_name, string $current_value, array $options): void
{
    echo "<select name='$field_name' ID='$field_name'>";
    foreach ($options as $option_value => $option_label) {
        echo "<option value='$option_value'";
        if ($option_value == $current_value) {
            echo " SELECTED";
        }
        echo ">$option_label</option>";
    }
    echo "</select>";
}

/** @param array<int, string> $options */
function _show_radio_group(string $field_name, string $current_value, array $options): void
{
    foreach ($options as $option_value => $option_label) {
        echo "<input type='radio' name='$field_name' value='$option_value'";
        if (strtolower($option_value) == strtolower($current_value)) {
            echo " CHECKED";
        }
        echo ">&nbsp;$option_label &nbsp;";
    }
}

/** @param string[] $extras */
function _show_text_input_field(string $field_type, string $field_name, string $current_value, array $extras): void
{
    [$size, $required, $rest] = $extras;
    $current_value_esc = attr_safe($current_value);
    if ($field_type == "number") {
        $min_attr = "min='1'";
    } else {
        $min_attr = "";
    }
    echo "<input type='$field_type' style='width: {$size}' name='$field_name' value='$current_value_esc' $min_attr $required>$rest";
}

/** @param string[] $extras */
function _show_textfield(string $field_name, string $current_value, array $extras): void
{
    _show_text_input_field('text', $field_name, $current_value, $extras);
}

/** @param string[] $extras */
function _show_emailfield(string $field_name, string $current_value, array $extras): void
{
    _show_text_input_Field('email', $field_name, $current_value, $extras);
}

/** @param string[] $extras */
function _show_numberfield(string $field_name, string $current_value, array $extras): void
{
    _show_text_input_field('number', $field_name, $current_value, $extras);
}

// ---------------------------------------------------------

function show_link_as_if_preference(
    string $label,
    string $pophelp_name,
    string $link_url,
    string $link_text
): void {
    th_label($label, $pophelp_name);
    echo "<td>";
    echo "<a href='$link_url'>$link_text</a>";
    echo "</td>";
}

function show_blank(): void
{
    th_label("&nbsp;");
    echo "<td>&nbsp;</td>";
    echo "<td>&nbsp;</td>\n";
}

// ---------------------------------------------------------

function th_label(string $label, string $pophelp_name = ""): void
{
    $pophelp_attr = $pophelp_name ? "data-pophelp='set_$pophelp_name'" : "";
    echo "<th class='label' $pophelp_attr>$label</th>";
}

function th_label_long(int $colspan, string $label, string $pophelp_name = ""): void
{
    $pophelp_attr = $pophelp_name ? "data-pophelp='set_$pophelp_name'" : "";
    echo "<th class='longlabel' colspan='$colspan' $pophelp_attr>$label</th>";
}
