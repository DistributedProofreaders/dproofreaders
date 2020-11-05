<?php

/**
 * @backupGlobals disabled
 */
class MARCRecordTest extends PHPUnit\Framework\TestCase
{
    private $YAZ_ARRAY = NULL;
    private $YAZ_ARRAY_STR = NULL;

    protected function setUp(): void
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
        $this->assertEquals(array(), $marc_record->get_yaz_array());
    }

    public function testLoadYazArray()
    {
        $marc_record = $this->_load_record();
        $this->assertEquals($this->YAZ_ARRAY, $marc_record->get_yaz_array());
    }

    public function testGetTitle()
    {
        $marc_record = $this->_load_record();
        $this->assertEquals(
            'The backwoods boy; or, The boyhood and manhood of Abraham Lincoln.',
            $marc_record->title
        );
    }

    public function testGetAuthor()
    {
        $marc_record = $this->_load_record();
        $this->assertEquals('Alger, Horatio', $marc_record->author);
    }

    public function testGetLCCN()
    {
        $marc_record = $this->_load_record();
        $this->assertEquals('55055815', $marc_record->lccn);
    }

    public function testGetISBN()
    {
        $marc_record = $this->_load_record();
        $this->assertEquals('', $marc_record->isbn);
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
        $this->assertEquals('English', $marc_record->language);
    }

    public function testGetLiteraryForm()
    {
        $marc_record = $this->_load_record();
        $this->assertEquals('', $marc_record->literary_form);
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
