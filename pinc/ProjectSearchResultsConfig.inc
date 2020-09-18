<?php
include_once($relPath.'metarefresh.inc');

class Selector
{
    function echo_select_item()
    {
        echo "
            <tr>
                <th class='right-align top-align'>$this->label</th>
                <td class='top-align'>", $this->get_html_control(), "</td>
            </tr>
        ";
    }
}

class ColumnSelector extends Selector
{
    public function __construct($column, $userSettings, $search_origin)
    {
        $this->column = $column;
        $this->label = $column->get_long_label();

        $this->userSettings = $userSettings;
        $this->search_origin = $search_origin;
    }

    function get_html_control()
    {
        $check = $this->column->is_active() ? " checked" : "";
        return "<input type='checkbox' name='{$this->column->id}'$check>";
    }
}

class OptionSelector extends Selector
{
    public function __construct($id, $label, $value, $options, $userSettings, $search_origin)
    {
        $this->id = $id;
        $this->label = $label;
        $this->def_value = $value;
        $this->options = $options;

        $this->userSettings = $userSettings;
        $this->search_origin = $search_origin;
    }

    function get_html_control()
    {
        echo "<select name='$this->id'>\n";
        $selected_item = $this->get_value();
        foreach ( $this->options as $option_value => $option_label )
        {
            $selected_attr = ($option_value == $selected_item) ? 'selected' : '';
            echo "<option value='", attr_safe($option_value), "' $selected_attr>", html_safe($option_label), "</option>\n";
        }
        echo "</select>\n";
    }

    function get_value()
    {
        $value = $this->userSettings->get_value("search:{$this->search_origin}.option:$this->id", $this->def_value);

        // Validate the value in the DB is one of the options and if not pick
        // the first one
        if(!isset($this->options[$value]))
        {
            $options = array_keys($this->options);
            $value = array_shift($options);
        }

        return $value;
    }

    function save_value($value)
    {
        // Save the value but only if it has changed and is a valid option
        if($this->get_value() != $value && isset($this->options[$value]))
        {
            $this->userSettings->set_value("search:{$this->search_origin}.option:$this->id", $value);
        }
    }
}

class OptionData
{
    public function __construct($userSettings, $search_origin)
    {
        $this->results_per_page = new OptionSelector(
            'n_results_per_page',
            _('Results per page'),
            100,
            array( 30 => 30, 100 => 100, 300 => 300 ),
            $userSettings,
            $search_origin
        );

        $this->time_format = new OptionSelector(
            'time_form',
            _("Time format"),
            'days',
            array('days' => _('Days ago'), 'date' => _('Date')),
            $userSettings,
            $search_origin
        );

        $this->options = array($this->results_per_page, $this->time_format);
    }
}

class ConfigForm extends ColumnData
{
    public function __construct($userSettings, $search_origin)
    {
        parent::__construct($userSettings, $search_origin);
        foreach($this->columns as $column)
            $this->column_selectors[] = new ColumnSelector($column, $userSettings, $search_origin);

        $this->option_data = new OptionData($userSettings, $search_origin);
    }

    public function render($page_title)
    {
        global $code_url, $pguser;

        echo "<h1>$page_title</h1>\n";
        $origin = array_get($_GET, 'origin', "$code_url/activity_hub.php" );
        echo "<div style='clear: both;'>
            <form method='GET'>
            <input type='hidden' name='show' value='set_columns'>
            <input type='hidden' name='origin' value='" . attr_safe($origin) . "'>
        ";
        // split the controls across two columns using tables within divs
        $index = 0;
        $count = count($this->column_selectors);
        $div_table_header = "
            <div style='width: 49%; float: left;'>
            <table style='width: 90%;'>
        ";

        echo $div_table_header;
        while($index < 9)
            $this->column_selectors[$index++]->echo_select_item();
        echo "</table></div>$div_table_header";
        while($index < $count)
            $this->column_selectors[$index++]->echo_select_item();
        foreach($this->option_data->options as $option)
            $option->echo_select_item();
        echo "
            </table>
            </div>
            <div class='center-align' style='clear: both;'>
            <input type='submit' value='", attr_safe(_("Set options")), "'>
            <input type='reset' value='", attr_safe(_("Reset")), "'>
            </div>
            </form></div>
        ";
    }
}

class ConfigSaver extends ColumnData
{
    public function __construct($userSettings, $search_origin)
    {
        parent::__construct($userSettings, $search_origin);
    }

    public function store_data()
    {
        global $pguser;

        $option_data = new OptionData($this->userSettings, $this->search_origin);

        $none_selected = true; // check for this case
        foreach ($this->columns as $column)
        {
            $value = isset($_GET[$column->id]); // can only be 'on'
            if($value)
                $none_selected = false;
            $column->set_active($value);
        }
        if($none_selected)
        {
            // force title to be on
            foreach($this->columns as $column)
            {
                if($column->id == "title")
                {
                    $column->set_active(true);
                    break;
                }
            }
        }
        // get the option values
        foreach($option_data->options as $option)
        {
            $value = array_get($_GET, $option->id, '');
            $option->save_value($value);
        }
    }
}

function get_search_configure_button()
{
    global $code_url;
    return "
        <form style='display: inline;' method='GET'>
        <input type='submit' value='" . attr_safe(_("Configure Result")) . "'>
        <input type='hidden' name='show' value='config'>
        <input type='hidden' name='origin' value='" . attr_safe($_SERVER['REQUEST_URI']) . "'>
        </form>";
}

function handle_set_cols($show_view, $search_origin)
{
    global $pguser, $code_url;

    if ($show_view == 'set_columns')
    {
        $userSettings =& Settings::get_Settings($pguser);
        $config_saver = new ConfigSaver($userSettings, $search_origin);
        $config_saver->store_data();
        $origin = array_get($_GET, 'origin', "$code_url/activity_hub.php");
        metarefresh(0, $origin);
    }
}

function handle_config($show_view, $search_origin, $page_title)
{
    global $pguser;

    if($show_view == 'config')
    {
        $userSettings =& Settings::get_Settings($pguser);
        $config_form = new ConfigForm($userSettings, $search_origin);
        $config_form->render($page_title);
        exit();
    }
}

// vim: sw=4 ts=4 expandtab
