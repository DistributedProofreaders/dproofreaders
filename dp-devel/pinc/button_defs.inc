<?

define('CHANGE_LAYOUT',               'A');
define('QUIT',                        'B');
define('REPORT_BAD_PAGE',             'C');
define('RETURN_PAGE',                 'D');
define('REVERT_TO_LAST_SAVE',         'E');
define('REVERT_TO_ORIGINAL',          'F');
define('RUN_COMMON_ERRORS_CHECK',     'G');
define('SAVE_AS_DONE_AND_PROOF_NEXT', 'H');
define('SAVE_AS_DONE_AND_QUIT',       'I');
define('SAVE_AS_IN_PROGRESS',         'J');
define('SET_IMAGE_ZOOM',              'K');
define('SPELL_CHECK',                 'L');

define('PROJECT_COMMENTS',            'M');
define('REFRESH_IMAGE',               'N');
define('REVERT_TO_LAST_SAVE_DISABLED','O');
define('SHOW_ALL_TEXT',               'P');

function echo_button( $button_id, $which_interface )
{
	global $userP;
	$CRLF = '\r\n';
	if ($which_interface == 's')
	{
		$label = 'value';
	}
	else if ($which_interface == 'a')
	{
		$label = 'alt';
	}
	else
	{
		die("echo_button: bad which_interface arg: '$which_interface'");
	}

	switch ($button_id)
	{
		case CHANGE_LAYOUT:
			if ( $userP['i_layout']==1)
			{
				$horvert = "Horizontal";
				$n = "5";
			}
			else
			{
				$horvert = "Vertical";
				$n = "4";
			}
			$attrs = array(
				'accesskey' => "6",
				'name'      => "button4",
				$label      => "Switch to $horvert",
				'title'     => "Save and Switch to $horvert Layout",
				'onclick'   => "return(confirm('Are you sure you want to {$CRLF}Change Layout{$CRLF} and {$CRLF}Save the Current Document?'));",
				'src'       => "gfx/bt$n.png",
			);
			break;

		case QUIT:
			$attrs = array(
				'accesskey' => "9",
				'name'      => "button3",
				$label      => "Stop Proofing",
				'title'     => "Stop Proofing",
				'onclick'   => "return(confirm('Are you sure you want to {$CRLF}{$CRLF}Stop Proofing?'));",
				'src'       => "gfx/bt1.png",
			);
			break;

		case REPORT_BAD_PAGE:
			$attrs = array(
				'accesskey' => '4',
				'name'      => 'button6',
				$label      => 'Report Bad Page',
				'title'     => 'Report Bad Page',
				'src'       => 'gfx/bt14.png',
			);
			break;

		case RETURN_PAGE:
			$attrs = array(
				'accesskey' => "3",
				'name'      => "button7",
				$label      => "Return Page to Round",
				'title'     => "Return Page to Round",
				'onclick'   => "return(confirm('Are you sure you want to {$CRLF}return this page to the current round?'));",
				'src'       => "gfx/bt15.png",
			);
			break;

		case REVERT_TO_LAST_SAVE:
			$attrs = array(
				'name'      => "button9",
				$label      => "Revert to Last Save",
				'title'     => "Revert to Last Save",
				'onclick'   => "return(confirm('Are you sure you want to {$CRLF}revert to your last save?'));",
				'src'       => "gfx/bt7.png",
			);
			break;

		case REVERT_TO_ORIGINAL:
			$attrs = array(
				'name'      => "button8",
				$label      => "Revert to Original Document",
				'title'     => "Revert to Original Document",
				'onclick'   => "return(confirm('Are you sure you want to {$CRLF}revert to the original text for this round?'));",
				'src'       => "gfx/bt8.png",
			);
			break;

		case RUN_COMMON_ERRORS_CHECK:
			$attrs = array(
				'name'      => "button11",
				$label      => "Run Common Errors Check",
				'title'     => "Run Common Errors Check",
				'onclick'   => "alert('currently disabled');return(false);",
				'src'       => "gfx/bt17.png",
			);
			break;

		case SAVE_AS_DONE_AND_PROOF_NEXT:
			$attrs = array(
				'accesskey' => "8",
				'name'      => "button2",
				$label      => "Save as 'Done' & Proof Next Page",
				'title'     => "Save as 'Done' & Proof Next Page",
				'src'       => "gfx/bt2.png",
			);
			break;

		case SAVE_AS_DONE_AND_QUIT:
			$attrs = array(
				'accesskey' => "5",
				'name'      => "button5",
				$label      => "Save as 'Done'",
				'title'     => "Save as 'Done'",
				'src'       => "gfx/bt13.png",
			);
			break;

		case SAVE_AS_IN_PROGRESS:
			$attrs = array(
				'accesskey' => "7",
				'name'      => "button1",
				$label      => "Save as 'In Progress'",
				'title'     => "Save as 'In Progress'",
				'src'       => "gfx/bt3.png",
			);
			break;

		case SET_IMAGE_ZOOM:
			$attrs = array(
				'name'      => "button35",
				$label      => "Set Image Zoom Percent",
				'title'     => "Set Image Zoom Percent",
				'src'       => "gfx/bt10.png",
				'onclick'   => "return(showIZ());",
			);
			break;

		case SPELL_CHECK:
			$attrs = array(
				'name'      => "button10",
				$label      => "Spell Check",
				'title'     => "Run Spelling Check",
				'src'       => "gfx/bt16.png",
			);
			break;
	}
	if ($which_interface == 's')
	{
		unset($attrs['src']);
		$attrs['type'] = 'submit';
		$attrs['id'] = $attrs['name'];
	}
	else if ($which_interface == 'a')
	{
		$attrs['type' ] = 'image';
		$attrs['align'] = 'top';
		$attrs['size' ] = '26';
		$attrs['id'   ] = $attrs['name'];
	}

	echo "<INPUT\n";
	foreach ($attrs as $at_name => $at_value)
	{
		echo "$at_name=\"$at_value\"\n";
	}
	echo ">";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function echo_img( $id )
{
	switch ($id)
	{
		case PROJECT_COMMENTS:
			$attrs = array(
				'src'       => "gfx/bt12.png",
				'alt'       => "Project Comments",
				'title'     => "Project Comments",
			);
			break;

		case REFRESH_IMAGE:
			$attrs = array(
				'src'       => "gfx/bt6.png",
				'alt'       => "Refresh Image",
				'title'     => "Refresh Image",
			);
			break;

		case REVERT_TO_LAST_SAVE_DISABLED:
			$attrs = array(
				'src'       => "gfx/bt7_n.png",
				'alt'       => "Revert to Last Save Disabled",
				'title'     => "Revert to Last Save Disabled",
			);
			break;

		case SHOW_ALL_TEXT:
			$attrs = array(
				'src'       => "gfx/bt9.png",
				'alt'       => "Show All Text",
				'title'     => "Show All Text",
			);
			break;
	}
	$attrs['align' ] = 'top';
	$attrs['width' ] = "26";
	$attrs['height'] = "26";
	$attrs['border'] = "0";

	echo "<img\n";
	foreach( $attrs as $at_name => $at_value )
	{
		print "$at_name=\"$at_value\"\n";
	}
	echo ">";
}

?>
