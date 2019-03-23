<?php

/**
 * @backupGlobals disabled
 */
class MARCRecordTest extends PHPUnit\Framework\TestCase
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

    private function _load_record()
    {
        $marc_record = new MARCRecord();
        $marc_record->load_yaz_array($this->YAZ_ARRAY);
        return $marc_record;
    }

    public function testEmptyConstructor()
    {
        $marc_record = new MARCRecord();
        $this->assertEquals($marc_record->get_yaz_array(), array());
    }

    public function testLoadYazArray()
    {
        $marc_record = $this->_load_record();
        $this->assertEquals($marc_record->get_yaz_array(), $this->YAZ_ARRAY);
    }

    public function testGetTitle()
    {
        $marc_record = $this->_load_record();
        $this->assertEquals(
            'The Backwoods Boy; or, The Boyhood and Manhood of Abraham Lincoln.',
            $marc_record->title
        );
    }

    public function testGetAuthor()
    {
        $marc_record = $this->_load_record();
        $this->assertEquals($marc_record->author, 'Alger Jr., Horatio');
    }

    public function testGetLCCN()
    {
        $marc_record = $this->_load_record();
        $this->assertEquals($marc_record->lccn, '55055815');
    }

    public function testGetISBN()
    {
        $marc_record = $this->_load_record();
        $this->assertEquals($marc_record->isbn, '');
    }

    public function testGetPages()
    {
        $marc_record = $this->_load_record();
        $this->assertEquals('307 p.', $marc_record->pages);
    }

    public function testGetDate()
    {
        $marc_record = $this->_load_record();
        $this->assertEquals('[c1883', $marc_record->date);
    }

    public function testGetLanguage()
    {
        $marc_record = $this->_load_record();
        // 'ng ' doesn't seem like a language, but that's what the current
        // code returns, so we'll consider that valid for the moment
        $this->assertEquals('ng ', $marc_record->language);
    }

    public function testGetLiteraryForm()
    {
        $marc_record = $this->_load_record();
        $this->assertEquals('Biography', $marc_record->literary_form);
    }

    public function testGetSubject()
    {
        $marc_record = $this->_load_record();
        $this->assertEquals('', $marc_record->subject);
    }

    public function testGetDescription()
    {
        $marc_record = $this->_load_record();
        $this->assertEquals('', $marc_record->description);
    }

    public function testGetPublisher()
    {
        $marc_record = $this->_load_record();
        $this->assertEquals('Street and Smith, [c1883', $marc_record->publisher);
    }

    public function testArrayToString()
    {
        $marc_record = $this->_load_record();

        $marc_string = (string)$marc_record;
        $this->assertEquals($this->YAZ_ARRAY_STR, $marc_string);
    }
}
