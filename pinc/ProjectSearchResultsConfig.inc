<?php

class OptionValue
{
    public function __construct()
    {
        $this->id = 'n_results_per_page';
    }

    function get_value($userSettings)
    {
        return $userSettings->get_value("PM$this->id", 100);
    }
}

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
    public function __construct($id, $label, $value)
    {
        $this->id = $id;
        $this->label = $label;
        $this->value = $value;
    }

    function get_html_control()
    {
        $check = $this->value ? " checked" : "";
        return "<input type='checkbox' name='$this->id'$check>";
    }
}

class OptionSelector extends Selector
{
    public function __construct($id, $label, $value, $options)
    {
        $this->id = $id;
        $this->label = $label;
        $this->value = $value;
        $this->options = $options;
    }

    function get_html_control()
    {
        echo "<select name='$this->id' size='3'>\n";
        foreach ( $this->options as $option_value => $option_label )
        {
            $selected_attr = ($option_value == $this->value) ? 'selected' : '';
            echo "<option value='", attr_safe($option_value), "' $selected_attr>", html_safe($option_label), "</option>\n";
        }
        echo "</select>\n";
    }
}

class ConfigForm extends ColumnData
{
    public function __construct()
    {
        global $pguser;

        parent::__construct();
        $option_value = new OptionValue();
        $userSettings =& Settings::get_Settings($pguser);
        foreach($this->columns as $column)
            $this->column_selectors[] = new ColumnSelector($column->id, $column->get_long_label(), $column->is_active($userSettings));

        $this->results_selector = new OptionSelector(
            'n_results_per_page',
            _('Results per page'),
            $option_value->get_value($userSettings),
            array( 30 => 30, 100 => 100, 300 => 300 )
        );
    }

    public function render()
    {
        global $code_url, $pguser;

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
            $this->column_selectors[$index++]->echo_select_item();
        echo "</table></div>$div_table_header";
        while($index < $count)
            $this->column_selectors[$index++]->echo_select_item();
        $this->results_selector->echo_select_item();
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

        $option_value = new OptionValue();
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
        // get the results per page value
        $id = $option_value->id;
        $value = $_GET[$id];
        if($value != $option_value->get_value($userSettings))
            $userSettings->set_value("PM$id", $value);
    }
}

function echo_configure_button($url)
{
    global $code_url;
    echo "\n<form style='display: inline;' method='GET' action='$code_url/tools/search_options.php'>
    <input type='submit' value='", attr_safe(_("Configure Results")), "'>
    <input type='hidden' name='origin' value='", attr_safe($url), "'>
    </form>
    ";
}

// vim: sw=4 ts=4 expandtab
