<?
include($relPath.'v_site.inc');

    function maybe_mail( $to, $subject, $message, $additional_headers )
    // If this is for real, send the message.
    // If we're testing, just report what would have been sent.
    {
        global $testing;

        if ( $testing )
        {
            echo "\n<hr>\n";
            echo "\$testing is $testing. If it were FALSE, the following mail would have been sent:\n";
            echo "<pre>\n";
            echo "To: $to\n";
            echo "Subject: $subject\n";
            echo "$additional_headers";
            echo "\n";
            echo htmlspecialchars("$message\n", ENT_NOQUOTES);
            echo "</pre>\n";
            echo "<hr>\n";
        }
        else
        {
            mail( $to, $subject, $message, $additional_headers );
        }
    }

function maybe_mail_project_manager( $project, $info, $prefix)
// $project is either a string containing a projectid,
// or an associative array containing various attributes of a project.
{
	global $code_url, $auto_email_addr;

	if (is_string($project))
	{
		$project = mysql_fetch_assoc(mysql_query("
			SELECT * FROM projects WHERE projectid='$project'
		"));
	}

	$projectid  = $project['projectid'];
	$nameofwork = $project['nameofwork'];
	$username   = $project['username'];

	$body =
_("Hello")." $username,\n".
_("This is an automated message from the Distributed Proofreaders site.")."

"._("Regarding").":
\"$nameofwork\"
  ($projectid)
  $code_url/project.php?id=$projectid

$info

"._("Thank You!")."
"._("Distributed Proofreaders")."
";

	$email_addr = mysql_result(mysql_query("
		SELECT email FROM users WHERE username = '$username'
	"),0);

	maybe_mail($email_addr, "$prefix: \"$nameofwork\"", $body,
		"From: $auto_email_addr\r\nReply-To: $auto_email_addr\r\n");
}


?>
