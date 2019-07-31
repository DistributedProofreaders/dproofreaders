<?php
include_once($relPath.'api_utils.inc'); // check_login()

// get the set of pickers
function pickers()
{
    check_login();
    
    $picker_set =
    [
        [
            "code" => "A",
            "upper" => "ÀÁÂÃÄÅÆ",
            "lower" => "àáâãäåæ",
        ],
        [
            "code" => "E",
            "upper" => "ÈÉÊË",
            "lower" => "èéêë",
        ],
        [
            "code" => "I",
            "upper" => "ÌÍÎÏ",
            "lower" => "ìíîï",
        ],
        [
            "code" => "O",
            "upper" => "ÒÓÔÕÖØ",
            "lower" => "òóôõöø",
        ],
        [
            "code" => "U",
            "upper" => "ÙÚÛÜ",
            "lower" => "ùúûü",
        ],
        [
            "code" => "+",
            "upper" => '$£¥¡©« Ý ÇÐÑÞ¦ªº´¨¯¸°¹²³',
            "lower" => "¢¤ ¿®»ßýÿçðñþ§¼½¾¶¬·±×÷µ",
        ],
    ];

    return ['pickers' => $picker_set];
}
