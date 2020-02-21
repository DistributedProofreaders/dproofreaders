<?php

/**
 * Detects columns in the text by finding column descriptions. A column description
 * contains the name of the column prefixed by "## " (without quotes).
 *
 * For example, for a column named something_useful, its column description would
 * look like this:
 *
 * ## something_useful
 *
 * @param array $lines an array of strings -- the lines of the text
 * @return array an array of strings containing the column names detected in text
 */
function detect_columns_in_text(array $lines): array {
    // array_values is used to reindex the array
    return array_values(preg_filter('/^## ([a-zA-Z0-9_]+)/', '$1', $lines));
}