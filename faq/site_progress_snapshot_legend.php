<?php
$relPath = '../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'faq.inc');
include_once($relPath.'theme.inc');

output_header(_('Site Progress Snapshot'), NO_STATSBAR);
?>

<h1>Site Progress Snapshot</h1>

<p>The Site Progress Snapshot is a status dashboard of how many projects there are in the various stages of production and how well we're tracking to the page goals for the day.</p>

<p>Each row has three major components:
<ul>
  <li>General activity information: abbreviation, name, and your access to that activity.</li>
  <li>Project-specific details.</li>
  <li>Page-specific details.</li>
</ul>
</p>


<h2>Activity information</h2>
<p>Every row contains information about the activity, including an abbreviation and name. Hovering over the name also shows a description of the round. For example:
<ul>
  <li><b>Abbreviation:</b> <?php echo $ELR_round->id; ?></li>
  <li><b>Name:</b> <?php echo $ELR_round->name; ?></li>
  <li><b>Description:</b> <?php echo $ELR_round->description; ?></li>
</ul>
</p>

<p>In addition each activity has an icon that represents your current ability to work in that stage:
<ul>
  <li><div class='access-yes'>✓</div> - You have access to, and are able to work in, this stage.</li>
  <li><div class='access-eligible'>ⓘ</div> - You satisfy the basic criteria and are eligible to work in this stage. Clicking on the icon will provide more information on how to obtain access.</li>
  <li><div class='access-no'>✗</div> - You are not yet eligible to work in this stage. Clicking on the icon will show you the entrance requirements for that stage.</li>
</ul>
</p>

<h2>Project information</h2>
<p>For each activity, information about projects in that activity is included. For projects in the proofreading rounds, this includes the Total, Waiting, and Available projects as well as a count of how many projects were Completed Today. For non-proofreading rounds, this includes the Total, Available, and In Progress projects.</p>

<p>Project numbers can optionally be filtered using the filter criteria specified on corresponding pages. You can toggle between viewing project numbers for All projects or Filtered projects that match your filter.</p>

<p><i>Note:</i> The number of projects Completed Today is not available for the Filtered listing and will show N/A.</p>


<h2>Page information</h2>
<p>Each round includes information about the number of pages in that round. This includes the Target number (ie: Goal) of pages to complete that day, the number of pages actually completed and the current percentage of the goal.</p>

<p>The Status includes both the current percentage as well as a progress bar. The progress bar's color indicates how work is progressing throughout the day <i>based on the time of day</i>. If the progress bar is green, that round is on track to complete the target number of pages by the end of the day. If the progress bar is orange, that round is behind the target and will need some additional help to complete the target number of pages by the end of the day. If the progress bar is red, the round is less than 75% of the way through the pages it needs to complete the target by the end of the day. Using this sliding scale allows for a quick at-a-glance view of which round needs help to accomplish its goal.</p>


<?php
