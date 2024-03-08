<?php
include_once($relPath.'authors.inc');

/**
 * Parse (render) project comments into HTML
 *
 * Project comments can be either Markdown or HTML. Sanitize the HTML if its
 * in that format otherwise render the Markdown to save HTML.
 *
 * Comments can also contain one kind of special markup `[biography=....]`.
 * This markup should always be replaced with the proper biography before the
 * project comments are displayed, zipped up for the post-processor, etc.
 *
 * @param Project $project Project object
 *
 * @return string
 */
function parse_project_comments($project)
{
    global $relPath;

    if ($project->comment_format == 'html') {
        $comments = sanitize_html($project->comments, 'td');
    } else {
        $comments = render_markdown_as_html($project->comments);
    }

    // insert biographies instead of [biography=123]-markup
    $biography_count = substr_count($comments, '[biography=');
    if (!empty($biography_count)) {
        $i = 1;
        while ($i <= $biography_count) {
            $comments_backup = $comments;
            // where the [biography=123] starts and ends
            $pos = strpos($comments_backup, '[biography=');
            $pos2 = strpos($comments_backup, ']', $pos);
            $comments = substr($comments_backup, 0, $pos).'<br>';
            $comments .= get_biography(substr($comments_backup, $pos + 11, $pos2 - $pos - 11));
            $comments .= '<br>'.substr($comments_backup, $pos2 + 1);
            $i++;
        }
    }

    return $comments;
}
