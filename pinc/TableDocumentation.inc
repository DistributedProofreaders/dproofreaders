<?php

class TableDocumentation
{

    private $display_name;
    private $columns_information;

    public function __construct(string $display_name, array $columns_information)
    {
        $this->display_name = $display_name;
        $this->columns_information = $columns_information;
    }

    public function generate_documentation_table(): string
    {
        $columns_information = $this->columns_information;

        // Add hyper links to field names
        foreach ($columns_information as $index => $column_information) {
            $field = $column_information['Field'];
            $columns_information[$index]['Field'] = "[`$field`](#$field)";
        }

        return implode("\n",
            TableDocumentation::create_markdown_table($columns_information,
                [ 'Field', 'Type', 'Null', 'Key', 'Default', 'Extra' ]));
    }

    public function generate_column_definitions(): string
    {
        $lines = [];

        // Add column definitions
        foreach ($this->columns_information as $index => $column_information) {
            $lines[] = '## `' . $column_information['Field'] . '`';
            $lines[] = '';
        }

        return implode("\n", $lines);
    }

    public function __toString(): string
    {
        return implode("\n", [
            '# `' . $this->display_name . '`',
            '',
            $this->generate_documentation_table(),
            '',
            $this->generate_column_definitions(),
        ]);
    }

    /**
     * Creates a markdown table of the data with the only the specified columns shown and in the specified order.
     * Every cell is padded right with spaces to ensure every column is as wide as the widest cell in that column.
     *
     * @param array $contents the data that is shown within the table
     * @param array $table_columns the columns to display
     * @return array array of strings -- the generate markdown table
     */
    public static function create_markdown_table(array $contents, array $table_columns): array
    {
        // For each column find the length of the longest value (used for padding in the table)
        $column_lengths = [];

        foreach ($contents as $row) {
            foreach ($row as $column => $value) {
                $column_lengths[$column] = max(strlen($value), array_get($column_lengths, $column, strlen($column)));
            }
        }

        $output_table = [];

        // Add headers
        $header = '|';
        $line_under_header = '|';

        foreach ($table_columns as $column) {
            $header .= str_pad($column, $column_lengths[$column]) . '|';
            $line_under_header .= str_repeat('-', $column_lengths[$column]) . '|';
        }

        $output_table[] = $header;
        $output_table[] = $line_under_header;

        // Add data
        foreach ($contents as $row) {
            $output_row = '|';

            foreach ($table_columns as $column) {
                $output_row .= str_pad(array_get($row, $column, ''), $column_lengths[$column]) . '|';
            }

            $output_table[] = $output_row;
        }

        return $output_table;
    }

    /**
     * Detects the first markdown table in the text.
     *
     * @param array $lines an array of strings -- the lines of the text
     * @return array an array of strings containing the first markdown table in the text
     */
    public static function detect_first_table_in_text(array $lines): array {
        $first_table_lines = [];

        foreach ($lines as $index => $line) {
            if (startswith($line, '|')) {
                $first_table_lines[] = $line;
            }
            elseif (count($first_table_lines) > 0) {
                break;
            }
        }

        return $first_table_lines;
    }

    /**
     * Detects columns in the text by finding column descriptions. A column description contains the name of the column
     * prefixed by "## " (without quotes) and surrounded by `.
     *
     * For example, for a column named something_useful, its column description would look like this:
     *
     * ## `something_useful`
     *
     * @param array $lines an array of strings -- the lines of the text
     * @return array an array of strings containing the column names detected in text
     */
    public static function detect_columns_in_text(array $lines): array
    {
        // array_values is used to reindex the array
        return array_values(preg_filter('/^## `([a-zA-Z0-9_]+)`/', '$1', $lines));
    }
}