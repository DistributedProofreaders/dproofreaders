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

?>
