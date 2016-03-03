<?php
$relPath='../../pinc/';
include_once($relPath.'MARCRecord.inc');

class MARCRecordTest extends PHPUnit_Framework_TestCase
{
    private $YAZ_ARRAY = NULL;
    private $YAZ_ARRAY_STR = NULL;

    protected function setUp()
    {
        // Load the yaz array from disk
        $yaz_array_b64 = file_get_contents("./data/yaz_array.b64");
        $this->YAZ_ARRAY = unserialize(base64_decode($yaz_array_b64));
        $this->YAZ_ARRAY_STR = base64_decode(
            file_get_contents("./data/yaz_array_str.b64"));
    }

    public function testGetTitle()
    {
        $title = marc_title($this->YAZ_ARRAY);
        $this->assertEquals(
            'The Backwoods Boy; or, The Boyhood and Manhood of Abraham Lincoln.',
            $title
        );
    }

    public function testGetAuthor()
    {
        $author = marc_author($this->YAZ_ARRAY);
        $this->assertEquals('Alger Jr., Horatio', $author);
    }

    public function testGetLCCN()
    {
        $lccn = marc_lccn($this->YAZ_ARRAY);
        $this->assertEquals('55055815', $lccn);
    }

    public function testGetISBN()
    {
        $isbn = marc_isbn($this->YAZ_ARRAY);
        $this->assertEquals('', $isbn);
    }

    public function testGetPages()
    {
        $pages = marc_pages($this->YAZ_ARRAY);
        $this->assertEquals('307 p.', $pages);
    }

    public function testGetDate()
    {
        $date = marc_date($this->YAZ_ARRAY);
        $this->assertEquals('[c1883', $date);
    }

    public function testGetLanguage()
    {
        $language = marc_language($this->YAZ_ARRAY);
        // 'ng ' doesn't seem like a language, but that's what the current
        // code returns, so we'll consider that valid for the moment
        $this->assertEquals('ng ', $language);
    }

    public function testGetLiteraryForm()
    {
        $literary_form = marc_literary_form($this->YAZ_ARRAY);
        $this->assertEquals('Biography', $literary_form);
    }

    public function testGetSubject()
    {
        $subject = marc_subject($this->YAZ_ARRAY);
        $this->assertEquals('', $subject);
    }

    public function testGetDescription()
    {
        $description = marc_description($this->YAZ_ARRAY);
        $this->assertEquals('', $description);
    }

    public function testGetPublisher()
    {
        $publisher = marc_publisher($this->YAZ_ARRAY);
        $this->assertEquals('Street and Smith, [c1883', $publisher);
    }

    public function testUpdateMarc()
    {
        $marc_array = $this->YAZ_ARRAY;

        $_POST = array(
            'nameofwork' => 'Title',
            'authorsname' => 'Author',
            'pri_language' => 'English',
            'genre' => 'Science',
        );
        $new_marc_array = update_marc_array($marc_array);

        $this->assertEquals('Title', marc_title($new_marc_array));
        $this->assertEquals('Author', marc_author($new_marc_array));
        $this->assertEquals('English', marc_language($new_marc_array));
        $this->assertEquals('Science', marc_literary_form($new_marc_array));
    }

    public function testArrayToString()
    {
        $marc_array = $this->YAZ_ARRAY;

        $marc_string = convert_marc_array_to_str($marc_array);
        $this->assertEquals($this->YAZ_ARRAY_STR, $marc_string);
    }
}
