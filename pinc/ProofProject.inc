<?php
declare(strict_types=1);

class ProofProjectPage extends LPage
{
    public function __construct(ProofProject $proof_project, ProjectPage $project_page)
    {
        parent::__construct($proof_project->project, $project_page->page_name, $project_page->page_state, 0);
    }

    public function pp_checkout($user): array
    {
        parent::checkout($user);
        return $this->render_page_data();
    }

    public function attempt_checkin(string $page_text): array
    {
        global $pguser;

        $this->project->validate_text($page_text);
        // see comment in LPage.inc
        [$saved, $dpl_reached] = $this->attemptSaveAsDone($page_text, $pguser);

        if (!$saved) {
            $sentence = sprintf(
                _("Your page was not checked-in because you have already reached the daily page limit for %s. You can save it and will be able to check it in after server midnight."),
                $this->round->id
            );
            throw new DailyLimitExceededException($sentence);
        }

        if (!$dpl_reached) {
            $sentence = "";
            $status_code = 0;
        } else {
            $sentence = sprintf(
                _("Your page has been checked-in. However, you have now reached the daily page limit for %s."),
                $this->round->id
            );
            $status_code = 1;
        }
        return ["message" => $sentence, "status" => $status_code];
    }

    public function save(string $page_text): array
    {
        global $pguser;
        $this->project->validate_text($page_text);
        $this->saveAsInProgress($page_text, $pguser);
        return $this->get_page_text_data();
    }

    public function save_and_revert(string $page_text): array
    {
        global $pguser;
        $this->project->validate_text($page_text);
        $this->saveAsInProgress($page_text, $pguser);
        $this->revertToOriginal();
        return $this->get_page_text_data();
    }

    public function pp_resume_page(): array
    {
        global $pguser;

        parent::resume_page($pguser);
        return $this->render_page_data();
    }

    private function render_page_data(): array
    {
        return
            $this->get_page_text_data() +
            [
                "pagenum" => $this->get_filename(),
                "round_info" => $this->get_info(),
                "pagename" => $this->imagefile,
                "image_url" => "{$this->project->url}/{$this->imagefile}",
                "language_direction" => lang_direction(langcode2_for_langname($this->project->languages[0])),
            ];
    }

    public function get_page_text_data(): array
    {
        return [
            "text" => $this->get_text(),
            "pagestate" => $this->page_state,
            "saved" => $this->can_be_reverted_to_last_save(),
        ];
    }

    public function wc_report(array $accepted_words): void
    {
        global $pguser;

        _Page_require(
            $this->projectid,
            $this->imagefile,
            [$this->round->page_out_state, $this->round->page_temp_state],
            $this->round->user_column_name,
            $pguser,
            'report'
        );
        save_wordcheck_event(
            $this->projectid,
            $this->round->id,
            $this->imagefile,
            $pguser,
            $accepted_words
        );
    }
}

class ProofProject
{
    public $project;
    public $round;

    public function __construct(Project $project)
    {
        global $pguser;

        $project_round = $project->get_project_available_round();
        $project_round->validate_user_can_access($pguser);

        $this->project = $project;
        $this->round = $project_round;
    }

    public function checkout(): array
    {
        global $pguser;

        [$page_name, $state] = get_available_proof_page_array($this->project, $this->round, $pguser);
        $project_page = new ProjectPage($page_name, $state);
        $proof_project_page = new ProofProjectPage($this, $project_page);

        return $proof_project_page->pp_checkout($pguser);
    }
}

class TextChecker
{
    private const VALID_TEXT = 0;
    private const INVALID_TEXT = 1;

    private $text_array;
    private $valid_text_block;

    private function append_valid(): void
    {
        if ($this->valid_text_block != "") {
            $this->text_array[] = [$this->valid_text_block, "", self::VALID_TEXT];
            $this->valid_text_block = "";
        }
    }

    public function analyse(string $page_text, object $project): array
    {
        $pattern_string = build_character_regex_filter($project->get_valid_codepoints());
        $this->text_array = [];
        $this->valid_text_block = "";
        $all_valid = true;
        foreach (split_graphemes($page_text) as $grapheme) {
            if (1 === preg_match("/$pattern_string/u", $grapheme)) {
                $this->valid_text_block .= $grapheme;
            } else {
                $all_valid = false;
                $this->append_valid();
                $this->text_array[] = [$grapheme, utf8_character_name($grapheme), self::INVALID_TEXT];
            }
        }
        // append any remaining valid text
        $this->append_valid();
        return [
            "mark_array" => $this->text_array,
            "valid" => $all_valid,
        ];
    }
}

class WordChecker
{
    public const GOOD_TEXT = 0;
    // WC_* provides 1,2,3,4
    public const ACCEPTED_WORD = 5;
    public const PUNCTUATION = 6;
    public const UNCOMMON_SCRIPT_BASE = 10;

    private $good_char_buffer;
    private $wc_array;
    private $text_index;
    private $puncArray;
    private $page_text;

    public function __construct()
    {
        global $puncCharacters;

        $this->puncArray = make_grapheme_array($puncCharacters);
    }

    public function analyse($project, $page_text, $languages, $accepted_words): array
    {
        $project->validate_text($page_text);
        $this->page_text = $page_text;

        // this is the array which will be returned
        $this->wc_array = [];

        // this is used to accumulate good text
        $this->good_char_buffer = "";
        $this->text_index = 0;

        [$badWordHash, $languages, $messages] =
            get_bad_word_levels_for_project_text($this->page_text, $project->projectid, $languages, $accepted_words);

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
        ];
    }

    // append text up to $wordIndex
    private function append_good($wordIndex)
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

    private function append_word_to_array($word, $type)
    {
        $this->append_good_char_buffer();
        $this->wc_array[] = [$word, $type];
        $this->text_index += strlen($word);
    }

    private function process_uncommon_script($word, $scriptMap)
    {
        global $common_unicode_scripts;

        foreach (split_multiscript_string_mb($word) as [$chunk, $chunk_script]) {
            if (in_array($chunk_script, $common_unicode_scripts)) {
                $this->good_char_buffer .= $chunk;
            } else {
                $this->append_good_char_buffer();
                $this->wc_array[] = [$chunk, self::UNCOMMON_SCRIPT_BASE + $scriptMap[$chunk_script]];
            }
        }
        $this->text_index += strlen($word);
    }

    private function append_good_char_buffer()
    {
        if ($this->good_char_buffer !== "") {
            $this->wc_array[] = [$this->good_char_buffer, self::GOOD_TEXT];
            $this->good_char_buffer = "";
        }
    }
}

function make_grapheme_array($string)
{
    $next = 0;
    $maxbytes = strlen($string);
    $graphemes = [];
    while ($next < $maxbytes) {
        $grapheme = grapheme_extract($string, 1, GRAPHEME_EXTR_COUNT, $next, $next);
        if (false === $grapheme) {
            throw new Exception("grapheme_extract failure");
        }
        $graphemes[] = $grapheme;
    }
    return $graphemes;
}

/**
 * Split a string into chunks on Unicode boundaries.
 */
function split_multiscript_string_mb($string)
{
    $chunks = [];
    $chunk = null;
    $chunk_script = null;

    foreach (mb_str_split($string, 1, "UTF-8") as $char) {
        $script = utf8_char_script($char);
        if ($chunk === null) {
            $chunk = $char;
            $chunk_script = $script;
        } elseif ($script == $chunk_script || $script == "Inherited") {
            $chunk .= $char;
        } else {
            $chunks[] = [$chunk, $chunk_script];
            $chunk_script = $script;
            $chunk = $char;
        }
    }
    if ($chunk) {
        $chunks[] = [$chunk, $chunk_script];
    }

    return $chunks;
}
