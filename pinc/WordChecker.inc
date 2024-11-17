<?php

use voku\helper\UTF8;

class WordChecker
{
    public const GOOD_TEXT = 0;
    // WC_* provides 1,2,3,4
    public const ACCEPTED_WORD = 5;
    public const PUNCTUATION = 6;
    public const UNCOMMON_SCRIPT_BASE = 10;

    private string $good_char_buffer;
    private array $wc_array;
    private int $text_index;
    private array $puncArray;
    private string $page_text;

    public function __construct()
    {
        global $puncCharacters;

        $this->puncArray = UTF8::str_split($puncCharacters);
    }

    public function analyse(string $projectid, string $page_text, array $languages, array $accepted_words): array
    {
        $this->page_text = $page_text;

        // this is the array which will be returned
        $this->wc_array = [];

        // this is used to accumulate good text
        $this->good_char_buffer = "";
        $this->text_index = 0;

        [$badWordHash, $languages, $messages] =
            get_bad_word_levels_for_project_text($this->page_text, $projectid, $languages, $accepted_words);

        [$uncommonScriptWords, $uncommonScripts] =
            get_words_with_uncommon_scripts(array_keys(get_distinct_words_in_text($this->page_text)));
        // If Greek is present in $uncommonScripts, we always want it to be first
        // so it always has the same color across pages and projects. We do this
        // only for Greek as it is the most common non-Latin script at pgdp.net.
        if (in_array("Greek", $uncommonScripts)) {
            $uncommonScripts = array_merge(["Greek"], array_diff($uncommonScripts, ["Greek"]));
        }
        $uncommonScriptMap = array_flip($uncommonScripts);

        $badWords = array_keys($badWordHash);
        foreach (get_all_words_in_text_byte_offset($this->page_text, true) as [$word, $wordIndex]) {
            // bad words and accepted words are appended directly to the wc_array
            // plain text and uncommon script words build good_char_buffer. This is then
            // appended to wc_array before the other word types and at the end.
            // punc chars and uncommon script chars are added individually.
            if ($word == "") {
                continue;
            }
            $this->append_good($wordIndex);
            if (in_array($word, $badWords)) {
                $this->append_word_to_array($word, $badWordHash[$word]);
            } elseif (in_array($word, $accepted_words)) {
                $this->append_word_to_array($word, self::ACCEPTED_WORD);
            } elseif (in_array($word, $uncommonScriptWords)) {
                $this->process_uncommon_script($word, $uncommonScriptMap);
            }
        }
        // append any remaining good text
        $this->append_good(strlen($this->page_text));
        $this->append_good_char_buffer();
        return [
            "wc_array" => $this->wc_array,
            "scripts" => $uncommonScripts,
            "messages" => $messages,
        ];
    }

    // append text up to $wordIndex
    private function append_good(int $wordIndex): void
    {
        // look for punctuation in this text
        while ($this->text_index < $wordIndex) {
            // get each grapheme in turn
            $character = grapheme_extract($this->page_text, 1, GRAPHEME_EXTR_COUNT, $this->text_index, $this->text_index);
            // punctuation except in an HTML entity (aka: &amp; &lt; &gt;)
            // (text_index has already advanced beyond the character)
            if (in_array($character, $this->puncArray) && !($character == ';' && preg_match("/&\w+;$|&#\d+;$/", substr($this->page_text, 0, $this->text_index)))) {
                $this->append_good_char_buffer();
                $this->wc_array[] = [$character, self::PUNCTUATION];
            } else {
                $this->good_char_buffer .= $character;
            }
        }
    }

    private function append_word_to_array(string $word, int $type): void
    {
        $this->append_good_char_buffer();
        $this->wc_array[] = [$word, $type];
        $this->text_index += strlen($word);
    }

    private function process_uncommon_script(string $word, array $scriptMap): void
    {
        global $common_unicode_scripts;

        foreach (split_multiscript_string($word) as [$chunk, $chunk_script]) {
            if (in_array($chunk_script, $common_unicode_scripts)) {
                $this->good_char_buffer .= $chunk;
            } else {
                $this->append_good_char_buffer();
                $this->wc_array[] = [$chunk, self::UNCOMMON_SCRIPT_BASE + $scriptMap[$chunk_script]];
            }
        }
        $this->text_index += strlen($word);
    }

    private function append_good_char_buffer(): void
    {
        if ($this->good_char_buffer !== "") {
            $this->wc_array[] = [$this->good_char_buffer, self::GOOD_TEXT];
            $this->good_char_buffer = "";
        }
    }
}
