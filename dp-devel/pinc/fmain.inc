<?PHP
function ptog($varNM)
{if (isset($_POST[$varNM]))
{return $_POST[$varNM];}
else {return NULL;}}

function gtog($varNM)
{if (isset($_GET[$varNM]))
{return $_GET[$varNM];}
else {return NULL;}}

function dquery($qstring)
{return mysql_query($qstring);}

function nrows($res)
{return mysql_num_rows($res);}

function nfields($res)
{return mysql_num_fields($res);}

function arows()
{return mysql_affected_rows();}

function derror()
{return mysql_error();}

function fresult($res)
{return mysql_free_result($res);}

function dresult($res,$row,$field)
{return mysql_result($res,$row,$field);}

function drow($res)
{return mysql_fetch_row($res);}

function darray($res)
{return mysql_fetch_array($res);}

function dassoc($res)
{return mysql_fetch_assoc($res);}

function rowsquery($qString)
{$res=query($qString);
return nrows($res);}
?>