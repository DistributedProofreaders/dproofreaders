<?
$relPath="./../pinc/";
include($relPath.'v_site.inc');
include($relPath.'username.inc');
include($relPath.'email_address.inc');
include($relPath.'maybe_mail.inc');
include($relPath.'connect.inc');
$db_Connection=new dbConnect();
$db_link=$db_Connection->db_lk;
include($relPath.'theme.inc');

// This function takes any error message given to it, displays it & terminates the page

function abort_registration( $error )
{
    $header = _("Registration Incomplete");
    theme($header, "header");
    echo "$error<br>\n";
    $please = _("Please hit 'Back' and try again.");
    echo "$please<br>\n";
    $back = _("Back");
    echo "<a href=\"addproofer.php\">".$back."</a>";
    theme("", "footer");
    exit;
}

$password = isset($_POST['password'])? $_POST['password']: '';
if ($password=="proofer") {

    // From the form filled out at the end of this file

    $real_name = $_POST['real_name'];
    $username = $_POST['userNM'];
    $userpass = $_POST['userPW'];
    $email = $_POST['email'];
    $email_updates = $_POST['email_updates'];

    // Do some validity-checks on inputted username, password, e-mail and real name

    $err = check_username( $username );
    if ( $err != '' )
    {
        abort_registration( $err );
    }

    $err = check_email_address( $email );
    if ( $err != '' )
    {
        abort_registration( $err );
    }

    if (empty($userpass) || empty($real_name))
    {
        $error = _("You did not completely fill out the form.");
        abort_registration($error);
    }

    $ID = uniqid("userID");

    $todaysdate = time();

    $result = mysql_query ("INSERT INTO users (id, real_name, username, email, manager, date_created, emailupdates, u_plist, u_top10, u_neigh)
                VALUES ('$ID', '$real_name', '$username', '$email', 'no', '$todaysdate', '$email_updates', '3', '1', '10')");

    if (!$result) {
        $error = _("That user name already exists, please try another.");
        abort_registration($error);

    } else {
        // create profile
        $profileString="INSERT INTO user_profiles SET u_ref='".mysql_insert_id($db_link)."'";
        $makeProfile=mysql_query($profileString);

        // add ref to profile
        $refString="UPDATE users SET u_profile='".mysql_insert_id($db_link)."' WHERE id='$ID' AND username='$username'";
        $makeRef=mysql_query($refString);

        // join the all users team
        mysql_query("UPDATE user_teams SET member_count=member_count+1 WHERE id='1'");

        //code from php forums bb_register.php
        $passwd = md5($userpass);
        $sql = "SELECT max(user_id) AS total FROM phpbb_users";
        if(!$r = mysql_query($sql))
            die("Error connecting to the database.");
        list($total) = mysql_fetch_array($r);
        $currtime = time();

        $total += 1;
        $sql = "INSERT INTO phpbb_users (user_id, username, user_regdate, user_timezone, user_email, user_password, user_viewemail)
            VALUES ('$total', '$username', " . $currtime . ", '-8.00', '$email', '$passwd', '0')";
        $result = mysql_query($sql);

        // Send them an introduction e-mail
        $welcome = _("Welcome to the Distributed Proofreaders' Site!");
        maybe_mail($email, $welcome, "
Hello $real_name,

We want to first thank you for registering on our site. That is the
first step in helping us proofread books for Project Gutenberg
<http://www.gutenberg.net>.

To make sure you will be able to access and use our web site
properly, please check that your browser settings are as follows:

- javascript enabled
- cookies accepted (at least from us at www.pgdp.net)
- popup windows allowed (at least from us at www.pgdp.net)

Also, please ensure your PC clock is set to the correct date
and time.

As a new user, we recommend you read over our main page
<$code_url/> for an overview of the site,
a selection of the works that we are working on, along
with the books that have been completed through the site.
The Beginning Proofreaders FAQ <$code_url/faq/ProoferFAQ.php>
also provides a nice overview of the site. The Proofreading
Guidelines <$code_url/faq/document.php> cover most formatting
questions, and it is worth browsing through early on, but
don't feel you have to memorize everything in it immediately -
it really comes into its own as a detailed reference whenever
you are unsure of how to handle something. You may find that the
shorter, printable Handy Guide <$code_url/faq/summary.pdf>
offers a useful introduction to the most common of our standards,
and it may be worth keeping a printed copy by the computer (or an
online copy open in another window) as you proof.

Once you understand the work being done through this site,
the best thing to do is get started! Here's a step-by-step
process once you log in:

- When you log in you are taken to your Personal Page. Take
  special note of the navigational bar at the top of the screen.
  It appears on many pages on site, and towards the right contains
  many useful links, such as to our FAQs (help), the discussion
  Forums, your personal Inbox, etc. Next, scroll down past the
  Site News and Random Rule (feel free to read them!) and you
  will see a listing of the books currently available in first
  round. Each book on the site goes through two rounds of
  proofreading. When you first start you are only shown the
  books in the first round. Second round will be available to
  you later on.

- Select a book that you would like to read a little bit on.

- Click on the title you want to work on. This will open
  the Project Comments page for this project (book).

- Among the contents of the Project Comments page may be some
  special instructions from the Project Manager. Please read these
  carefully as they supersede the Proofreading Guidelines, for that
  project.

- Click on the \"Start Proofreading\" link near the top of the page,
  this will open the proofreading interface.

- Compare the text in the text box to what is in the image, making
  corrections for differences between the two and any additional
  items described in the comments. You can pull the comments back
  up by clicking on the link below the text box.

- Once you are finished with this page, click on either
  \"Save as 'Done'\" or \"Save as 'Done' & Proof Next\"

That is all there is to completing your first page.

Here are some brief answers to common questions:

Q: What books should I start on?

A: You can start on any that look interesting to you. As a new
   beginner, you might want to consider a book marked BEGINNERS ONLY,
   as these will be given especially close attention by experienced
   second round proofreaders who will send you messages containing
   feedback on your proofreading if you make any serious errors, via
   our on-site messaging system. (Be sure to check your personal Inbox
   regularly.) After you've done a few pages of a BEGINNERS ONLY
   project, you might want to sample one of the several EASY
   projects usually available. (Don't be afraid to try any project;
   if you run into a page you decide you'd rather not do, you can
   always press the \"Return Page to Round\" button and let someone
   else tackle it.)

Q: I'm not sure how to use the interface or how I should mark
   something up.

A: You can get help for the various buttons on the proofreading interface
   by pressing the ? button near the lower right corner. If there's
   something in the image you are unsure about, you can mark it with
   a *, which is our special universal signal for the next person
   working on the page to pay extra attention to a particular spot
   because there is something unusual there. Remember there will be
   several other pairs of eyes looking at this page before it gets
   posted to Project Gutenberg, so don't feel you are carrying the
   whole thing on your shoulders alone - the system is set up so
   we can all help each other and back each other up!

   If you have specific questions on a book, you can post a message
   in the forum thread reserved for it by following the link
   labeled \"Discuss this project\" that appears on the Project
   Comments page. Each project has a forum thread of its own; you
   can post a question or message via the \"Reply\" button near the
   bottom of the forum screen. It's often worthwhile reading through
   the discussion on a project even if you don't have a specific
   question, just to see what other proofreaders have asked and answered
   or warned each other about. (There are also several other forums
   dedicated to various phases of our operation. You can reach them
   by following the Forums link near the right hand end of the
   navigation bar at the top of many of the pages on site. The
   General Forum in particular is a great place to get your feet
   wet, browse and start to get a sense of the community of
   proofreaders here, to make suggestions or to ask general
   questions. You can learn a lot just by following some of the
   discussions. ***We strongly encourage everyone to participate in
   the Forums!***)

   (By the way, when you register on the main site you are automatically
   also registered on the site forums, but you will have to log on
   to them to access them. You do NOT need to register separately for
   the forums - in fact, if you try to, you will receive a \"username
   already exists\" error, since the forums will already have your
   username recorded from when you registered at the main site!)

Q: How do I know if I'm doing OK?

A: If you select a BEGINNERS ONLY project, you are more likely to
   receive some feedback. If you are making major errors on any
   project someone will let you know. If you want to increase your
   chances of receiving some feedback, you can leave a message to
   the second round proofreader at the top of the page, in square
   brackets [ ] and starting with two *, such as

      [** new proofreader, how am I doing?]
 or
      [** feedback welcome!]

   It's up to the individual second round proofreader, but many will
   respond to such a request.


Remember, every page you do helps make these books available to
the world, for free, forever, more rapidly. We hope that you will
continue to use our site, and that you enjoy your time on our site.
We're delighted to have you join us, as each page we proof is
another small step closer to building the greatest library in history!

Thanks,

The Distributed Proofreaders Team

PS - Your user name, in case you forget, is $username.
If your password doesn't work, go to
<$reset_password_url>
to have it reset.
            ",
            "From: $auto_email_addr\r\nReply-To: $auto_email_addr\r\n");

        // Page shown when account is succeffully created

        $header = _("User")." $username "._("Added Successfully");
	theme($header, "header");

        echo _("User")." <b>$username</b> "._("added successfully. Please check the e-mail being sent to you for further information about Distributed Proofreading.");
        echo "<center>";
        echo "<br><font size=+1>"._("Click below to sign in and start proofreading!!");
        echo "<form action='login.php' method='post'><input type='hidden' name='userNM' value='".$username."'><input type='hidden' name='userPW' value='".$userpass."'><input type='submit' value='"._("Sign In")."'></form>";

	theme("", "footer");
    }
} else {

// This is the portion that shows up when no parameters are given to the file
//
// When users fill the form out below, it will submit the information back
// to this file & run the above commands.

    $header = _("Create An Account");
    theme($header, "header");

    echo "<center><form method='post' action='addproofer.php'><input type=hidden name='password' value='proofer'>";
    echo "<br><table bgcolor='#ffffff' border='1' bordercolor='#111111' cellspacing='0' cellpadding='0' style='border-collapse:collapse' width='400'><tr><td bgcolor='#e0e8dd' align='center'><b>"._("Real Name").":</b>";
    echo "<td bgcolor='#ffffff' align='center'><input type='text' maxlength=70 name='real_name' size=20>";
    echo "</td></tr><tr><td bgcolor='#e0e8dd' align='center'><b>"._("User Name").":</b>";
    echo "<td bgcolor='#ffffff' align='center'><input type='text' maxlength=70 name='userNM' size=20>";
    echo "</td></tr><tr><td bgcolor='#e0e8dd' align='center'><b>"._("Password").":</b>";
    echo "<td bgcolor='#ffffff' align='center'><input type='password' maxlength=70 name='userPW' size=20>";
    echo "</td></tr><tr><td bgcolor='#e0e8dd' align='center'><b>"._("E-mail Address").":</b>";
    echo "<td bgcolor='#ffffff' align='center'><input type='text' maxlength=70 name='email' size=20>";
    echo "</td></tr><tr><td bgcolor='#e0e8dd' align='center'><b>"._("E-mail Updates").":</b>";
    echo "</td><td bgcolor='#ffffff' align='center'><input type='radio' name='email_updates' value='1' checked>"._("Yes")."&nbsp;&nbsp;<input type='radio' name='email_updates' value='0'>"._("No");
    echo "</td></tr><tr><td bgcolor='#336633' colspan='2' align='center'><input type='submit' value='"._("Create Account")."'>&nbsp;&nbsp;<input type='reset'>";
    echo "</td></tr><tr><td bgcolor='#ffffff' colspan='2' align='left'>";

    include($code_dir.'/faq/privacy.php');

    echo "</td></tr></table></form>";
    echo "</center>";
}
theme("", "footer");
?>
