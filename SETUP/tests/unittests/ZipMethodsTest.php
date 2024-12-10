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

    public function testValidatingNonExistingFile(): void
    {
        $this->assertFalse(is_valid_zip_file('nonexisting_file.zip'));
    }

    public function testValidatingValidZipFile(): void
    {
        $this->assertTrue(is_valid_zip_file('./data/valid.zip'));
    }

    public function testValidatingValidEmptyZipFile(): void
    {
        $this->assertTrue(is_valid_zip_file('./data/empty.zip'));
    }

    public function testValidatingCorruptedZipFile(): void
    {
        $this->assertFalse(is_valid_zip_file('./data/corrupted.zip'));
    }

    public function testValidatingValidZipFileWithInvalidExtension(): void
    {
        $this->assertFalse(is_valid_zip_file('./data/wrong.extension'));
    }

    public function testValidatingNonExistingFileWithDisabledExtensionCheck(): void
    {
        $this->assertFalse(is_valid_zip_file('nonexisting_file.zip', true));
    }

    public function testValidatingValidZipFileWithDisabledExtensionCheck(): void
    {
        $this->assertTrue(is_valid_zip_file('./data/valid.zip', true));
    }

    public function testValidatingValidEmptyZipFileWithDisabledExtensionCheck(): void
    {
        $this->assertTrue(is_valid_zip_file('./data/empty.zip', true));
    }

    public function testValidatingCorruptedZipFileWithDisabledExtensionCheck(): void
    {
        $this->assertFalse(is_valid_zip_file('./data/corrupted.zip', true));
    }

    public function testValidatingValidZipFileWithInvalidExtensionWithDisabledExtensionCheck(): void
    {
        $this->assertTrue(is_valid_zip_file('./data/wrong.extension', true));
    }

    // Testing validate_zip_file

    public function testValidateNonExistingFile(): void
    {
        $this->expectExceptionMessage("no file");
        validate_zip_file('nonexisting_file.zip');
    }

    public function testValidateValidZipFile(): void
    {
        validate_zip_file('./data/valid.zip');
        $this->assertTrue(true);
    }

    public function testValidateValidEmptyZipFile(): void
    {
        validate_zip_file('./data/empty.zip');
        $this->assertTrue(true);
    }

    public function testValidateCorruptedZipFile(): void
    {
        $this->expectExceptionMessage("ZipArchive::open() returned 'Zip archive inconsistent.'");
        validate_zip_file('./data/corrupted.zip');
    }

    public function testValidateValidZipFileWithInvalidExtension(): void
    {
        $this->expectExceptionMessage("wrong extension");
        validate_zip_file('./data/wrong.extension');
    }

    public function testValidateNonExistingFileWithDisabledExtensionCheck(): void
    {
        $this->expectExceptionMessage("no file");
        validate_zip_file('nonexisting_file.zip', true);
    }

    public function testValidateValidZipFileWithDisabledExtensionCheck(): void
    {
        validate_zip_file('./data/valid.zip', true);
        $this->assertTrue(true);
    }

    public function testValidateValidEmptyZipFileWithDisabledExtensionCheck(): void
    {
        validate_zip_file('./data/empty.zip', true);
        $this->assertTrue(true);
    }

    public function testValidateCorruptedZipFileWithDisabledExtensionCheck(): void
    {
        $this->expectExceptionMessage("ZipArchive::open() returned 'Zip archive inconsistent.'");
        validate_zip_file('./data/corrupted.zip', true);
    }

    public function testValidateValidZipFileWithInvalidExtensionWithDisabledExtensionCheck(): void
    {
        validate_zip_file('./data/wrong.extension', true);
        $this->assertTrue(true);
    }

    public function testValidateNonZipFile(): void
    {
        $this->expectExceptionMessage("ZipArchive::open() returned 'Not a zip archive.'");
        validate_zip_file('./data/not_zip.zip');
    }

    // Testing list_files_in_zip

    public function testListingContentsOfNonExistingFile(): void
    {
        $this->expectException(InvalidArgumentException::class);
        list_files_in_zip('nonexisting_file.zip');
    }

    public function testListingContentsOfNonEmptyZipFile(): void
    {
        $this->assertEquals(
            list_files_in_zip('./data/valid.zip'),
            ["first", "second", "third"]
        );
    }

    public function testListingContentsOfEmptyZipFile(): void
    {
        $this->assertEquals(
            list_files_in_zip('./data/empty.zip'),
            []
        );
    }

    public function testListingContentsOfCorruptedZipFile(): void
    {
        $this->expectException(InvalidArgumentException::class);
        list_files_in_zip('./data/corrupted.zip');
    }

    public function testListingContentsOfValidZipFileWithInvalidExtension(): void
    {
        $this->expectException(InvalidArgumentException::class);
        list_files_in_zip('./data/wrong.extension');
    }

    // Testing extract_zip_to

    public function testExtractingNonExistingZipFile(): void
    {
        $this->expectException(InvalidArgumentException::class);
        extract_zip_to('nonexisting_file.zip', self::TEMPORARY_EXTRACTION_DIRECTORY);
    }

    public function testExtractingNonEmptyZipFile(): void
    {
        $this->assertTrue(
            extract_zip_to('./data/valid.zip', self::TEMPORARY_EXTRACTION_DIRECTORY)
        );

        $this->assertTrue(file_exists(self::TEMPORARY_EXTRACTION_DIRECTORY . '/first'));
        $this->assertTrue(file_exists(self::TEMPORARY_EXTRACTION_DIRECTORY . '/second'));
        $this->assertTrue(file_exists(self::TEMPORARY_EXTRACTION_DIRECTORY . '/third'));
    }

    public function testExtractingEmptyZipFile(): void
    {
        $this->assertTrue(
            extract_zip_to('./data/empty.zip', self::TEMPORARY_EXTRACTION_DIRECTORY)
        );

        $this->assertEquals(count(glob(self::TEMPORARY_EXTRACTION_DIRECTORY . '/*')), 0);
    }

    public function testExtractingCorruptedZipFile(): void
    {
        $this->expectException(InvalidArgumentException::class);
        extract_zip_to('./data/corrupted.zip', self::TEMPORARY_EXTRACTION_DIRECTORY);
    }

    public function testExtractingValidZipFileWithInvalidExtension(): void
    {
        $this->expectException(InvalidArgumentException::class);
        extract_zip_to('./data/wrong.extension', self::TEMPORARY_EXTRACTION_DIRECTORY);
    }

    public function testExtractingValidZipFileToNonExistingDirectory(): void
    {
        $this->expectException(InvalidArgumentException::class);
        extract_zip_to('./data/empty.zip', 'non_existing_directory');
    }

    // Testing create_zip_from

    public function testCreatingZipFileFromDirectory(): void
    {
        $this->assertTrue(create_zip_from(
            ['page_compare_data/usernotes'],
            self::TEMPORARY_EXTRACTION_DIRECTORY . '/page_compare_data.zip'
        ));

        $this->assertTrue(is_valid_zip_file(self::TEMPORARY_EXTRACTION_DIRECTORY . '/page_compare_data.zip'));
    }

    public function testCreatingZipFile(): void
    {
        $this->assertTrue(create_zip_from(
            ['./*.php'],
            self::TEMPORARY_EXTRACTION_DIRECTORY . '/php_files.zip'
        ));

        $this->assertTrue(is_valid_zip_file(self::TEMPORARY_EXTRACTION_DIRECTORY . '/php_files.zip'));
    }

    public function testCreatingZipFileWithInvalidPath(): void
    {
        $this->expectException(InvalidArgumentException::class);
        create_zip_from(['./*.php'], 'ZipMethodsTest.php');
    }

    public function testCreatingZipFileContainingNonExistingFiles(): void
    {
        $this->expectException(InvalidArgumentException::class);
        create_zip_from(
            ['this_does_not_exist'],
            self::TEMPORARY_EXTRACTION_DIRECTORY . '/php_files.zip'
        );
    }
}
