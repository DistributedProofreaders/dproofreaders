<?php
include_once($relPath.'misc.inc'); // attr_safe()
include_once($relPath.'iso_lang_list.inc');
include_once($relPath.'Project.inc'); // get_project_difficulties()

// This file includes functions to handle the creation, update, and display of
// the project filters used on the Round, Project, and Smooth Reading pages.
// This includes displaying the form, processing updates made from the form,
// and getting the filter values from the user_filters table.
//
// For consistency the form field names (also $field and $fields in the
// functions) use the corresponding column name from the projects table. Hence
// the field name for the Post Processor is checkedoutby and the field for
// Project Managers is username.

class ProjectFilterElement
{
    public function __construct($id, $label, $data, $state_sql, $active_fields)
    {
        $this->id = $id;
        $this->label = $label;
        $this->active = $active_fields[$id];
        $this->data = $data;
        $this->state_sql = $state_sql;
        $this->selected_options = [];
    }

    public function echo_html_control()
    {
        if (!$this->active) {
            return;
        }
        $this->echo_active_html_control();
    }

    // Difficulty overrides this
    protected function echo_active_html_control()
    {
        echo "<td>";
        echo "<b> " . html_safe("$this->label:") . "</b><br>";
        $this->echo_selector();
        echo "</td>\n";
    }

    protected function echo_selector()
    {
        echo "<select name='{$this->id}[]' id=$this->id size='4' multiple>";
        $options = $this->get_options();
        $selected_keys = array_get($this->data, $this->id, []);
        echo_option("", _('Any'), empty($selected_keys));
        foreach ($options as $key => $value) {
            $selected = in_array($key, $selected_keys);
            if ($selected) {
                // make array of values to use in display
                $this->selected_options[] = $value;
            }
            echo_option($key, $value, $selected);
        }
        echo "</select>";
    }

    // project manager and post processor use this function, others override it
    protected function get_options()
    {
        $query = "SELECT distinct $this->id FROM projects WHERE ($this->state_sql) ORDER BY $this->id";
        $options = [];
        $result = DPDatabase::query($query);
        while ($cols = mysqli_fetch_row($result)) {
            $options[$cols[0]] = $cols[0];
        }
        return $options;
    }
}

function echo_option($key, $value, $selected)
{
    $selected_attr = $selected ? ' selected' : '';
    echo "<option value='" . attr_safe($key) . "'$selected_attr>$value</option>\n";
}

class GenreElement extends ProjectFilterElement
{
    protected function get_options()
    {
        maybe_create_temporary_genre_translation_table();
        $query = "
            SELECT distinct projects.genre, genre_translations.trans_genre
            FROM projects NATURAL JOIN genre_translations
            WHERE ($this->state_sql) ORDER BY trans_genre";
        $options = [];
        $result = DPDatabase::query($query);
        while ($cols = mysqli_fetch_row($result)) {
            $options[$cols[0]] = $cols[1];
        }
        return $options;
    }
}

class SpecialDayElement extends ProjectFilterElement
{
    protected function get_options()
    {
        $query = "
            SELECT DISTINCT special_code, display_name
            FROM projects, special_days
            WHERE projects.special_code = special_days.spec_code AND ($this->state_sql) ORDER BY display_name";
        $options = [];
        $result = DPDatabase::query($query);
        while ($cols = mysqli_fetch_row($result)) {
            $options[$cols[0]] = $cols[1];
        }
        return $options;
    }
}

class LanguageElement extends ProjectFilterElement
{
    protected function get_options()
    {
        $options = [];
        foreach (get_iso_language_list() as $name_code) {
            $lang = $name_code["lang_name"];
            $options[$lang] = $lang;
        }
        return $options;
    }

    protected function echo_active_html_control()
    {
        echo "<td>";
        echo "<b>" . html_safe("$this->label:") . "</b><br>";
        $this->echo_selector();
        echo "<br>";

        $selected_key = get_lang_match($this->data);
        $lang_options = [
            'primary' => _("Primary only"),
            'primwith' => _("Primary with any"),
            'anywhere' => _("Primary or Secondary"),
        ];
        echo "<select name='lang-match' id='lang-match'>";
        foreach ($lang_options as $key => $value) {
            echo_option($key, $value, $key == $selected_key);
        }
        echo "</select>";
        echo "</td>\n";
    }
}

class DifficultyElement extends ProjectFilterElement
{
    public function echo_active_html_control()
    {
        echo "<div class='flex_row'>";
        echo "<div class='entry'><b>" . html_safe("$this->label:") . "</b></div>";
        echo "<div class='entry'><input type='checkbox' name='difficulty[]' id='diff-all' value=''";
        $difficulty = array_get($this->data, "difficulty", []);
        if (!count($difficulty)) {
            echo " checked";
        }
        echo "> <label for='diff-all'>" . html_safe(pgettext("all difficulties", "All")) . "</label>\n";
        echo "</div>\n";
        foreach (get_project_difficulties() as $value => $option) {
            $escaped_option = html_safe($option);
            echo "<div class='entry'>";
            echo "<input type='checkbox' name='difficulty[]' class='diff-opt' id='$value' value='$value'";
            if (is_array($difficulty) && in_array($value, $difficulty)) {
                echo " checked";
                $this->selected_options[] = $option;
            }
            echo "> <label for='$value'>$escaped_option</label>\n";
            echo "</div>";
        }
        echo "</div>\n";
    }
}

function process_and_display_project_filter_form($username, $filter_type, $filter_label, $data, $state_sql, $custom_display_fields = null)
{
    $display_fields = [
        "language" => true,
        "genre" => true,
        "username" => true,
        "difficulty" => true,
        "special_code" => true,
        "checkedoutby" => false,
    ];
    // overwrite the default display state of the form fields with any
    // custom display requests
    if ($custom_display_fields && is_array($custom_display_fields)) {
        $display_fields = array_merge($display_fields, $custom_display_fields);
    }
    // if there is data save it, else get saved data
    // if they want to reset the filter, zero out the data passed in
    if (array_key_exists("reset", $data)) {
        $data = [];
        save_data($username, $filter_type, $data);
    } elseif (array_key_exists("apply", $data)) {
        unset($data["apply"]);
        // remove the 'any' options, save space,
        // for making sql we would have to exclude them if they were present
        // for controls we have to interpret nothing as any
        $new_data = [];
        foreach ($data as $category => $values) {
            if (is_array($values) && (($key = array_search('', $values)) !== false)) {
                unset($values[$key]);
            }
            $new_data[$category] = $values;
        }
        $data = $new_data;
        save_data($username, $filter_type, $data);
    } else {
        $data = get_saved_data($username, $filter_type);
    }

    $genre_element = new GenreElement("genre", _("Genre"), $data, $state_sql, $display_fields);
    $pm_element = new ProjectFilterElement("username", _("Project Manager"), $data, $state_sql, $display_fields);
    $pp_element = new ProjectFilterElement("checkedoutby", _("Post Processor"), $data, $state_sql, $display_fields);
    $special_day_element = new SpecialDayElement("special_code", _("Special Days"), $data, $state_sql, $display_fields);
    $difficulty_element = new DifficultyElement("difficulty", _("Difficulty"), $data, $state_sql, $display_fields);
    $pri_lang_element = new LanguageElement("language", _("Language"), $data, $state_sql, $display_fields);
    $elements = [
        $genre_element,
        $pm_element,
        $pp_element,
        $special_day_element,
        $pri_lang_element,
        $difficulty_element,
    ];

    echo "\n<form id='filter_form' method='post' action='#filter_form'>\n";
    echo "<fieldset><legend>" . sprintf(_("Filter available %s books by:"), $filter_label) . "</legend>\n";
    echo "<table class='filter'>\n";

    // create the row for listboxes for Language, Genre, PM, Special Days, PPers (when enabled)
    echo "<tr>\n";

    $elements_on_first_line = [$genre_element, $pm_element, $pp_element, $special_day_element, $pri_lang_element];

    foreach ($elements_on_first_line as $element) {
        $element->echo_html_control();
    }
    echo "</tr>\n";
    echo "</table>\n";

    $difficulty_element->echo_html_control();

    echo "<input type='submit' name='apply' value='", attr_safe(_("Apply Selections")), "'>";

    // construct the summary display
    $display_lines = [];
    foreach ($elements as $element) {
        if ($element->selected_options) {
            $display_lines[] = $element->label . " = " . implode("; ", $element->selected_options);
        }
    }
    // save a single line version to use in activity hub
    save_display($username, $filter_type, implode(" | ", $display_lines));

    // and show it here in the same form
    if (!empty($display_lines)) {
        echo "<fieldset style='margin-top: 1em'><legend>" . _("Current filter:") . "</legend>\n";
        echo html_safe(implode(" | ", $display_lines));
        echo "<br><input type='submit' name='reset' value='", attr_safe(_("Remove Filter")), "'>";
        echo "</fieldset>\n";
    }

    echo "</fieldset>";
    echo "</form>\n";
}

// ----- classes & functions for getting sql and display data

class ProjectSearchElement
{
    public function __construct($id, $data)
    {
        $this->id = $id;
        $this->data = $data;
    }

    public function get_sql_component()
    {
        $values = array_get($this->data, $this->id, []);
        if (empty($values)) {
            return "";
        }
        array_walk($values, function (&$value) {
            $value = DPDatabase::escape($value);
        });
        return $this->construct_sql($values);
    }

    public function construct_sql($values)
    {
        $values_list = surround_and_join($values, "'", "'", ", ");
        return " AND $this->id IN ($values_list)";
    }
}

class LanguageSearchElement extends ProjectSearchElement
{
    public function construct_sql($values)
    {
        // some languages have regex special chars which need escaping
        $values = array_map('preg_quote', $values);
        $langstring = DPDatabase::escape(implode("|", $values));
        // for primary language match strings starting with it
        // for primary only match end also
        // need to check "with" to avoid matching "French, Old" etc.
        switch (get_lang_match($this->data)) {
        case "primary":
            $prefix = "^";
            $suffix = "$";
            break;
        case "primwith":
            $prefix = "^";
            $suffix = "($| with.*)";
            break;
        case "anywhere":
        default:
            $prefix = ".*";
            $suffix = "($| with.*)";
            break;
        }
        return " AND language REGEXP '$prefix($langstring)$suffix'";
    }
}

function get_project_filter_sql($pguser, $filter_type)
{
    $data = get_saved_data($pguser, $filter_type);
    $search_elements = [
        new ProjectSearchElement("genre", $data),
        new ProjectSearchElement("username", $data),
        new ProjectSearchElement("checkedoutby", $data),
        new ProjectSearchElement("special_code", $data),
        new ProjectSearchElement("difficulty", $data),
        new LanguageSearchElement("language", $data),
    ];

    $filter = "";
    foreach ($search_elements as $element) {
        $filter .= $element->get_sql_component();
    }
    return $filter;
}

function get_lang_match($data)
{
    // set a default if not set
    return array_get($data, "lang-match", "primwith");
}

function save_data($pguser, $filter_type, $data)
{
    save_raw_data($pguser, "{$filter_type}_data", serialize($data));
}

function save_display($pguser, $filter_type, $data)
{
    save_raw_data($pguser, "{$filter_type}_display", $data);
}

function save_raw_data($pguser, $data_name, $data)
{
    $query = sprintf("
        REPLACE INTO user_filters
        (username, filtertype, value)
        VALUES ('%s', '%s', '%s')",
        DPDatabase::escape($pguser),
        DPDatabase::escape($data_name),
        DPDatabase::escape($data)
    );
    DPDatabase::query($query);
}

function get_saved_data($pguser, $filter_type)
{
    return unserialize(get_raw_saved_data($pguser, "{$filter_type}_data"));
}

function get_project_filter_display($pguser, $filter_type)
{
    return get_raw_saved_data($pguser, "{$filter_type}_display");
}

function get_raw_saved_data($pguser, $data_name)
{
    $query = sprintf("
        SELECT value
        FROM user_filters
        WHERE username = '%s'
            AND filtertype = '%s'",
        DPDatabase::escape($pguser),
        DPDatabase::escape($data_name)
    );
    $result = DPDatabase::query($query);
    $row = mysqli_fetch_assoc($result);
    return $row ? $row["value"] : "";
}
