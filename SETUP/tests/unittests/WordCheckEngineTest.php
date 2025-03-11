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

        N̈oon

        Γreat

        b[oe]uf

        EOTEXT;

    public function testGetAllWordsInTextNoOffsets(): void
    {
        $words = get_all_words_in_text($this->TEXT1);
        $this->assertEquals($words[0], "Not");
        $this->assertEquals($words[89], "words");
        $this->assertEquals($words[93], "N̈oon");
    }

    public function testGetAllWordsInTextWithOffsets(): void
    {
        $words = get_all_words_in_text($this->TEXT1, true);
        $this->assertEquals($words[0], "Not");
        $this->assertEquals($words[530], "words");
    }

    public function testGetDistinctWordsInTextSmall(): void
    {
        $words = get_distinct_words_in_text($this->TEXT1);
        $this->assertEquals($words["of"], 4);
        $this->assertEquals($words["words"], 1);
    }

    public function testGetDistinctWordsInTextLarge(): void
    {
        // We need to make $text large enough that the function uses
        // the too-big function.
        $multiplier = 100;
        $text = '';
        for ($i = 0; $i < $multiplier; $i++) {
            $text .= $this->TEXT1;
        }

        $words = get_distinct_words_in_text($text);
        $this->assertEquals($words["of"], 4 * $multiplier);
        $this->assertEquals($words["words"], 1 * $multiplier);
    }

    public function testGetDistinctWordsInTextArray(): void
    {
        $array_size = 100;
        $text_array = [];
        for ($i = 0; $i < $array_size; $i++) {
            $text_array[] = $this->TEXT1;
        }

        $words = get_distinct_words_in_text($text_array);
        $this->assertEquals($words["of"], 4 * $array_size);
        $this->assertEquals($words["words"], 1 * $array_size);
    }

    public function testGetBadWordsWithDiacriticalMarkup(): void
    {
        $words = get_distinct_words_in_text($this->TEXT1);
        $bad_words = get_bad_words_with_diacritical_markup($words);
        $this->assertEquals(count($bad_words), 1);
        $this->assertEquals($bad_words[0], "b[oe]uf");
    }

    public function testGetBadWordsViaPattern(): void
    {
        $languages = ["English"];

        $words = get_distinct_words_in_text($this->TEXT1);
        $bad_words = get_bad_words_via_pattern($words, $languages);
        $this->assertEquals(count($bad_words), 1);
        $this->assertEquals($bad_words[0], "a1l");
    }

    public function testGetBadWordsForTextNoWordLists(): void
    {
        $languages = ["English"];

        [$input_words_w_freq, $bad_words, $messages] =
            get_bad_words_for_text($this->TEXT1, $languages);

        $this->assertEquals(count($messages), 0);
        $this->assertEquals(count($bad_words), 3);
        $this->assertEquals($bad_words["Snelling"], WC_WORLD);
        $this->assertEquals($bad_words["b[oe]uf"], WC_WORLD);
        $this->assertEquals($bad_words["a1l"], WC_SITE);
    }

    public function testGetBadWordsForTextSiteWordList(): void
    {
        $languages = ["English"];

        $word_lists = [
            "site_bad" => ["disembarked"],
            "site_good" => ["Snelling", "b[oe]uf"],
        ];

        [$input_words_w_freq, $bad_words, $messages] =
            get_bad_words_for_text($this->TEXT1, $languages, $word_lists);

        $this->assertEquals(count($messages), 0);
        $this->assertEquals(count($bad_words), 2);
        $this->assertEquals($bad_words["disembarked"], WC_SITE);
        $this->assertEquals($bad_words["a1l"], WC_SITE);
    }

    public function testGetBadWordsForTextProjectWordList(): void
    {
        $languages = ["English"];

        $word_lists = [
            "project_bad" => ["disembarked"],
            "project_good" => ["Snelling"],
        ];

        [$input_words_w_freq, $bad_words, $messages] =
            get_bad_words_for_text($this->TEXT1, $languages, $word_lists);

        $this->assertEquals(count($messages), 0);
        $this->assertEquals(count($bad_words), 3);
        $this->assertEquals($bad_words["b[oe]uf"], WC_WORLD);
        $this->assertEquals($bad_words["disembarked"], WC_PROJECT);
        $this->assertEquals($bad_words["a1l"], WC_SITE);
    }

    public function testGetBadWordsForTextSiteAndProjectWordList(): void
    {
        $languages = ["English"];

        $word_lists = [
            "site_bad" => ["disembarked"],
            "site_good" => ["Snelling"],
            "project_bad" => ["opposite"],
        ];

        [$input_words_w_freq, $bad_words, $messages] =
            get_bad_words_for_text($this->TEXT1, $languages, $word_lists);

        $this->assertEquals(count($messages), 0);
        $this->assertEquals(count($bad_words), 4);
        $this->assertEquals($bad_words["b[oe]uf"], WC_WORLD);
        $this->assertEquals($bad_words["disembarked"], WC_SITE);
        $this->assertEquals($bad_words["opposite"], WC_PROJECT);
        $this->assertEquals($bad_words["a1l"], WC_SITE);
    }

    public function testGetBadWordsForTextAdhocWordList(): void
    {
        $languages = ["English"];

        $word_lists = [
            "adhoc_good" => ["Snelling"],
        ];

        [$input_words_w_freq, $bad_words, $messages] =
            get_bad_words_for_text($this->TEXT1, $languages, $word_lists);

        $this->assertEquals(count($messages), 0);
        $this->assertEquals(count($bad_words), 2);
        $this->assertEquals($bad_words["b[oe]uf"], WC_WORLD);
        $this->assertEquals($bad_words["a1l"], WC_SITE);
    }

    public function testWordListNormalization(): void
    {
        $words = [
            " one",
            "two ",
            "three  3",
            "Ṅice",  // U+004e>U+0307 but normalizes to U+1e44
        ];
        $norm_words = normalize_word_list($words);

        $expected_words = [
            "one",
            "two",
            "three",
            "Ṅice",  // U+1e44
        ];

        $this->assertEquals($norm_words, $expected_words);
    }

    public function testGetWordsWithUncommonScripts(): void
    {
        $words = [
            "one",
            "o'neill",
            "Ṅice",
            "Диф",
            "Жblarg",
            "Δπ",
        ];
        [$uncommon_words, $scripts] = get_words_with_uncommon_scripts($words);

        $expected_words = [
            "Диф",
            "Жblarg",
            "Δπ",
        ];
        $expected_scripts = [
            "Cyrillic",
            "Greek",
        ];

        $this->assertEquals($expected_words, $uncommon_words);
        $this->assertEquals($expected_scripts, $scripts);
    }

    // Regression tests for Task 2100
    private $ENGLISH_TEXT = <<<EOTEXT
        perfectly valid English words
        blearg blearg's
        EOTEXT;

    public function testEnglish()
    {
        $languages = ["English"];
        $words = get_distinct_words_in_text($this->ENGLISH_TEXT);

        [$bad_words, $messages] =
            get_bad_words_via_external_checker($words, $languages);

        $this->assertEquals(["blearg", "blearg's"], $bad_words);
    }

    private $FRENCH_TEXT = <<<EOTEXT
        mots français valides
        bonjuor l'absolulion
        EOTEXT;

    public function testFrench()
    {
        $languages = ["French"];
        $words = get_distinct_words_in_text($this->FRENCH_TEXT);

        [$bad_words, $messages] =
            get_bad_words_via_external_checker($words, $languages);

        $this->assertEquals(["bonjuor", "l'absolulion"], $bad_words);
    }

    public function testEnglishFrench()
    {
        $languages = ["English", "French"];
        $words = get_distinct_words_in_text($this->ENGLISH_TEXT . ' ' . $this->FRENCH_TEXT);

        [$bad_words, $messages] =
            get_bad_words_via_external_checker($words, $languages);

        $this->assertEquals(["blearg", "blearg's", "bonjuor", "l'absolulion"], $bad_words);
    }
}
