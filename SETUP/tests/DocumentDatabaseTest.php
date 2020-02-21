<?php

class DocumentDatabaseTest extends PHPUnit\Framework\TestCase
{
    // create_markdown_table
    public function testCreateMarkdownTableWithSingleColumnAndRow()
    {
        $this->assertEquals([
            '|column|',
            '|------|',
            '|value |'
        ], create_markdown_table([
            [ 'column' => 'value' ]
        ], [ 'column' ]));
    }

    public function testCreateMarkdownTableWithSingleColumnAndMultipleShortRows()
    {
        $this->assertEquals([
            '|column|',
            '|------|',
            '|value1|',
            '|value2|',
            '|value3|'
        ], create_markdown_table([
            [ 'column' => 'value1' ],
            [ 'column' => 'value2' ],
            [ 'column' => 'value3' ]
        ], [ 'column' ]));
    }

    public function testCreateMarkdownTableWithSingleColumnAndMultipleLongRows()
    {
        $this->assertEquals([
            '|column            |',
            '|------------------|',
            '|long_value1       |',
            '|even_longer_value2|',
            '|short_value3      |'
        ], create_markdown_table([
            [ 'column' => 'long_value1' ],
            [ 'column' => 'even_longer_value2' ],
            [ 'column' => 'short_value3' ]
        ], [ 'column' ]));
    }

    public function testCreateMarkdownTableWithMultipleColumnsAndRows()
    {
        $this->assertEquals([
            '|column1           |column2 |',
            '|------------------|--------|',
            '|long_value1       |value1  |',
            '|even_longer_value2|value2  |',
            '|short_value3      |value123|'
        ], create_markdown_table([
            [ 'column1' => 'long_value1', 'column2' => 'value1' ],
            [ 'column1' => 'even_longer_value2', 'column2' => 'value2' ],
            [ 'column1' => 'short_value3', 'column2' => 'value123' ]
        ], [ 'column1', 'column2' ]));
    }

    public function testCreateMarkdownTableWithMissingData() {
        $this->assertEquals([
            '|column1           |column2 |',
            '|------------------|--------|',
            '|                  |value1  |',
            '|even_longer_value2|        |',
            '|short_value3      |value123|'
        ], create_markdown_table([
            [ 'column2' => 'value1' ],
            [ 'column1' => 'even_longer_value2' ],
            [ 'column1' => 'short_value3', 'column2' => 'value123' ]
        ], [ 'column1', 'column2' ]));
    }

    public function testCreateMarkdownTableAndIgnoreColumn()
    {
        $this->assertEquals([
            '|column1           |',
            '|------------------|',
            '|                  |',
            '|even_longer_value2|',
            '|short_value3      |'
        ], create_markdown_table([
            [ 'column2' => 'value1' ],
            [ 'column1' => 'even_longer_value2' ],
            [ 'column1' => 'short_value3', 'column2' => 'value123' ]
        ], [ 'column1' ]));
    }

    // detect_columns_in_text
    public function testDetectColumnsWithNoLines()
    {
        $this->assertEquals([], detect_columns_in_text([]));
    }

    public function testDetectColumnsWithLinesButNoColumns()
    {
        $this->assertEquals([], detect_columns_in_text([
            'These are description lines',
            'and they do not contain a',
            'column.'
        ]));
    }

    public function testDetectColumnsWithOnlyColumns()
    {
        $this->assertEquals([
            'firstcolumn',
            'secondcolumn',
            'thirdcolumn'
        ], detect_columns_in_text([
            '## firstcolumn',
            '## secondcolumn',
            '## thirdcolumn'
        ]));
    }

    public function testDetectColumnsWithColumnsContainingUnderscores()
    {
        $this->assertEquals([
            'first_column',
            'second_column',
            'third_column'
        ], detect_columns_in_text([
            '## first_column',
            '## second_column',
            '## third_column'
        ]));
    }

    public function testDetectColumnsWithMixedLines()
    {
        $this->assertEquals([
            'first_column',
            'second_column',
            'third_column',
        ], detect_columns_in_text([
            '## first_column',
            'here is the description of the first column...',
            '## second_column',
            'here is the descriptio of the second column,',
            'which doesn\' fit in one line...',
            '## third_column',
            'and here is the description of the third column'
        ]));
    }
}
