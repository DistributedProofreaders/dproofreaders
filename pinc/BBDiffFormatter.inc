<?php
// This file allows formatting of diffs using BB code, inherited from
// MediaWiki difference formatter base class
//
// See 3rdparty/mediawiki/README.md for which MediaWiki version this maps to.
include_once($relPath."misc.inc"); // html_safe()
include_once($relPath."3rdparty/mediawiki/DiffFormatter.php");

class BBDiffFormatter extends DiffFormatter
{
    protected $diffurl;
    protected $leadingContextLines = 1;   // Code assumes 1 leading context line - displayed for add/delete only
    protected $trailingContextLines = 0;  // Code assumes 0 trailing context lines

    public function __construct($diffurl)
    {
        $this->diffurl = $diffurl;
    }

    // Outputs an edit block
    // Leading context line is output before add/delete changes
    // Assumes zero trailing context lines
    protected function block($xbeg, $xlen, $ybeg, $ylen, &$edits)
    {
        // We don't display leading context line for change edits, so need to
        // increment the starting line number for change edits with context
        $has_context = false;
        $has_change = false;
        foreach ($edits as $edit) {
            if ($edit->type == 'copy') {
                $has_context = true;
            } elseif ($edit->type == 'change') {
                $has_change = true;
            }
        }
        if ($has_context and $has_change) {
            $xbeg++;
        }

        $this->startBlock($this->blockHeader($xbeg, $xlen, $ybeg, $ylen));

        $leading_context = null;
        foreach ($edits as $edit) {
            if ($edit->type == 'copy') {
                $leading_context = $edit->orig;
            } elseif ($edit->type == 'add') {
                $this->added($edit->closing, $leading_context);
            } elseif ($edit->type == 'delete') {
                $this->deleted($edit->orig, $leading_context);
            } elseif ($edit->type == 'change') {
                $this->changed($edit->orig, $edit->closing);
            } else {
                throw new MWException("Unknown edit type: {$edit->type}");
            }
        }
        $this->endBlock();
    }

    // Called at the start of a block of connected edits.
    protected function startBlock($header)
    {
        $linenum = preg_replace('/^(\d+).*/', 'Line $1', $header);
        $difflabel = $this->make_safe(preg_replace('/.*image=(.+\.png).*/', '$1', $this->diffurl));
        $safeurl = $this->make_safe($this->diffurl);
        $this->writeOutput("\n----- [b]$linenum, page [url=$safeurl]${difflabel}[/url][/b] -----\n");
    }

    // Writes all (optionally prefixed) lines to the output buffer, separated by newlines
    // and enclosed in BBCode [code] markup
    protected function lines($lines, $prefix = '')
    {
        $this->writeOutput("[code]\n");
        foreach ($lines as $line) {
            $this->writeOutput($this->make_safe("$prefix$line\n"));
        }
        $this->writeOutput("[/code]\n");
    }

    // Output lines that were added by the diff
    protected function added($lines, $leading_context = null)
    {
        $this->added_deleted('added', $lines, $leading_context);
    }

    // Output lines that were deleted by the diff
    protected function deleted($lines, $leading_context = null)
    {
        $this->added_deleted('deleted', $lines, $leading_context);
    }

    // Output lines that were added/deleted by the diff
    private function added_deleted($adddel_string, $lines, $leading_context)
    {
        if (empty($leading_context)) {  // Must be first line of the page if no context
            $this->writeOutput("At the top of the page, ");
        } elseif ($this->count_all_blank_lines($leading_context) > 0) {
            $this->writeOutput("After the blank line, ");   // Context line is a blank line
        } else {
            $this->writeOutput("After this line:\n");
            $this->lines($leading_context);
        }

        // If all added/deleted lines are blank, tell user about them, rather than literal output
        $blank_lines = $this->count_all_blank_lines($lines);
        if ($blank_lines > 1) {
            $this->writeOutput("$blank_lines blank lines need to be $adddel_string.\n");
        } elseif ($blank_lines === 1) {
            $this->writeOutput("$blank_lines blank line needs to be $adddel_string.\n");
        } else {
            $this->writeOutput("the following needs to be $adddel_string:\n");
            $this->lines($lines);
        }
    }

    // Count blank lines in given array if they are all blank
    //
    private function count_all_blank_lines($lines)
    {
        foreach ($lines as &$line) {
            if (!empty($line)) {
                return 0;
            }
        }
        return count($lines);
    }

    // Output lines that were changed by the diff
    protected function changed($orig, $closing)
    {
        $this->lines($orig);
        $this->writeOutput("is proofread as\n");
        $this->lines($closing);
    }

    // Make string safe for this type of output
    // No changes needed for BBCode output
    protected function make_safe($string)
    {
        return $string;
    }
}

// Makes BBCode diffs suitable for displaying within HTML
class BBDiffFormatterHTML extends BBDiffFormatter
{
    // Make string safe for HTML output
    protected function make_safe($string)
    {
        return html_safe($string);
    }
}
