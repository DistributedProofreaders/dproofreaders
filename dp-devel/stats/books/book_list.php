<?php
$relPath="./pinc/";
include($relPath.'connect.inc');
$db_Connection=new dbConnect();
include_once($relPath.'theme.inc');
include($relPath.'bookpages.inc');
include_once($relPath.'project_states.inc');
include($relPath.'showtexts.inc');

$type = $_GET['type'];
$etext_limit = $_GET['etext_limit'];
$type = $_GET['type'];
$orderby = $_GET['orderby']
$field_name = $_GET['field_name'];
$search_char = $_GET['search_char'];
$show_total = $_GET['show_total'];

theme("$type E-Texts", "header");
?>

<center><font face="Verdana" size="6"><b>E-Texts</b></font></center>

<center>
<?
if ($type == "gold") {
echo "<a href='$code_url/stats/books/book_list.php?etext_limit=$etext_limit&type=silver&orderby=$orderby&field_name=$field_name&search_char=$search_char&show_total=$show_total'>Silver</a> | "

echo "<a href='$code_url/stats/books/book_list.php?etext_limit=$etext_limit&type=bronze&orderby=$orderby&field_name=$field_name&search_char=$search_char&show_total=$show_total'>Bronze</a>"

} elseif ($type == "silver") {
echo "<a href='$code_url/stats/books/book_list.php?etext_limit=$etext_limit&type=bronze&orderby=$orderby&field_name=$field_name&search_char=$search_char&show_total=$show_total'>Bronze</a> | "

echo "<a href='$code_url/stats/books/book_list.php?etext_limit=$etext_limit&type=silver&orderby=$orderby&field_name=$field_name&search_char=$search_char&show_total=$show_total'>Silver</a>"

} elseif ($type == "bronze") {

echo "<a href='$code_url/stats/books/book_list.php?etext_limit=$etext_limit&type=gold&orderby=$orderby&field_name=$field_name&search_char=$search_char&show_total=$show_total'>Gold</a> | "

echo "<a href='$code_url/stats/books/book_list.php?etext_limit=$etext_limit&type=silver&orderby=$orderby&field_name=$field_name&search_char=$search_char&show_total=$show_total'>Silver</a>"

} else {

echo "<a href='$code_url/stats/books/book_list.php?etext_limit=$etext_limit&type=bronze&orderby=$orderby&field_name=$field_name&search_char=$search_char&show_total=$show_total'>Bronze</a> | "

echo "<a href='$code_url/stats/books/book_list.php?etext_limit=$etext_limit&type=silver&orderby=$orderby&field_name=$field_name&search_char=$search_char&show_total=$show_total'>Silver</a> | "

echo "<a href='$code_url/stats/books/book_list.php?etext_limit=$etext_limit&type=gold&orderby=$orderby&field_name=$field_name&search_char=$search_char&show_total=$show_total'>Gold</a>"
}
?>
</center><br>

<center>
<i>Title:</i> 
<?

echo "<a href='$code_url/stats/books/book_list.php?etext_limit=$etext_limit&type=$type&orderby=asc&field_name=nameofwork&search_char=$search_char&show_total=$show_total'>asc</a> or "

echo "<a href='$code_url/stats/books/book_list.php?etext_limit=$etext_limit&type=$type&orderby=desc&field_name=nameofwork&search_char=$search_char&show_total=$show_total'>desc</a> | "

?>
<i>Author:</i> 
<?

echo "<a href='$code_url/stats/books/book_list.php?etext_limit=$etext_limit&type=$type&orderby=asc&field_name=author&search_char=$search_char&show_total=$show_total'>asc</a> or "

echo "<a href='$code_url/stats/books/book_list.php?etext_limit=$etext_limit&type=$type&orderby=desc&field_name=author&search_char=$search_char&show_total=$show_total'>desc</a> | "

?>
<i>Submitted Date:</i> 
<?

echo "<a href='$code_url/stats/books/book_list.php?etext_limit=$etext_limit&type=$type&orderby=asc&field_name=modifieddate&search_char=$search_char&show_total=$show_total'>asc</a> or "

echo "<a href='$code_url/stats/books/book_list.php?etext_limit=$etext_limit&type=$type&orderby=desc&field_name=modifieddate&search_char=$search_char&show_total=$show_total'>desc</a>"

?>
<hr width="75%" align="center"><br>

<?

showtexts($etext_limit, $type, $orderby, $field_name, $search_char, $show_total);

theme("", "footer");
?>