<?php

class FlattenDirectoryTest extends PHPUnit\Framework\TestCase
{
    public const TMP_DIRECTORY_PATH = 'tmp_flatten_directory';
    public const EMPTY_DIRECTORY_PATH = 'tmp_flatten_directory/empty_directory';
    public const NESTED_DIRECTORY_PATH = 'tmp_flatten_directory/nested_directory';

    protected function setUp(): void
    {
        mkdir(self::TMP_DIRECTORY_PATH);
        mkdir(self::EMPTY_DIRECTORY_PATH);
        mkdir(self::NESTED_DIRECTORY_PATH);

        mkdir(self::NESTED_DIRECTORY_PATH.'/one');
        mkdir(self::NESTED_DIRECTORY_PATH.'/one/two1');
        mkdir(self::NESTED_DIRECTORY_PATH.'/one/two1/three');
        mkdir(self::NESTED_DIRECTORY_PATH.'/one/two2');

        touch(self::NESTED_DIRECTORY_PATH.'/a');
        touch(self::NESTED_DIRECTORY_PATH.'/one/b');
        touch(self::NESTED_DIRECTORY_PATH.'/one/two1/c');
        touch(self::NESTED_DIRECTORY_PATH.'/one/two1/d');
        touch(self::NESTED_DIRECTORY_PATH.'/one/two1/three/e');
        touch(self::NESTED_DIRECTORY_PATH.'/one/two2/f');
    }

    protected function tearDown(): void
    {
        $this->recursiveDeleteDirectory(self::TMP_DIRECTORY_PATH);
    }

    private function recursiveDeleteDirectory(string $path_to_directory): void
    {
        // Copied from https://stackoverflow.com/a/3352564
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path_to_directory, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($files as $fileinfo) {
            $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
            $todo($fileinfo->getRealPath());
        }

        rmdir($path_to_directory);
    }

    public function testFlatteningEmptyDirectory(): void
    {
        flatten_directory(self::EMPTY_DIRECTORY_PATH);

        // PHPUnit labels tests without at least one assert as risky
        $this->assertTrue(true);
    }

    public function testFlatteningNonExistentDirectory(): void
    {
        $this->expectException(InvalidArgumentException::class);
        flatten_directory('non_existent_directory');
    }

    public function testFlatteningDirectoryWithNestedSubdirectories(): void
    {
        flatten_directory(self::NESTED_DIRECTORY_PATH);

        $actual = glob(self::NESTED_DIRECTORY_PATH.'/*');
        $expected = ['a', 'b', 'c', 'd', 'e', 'f'];

        $this->assertEquals(sort($actual), sort($expected));
    }
}
