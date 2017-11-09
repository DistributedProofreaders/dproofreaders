<?php
class Selector
{
    function echo_select_item($userSettings)
    {
        echo "
            <tr>
                <th class='right-align top-align'>$this->label</th>
                <td class='top-align'>", $this->get_html_control($userSettings), "</td>
            </tr>
        ";
    }
}
class ColumnSelector extends Selector
{
    public function __construct($column)
    {
        $this->column = $column;
        $this->label = $column->get_long_label();
    }

    function get_html_control($userSettings)
    {
        $check = $this->column->is_active($userSettings) ? " checked" : "";
        return "<input type='checkbox' name='{$this->column->id}'$check>";
    }
}

class OptionSelector extends Selector
{
    public function __construct($id, $label, $value, $options)
    {
        $this->id = $id;
        $this->label = $label;
        $this->def_value = $value;
        $this->options = $options;
    }

    function get_html_control($userSettings)
    {
        echo "<select name='$this->id'>\n";
        $selected_item = $this->get_value($userSettings);
        foreach ( $this->options as $option_value => $option_label )
        {
            $selected_attr = ($option_value == $selected_item) ? 'selected' : '';
            echo "<option value='", attr_safe($option_value), "' $selected_attr>", html_safe($option_label), "</option>\n";
        }
        echo "</select>\n";
    }

    function get_value($userSettings)
    {
        return $userSettings->get_value("PM$this->id", $this->def_value);
    }
}

class OptionData
{
    public function __construct()
    {
        $this->results_per_page = new OptionSelector(
            'n_results_per_page',
            _('Results per page'),
            100,
            array( 30 => 30, 100 => 100, 300 => 300 )
        );

        $this->time_format = new OptionSelector(
            'tim_form',
            _("Time format"),
            'days',
            array('days' => _('Days ago'), 'date' => _('Date'))
        );

        $this->options = array($this->results_per_page, $this->time_format);
    }
}

class ConfigForm extends ColumnData
{
    public function __construct()
    {
        global $pguser;

        parent::__construct();
        foreach($this->columns as $column)
            $this->column_selectors[] = new ColumnSelector($column);

        $this->option_data = new OptionData();
    }

    public function render()
    {
        global $code_url, $pguser;

        $userSettings =& Settings::get_Settings($pguser);
        echo "<h1>", _("Configure Search Results"), "</h1>\n";
        if(isset($_GET['origin']))
            $origin = $_GET['origin'];
        else
            $origin = "$code_url/activity_hub.php";
        echo "<div style='clear: both;'>
            <form method='GET' action='search_options.php'>
            <input type='hidden' name='show' value='set_columns'>
            <input type='hidden' name='origin' value='", $origin , "'>
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
            $this->column_selectors[$index++]->echo_select_item($userSettings);
        echo "</table></div>$div_table_header";
        while($index < $count)
            $this->column_selectors[$index++]->echo_select_item($userSettings);
        foreach($this->option_data->options as $option)
            $option->echo_select_item($userSettings);
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
    public function __construct()
    {
        parent::__construct();
    }

    public function store_data()
    {
        global $pguser;

        $option_data = new OptionData();
        $userSettings =& Settings::get_Settings($pguser);

        $none_selected = true; // check for this case
        foreach ($this->columns as $column)
        {
            $id = $column->id;
            $value = isset($_GET[$id]); // can only be 'on'
            if($value)
                $none_selected = false;
            if($value != $column->is_active($userSettings))
                $userSettings->set_value("PM$id", $value ? "yes" : "no");
        }
        if($none_selected)
        {
            // force title to be on
            $userSettings->set_value("PMtitle", "yes");
        }
        // get the option values
        foreach($option_data->options as $option)
        {
            $id = $option->id;
            $value = $_GET[$id];
            if($value != $option->get_value($userSettings))
                $userSettings->set_value("PM$id", $value);
        }
    }
}

function get_search_configure_button($url)
{
    global $code_url;
    return "
        <form style='display: inline;' method='GET' action='$code_url/tools/search_options.php'>
        <input type='submit' value='" . attr_safe(_("Configure Results")) . "'>
        <input type='hidden' name='origin' value='" . attr_safe($url) . "'>
        </form>";
}

function get_search_configure_link($url)
{
    global $code_url;
    $origin = urlencode($url);
    return "<a href='$code_url/tools/search_options.php?origin=$origin'>" . html_safe(_("Configure Result")) . "</a>";
}

// vim: sw=4 ts=4 expandtab
