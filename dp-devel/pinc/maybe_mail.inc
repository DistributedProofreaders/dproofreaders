<?
    function maybe_mail( $to, $subject, $message, $additional_headers )
	// If this is the real DP site, send mail.
	// Otherwise, just report what would have been sent.
    {
        $HOSTNAME_OF_REAL_SITE = "texts01.archive.org";

        $hostname = $_ENV["HOSTNAME"];
        if ( $hostname == $HOSTNAME_OF_REAL_SITE )
        {
            mail( $to, $subject, $message, $additional_headers );
        }
        else
        {
            echo '<p>$_ENV["HOSTNAME"] == ' . $hostname . "\n";
            echo "<p>If it had been $HOSTNAME_OF_REAL_SITE, the following mail would have been sent:\n";
            echo "<pre>\n";
            echo "To: $to\n";
            echo "Subject: $subject\n";
            echo "$additional_headers";
            echo "\n";
            echo "$message\n";
            echo "</pre>\n";
        }
    }

?>
