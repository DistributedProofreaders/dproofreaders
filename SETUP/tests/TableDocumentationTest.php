<?php


class TableDocumentationTest extends PHPUnit\Framework\TestCase
{
    const TABLE_DESCRIPTION = [
        [ 'Field' => 'field1', 'Type' => 'type1', 'Null' => 'null1', 'Key' => 'key1', 'Default' => 'default1', 'Extra' => 'extra1' ],
        [ 'Field' => 'field2', 'Type' => 'type2', 'Null' => 'null2', 'Key' => 'key2', 'Default' => 'default2', 'Extra' => 'extra2' ],
        [ 'Field' => 'field3', 'Type' => 'type3', 'Null' => 'null3', 'Key' => 'key3', 'Default' => 'default3', 'Extra' => 'extra3' ],
    ];

    // to string
    public function testToStringForNormalTable() {
        $table_documentation = new TableDocumentation('display_table1', self::TABLE_DESCRIPTION);

        $this->assertEquals(implode("\n", [
            '# `display_table1`',
            '',
            '|Field              |Type |Null |Key |Default |Extra |',
            '|-------------------|-----|-----|----|--------|------|',
            '|[`field1`](#field1)|type1|null1|key1|default1|extra1|',
            '|[`field2`](#field2)|type2|null2|key2|default2|extra2|',
            '|[`field3`](#field3)|type3|null3|key3|default3|extra3|',
            '',
            '## `field1`',
            '',
            '## `field2`',
            '',
            '## `field3`',
            '',
        ]), (string) $table_documentation);
    }

    // create_markdown_table
    public function testCreateMarkdownTableWithSingleColumnAndRow()
    {
        $this->assertEquals([
            '|column|',
            '|------|',
            '|value |'
        ], TableDocumentation::create_markdown_table([
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
        ], TableDocumentation::create_markdown_table([
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
        ], TableDocumentation::create_markdown_table([
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
        ], TableDocumentation::create_markdown_table([
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
        ], TableDocumentation::create_markdown_table([
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
        ], TableDocumentation::create_markdown_table([
            [ 'column2' => 'value1' ],
            [ 'column1' => 'even_longer_value2' ],
            [ 'column1' => 'short_value3', 'column2' => 'value123' ]
        ], [ 'column1' ]));
    }

    // detect_first_table_in_text
    public function testDetectFirstTableWithDocumentationOutput() {
        $this->assertEquals([
            '|Field              |Type |Null |Key |Default |Extra |',
            '|-------------------|-----|-----|----|--------|------|',
            '|[`field1`](#field1)|type1|null1|key1|default1|extra1|',
            '|[`field2`](#field2)|type2|null2|key2|default2|extra2|',
            '|[`field3`](#field3)|type3|null3|key3|default3|extra3|',
        ], TableDocumentation::detect_first_table_in_text([
            '# `display_table1`',
            '',
            '|Field              |Type |Null |Key |Default |Extra |',
            '|-------------------|-----|-----|----|--------|------|',
            '|[`field1`](#field1)|type1|null1|key1|default1|extra1|',
            '|[`field2`](#field2)|type2|null2|key2|default2|extra2|',
            '|[`field3`](#field3)|type3|null3|key3|default3|extra3|',
            '',
            '## `field1`',
            '',
            '## `field2`',
            '',
            '## `field3`',
            '',
        ]));
    }

    public function testDetectFirstTableWithDocumentationOutputContainingAnotherTable() {
        $this->assertEquals([
            '|Field              |Type |Null |Key |Default |Extra |',
            '|-------------------|-----|-----|----|--------|------|',
            '|[`field1`](#field1)|type1|null1|key1|default1|extra1|',
            '|[`field2`](#field2)|type2|null2|key2|default2|extra2|',
            '|[`field3`](#field3)|type3|null3|key3|default3|extra3|',
        ], TableDocumentation::detect_first_table_in_text([
            '# `display_table1`',
            '',
            '|Field              |Type |Null |Key |Default |Extra |',
            '|-------------------|-----|-----|----|--------|------|',
            '|[`field1`](#field1)|type1|null1|key1|default1|extra1|',
            '|[`field2`](#field2)|type2|null2|key2|default2|extra2|',
            '|[`field3`](#field3)|type3|null3|key3|default3|extra3|',
            '',
            '## `field1`',
            '|Field1  |Field2  | Field3  |',
            '|--------|--------|---------|',
            '|Value1__|Value2__|Value3___|',
            '',
            '## `field2`',
            '',
            '## `field3`',
            '',
        ]));
    }


    // detect_columns_in_text
    public function testDetectColumnsWithNoLines()
    {
        $this->assertEquals([], TableDocumentation::detect_columns_in_text([]));
    }

    public function testDetectColumnsWithLinesButNoColumns()
    {
        $this->assertEquals([], TableDocumentation::detect_columns_in_text([
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
        ], TableDocumentation::detect_columns_in_text([
            '## `firstcolumn`',
            '## `secondcolumn`',
            '## `thirdcolumn`'
        ]));
    }

    public function testDetectColumnsWithColumnsContainingUnderscores()
    {
        $this->assertEquals([
            'first_column',
            'second_column',
            'third_column'
        ], TableDocumentation::detect_columns_in_text([
            '## `first_column`',
            '## `second_column`',
            '## `third_column`'
        ]));
    }

    public function testDetectColumnsWithMixedLines()
    {
        $this->assertEquals([
            'first_column',
            'second_column',
            'third_column',
        ], TableDocumentation::detect_columns_in_text([
            '## `first_column`',
            'here is the description of the first column...',
            '## `second_column`',
            'here is the descriptio of the second column,',
            'which doesn\' fit in one line...',
            '## `third_column`',
            'and here is the description of the third column'
        ]));
    }
}
