<?php

class ZipMethodsTest extends PHPUnit\Framework\TestCase
{
    public const TEMPORARY_EXTRACTION_DIRECTORY = 'tmp';

    protected function setUp(): void
    {
        mkdir(self::TEMPORARY_EXTRACTION_DIRECTORY);
    }

    protected function tearDown(): void
    {
        // Delete all files in the temporary extraction directory
        //   https://www.php.net/manual/en/function.unlink.php#109971
        array_map('unlink', glob(self::TEMPORARY_EXTRACTION_DIRECTORY . '/*'));

        rmdir(self::TEMPORARY_EXTRACTION_DIRECTORY);
    }

    // Testing is_valid_zip_file

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

    // Testing validate_zip_file

    public function testValidateNonExistingFile()
    {
        $this->expectExceptionMessage("no file");
        validate_zip_file('nonexisting_file.zip');
    }

    public function testValidateValidZipFile()
    {
        validate_zip_file('./data/valid.zip');
        $this->assertTrue(true);
    }

    public function testValidateValidEmptyZipFile()
    {
        validate_zip_file('./data/empty.zip');
        $this->assertTrue(true);
    }

    public function testValidateCorruptedZipFile()
    {
        $this->expectExceptionMessage("ZipArchive::open() returned 'Zip archive inconsistent.'");
        validate_zip_file('./data/corrupted.zip');
    }

    public function testValidateValidZipFileWithInvalidExtension()
    {
        $this->expectExceptionMessage("wrong extension");
        validate_zip_file('./data/wrong.extension');
    }

    public function testValidateNonExistingFileWithDisabledExtensionCheck()
    {
        $this->expectExceptionMessage("no file");
        validate_zip_file('nonexisting_file.zip', true);
    }

    public function testValidateValidZipFileWithDisabledExtensionCheck()
    {
        validate_zip_file('./data/valid.zip', true);
        $this->assertTrue(true);
    }

    public function testValidateValidEmptyZipFileWithDisabledExtensionCheck()
    {
        validate_zip_file('./data/empty.zip', true);
        $this->assertTrue(true);
    }

    public function testValidateCorruptedZipFileWithDisabledExtensionCheck()
    {
        $this->expectExceptionMessage("ZipArchive::open() returned 'Zip archive inconsistent.'");
        validate_zip_file('./data/corrupted.zip', true);
    }

    public function testValidateValidZipFileWithInvalidExtensionWithDisabledExtensionCheck()
    {
        validate_zip_file('./data/wrong.extension', true);
        $this->assertTrue(true);
    }

    public function testValidateNonZipFile()
    {
        $this->expectExceptionMessage("ZipArchive::open() returned 'Not a zip archive.'");
        validate_zip_file('./data/not_zip.zip');
    }

    // Testing list_files_in_zip

    public function testListingContentsOfNonExistingFile()
    {
        $this->expectException(InvalidArgumentException::class);
        list_files_in_zip('nonexisting_file.zip');
    }

    public function testListingContentsOfNonEmptyZipFile()
    {
        $this->assertEquals(
            list_files_in_zip('./data/valid.zip'),
            ["first", "second", "third"]
        );
    }

    public function testListingContentsOfEmptyZipFile()
    {
        $this->assertEquals(
            list_files_in_zip('./data/empty.zip'),
            []
        );
    }

    public function testListingContentsOfCorruptedZipFile()
    {
        $this->expectException(InvalidArgumentException::class);
        list_files_in_zip('./data/corrupted.zip');
    }

    public function testListingContentsOfValidZipFileWithInvalidExtension()
    {
        $this->expectException(InvalidArgumentException::class);
        list_files_in_zip('./data/wrong.extension');
    }

    // Testing extract_zip_to

    public function testExtractingNonExistingZipFile()
    {
        $this->expectException(InvalidArgumentException::class);
        extract_zip_to('nonexisting_file.zip', self::TEMPORARY_EXTRACTION_DIRECTORY);
    }

    public function testExtractingNonEmptyZipFile()
    {
        $this->assertTrue(
            extract_zip_to('./data/valid.zip', self::TEMPORARY_EXTRACTION_DIRECTORY)
        );

        $this->assertTrue(file_exists(self::TEMPORARY_EXTRACTION_DIRECTORY . '/first'));
        $this->assertTrue(file_exists(self::TEMPORARY_EXTRACTION_DIRECTORY . '/second'));
        $this->assertTrue(file_exists(self::TEMPORARY_EXTRACTION_DIRECTORY . '/third'));
    }

    public function testExtractingEmptyZipFile()
    {
        $this->assertTrue(
            extract_zip_to('./data/empty.zip', self::TEMPORARY_EXTRACTION_DIRECTORY)
        );

        $this->assertEquals(count(glob(self::TEMPORARY_EXTRACTION_DIRECTORY . '/*')), 0);
    }

    public function testExtractingCorruptedZipFile()
    {
        $this->expectException(InvalidArgumentException::class);
        extract_zip_to('./data/corrupted.zip', self::TEMPORARY_EXTRACTION_DIRECTORY);
    }

    public function testExtractingValidZipFileWithInvalidExtension()
    {
        $this->expectException(InvalidArgumentException::class);
        extract_zip_to('./data/wrong.extension', self::TEMPORARY_EXTRACTION_DIRECTORY);
    }

    public function testExtractingValidZipFileToNonExistingDirectory()
    {
        $this->expectException(InvalidArgumentException::class);
        extract_zip_to('./data/empty.zip', 'non_existing_directory');
    }

    // Testing create_zip_from

    public function testCreatingZipFileFromDirectory()
    {
        $this->assertTrue(create_zip_from(
            ['manual_web/page_compare'],
            self::TEMPORARY_EXTRACTION_DIRECTORY . '/manual_web.zip'
        ));

        $this->assertTrue(is_valid_zip_file(self::TEMPORARY_EXTRACTION_DIRECTORY . '/manual_web.zip'));
    }

    public function testCreatingZipFile()
    {
        $this->assertTrue(create_zip_from(
            ['./*.php'],
            self::TEMPORARY_EXTRACTION_DIRECTORY . '/php_files.zip'
        ));

        $this->assertTrue(is_valid_zip_file(self::TEMPORARY_EXTRACTION_DIRECTORY . '/php_files.zip'));
    }

    public function testCreatingZipFileWithInvalidPath()
    {
        $this->expectException(InvalidArgumentException::class);
        create_zip_from(['./*.php'], 'misc_ZipMethodsTest.php');
    }

    public function testCreatingZipFileContainingNonExistingFiles()
    {
        $this->expectException(InvalidArgumentException::class);
        create_zip_from(
            ['this_does_not_exist'],
            self::TEMPORARY_EXTRACTION_DIRECTORY . '/php_files.zip'
        );
    }
}
