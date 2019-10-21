<?php

class WordCheckEngineTest extends PHPUnit\Framework\TestCase
{

    private $TEXT1 = <<<EOTEXT
Not until the twenty-fourth day of August, 1819,
when less than a hundred soldiers of the Fifth
United States Infantry disembarked opposite the
towering height where Ж few years later rose the
white walls of Fort Snelling, did the nation which
was to rule assert its power. The event was, indeed,
epochal. It not only marked a change in the sovereignty
over the vast region, but it also made possible
the development of those factors which were to bring
about the great transformation.

"double quotes" "single quotes" hyphenated-words

a1l 1st 33rd

EOTEXT;

    protected function setUp()
    {
        global $aspell_temp_dir;
        if(!is_dir($aspell_temp_dir))
            mkdir($aspell_temp_dir);
    }

    public function testGetAllWordsInTextNoOffsets()
    {
        $words = get_all_words_in_text($this->TEXT1);
        $this->assertEquals($words[0], "Not");
        $this->assertEquals($words[89], "words");
    }

    public function testGetAllWordsInTextWithOffsets()
    {
        $words = get_all_words_in_text($this->TEXT1, TRUE);
        $this->assertEquals($words[0], "Not");
        $this->assertEquals($words[530], "words");
    }

    public function testGetDistinctWordsInTextSmall()
    {
        $words = get_distinct_words_in_text($this->TEXT1);
        $this->assertEquals($words["of"], 4);
        $this->assertEquals($words["words"], 1);
    }

    public function testGetDistinctWordsInTextLarge()
    {
        // We need to make $text large enough that the function uses
        // the too-big function.
        $multiplier = 100;
        $text = '';
        for($i=0; $i<$multiplier; $i++)
            $text .= $this->TEXT1;

        $words = get_distinct_words_in_text($text);
        $this->assertEquals($words["of"], 4 * $multiplier);
        $this->assertEquals($words["words"], 1 * $multiplier);
    }

    public function testGetDistinctWordsInTextArray()
    {
        $array_size = 100;
        $text_array = array();
        for($i=0; $i<$array_size; $i++)
            $text_array[] = $this->TEXT1;

        $words = get_distinct_words_in_text($text_array);
        $this->assertEquals($words["of"], 4 * $array_size);
        $this->assertEquals($words["words"], 1 * $array_size);
    }

    public function testGetBadWordsViaPattern()
    {
        $languages = [ "English" ];

        $words = get_distinct_words_in_text($this->TEXT1);
        $bad_words = get_bad_words_via_pattern($words, $languages);
        $this->assertEquals(count($bad_words), 1);
        $this->assertEquals($bad_words[0], "a1l");
    }

    public function testGetBadWordsForTextNoWordLists()
    {
        $languages = [ "English" ];

        list($input_words_w_freq, $bad_words, $messages) =
            get_bad_words_for_text($this->TEXT1, $languages);

        $this->assertEquals(count($messages), 0);
        $this->assertEquals(count($bad_words), 2);
        $this->assertEquals($bad_words["Snelling"], WC_WORLD);
        $this->assertEquals($bad_words["a1l"], WC_SITE);
    }

    public function testGetBadWordsForTextSiteWordList()
    {
        $languages = [ "English" ];

        $word_lists = [
            "site_bad" => [ "disembarked" ],
            "site_good" =>  [ "Snelling" ],
        ];

        list($input_words_w_freq, $bad_words, $messages) =
            get_bad_words_for_text($this->TEXT1, $languages, $word_lists);

        $this->assertEquals(count($messages), 0);
        $this->assertEquals(count($bad_words), 2);
        $this->assertEquals($bad_words["disembarked"], WC_SITE);
        $this->assertEquals($bad_words["a1l"], WC_SITE);
    }

    public function testGetBadWordsForTextProjectWordList()
    {
        $languages = [ "English" ];

        $word_lists = [
            "project_bad" => [ "disembarked" ],
            "project_good" =>  [ "Snelling" ],
        ];

        list($input_words_w_freq, $bad_words, $messages) =
            get_bad_words_for_text($this->TEXT1, $languages, $word_lists);

        $this->assertEquals(count($messages), 0);
        $this->assertEquals(count($bad_words), 2);
        $this->assertEquals($bad_words["disembarked"], WC_PROJECT);
        $this->assertEquals($bad_words["a1l"], WC_SITE);
    }

    public function testGetBadWordsForTextSiteAndProjectWordList()
    {
        $languages = [ "English" ];

        $word_lists = [
            "site_bad" => [ "disembarked" ],
            "site_good" =>  [ "Snelling" ],
            "project_bad" => [ "opposite" ],
        ];

        list($input_words_w_freq, $bad_words, $messages) =
            get_bad_words_for_text($this->TEXT1, $languages, $word_lists);

        $this->assertEquals(count($messages), 0);
        $this->assertEquals(count($bad_words), 3);
        $this->assertEquals($bad_words["disembarked"], WC_SITE);
        $this->assertEquals($bad_words["opposite"], WC_PROJECT);
        $this->assertEquals($bad_words["a1l"], WC_SITE);
    }

    public function testGetBadWordsForTextAdhocWordList()
    {
        $languages = [ "English" ];

        $word_lists = [
            "adhoc_good" =>  [ "Snelling" ],
        ];

        list($input_words_w_freq, $bad_words, $messages) =
            get_bad_words_for_text($this->TEXT1, $languages, $word_lists);

        $this->assertEquals(count($messages), 0);
        $this->assertEquals(count($bad_words), 1);
        $this->assertEquals($bad_words["a1l"], WC_SITE);
    }
}
