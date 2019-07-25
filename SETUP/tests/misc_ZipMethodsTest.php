<?php

class ZipMethodsTest extends PHPUnit\Framework\TestCase
{
    public function testValidatingNonExistingFile()
    {
        $this->assertFalse(is_valid_zip_file('nonexisting_file.zip'));
    }

    public function testValidatingValidZipFile()
    {
        $this->assertTrue(is_valid_zip_file('./data/valid.zip'));
    }

    public function testValidatingValidEmptyZipFile()
    {
        $this->assertTrue(is_valid_zip_file('./data/empty.zip'));
    }

    public function testValidatingCorruptedZipFile()
    {
        $this->assertFalse(is_valid_zip_file('./data/corrupted.zip'));
    }

    public function testValidatingValidZipFileWithInvalidExtension()
    {
        $this->assertFalse(is_valid_zip_file('./data/wrong.extension'));
    }

    public function testValidatingNonExistingFileWithDisabledExtensionCheck()
    {
        $this->assertFalse(is_valid_zip_file('nonexisting_file.zip', true));
    }

    public function testValidatingValidZipFileWithDisabledExtensionCheck()
    {
        $this->assertTrue(is_valid_zip_file('./data/valid.zip', true));
    }

    public function testValidatingValidEmptyZipFileWithDisabledExtensionCheck()
    {
        $this->assertTrue(is_valid_zip_file('./data/empty.zip', true));
    }

    public function testValidatingCorruptedZipFileWithDisabledExtensionCheck()
    {
        $this->assertFalse(is_valid_zip_file('./data/corrupted.zip', true));
    }

    public function testValidatingValidZipFileWithInvalidExtensionWithDisabledExtensionCheck()
    {
        $this->assertTrue(is_valid_zip_file('./data/wrong.extension', true));
    }

    // Testing list_files_in_zip

    /**
     * @expectedException InvalidArgumentException
     */
    public function testListingContentsOfNonExistingFile()
    {
        list_files_in_zip('nonexisting_file.zip');
    }

    public function testListingContentsOfNonEmptyZipFile() {
        $this->assertEquals(
            list_files_in_zip('./data/valid.zip'),
            ["first", "second", "third"]
        );
    }

    public function testListingContentsOfEmptyZipFile() {
        $this->assertEquals(
            list_files_in_zip('./data/empty.zip'),
            []
        );
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testListingContentsOfCorruptedZipFile()
    {
        list_files_in_zip('./data/corrupted.zip');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testListingContentsOfValidZipFileWithInvalidExtension()
    {
        list_files_in_zip('./data/wrong.extension');
    }
}
