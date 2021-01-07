<?php
include_once($relPath.'iso_lang_list.inc');
include_once($relPath."misc.inc"); // attr_safe, array_get
include_once($relPath.'genres.inc'); // load_genre_translation_array

class ProjectSearchWidget
{
    public $can_be_multiple = false;
    public $initial_value = '';
    public $has_invert = false;

    function __construct( $properties )
    {
        foreach ( $properties as $property => $value )
        {
            $this->$property = $value;
        }
    }

    function get_html_control()
    {
        # make all widgets 100% width
        $size_attr = "style='width: 100%;'";

        // If you don't specify a size for a <select> control,
        // browsers vary widely in what they use for a default.
        // (e.g., Firefox 1.0 uses 20, IE 5.5 and Opera 8 use 4, Opera 9 uses 1.)
        // To avoid this, set a reasonable size.
        if ($this->type == 'select')
        {
            $co = count($this->options);
            $this->size = ( $co <= 6 ? $co : 4 );
            $size_attr .= " size='{$this->size}'";
        }

        if ( $this->type == 'text' )
        {
            if ( isset($_GET[$this->id]) )
            {
                $value_attr = "value='" . attr_safe($_GET[$this->id]) . "'";
            }
            else // initial_value is set for base class widget
            {
                $value_attr = "value='" . attr_safe($this->initial_value) . "'";
            }
            $placeholder = $this->can_be_multiple ? "placeholder = 'list ok'" : "";
            return "<input type='text' $placeholder name='{$this->id}' $size_attr $value_attr>";
        }
        else if ( $this->type == 'select' )
        {
            if ( $this->can_be_multiple )
            {
                $r = "<select name='{$this->id}[]' $size_attr multiple>\n";
            }
            else
            {
                $r = "<select name='$this->id' $size_attr>\n";
            }
            foreach ( $this->options as $option_value => $option_label )
            {
                if(isset($_GET[$this->id]))
                {
                    if(($this->can_be_multiple) && is_array($_GET[$this->id]))
                        $selected = in_array($option_value, $_GET[$this->id]);
                    else
                        $selected = ($option_value == $_GET[$this->id]);
                }
                else
                    $selected = ($option_value == $this->initial_value);
                $selected_attr = $selected ? 'selected' : '';
                $r .= "<option value='" . attr_safe($option_value) . "' $selected_attr>" . html_safe($option_label) . "</option>\n";
            }
            $r .= "</select>\n";
            if($this->has_invert)
            {
                $check = isset($_GET[$this->id . "_inv"]) ? " checked" : "";
                $r .= "<br>$this->invert_label<input type='checkbox' name='{$this->id}_inv'$check>";
            }
            return $r;
        }
    }

    function echo_search_item()
    {
        // could fix width of heading cells so they line up when 2nd table is below
        echo "
            <tr>
                <th class='right-align top-align'>$this->label</th>
                <td class='search-input top-align left-align'>".$this->get_html_control()."</td>
            </tr>";
    }

    function get_sql_contribution()
    {
        $value = array_get($_GET, $this->id, '');
        if ( $value == '' )
            return NULL;
        list($column_name,$comparator) = $this->q_contrib;
        if ( @$this->can_be_multiple )
        {
            if ( $this->type == 'text' )
            {
                $values = preg_split( "($this->separator)",  trim($value) );
            }
            elseif ( $this->type == 'select' )
            {
                $values = $value;

                // If $value isn't an array, someone is mucking with
                // the URL -- return instead of erroring out below.
                if( !is_array($values) )
                    return NULL;
                // If the user picks the 'any' option as well as some others,
                // remove the any option.
                if (($key = array_search('', $values)) !== false)
                    unset($values[$key]);
                if(empty($values))
                    return NULL;
            }

            $contribution = $this->sql_from_multi($values, $column_name, $comparator);
        }
        else
        {
            $value = DPDatabase::escape($value);
            if ( $comparator == '=' )
            {
                $contribution = "$column_name = '$value'";
            }
            elseif ( $comparator == 'LIKE' )
            {
                $value = normalize_whitespace($value);
                $contribution = "$column_name LIKE '%$value%'";
            }
        }
        return $contribution;
    }

    function sql_from_multi($values, $column_name, $comparator)
    {
        $values = array_map("DPDatabase::escape", $values);
        $inv = isset($_GET[$this->id . "_inv"]) ? " NOT " : "";
        if ( $comparator == '=' )
        {
            $values_list = surround_and_join( $values, "'", "'", "," );
            $contribution = "$column_name$inv IN ($values_list)";
        }
        elseif ( $comparator == 'LIKE' )
        {
            $likes_str = surround_and_join( $values, "$column_name LIKE '%", "%'", ' OR ' );
            $contribution = "$inv($likes_str)";
        }
        return $contribution;
    }
}

class SpecialDayWidget extends ProjectSearchWidget
{
    function sql_from_multi($values, $column_name, $comparator)
    {
        $values = array_map("DPDatabase::escape", $values);
        $contributions = [];
        foreach($values as $value)
        {
            if("Birthday" == $value)
            {
                $contributions[] = "$column_name REGEXP 'Birthday [0-9]{4}'";
            }
            else if("Otherday" == $value)
            {
                $contributions[] = "$column_name REGEXP 'Otherday [0-9]{4}'";
            }
            else
            {
                $contributions[] = "$column_name = '$value'";
            }
        }
        return "(" . implode(" OR ", $contributions) . ")";
    }
}

class HoldWidget extends ProjectSearchWidget
{
    public function get_html_control()
    {
        $check = isset($_GET[$this->id]) ? " checked" : "";
        return "<input type='checkbox' name='$this->id'$check>";
    }

    public function get_sql_contribution()
    {
        if(isset($_GET[$this->id])) // can only be 'on'
            return 'project_holds.projectid IS NOT NULL';
        else
            return '';
    }
}

class ProjectSearchForm
{
    public function __construct()
    {
        $this->define_form_widgets();
    }

    function all_special_day_options()
    {
        return array(
            '' => _('Any day'),
            'Birthday' => _('Birthday'),
            'Otherday' => _('Otherday'),
        ) + $this->special_day_options();
    }

    static public function special_day_options()
    {
        $special_day_options = array();
        $sql = "
            SELECT
                spec_code,
                display_name,
                DATE_FORMAT(concat('2000-',open_month,'-',open_day),'%e %b')
            FROM special_days
            WHERE enable = 1
            ORDER BY open_month, open_day
        ";
        $special_day_res = DPDatabase::query($sql);
        while ( list($s_code, $s_display_name, $s_start) = mysqli_fetch_row($special_day_res) )
        {
            $special_day_options[$s_code] = "$s_display_name ($s_start)";
        }
        return $special_day_options;
    }

    static public function language_options()
    {
        $lang_options[''] = _('Any');
        foreach(get_iso_language_list() as $k => $v)
        {
            $lang_options[$v['lang_name']] = $v['lang_name'];
        }
        return $lang_options;
    }

    static public function genre_options()
    {
        return array_merge( array( '' => _('Any') ), load_genre_translation_array());
    }

    static public function difficulty_options()
    {
        return array(
            ''         => _('Any'),
            'beginner' => _('Beginner'),
            'easy'     => _('Easy'),
            'average'  => _('Average'),
            'hard'     => _('Hard'),
        );
    }

    static public function state_options()
    {
        global $PROJECT_STATES_IN_ORDER;

        $state_options[''] = _('Any state');
        foreach ($PROJECT_STATES_IN_ORDER as $proj_state)
        {
            $state_options[$proj_state] = project_states_text($proj_state);
        }
        return $state_options;
    }

    public function define_form_widgets()
    {
        $this->widgets = array(
            new ProjectSearchWidget( array(
                'id'         => 'title',
                'label'      => _('Title'),
                'type'       => 'text',
                'q_contrib'  => array('nameofwork', 'LIKE'),
            )),
            new ProjectSearchWidget( array(
                'id'         => 'author',
                'label'      => _('Author'),
                'type'       => 'text',
                'q_contrib'  => array('authorsname', 'LIKE'),
            )),
            new ProjectSearchWidget( array(
                'id'         => 'projectid',
                'label'      => _('Project ID'),
                'type'       => 'text',
                'can_be_multiple' => TRUE,
                'separator'  => '[\s,;]+',
                'q_contrib'  => array('projectid', 'LIKE'),
            )),
            new ProjectSearchWidget( array(
                'id'         => 'project_manager',
                'label'      => _('Project Manager'),
                'type'       => 'text',
                'q_contrib'  => array('username', 'LIKE'),
            )),
            new ProjectSearchWidget( array(
                'id'         => 'checkedoutby',
                'label'      => _('Checked Out By'),
                'type'       => 'text',
                'q_contrib'  => array('checkedoutby', 'LIKE'),
            )),
            new ProjectSearchWidget( array(
                'id'         => 'pp_er',
                'label'      => _('Post-processor'),
                'type'       => 'text',
                'q_contrib'  => array('postproofer', 'LIKE'),
            )),
            new ProjectSearchWidget( array(
                'id'         => 'ppv_er',
                'label'      => _('Post-processing Verifier'),
                'type'       => 'text',
                'q_contrib'  => array('ppverifier', 'LIKE'),
            )),
            new ProjectSearchWidget( array(
                'id'           => 'postednum',
                'label'        => _('PG etext number'),
                'type'         => 'text',
                'can_be_multiple' => TRUE,
                'separator'  => '[\s,;]+',
                'q_contrib'    => array('postednum', '='),
            )),
            new ProjectSearchWidget( array(
                'id'         => 'language',
                'label'      => _('Language'),
                'type'       => 'select',
                'options'    => $this->language_options(),
                'can_be_multiple' => TRUE,
                'initial_value'   => '',
                'q_contrib'  => array('language', 'LIKE'),
                'has_invert' => true,
                'invert_label' => _("Invert language filter"),
            )),
            new ProjectSearchWidget( array(
                'id'         => 'genre',
                'label'      => _('Genre'),
                'type'       => 'select',
                'options'    => $this->genre_options(),
                'can_be_multiple' => TRUE,
                'q_contrib'  => array('genre', '='),
            )),
            new ProjectSearchWidget( array(
                'id'         => 'difficulty',
                'label'      => _('Difficulty'),
                'type'       => 'select',
                'options'    => $this->difficulty_options(),
                'can_be_multiple' => TRUE,
                'q_contrib'  => array('difficulty', '='),
            )),
            new SpecialDayWidget( array(
                'id'         => 'special_day',
                'label'      => _('Special day'),
                'type'       => 'select',
                'options'    => $this->all_special_day_options(),
                'can_be_multiple' => TRUE,
                'initial_value'   => '',
                'q_contrib'  => array('special_code', '='),
            )),
            new ProjectSearchWidget( array(
                'id'           => 'state',
                'label'        => pgettext('project state', 'State'),
                'type'         => 'select',
                'options'      => $this->state_options(),
                'can_be_multiple' => TRUE,
                'q_contrib'    => array('state', '='),
            )),
            new HoldWidget( array(
                'id'        => 'hold',
                'label'     => _('Projects on Hold'),
            )),
        );
    }

    public function render()
    {
        echo "<p>" . _("Search for projects matching the following criteria:")."</p>\n";
        echo "<div class='search-columns'>
            <form style='display: inline;' method='GET'>
            <input type='hidden' name='show' value='search'>";
        // split the widgets into two tables which can be side-by-side if there
        // is enough room
        $widget_index = 0;
        $widget_count = count($this->widgets);
        $table_header = "<table class='search-column'>";
        echo $table_header;
        while($widget_index < 9)
            $this->widgets[$widget_index++]->echo_search_item();
        echo "</table>$table_header";
        while($widget_index < $widget_count)
            $this->widgets[$widget_index++]->echo_search_item();
        echo "
            </table>
            <div class='center-align' style='clear: both;'></div>
            <input type='submit' value='", attr_safe(_("Search")), "'>
            <input type='button' onclick=\"window.location.assign('?show=search_form');\" value='", attr_safe(_("Clear form")), "'>
            </form>";
        echo get_search_configure_button();
        echo "</div>";
        echo "<p>
            "._("For terms that you type in, matching is case-insensitive and unanchored; so, for instance, 'jim' matches both 'Jimmy Olsen' and 'piggyjimjams'. This doesn't apply to PG etext numbers, for which you should type in the complete number.")."
            </p><p>
            "._('"(list ok)": You can search by multiple ProjectIDs or PG etext numbers at once: enter the list of ProjectIDs or PG etext numbers, separated by commas, semicolons, or spaces.')."
            </p><p>
            "._('If desired, you can select multiple values for Language, Genre, Difficulty, Special Day, or State (e.g., by holding down Ctrl).')."
        </p>
        ";
    }

    public function get_condition()
    {
        $condition = '1';
        foreach ( $this->widgets as $widget )
        {
            $contribution = $widget->get_sql_contribution();
            if ( $contribution == '' )
                continue;
            $condition .= "\nAND $contribution";
        }
        return $condition;
    }
}
