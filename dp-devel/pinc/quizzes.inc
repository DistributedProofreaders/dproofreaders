<?php
// This file creates quiz objects for each quiz with data in CVS
include_once($relPath."Quiz.inc");

$valid_quiz_ids = array('PQ','FQ');

$FQ = new Quiz(
 "FQ",
 _("Formatting Quiz"),
 _("This quiz covers the corrections you should make in the formatting rounds. There's no need to do this quiz until you are ready to work in F1, and in fact it may confuse you if you try it earlier."),
 array(
 _('Page one: Italics, bold, and small caps') => 'formatting1',
 _('Page two: Section headings, hyphens and em-dashes') => 'formatting2',
 _('Page three: Chapter headings and thought breaks') => 'formatting3',
 _('Page four: Poetry, blockquotes and lists') => 'formatting4',
 _('Page five: Illustrations and footnotes') => 'formatting5'),
 array(
 'maximum_age' => 15778463) // 6 months
);


$PQ = new Quiz(
 "PQ",
 "Proofreading Quiz",
 _("This quiz covers the corrections you should make in the proofreading rounds."),
 array(
 _('Page One') => 'step1',
 _('Page Two') => 'step2',
 _('Page Three') => 'step3',
 _('Page Four') => 'step4',
 _('Page Five') => 'step5'),
  array(
 'maximum_age' => 15778463) // 6 months
);

// A list of valid quiz page ids used for constructing filenames
// used in the quizzes, eg qd_formatting3.inc, qi_step2.png etc

$valid_page_ids = array();
foreach($valid_quiz_ids as $quiz_id) {
    foreach(${$quiz_id}->pages as $page_id) {
        array_push($valid_page_ids, $page_id);
    }
}
?>
