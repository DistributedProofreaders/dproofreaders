<?
// This file creates quiz objects for each quiz with data in CVS
include_once($relPath."Quiz.inc");

$defined_quizzes = array('FQ','PQ');

$FQ = new Quiz(
 "FQ",
 _("Formatting Quiz"),
 array(
 _('Page one: Italics, bold, and small caps') => 'formatting1',
 _('Page two: Section headings, hyphens and em-dashes') => 'formatting2',
 _('Page three: Chapter headings and thought breaks') => 'formatting3',
 _('Page four: Poetry, blockquotes and lists') => 'formatting4',
 _('Page five: Illustrations and footnotes') => 'formatting5'),
 array()
);


$PQ = new Quiz(
 "PQ",
 "Proofreading Quiz",
 array(
 _('Page One') => 'step1',
 _('Page Two') => 'step2',
 _('Page Three') => 'step3',
 _('Page Four') => 'step4',
 _('Page Five') => 'step5'),
 array()
);

?>
