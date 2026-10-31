<?php

class SpellCheckTest extends ProjectUtils
{
    public function testSpellCheckTextStrictComparison(): void
    {
        // Create a bad words list with a word that can loose compare
        $project = $this->_create_project();
        file_put_contents($project->dir . "/bad_words.txt", "12\n");

        // Do a spellcheck on text that contains two versions of the word
        // one that strictly compares, and one that loosely compares.
        // We want spellcheck to
        // a) not throw a TypeError calling _wrapBadWord
        // b) only wrap the bad word '12' and not the different word '012'
        $output_text = spellcheck_text(
            orig_text: "12\n012\n",
            projectid: $project->projectid,
            languages: ["en_US"],
            accepted_words: []
        )[0];
        $this->assertStringContainsString("value='12'", $output_text);
        $this->assertStringNotContainsString("value='012'", $output_text);
    }
}
