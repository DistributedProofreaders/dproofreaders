<?php
include_once($relPath.'iso_lang_list.inc');

define( 'DEFAULT_N_RESULTS_PER_PAGE', 100 );

class ProjectSearchWidget
{
    function __construct( $properties )
    {
        foreach ( $properties as $property => $value )
        {
            $this->$property = $value;
        }
    }

    function get_html_control()
    {
        // If you don't specify a size for a <select> control,
        // browsers vary widely in what they use for a default.
        // (e.g., Firefox 1.0 uses 20, IE 5.5 and Opera 8 use 4, Opera 9 uses 1.)
        // To avoid this, set a reasonable size.
        if ( $this->type == 'select' && !isset($this->size) )
        {
            $co = count($this->options);
            $this->size = ( $co <= 6 ? $co : 4 );
        }

        $size_attr = ( isset($this->size) ? "size='{$this->size}'" : '' );
        if ( $this->type == 'text' )
        {
            if ( isset($_GET[$this->id]) )
            {
                $value_attr = "value='" . attr_safe($_GET[$this->id]) . "'";
            }
            else if ( isset($this->initial_value) )
            {
                $value_attr = "value='" . attr_safe($this->initial_value) . "'";
            }
            else
            {
                $value_attr = '';
            }
            return "<input type='text' name='{$this->id}' $size_attr $value_attr>";
        }
        else if ( $this->type == 'select' )
        {
            if ( $this->can_be_multiple )
            {
                $r = "<select name='{$this->id}[]' $size_attr multiple>\n";
            }
            else
            {
                $r = "<select name='{$this->id}' $size_attr>\n";
            }
            foreach ( $this->options as $option_value => $option_label )
            {
                $selected_attr = ( 
                    ( isset($this->initial_value) && $option_value == $this->initial_value )
                    ? 'selected'
                    : ''
                );
                $r .= "<option value='" . attr_safe($option_value) . "' $selected_attr>" . html_safe($option_label) . "</option>\n";
            }
            $r .= "</select>\n";
            return $r;
        }
    }

    function get_sql_contribution()
    {
        $value = @$_GET[$this->id];
        if ( $value == '' ) return NULL;

        $contrib_template = $this->q_contrib;
        if ( is_string($contrib_template) && function_exists($contrib_template) )
        {
            $contribution = $contrib_template( $value );
        }
        else
        {
            if ( $this->q_part == 'WHERE' )
            {
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
                        if( !is_array($values) ) return NULL;

                        // If the user picks the 'any' option as well as some others,
                        // it's as if they'd just picked the 'any' option.
                        if ( in_array( '', $values ) ) return NULL;
                    }

                    $values = array_map("escape_value", $values);

                    if ( $comparator == '=' )
                    {
                        $values_list = surround_and_join( $values, "'", "'", "," );
                        $contribution = "$column_name IN ($values_list)";
                    }
                    elseif ( $comparator == 'LIKE' )
                    {
                        $likes_str = surround_and_join( $values, "$column_name LIKE '%", "%'", ' OR ' );
                        $contribution = "($likes_str)";
                    }
                }
                else
                {
                    $value = mysqli_real_escape_string(DPDatabase::get_connection(), $value);
                    if ( $comparator == '=' )
                    {
                        $contribution = "$column_name = '$value'";
                    }
                    elseif ( $comparator == 'LIKE' )
                    {
                        $contribution = "$column_name LIKE '%$value%'";
                    }
                }
            }
            else
            {
                $contribution = str_replace('{VALUE}', $value, $contrib_template);
            }
        }

        return $contribution;
    }

}

class ProjectSearchForm
{
    public function __construct()
    {
        $this->define_form_widgets();
    }

    private function _get_options_special_day()
    {
        $special_day_options = array();
        $special_day_options[''] = _('Any day');
        $special_day_res = mysqli_query(DPDatabase::get_connection(), "
            SELECT
                spec_code,
                display_name,
                DATE_FORMAT(concat('2000-',open_month,'-',open_day),'%e %b')
            FROM special_days
            WHERE enable = 1
            ORDER BY open_month, open_day
        ");
        while ( list($s_code, $s_display_name, $s_start) = mysqli_fetch_row($special_day_res) )
        {
            $special_day_options[$s_code] = "$s_display_name ($s_start)";
        }
        return $special_day_options;
    }

    private function _get_options_lang()
    {
        global $lang_list;

        $lang_options[''] = _('Any');
        foreach($lang_list as $k=>$v)
        {
            $lang_options[$v['lang_name']] = $v['lang_name'];
        }
        return $lang_options;
    }

    private function _get_options_genre()
    {
        global $relPath;
        include($relPath.'genres.inc');

        return array_merge( array( '' => _('any') ), $GENRES );
    }

    private function _get_options_difficulty()
    {
        return array(
            ''         => _('any'),
            'beginner' => _('Beginner'),
            'easy'     => _('Easy'),
            'average'  => _('Average'),
            'hard'     => _('Hard'),
        );
    }

    private function _get_options_state()
    {
        global $PROJECT_STATES_IN_ORDER;

        $state_options[''] = _('any state');
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
                'q_part'     => 'WHERE',
                'q_contrib'  => array('nameofwork', 'LIKE'),
            )),
            new ProjectSearchWidget( array(
                'id'         => 'author',
                'label'      => _('Author'),
                'type'       => 'text',
                'q_part'     => 'WHERE',
                'q_contrib'  => array('authorsname', 'LIKE'),
            )),
            new ProjectSearchWidget( array(
                'id'         => 'language',
                'label'      => _('Language'),
                'type'       => 'select',
                'options'    => $this->_get_options_lang(),
                'can_be_multiple' => TRUE,
                'initial_value'   => '',
                'q_part'     => 'WHERE',
                'q_contrib'  => array('language', 'LIKE'),
            )),
            new ProjectSearchWidget( array(
                'id'         => 'genre',
                'label'      => _('Genre'),
                'type'       => 'select',
                'options'    => $this->_get_options_genre(),
                'can_be_multiple' => TRUE,
                'q_part'     => 'WHERE',
                'q_contrib'  => array('genre', '='),
            )),
            new ProjectSearchWidget( array(
                'id'         => 'difficulty',
                'label'      => _('Difficulty'),
                'type'       => 'select',
                'options'    => $this->_get_options_difficulty(),
                'can_be_multiple' => TRUE,
                'q_part'     => 'WHERE',
                'q_contrib'  => array('difficulty', '='),
            )),
            new ProjectSearchWidget( array(
                'id'         => 'special_day',
                'label'      => _('Special day'),
                'type'       => 'select',
                'options'    => $this->_get_options_special_day(),
                'can_be_multiple' => TRUE,
                'initial_value'   => '',
                'q_part'     => 'WHERE',
                'q_contrib'  => array('special_code', '='),
            )),
            new ProjectSearchWidget( array(
                'id'         => 'projectid',
                'label'      => _('Project ID'),
                'type'       => 'text',
                'size'       => 45, // big enough to show two projectids without scrolling.
                'can_be_multiple' => TRUE,
                'separator'  => '[\s,;]+',
                'q_part'     => 'WHERE',
                'q_contrib'  => array('projectid', 'LIKE'),
            )),
            new ProjectSearchWidget( array(
                'id'         => 'project_manager',
                'label'      => _('Project Manager'),
                'type'       => 'text',
                'q_part'     => 'WHERE',
                'q_contrib'  => array('username', 'LIKE'),
            )),
            new ProjectSearchWidget( array(
                'id'         => 'checkedoutby',
                'label'      => _('Checked Out By'),
                'type'       => 'text',
                'q_part'     => 'WHERE',
                'q_contrib'  => array('checkedoutby', 'LIKE'),
            )),
            new ProjectSearchWidget( array(
                'id'         => 'pp_er',
                'label'      => _('Post-processor'),
                'type'       => 'text',
                'q_part'     => 'WHERE',
                'q_contrib'  => array('postproofer', 'LIKE'),
            )),
            new ProjectSearchWidget( array(
                'id'         => 'ppv_er',
                'label'      => _('Post-processing Verifier'),
                'type'       => 'text',
                'q_part'     => 'WHERE',
                'q_contrib'  => array('ppverifier', 'LIKE'),
            )),
            new ProjectSearchWidget( array(
                'id'           => 'postednum',
                'label'        => _('PG etext number'),
                'type'         => 'text',
                'can_be_multiple' => TRUE,
                'separator'  => '[\s,;]+',
                'q_part'       => 'WHERE',
                'q_contrib'    => array('postednum', '='),
            )),
            new ProjectSearchWidget( array(
                'id'           => 'state',
                'label'        => pgettext('project state', 'State'),
                'type'         => 'select',
                'options'      => $this->_get_options_state(),
                'can_be_multiple' => TRUE,
                'q_part'       => 'WHERE',
                'q_contrib'    => array('state', '='),
            )),
            new ProjectSearchWidget( array(
                'id'           => 'n_results_per_page',
                'label'        => _('Number of results per page'),
                'type'         => 'select',
                'options'      => array( 30 => 30, 100 => 100, 300 => 300 ),
                'can_be_multiple' => FALSE,
                'initial_value'   => DEFAULT_N_RESULTS_PER_PAGE,
                'q_part'       => 'LIMIT',
                'q_contrib'    => '{VALUE}',
            )),
        );
    }

    public function render($page)
    {
        echo "<form method='get' action='$page'>";
        echo "<input type='hidden' name='show' value='search'>";
        echo "<table>";

        foreach ( $this->widgets as $widget )
        {
            if ( @$widget->can_be_multiple )
            {
                if ( $widget->type == 'text' )
                {
                    $help = _('list ok');
                }
                elseif ( $widget->type == 'select' )
                {
                    $help = _('multi-select');
                }
                $help = "<br>($help)";
            }
            else
            {
                $help = '';
            }
            echo "
                <tr>
                    <td align='right'>{$widget->label}$help</td>
                    <td>".$widget->get_html_control()."</td>
                </tr>
            ";
        }

        echo "
            <tr>
                <td></td>
                <td>
                    <table width='100%'>
                    <tr>
                        <td align='left'><input type='submit' value='", attr_safe(_("Search")), "'></td>
                        <td align='right'><input type='reset' value='", attr_safe(_("Clear form")), "'></td>
                    </tr>
                    </table>
                </td>
            </tr>
            </table>
            </form>
        ";

        echo _("For terms that you type in, matching is case-insensitive and unanchored; so, for instance, 'jim' matches both 'Jimmy Olsen' and 'piggyjimjams'. This doesn't apply to PG etext numbers, for which you should type in the complete number.");
        echo "<br><br>";
        echo _('"(list ok)": You can search by multiple ProjectIDs or PG etext numbers at once: enter the list of ProjectIDs or PG etext numbers, separated by commas, semicolons, or spaces.');
        echo "<br><br>";
        echo _('"(multi-select)": If desired, you should be able to select multiple values for Language, Difficulty, Special Day, or State (e.g., by holding down Ctrl).');
    }

    public function get_widget_contribution()
    {
        $condition = '1';
        foreach ( $this->widgets as $widget )
        {
            $contribution = $widget->get_sql_contribution();
            if ( $contribution == '' ) continue;

            if ( $widget->q_part == 'WHERE' )
            {
                $condition .= "\nAND $contribution";
            }
            else if ( $widget->q_part == 'LIMIT' )
            {
                // n_results_per_page is handled below
            }
            else
            {
                assert(FALSE);
            }
        }
        return $condition;
    }

    public function get_page_size()
    {
        $n_results_per_page = intval(@$_GET['n_results_per_page']);
        if ( $n_results_per_page == 0 ) $n_results_per_page = DEFAULT_N_RESULTS_PER_PAGE;
        return $n_results_per_page;
    }
}

function escape_value($value)
{
    return mysqli_real_escape_string(DPDatabase::get_connection(), $value);
}
