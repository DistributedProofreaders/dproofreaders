<?PHP

// TODO - Utilize the themes as the source of colors

class htmlStyles
{
// set unused vars to NULL as 0 will still render the attribute="0"

// BODY
// onload can be set in the specific function
var $body = array(
0=> array(
'BACKGROUND'=>NULL,
'BGCOLOR'=>"#FFFFFF",
'TEXT'=>"#000000",
'LINK'=>"#000066",
'VLINK'=>"#660000",
'ALINK'=>"#006600",
'STYLE'=>NULL,
'CLASS'=>NULL
)

);

// TABLE
//width can be set in the specific function
var $table = array(
0=> array(
'ID'=>NULL,
'BGCOLOR'=>"#FFFFFF",
'BORDER'=>"1",
'BORDERCOLOR'=>"#111111",
'CELLSPACING'=>"0",
'CELLPADDING'=>"0",
'STYLE'=>"BORDER-COLLAPSE: collapse",
'CLASS'=>NULL
),

1=> array(
'ID'=>NULL,
'BGCOLOR'=>"#FFFFFF",
'BORDER'=>"1",
'BORDERCOLOR'=>"#111111",
'CELLSPACING'=>"0",
'CELLPADDING'=>"5",
'STYLE'=>"BORDER-COLLAPSE: collapse",
'CLASS'=>NULL
)

);

//TR's
// 0 goes with the default style TD, 1 goes with 'light' TD, 2 goes with 'dark' TD

var $tr = array(
0 => array(
'ID'=>NULL,
'STYLE'=>NULL,
'CLASS'=>NULL
),

1 => array(
'ID'=>NULL,
'STYLE'=>NULL,
'CLASS'=>NULL
),

2 => array(
'ID'=>NULL,
'STYLE'=>NULL,
'CLASS'=>NULL
)
);


// TD's
// 0 is default style TD, 1 is 'light' TD, 2 is 'dark' TD
// width, rowspan and/or colspan must be set in the specific function

var $td = array(
0 => array(
'ID'=>NULL,
'BGCOLOR'=>"#FFFFFF",
'STYLE'=>NULL,
'CLASS'=>NULL
),

1 => array(
'ID'=>NULL,
'BGCOLOR'=>"#e0e8dd",
'STYLE'=>NULL,
'CLASS'=>NULL
),

2 => array(
'ID'=>NULL,
'BGCOLOR'=>"#336633",
'STYLE'=>NULL,
'CLASS'=>NULL
),
);
}
?>