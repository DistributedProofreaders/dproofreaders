<?php

// functions to remove formatting in order to compare P to F (or F to F) rounds
class PageUnformatter
{
    private function find_close($text, $offset)
    {
        // find next unmatched ]
        $nest_level = 0;
        while(true)
        {
            // find next [ or ]
            $match_brak = preg_match("/\[|\]/", $text, $brak_matches, PREG_OFFSET_CAPTURE, $offset);
            if(!$match_brak) // none found or error
            {
                return 0;
            }
            if ("[" === $brak_matches[0][0])
            {
                $nest_level += 1;
            }
            else
            { // must be ]
                if($nest_level > 0)
                {
                    $nest_level -= 1;
                }
                else
                {
                    return $brak_matches[0][1];
                }
            }
            $offset = $brak_matches[0][1] + 1;
        }
    }

    private function remove_user_notes($text)
    {
        $start = strpos($text, "[**");
        if(FALSE === $start) // not found
        {
            return $text;
        }
        $end = $this->find_close($text, $start + 3);
        if(0 === $end) // no ] found
        {
            return $text;
        }
        return substr($text, 0, $start) . $this->remove_user_notes(substr($text, $end + 1));
    }

    private function advance_end($text, $end)
    {
        // for continuing footnote etc., if * follows ] remove it
        $new_start = $end + 1;
        if(($new_start < strlen($text)) && ($text[$new_start] == "*"))
        {
            $new_start += 1;
        }
        return $new_start;
    }

    private function adjust_footnotes($text)
    {
        // remove "[Footnote" and ":" and "]", if ref. is an upper-case letter replace with *
        $match = preg_match ("/\[Footnote (?:(\d+|[a-z])|[A-Z]):/", $text, $matches, PREG_OFFSET_CAPTURE);
        if(!$match) // if no match or error
        {
            return $text;
        }
        $start = $matches[0][1];
        if(isset($matches[1]))
        { // number or lower-case letter reference
            $note_ref = $matches[1][0];
        }
        else
        { // must be an upper-case letter
            $note_ref = "*";
        }
        $length = strlen($matches[0][0]);
        $right_start = $start + $length; // following the :
        $end = $this->find_close($text, $right_start); // before the closing ]
        if(0 === $end) // no ] found
        {
            return $text;
        }

        $new_start = $this->advance_end($text, $end);
        return substr($text, 0, $start) . $note_ref . substr($text, $right_start, $end - $right_start) . $this->adjust_footnotes(substr($text, $new_start));
    }

    private function adjust_notes($text)
    {
        // Illustration with text, Sidenote, continuation Footnote without a ref.
        $match = preg_match ("/\*\[Footnote:|\*?\[Sidenote:|\[Illustration:/", $text, $matches, PREG_OFFSET_CAPTURE);
        if(!$match) // if no match or error
        {
            return $text;
        }
        $start = $matches[0][1];
        $length = strlen($matches[0][0]);
        $right_start = $start + $length; // following the :
        $end = $this->find_close($text, $right_start); // before the closing ]
        if(0 === $end) // no ] found
        {
            return $text;
        }

        $new_start = $this->advance_end($text, $end);
        return substr($text, 0, $start) . substr($text, $right_start, $end - $right_start) . $this->adjust_notes(substr($text, $new_start));
    }

    public function remove_formatting($text, $unwrap)
    {
        $text = str_replace( "\r\n", "\n", $text);
        // these can leave blank lines which will be removed later
        $text = $this->remove_user_notes($text);
        // remove tags [Illustration] (without text)
        $text = preg_replace("/<\/?(?:[ibfgu]|sc|tb)>|\/[\*#]|[\*#]\/|\[Illustration\]/", "", $text);
        // change alphabetic footnote anchors to *
        $text = preg_replace("/\[[A-Z]\]/", "[*]", $text);
        $text = $this->adjust_footnotes($text);
        $text = $this->adjust_notes($text);
        if($unwrap)
        {
            // change one or more newlines or spaces to a single space
            $text = preg_replace("/\s+/", " ", $text);
        }
        else
        {
            // remove leading spaces
            $text = preg_replace("/(?<=\n|^)\ +/", "", $text);
            // remove trailing spaces
            $text = preg_replace("/\ +(?=\n|$)/", "", $text);
            // remove blank lines - one or more \n -> one \n
            $text = preg_replace("/\n+/", "\n", $text);
            // multiple spaces to single space 
            $text = preg_replace("/\ +/", " ", $text);
        }
        // remove whitespace from beginning and end
        return trim($text);
    }
}

// vim: sw=4 ts=4 expandtab
