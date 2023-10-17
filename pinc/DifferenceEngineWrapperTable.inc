<?php
// This file inherits from DifferenceEngineWrapper to facilitate
// formatting diffs in an HTML table.
include_once($relPath."prefs_options.inc"); // get_user_proofreading_font()
include_once($relPath."DifferenceEngineWrapper.inc");
include_once($relPath."3rdparty/mediawiki/TableDiffFormatter.php");

class DifferenceEngineWrapperTable extends DifferenceEngineWrapper
{
    public function __construct()
    {
        parent::__construct(new TableDiffFormatter());
    }

    protected function addHeader($diff, $otitle, $ntitle, $multi = '', $notice = '')
    {
        // lifted from DifferenceEngine::addHeader()
        // but without any of the $userLang bits

        $tableClass = 'diff';
        $header = "<table class='$tableClass'>";

        if (!$diff && !$otitle) {
            $header .= "
            <tr class='diff-title'>
            <td class='diff-ntitle'>{$ntitle}</td>
            </tr>";
            $multiColspan = 1;
        } else {
            if ($diff) { // Safari/Chrome show broken output if cols not used
                $header .= "
                <col class='diff-marker' />
                <col class='diff-content' />
                <col class='diff-marker' />
                <col class='diff-content' />";
                $colspan = 2;
                $multiColspan = 4;
            } else {
                $colspan = 1;
                $multiColspan = 2;
            }
            $header .= "
            <tr class='diff-title'>
            <td colspan='$colspan' class='diff-otitle'>{$otitle}</td>
            <td colspan='$colspan' class='diff-ntitle'>{$ntitle}</td>
            </tr>";
        }

        if ($multi != '') {
            $header .= "<tr><td colspan='{$multiColspan}' class='diff-multi'>{$multi}</td></tr>";
        }
        if ($notice != '') {
            $header .= "<tr><td colspan='{$multiColspan}' class='diff-notice'>{$notice}</td></tr>";
        }

        return $header . $diff . "</table>";
    }
}

/**
 * Override default css for DP customizations
 */
function get_DifferenceEngine_css_data()
{
    [, $font_size, $font_family] = get_user_proofreading_font();
    if ($font_size != '') {
        $font_size = "font-size: $font_size;";
    }

    return "
.diff-otitle,
.diff-ntitle {
    font-weight: bold;
}
.diff-marker,
.diff-addedline,
.diff-deletedline,
.diff-context {
    font-family: $font_family;
    $font_size
}
/* Adjust padding to prevent descenders from being chopped off. Task 1936 */
.diff-deletedline .diffchange,
.diff-addedline .diffchange {
    padding: 0.1em 0;
}
";
}
