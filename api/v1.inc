<?php
include_once('api_pickers.inc');

// DP API v1
function v1_pickers_GET($params)
{
    return pickers();
}
