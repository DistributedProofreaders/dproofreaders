<?php

// Mock database / file system functions to simplify testing

$columns_per_table = [
    'table1' => [
        [ 'Field' => 'field1', 'Type' => 'type1', 'Null' => 'null1', 'Key' => 'key1', 'Default' => 'default1', 'Extra' => 'extra1' ],
        [ 'Field' => 'field2', 'Type' => 'type2', 'Null' => 'null2', 'Key' => 'key2', 'Default' => 'default2', 'Extra' => 'extra2' ],
        [ 'Field' => 'field3', 'Type' => 'type3', 'Null' => 'null3', 'Key' => 'key3', 'Default' => 'default3', 'Extra' => 'extra3' ],
    ],
    'table2' => [
        [ 'Field' => 'field1', 'Type' => 'type1', 'Null' => 'null1', 'Key' => 'key1', 'Default' => 'default1', 'Extra' => 'extra1' ],
        [ 'Field' => 'field2', 'Type' => 'type2', 'Null' => 'null2', 'Key' => 'key2', 'Default' => 'default2', 'Extra' => 'extra2' ],
        [ 'Field' => 'field3', 'Type' => 'type3', 'Null' => 'null3', 'Key' => 'key3', 'Default' => 'default3', 'Extra' => 'extra3' ],
    ],
    'projectIDabcd' => [
        [ 'Field' => 'field1', 'Type' => 'type1', 'Null' => 'null1', 'Key' => 'key1', 'Default' => 'default1', 'Extra' => 'extra1' ],
        [ 'Field' => 'field2', 'Type' => 'type2', 'Null' => 'null2', 'Key' => 'key2', 'Default' => 'default2', 'Extra' => 'extra2' ],
        [ 'Field' => 'field3', 'Type' => 'type3', 'Null' => 'null3', 'Key' => 'key3', 'Default' => 'default3', 'Extra' => 'extra3' ],
    ],
];

$table_names_in_database = [ [ 'table1' ], [ 'projectIDabcd' ] ];

$write_to_path_storage = [
    'filename' => 'contents'
];

function query_columns_for_table(string $table_name): array {
    global $columns_per_table;

    return $columns_per_table[$table_name];
}

function query_table_names_in_current_database(): array {
    global $table_names_in_database;

    return $table_names_in_database;
}

function write_to_file(string $file_path, string $contents) {
    global $write_to_path_storage;

    $write_to_path_storage[$file_path] = $contents;
}

class DocumentDatabaseTest extends PHPUnit\Framework\TestCase
{
    protected function setUp()
    {
        // Clear write to path storage, the rest should not be changed by the tests
        global $write_to_path_storage;

        $write_to_path_storage = [];
    }

    // generate_files_for_all_tables
    public function testGenerateFilesForAllTables() {
        global $write_to_path_storage;

        generate_files_for_all_tables('docs');

        $result = $write_to_path_storage;

        $this->assertEquals([
            'docs/table1.md' => implode("\n", [
                '# table1',
                '',
                '|Field            |Type |Null |Key |Default |Extra |',
                '|-----------------|-----|-----|----|--------|------|',
                '|[field1](#field1)|type1|null1|key1|default1|extra1|',
                '|[field2](#field2)|type2|null2|key2|default2|extra2|',
                '|[field3](#field3)|type3|null3|key3|default3|extra3|',
                '',
                '## field1',
                '',
                '## field2',
                '',
                '## field3',
                '',
            ]),
            'docs/$projectid.md' => implode("\n", [
                '# $projectid',
                '',
                '|Field            |Type |Null |Key |Default |Extra |',
                '|-----------------|-----|-----|----|--------|------|',
                '|[field1](#field1)|type1|null1|key1|default1|extra1|',
                '|[field2](#field2)|type2|null2|key2|default2|extra2|',
                '|[field3](#field3)|type3|null3|key3|default3|extra3|',
                '',
                '## field1',
                '',
                '## field2',
                '',
                '## field3',
                '',
            ])
        ], $result);
    }

    // generate_file_for_table
    public function testGenerateFileForTableForNormalTable() {
        global $write_to_path_storage;

        generate_file_for_table('table1', 'table1.md', 'display_table1');

        $result = $write_to_path_storage['table1.md'];

        $this->assertEquals(implode("\n", [
            '# display_table1',
            '',
            '|Field            |Type |Null |Key |Default |Extra |',
            '|-----------------|-----|-----|----|--------|------|',
            '|[field1](#field1)|type1|null1|key1|default1|extra1|',
            '|[field2](#field2)|type2|null2|key2|default2|extra2|',
            '|[field3](#field3)|type3|null3|key3|default3|extra3|',
            '',
            '## field1',
            '',
            '## field2',
            '',
            '## field3',
            '',
        ]), $result);
    }

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
