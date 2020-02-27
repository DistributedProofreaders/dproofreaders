<?php
include_once($relPath.'CharSuites.inc');

class QuizCharsuiteSet
{
    use CharSuiteSet;

    public function get_charsuites()
    {
        $charsuites[] = CharSuites::get('basic-latin');
        return $charsuites;
    }
}
