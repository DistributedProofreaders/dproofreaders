<?
$relPath='../pinc/';
include_once($relPath.'v_site.inc');
include_once($relPath.'connect.inc');
include_once($relPath.'theme.inc');
new dbConnect();
theme('Custom Font Comparison','header');
?>



<H1>DPCustomMono2</H1>
<P>DPCustomMono2 is a font adapted by DP's own big_bill, based on the suggestions and ideas of many experienced proofreaders, that helps proofreaders
find mistakes. You can change the font that you use for proofing in
your <? echo "<a href='$code_url/userprefs.php'>preferences</a>"; ?>. Here are some samples that compare DPCustomMono2 to
other fonts. For information on installing and using the font, read the <? echo "<a href='$forums_url/viewtopic.php?p=31521#31521'>Wiki about this</a>"; ?></P> 
<P><FONT FACE="DPCustomMono2, Times New Roman, Times, serif">If you already have the font
installed, you will see this sentence in the DPCustomMono2 typeface.
If this sentence looks normal, you can download DPCustomMono2 from
<a href="DPCustomMono2.ttf">here</a> (right click the link, and choose &ldquo;Save to Disk&rdquo;).</FONT></P>
<P><BR><BR>
</P>
<TABLE WIDTH=1108 BORDER=0 CELLPADDING=4 CELLSPACING=0>
	<COL WIDTH=506>
	<COL WIDTH=586>
	<THEAD>
		<TR VALIGN=TOP>
			<TH WIDTH=506>
				<P><IMG SRC="Courier.gif" NAME="Graphic1" ALIGN=LEFT WIDTH=582 HEIGHT=614 BORDER=0><BR CLEAR=LEFT><BR>
				</P>
			</TH>
			<TH WIDTH=586>
				<P><IMG SRC="DPCustomMono2.gif" NAME="Graphic2" ALT="Sample of DPCustomMono2 font" ALIGN=LEFT WIDTH=582 HEIGHT=622 BORDER=0><BR CLEAR=LEFT><BR>
				</P>
			</TH>
		</TR>
	</THEAD>
	<TBODY>
		<TR VALIGN=TOP>
			<TD WIDTH=506>
				<P ALIGN=CENTER>Courier</P>
			</TD>
			<TD WIDTH=586>
				<P ALIGN=CENTER>DPCustomMono2</P>
			</TD>
		</TR>
		<TR VALIGN=TOP>
			<TD WIDTH=506>
				<P ALIGN=CENTER><IMG SRC="Arial.gif" NAME="Graphic4" ALIGN=LEFT WIDTH=414 HEIGHT=617 BORDER=0><BR CLEAR=LEFT><BR>
				</P>
			</TD>
			<TD WIDTH=586>
				<P ALIGN=CENTER><IMG SRC="DPCustomMono2.gif" NAME="Graphic3" ALT="Sample of DPCustomMono2 font" ALIGN=LEFT WIDTH=582 HEIGHT=622 BORDER=0><BR CLEAR=LEFT><BR>
				</P>
			</TD>
		</TR>
		<TR VALIGN=TOP>
			<TD WIDTH=506>
				<P ALIGN=CENTER>Arial</P>
			</TD>
			<TD WIDTH=586>
				<P ALIGN=CENTER>DPCustomMono2</P>
			</TD>
		</TR>
		<TR VALIGN=TOP>
			<TD WIDTH=506>
				<P ALIGN=CENTER><IMG SRC="Times.gif" NAME="Graphic6" ALIGN=LEFT WIDTH=407 HEIGHT=618 BORDER=0><BR CLEAR=LEFT><BR>
				</P>
			</TD>
			<TD WIDTH=586>
				<P ALIGN=CENTER><IMG SRC="DPCustomMono2.gif" NAME="Graphic5" ALT="Sample of DPCustomMono2 font" ALIGN=LEFT WIDTH=582 HEIGHT=622 BORDER=0><BR CLEAR=LEFT><BR>
				</P>
			</TD>
		</TR>
		<TR VALIGN=TOP>
			<TD WIDTH=506>
				<P ALIGN=CENTER>Times</P>
			</TD>
			<TD WIDTH=586>
				<P ALIGN=CENTER>DPCustomMono2</P>
			</TD>
		</TR>
	</TBODY>
</TABLE>
<TABLE WIDTH=100% BORDER=0 CELLPADDING=4 CELLSPACING=3>
	<COL WIDTH=256*>
	<THEAD>
		<TR>
			<TD WIDTH=100% VALIGN=TOP>
				<P ALIGN=CENTER><IMG SRC="Original.gif" NAME="Graphic7" ALIGN=LEFT WIDTH=374 HEIGHT=539 BORDER=0><BR CLEAR=LEFT><BR>
				</P>
			</TD>
		</TR>
	</THEAD>
	<TBODY>
		<TR>
			<TD WIDTH=100% VALIGN=TOP>
				<P ALIGN=LEFT>Original Image</P>
			</TD>
		</TR>
	</TBODY>
</TABLE>
<P ALIGN=CENTER><BR><BR>
</P>

<style>
<!--
h1 {font-family: Verdana, Arial, sans-serif;}
p {font-family: "Times New Roman, Times, serif";
-->
</style>

<? theme("", "footer"); ?>
