<?php

class DocumentDatabaseTest extends PHPUnit\Framework\TestCase
{
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
