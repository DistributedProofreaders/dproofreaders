<?PHP

// A "themed table" is an HTML table that uses various settings
// from the current $theme for some of its presentational aspects.

// ThemedTable is a class for handling the formulaic parts of a themed table.

include_once($relPath.'theme.inc');

class ThemedTable
{
    function ThemedTable( $n_cols, $title, $options=array() )
    {
        $this->n_cols = $n_cols;

        $this->rollover_highlight = FALSE;
        $border = 1;
        $width = null;
        $subtitle = null;
        foreach ( $options as $option_name => $option_value )
        {
            switch ( $option_name )
            {
                case 'rollover_highlight':
                    $this->rollover_highlight = $option_value;
                    break;
                case 'border':
                    $border = $option_value;
                    break;
                case 'width':
                    $width = $option_value;
                    break;
                case 'subtitle':
                    $subtitle = $option_value;
                    break;
                default:
                    die( "ThemedTable created with invalid option: '$option_name'" );
            }
        }

        echo "\n";
        echo "<table rules='none' border='$border' bordercolor='#111111' cellspacing='0' cellpadding='1' style='border-collapse: collapse' width='$width%'>";

        {
            $possible_subtitle =
                is_null($subtitle)
                ? ''
                : "<br><font size='1'>$subtitle</font>";

            global $theme;
            $bgcolor    = $theme['color_headerbar_bg'];
            $font_face  = $theme['font_headerbar'];
            $font_color = $theme['color_headerbar_font'];

            echo "\n";
            echo "<tr>";
            echo   "<td colspan='$this->n_cols' bgcolor='$bgcolor'>";
            echo     "<center>";
            echo       "<font face='$font_face' color='$font_color' size='2'>";
            echo         "<b>";
            echo           $title;
            echo         "</b>";
            echo         $possible_subtitle;
            echo       "</font>";
            echo     "</center>";
            echo   "</td>";
            echo "</tr>";
        }

        // If there's only 1 column, it must get the whole width of the table.
        if ($this->n_cols == 1)
        {
            $this->column_widths = array( 100 );
        }
    }

    function set_column_widths()
    {
        // There should be an arg (width) for each column.
        assert( func_num_args() == $this->n_cols );
        $this->column_widths = func_get_args();

        // They should add up to 100.
        assert( array_sum($this->column_widths) == 100 );
    }

    function column_headers()
    {
        $args = func_get_args();

        if ( $this->n_cols > 1 && count($args) == 1 && is_array($args[0]) )
        {
            $args = $args[0];
        }

        // There should be an arg (header) for each column.
        assert( count($args) == $this->n_cols );

        global $theme;
        $bgcolor    = $theme['color_navbar_bg'];
        $font_face  = $theme['font_navbar'];
        $font_color = $theme['color_navbar_font'];

        echo "\n";
        echo "<tr bgcolor='$bgcolor'>";

        for ( $c = 0; $c < $this->n_cols; $c++ )
        {
            $header_text = $args[$c];
            $width = $this->column_widths[$c];

            echo "<td width='$width%'>";
            echo   "<font face='$font_face' color='$font_color' size='2'>";
            echo     "<b><u><i>";
            echo       $header_text;
            echo     "</i></u></b>";
            echo   "</font>";
            echo "</td>";
        }
        echo "</tr>";
    }

    function row()
    {
        // There should be an arg (cell content) for each column.
        assert( func_num_args() == $this->n_cols );

        global $theme;
        $bgcolor         = $theme['color_navbar_bg'];
        $highlight_color = $theme['color_mainbody_bg'];
        $font_face       = $theme['font_navbar'];
        $font_color      = $theme['color_navbar_font'];

        echo "\n";
        if ($this->rollover_highlight)
        {
            echo "<tr"
                . " bgcolor='$bgcolor'"
                . " onmouseover='javascript:style.background=\"$highlight_color\"'"
                .  " onmouseout='javascript:style.background=\"$bgcolor\"'"
                . ">";
            ;
        }
        else
        {
            echo "<tr bgcolor='$bgcolor'>";
        }

        for ( $c = 0; $c < $this->n_cols; $c++ )
        {
            $cell_text = func_get_arg($c);
            $width = $this->column_widths[$c];

            echo "<td width='$width%'>";
            // echo   "<font face='$font_face' color='$font_color' size='2'>";
            echo     $cell_text;
            // echo   "</font>";
            echo "</td>";
        }

        echo "</tr>";
    }

    function hr( $width )
    {
        global $theme;
        $bgcolor = $theme['color_navbar_bg'];

        echo "\n";
        echo "<tr bgcolor='$bgcolor'>";
        echo   "<td colspan='$this->n_cols'>";
        echo     "<hr width='$width%'>";
        echo   "</td>";
        echo "</tr>";
    }

    function end()
    {
        echo "</table><p>";
    }
}

// vim: sw=4 ts=4 expandtab
?>
