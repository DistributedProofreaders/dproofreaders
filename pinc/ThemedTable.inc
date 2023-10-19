<?php

// A "themed table" is an HTML table that uses various settings
// from the current theme for some of its presentational aspects.

// ThemedTable is a class for handling the formulaic parts of a themed table.

class ThemedTable
{
    public function __construct($n_cols, $title, $options = [])
    {
        $this->n_cols = $n_cols;

        $class = "themed";
        $border = 1;
        $width = "";
        $subtitle = null;
        foreach ($options as $option_name => $option_value) {
            switch ($option_name) {
                case 'theme_striped':
                    $class .= ' theme_striped';
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
                    die("ThemedTable created with invalid option: '$option_name'");
            }
        }

        echo "\n";
        echo "<table class='$class' style='border-width: {$border}px; {$width}'>";

        {
            $possible_subtitle =
                is_null($subtitle)
                ? ''
                : "<br>$subtitle";

            echo "\n";
            echo "<tr>";
            $maybe_colspan = "";
            if ($this->n_cols > 1) {
                $maybe_colspan = "colspan='$this->n_cols'";
            }
            echo   "<th style='text-align: center;' $maybe_colspan>";
            echo     $title;
            echo     $possible_subtitle;
            echo   "</th>";
            echo "</tr>";
        }
    }

    /**
     * Set explicit column widths.
     *
     * If you don't call this method, <td> tags will be output without a 'width' attribute.
     */
    public function set_column_widths()
    {
        // There should be an arg (width) for each column.
        assert(func_num_args() == $this->n_cols);
        $this->column_widths = func_get_args();

        // They should add up to 100.
        assert(array_sum($this->column_widths) == 100);
    }

    public function set_column_alignments()
    {
        assert(func_num_args() == $this->n_cols);
        $this->column_alignments = func_get_args();

        // Should check that they're sensible.
    }

    public function column_headers()
    {
        $args = func_get_args();
        $this->_row($args, true);
    }

    public function row()
    {
        $args = func_get_args();
        $this->_row($args, false);
    }

    public function _row($cell_texts, $is_header)
    {
        if ($this->n_cols > 1 && count($cell_texts) == 1 && is_array($cell_texts[0])) {
            $cell_texts = $cell_texts[0];
        }

        // There should be an arg (cell content) for each column.
        assert(count($cell_texts) == $this->n_cols);

        echo "<tr>";

        for ($c = 0; $c < $this->n_cols; $c++) {
            if (isset($this->column_widths)) {
                $width = $this->column_widths[$c];
                $width_style = "width: $width%;";
            } else {
                $width_style = '';
            }

            if (isset($this->column_alignments)) {
                $alignment = $this->column_alignments[$c];
                if (empty($alignment)) {
                    $align_style = '';
                } else {
                    $align_style = "text-align: $alignment;";
                }
            } else {
                $align_style = '';
            }

            $cell_content = $cell_texts[$c];

            if ($is_header) {
                $cell_content = "<b><i>$cell_content</i></b>";
            }

            $style = '';
            if ($width_style or $align_style) {
                $style = "style='$width_style $align_style'";
            }

            echo "<td $style>$cell_content</td>";
        }

        echo "</tr>\n";
    }

    public function hr($width)
    {
        echo "\n";
        echo "<tr>";
        echo   "<td colspan='$this->n_cols'>";
        echo     "<hr style='width: $width%;'>";
        echo   "</td>";
        echo "</tr>";
    }

    public function end()
    {
        echo "</table><p>";
    }
}
