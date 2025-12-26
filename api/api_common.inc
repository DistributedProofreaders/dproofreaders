<?php
/**
 * Check to see if the user requested a flag parameter.
 *
 * This returns one of three possible states:
 * - null - no parameter with this key was set
 * - true - parameter was set to "true" or an empty string (for ?flagname)
 * - false - parameter was set to "false"
 *
 * @param array<string, string|string[]> $query_params
 */
function get_flag_value(array $query_params, string $flagname)
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

/**
 * Send headers telling the client it's OK to cache the page
 */
function send_cache_header(int $cache_time, ?string $cache_type = "private", bool $immutable = true): void
{
    if ($cache_type && !in_array($cache_type, ["public", "private"])) {
        throw new ValueError("Invalid value for 'cache_type'");
    }

    $cache_settings = ["max-age=$cache_time"];

    if ($cache_type) {
        $cache_settings[] = $cache_type;
    }

    if ($immutable) {
        $cache_settings[] = "immutable";
    }

    header("Cache-Control: " . join(", ", $cache_settings));
}
