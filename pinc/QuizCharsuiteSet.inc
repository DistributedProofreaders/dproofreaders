<?php
include_once($relPath.'CharSuites.inc');

class QuizCharsuiteSet
{
    use CharSuiteSet;

    public function get_charsuites()
    {
        $charsuites[] = CharSuites::get('basic-latin');
        $charsuites[] = CharSuites::get('extended-european-latin');
        return $charsuites;
    }
}
