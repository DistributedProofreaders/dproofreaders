<?php

// A "themed table" is an HTML table that uses various settings
// from the current theme for some of its presentational aspects.

// ThemedTable is a class for handling the formulaic parts of a themed table.

class ThemedTable
{
    function ThemedTable( $n_cols, $title, $options=array() )
    {
        $this->n_cols = $n_cols;

        $class = "themed";
        $border = 1;
        $width = "";
        $subtitle = null;
        foreach ( $options as $option_name => $option_value )
        {
            switch ( $option_name )
            {
                case 'striped':
                    $class .= ' striped';
                    break;
                case 'border':
                    $border = $option_value;
                    break;
                case 'width':
                    $width = " width: $option_value;";
                    break;
                case 'subtitle':
                    $subtitle = $option_value;
                    break;
                default:
                    die( "ThemedTable created with invalid option: '$option_name'" );
            }
        }

        echo "\n";
        echo "<table class='$class' style='border-width: ${border}px; ${width}'>";

        {
            $possible_subtitle =
                is_null($subtitle)
                ? ''
                : "<br>$subtitle";

            echo "\n";
            echo "<tr>";
                $maybe_colspan="";
                if ($this->n_cols > 1) { $maybe_colspan = "colspan='$this->n_cols'"; }
            echo   "<th $maybe_colspan>";
            echo     "<center>";
            echo           $title;
            echo         $possible_subtitle;
            echo     "</center>";
            echo   "</th>";
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

        echo "<tr>";

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
            }

            echo "<td $width_attr $align_attr>$cell_content</td>";
        }

        echo "</tr>\n";
    }

    function hr( $width )
    {
        echo "\n";
        echo "<tr>";
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
