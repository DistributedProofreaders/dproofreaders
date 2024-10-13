<?php

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

    return $comments;
}
