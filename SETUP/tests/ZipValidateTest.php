<?php

class ZipValidateTest extends PHPUnit\Framework\TestCase
{
    public const TEMPORARY_EXTRACTION_DIRECTORY = 'tmp';

    protected function setUp(): void
    {
    }

    protected function tearDown(): void
    {
    }

    // Testing validate_zip_file

    public function testValidatingNonExistingFile()
    {
        $this->expectExceptionMessage("no file");
        validate_zip_file('nonexisting_file.zip');
    }

    public function testValidatingValidZipFile()
    {
        validate_zip_file('./data/valid.zip');
        $this->assertTrue(true);
    }

    public function testValidatingValidEmptyZipFile()
    {
        validate_zip_file('./data/empty.zip');
        $this->assertTrue(true);
    }

    public function testValidatingCorruptedZipFile()
    {
        $this->expectExceptionMessage("ZipArchive::open() returned 'Zip archive inconsistent.'");
        validate_zip_file('./data/corrupted.zip');
    }

    public function testValidatingValidZipFileWithInvalidExtension()
    {
        $this->expectExceptionMessage("wrong extension");
        validate_zip_file('./data/wrong.extension');
    }

    public function testValidatingNonExistingFileWithDisabledExtensionCheck()
    {
        $this->expectExceptionMessage("no file");
        validate_zip_file('nonexisting_file.zip', true);
    }

    public function testValidatingValidZipFileWithDisabledExtensionCheck()
    {
        validate_zip_file('./data/valid.zip', true);
        $this->assertTrue(true);
    }

    public function testValidatingValidEmptyZipFileWithDisabledExtensionCheck()
    {
        validate_zip_file('./data/empty.zip', true);
        $this->assertTrue(true);
    }

    public function testValidatingCorruptedZipFileWithDisabledExtensionCheck()
    {
        $this->expectExceptionMessage("ZipArchive::open() returned 'Zip archive inconsistent.'");
        validate_zip_file('./data/corrupted.zip', true);
    }

    public function testValidatingValidZipFileWithInvalidExtensionWithDisabledExtensionCheck()
    {
        validate_zip_file('./data/wrong.extension', true);
        $this->assertTrue(true);
    }

    public function testValidatingNonZipFile()
    {
        $this->expectExceptionMessage("ZipArchive::open() returned 'Not a zip archive.'");
        validate_zip_file('./data/not_zip.zip');
    }
}
