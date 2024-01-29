<?php
/**
 * Check to see if the user requested a flag parameter.
 *
 * This returns one of three possible states:
 * - null - no parameter with this key was set
 * - true - parameter was set to "true" or an empty string (for ?flagname)
 * - false - parameter was set to "false"
 */
function get_flag_value($query_params, $flagname)
{
    if (!isset($query_params[$flagname])) {
        return null;
    } elseif ($query_params[$flagname] == "") {
        // make '?flagname' work as if '?flagname=true'
        return true;
    } else {
        $bool = filter_var($query_params[$flagname], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        if ($bool === null) {
            throw new ValueError("Invalid value for '$flagname'");
        }
        return $bool;
    }
}
