#!/bin/bash
#
# This script will parse faq/document.php and create an SQL file which
# will delete existing entries in the 'rules' table and inset new ones
# created from the Guidelines document.
#
# Criteria in the Guidelines document are as follows:
#     Everything from an <H3> tag to the next <H3> tag are parsed as a
#     single "Random Rule" unless interrupted by an HTML comment of the
#     form <!-- END RR --> (End Random Rule).
# This allows examples (including arbitrary tables, images, etc.) to be
# included in the Guidelines document, but not in the Random Rules which
# appear on the user "Personal" page.
#
# This script should be run each time the Guidelines document is updated,
# and the resulting SQL file executed as indicated in the script output.
# (See echo at end of this script for details)
#
#=============================================================================

# All documents assumed to be in faq directory
#
DOCUMENTS="proofreading_guidelines.php \
document.php"

# Leave these out until the documents can be reformatted to this scripts
# expectations.
#
#faq/post_proof.php \
#faq/ppv.php"

# Empty the table before we start
echo "DELETE FROM rules;" > /tmp/randomrules.sql

typeset -i basecount=1

for document in $DOCUMENTS
do

echo "Processing $document ..."

# Context split on the H3 tags
csplit -f tip -b %03d ../faq/$document "/<h3*3>/" {*} 1>/dev/null 2>&1

# Throw away the preface of the document, it is not a tip
rm -f tip000

# Process each resulting file...
typeset -i rulecount=$basecount
for file in $(ls -1 tip*)
do
awk -v id=$rulecount -v document=$document '
# This is a quoted apostrophe
BEGIN { quoap = "\\x27" }
{
if ( $0 == "<!-- END RR -->") {
    exit
    }
# First line of each file contains an H3 tag and an A NAME ... TAG
# Parse out the NAME into anchor, which is one of our SQL fields
# Ditto for subject, which is the text between the Anchor Tags
if ( NR == 1) {
    match($0,/name="(.*)"/)
    anchor = substr($0,RSTART+6,RLENGTH-7)
    # Do not include these sections of the Guidelines as Random Rules.
    if ( anchor == "about" || anchor == "uncertain" ) { printf("%s",anchor); exit }
    match($0,/">(.*)<\/a>/);
    subject = substr($0,RSTART+2,RLENGTH-6)
    # Quote all apostrophes
    gsub( /\x27/, "\\\\&", anchor)
    gsub( /\x27/, "\\\\&", subject)
    # Output the first part of the insert statement
    printf("INSERT INTO `rules` VALUES (%d, \x27%s\x27, \x27%s\x27, \x27%s\x27, \x27",id,document,anchor,subject)
    next
    }
# The rest of the lines in the file are the rule text itself.
# They contain HREFs which must be manipulated
# Fix local references within faq directory
/href="(.*)\.(php|pdf)/ gsub( /href="/, "href=\"/c/faq/")
# Fix internal references to FAQ documentation
gsub( /faq\/#/, "faq/"document"#")
# Unmunge external references
gsub( /href="\/c\/faq\/http:/, "href=\"http:")
# Ditch all CRLF
gsub( /(\r|\n)/, " ")
# Quote all apostrophes in actual Rule lines
gsub( /\x27/, "\\\\&")

# Output the line
printf ("%s ", $0)
}
# End will close insert statement and add newline
END { print "\x27\x29;"
    } ' $file >new$file
rm -f $file

# Ditch the empties that we're ignoring and decrement counter
if [ $(cat new$file |wc -w) -le 3 ]
then
    echo "skipped rule ('$(cat new$file)"
    rm -f new$file
    rulecount=$rulecount-1
fi
rulecount=$rulecount+1
done

# Concatenate the files back together
cat newtip0* >> /tmp/randomrules.sql

rm -f newtip0*

# Bump the rule counter
basecount=$rulecount+1

done

echo "
Now, examine the generated sql file (/tmp/randomrules.sql)...
Once you are satisfied with the content, simply run the generated script
into the database with:

mysql dp_db -u USER -p < /tmp/randomrules.sql
"

