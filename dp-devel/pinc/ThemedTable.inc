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
    }

    function set_column_widths()
    // Set explicit column widths.
    // (If you don't call this method, <td> tags
    // will be output without a 'width' attribute.)
    {
        // There should be an arg (width) for each column.
        assert( func_num_args() == $this->n_cols );
        $this->column_widths = func_get_args();

        // They should add up to 100.
        assert( array_sum($this->column_widths) == 100 );
    }

    function set_column_alignments()
    {
        assert( func_num_args() == $this->n_cols );
        $this->column_alignments = func_get_args();

        // Should check that they're sensible.
    }

    function column_headers()
    {
        $args = func_get_args();
        $this->_row( $args, TRUE );
    }

    function row()
    {
        $args = func_get_args();
        $this->_row( $args, FALSE );
    }

    function _row( $cell_texts, $is_header )
    {
        if ( $this->n_cols > 1 && count($cell_texts) == 1 && is_array($cell_texts[0]) )
        {
            $cell_texts = $cell_texts[0];
        }

        // There should be an arg (cell content) for each column.
        assert( count($cell_texts) == $this->n_cols );

        global $theme;
        $bgcolor         = $theme['color_navbar_bg'];
        $highlight_color = $theme['color_mainbody_bg'];
        $font_face       = $theme['font_navbar'];
        $font_color      = $theme['color_navbar_font'];

        echo "\n";
        // (We don't put the rollover highlight on a header row,
        // though there's no particular reason we couldn't.)
        if ($this->rollover_highlight && !$is_header)
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
            if ( isset($this->column_widths) )
            {
                $width = $this->column_widths[$c];
                $width_attr = "width='$width%'";
            }
            else
            {
                $width_attr = '';
            }

            if ( isset($this->column_alignments) )
            {
                $alignment = $this->column_alignments[$c];
                if (empty($alignment))
                {
                    $align_attr = '';
                }
                else
                {
                    $align_attr = "align='$alignment'";
                }
            }
            else
            {
                $align_attr = '';
            }

            $cell_content = $cell_texts[$c];

            if ($is_header)
            {
                $cell_content = "<b><u><i>$cell_content</i></u></b>";
                $cell_content = "<font face='$font_face' color='$font_color' size='2'>$cell_content</font>";
            }

            echo "<td $width_attr $align_attr>$cell_content</td>";
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
