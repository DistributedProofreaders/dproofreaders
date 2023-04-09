<?php
// This file inherits from DifferenceEngineWrapper to facilitate
// formatting diffs using BB code.
include_once($relPath."DifferenceEngineWrapper.inc");
include_once($relPath."BBDiffFormatter.inc");

// Format diffs using BB code, without HTML markup
class DifferenceEngineWrapperBB extends DifferenceEngineWrapper
{
    public function __construct($diffurl)
    {
        parent::__construct(new BBDiffFormatter($diffurl));
    }
}

// Format diffs using BB code, displayed within HTML markup
class DifferenceEngineWrapperBBHTML extends DifferenceEngineWrapper
{
    public function __construct($diffurl)
    {
        parent::__construct(new BBDiffFormatterHTML($diffurl));
    }

    protected function addHeader($diff, $otitle, $ntitle, $multi = '', $notice = '')
    {
        $header = "";
        if (!$diff && !$otitle) {
            $header .= $ntitle;
        } else {
            $header .= "\n$otitle<br>\n$ntitle";
        }
        return "$header\n<pre>$diff</pre>\n";
    }
}
