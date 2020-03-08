<?php
include_once($relPath."misc.inc"); // startswith()

class RandomRule
{
    private $table_row = [];

    // Because these are static class variables we can't use _() to translate
    // them. But they're only used in the Admin interface so that's probably
    // OK anyway. If, in the future, we want to translate them we can move
    // them to a class function (get_document_values()) instead.
    public static $document_values = [
        "proofreading_guidelines.php" => "Proofreading Guidelines",
        "formatting_guidelines.php" => "Formatting Guidelines",
    ];

    public function __construct($id=NULL)
    {
        if($id !== NULL)
        {
            $this->load($id);
        }
    }

    public function __get($name)
    {
        return $this->table_row[$name];
    }

    public function __isset($name)
    {
        return isset($this->table_row[$name]);
    }

    // Load a specific rule into this object given its ID
    public function load($id)
    {
        $sql = sprintf("
            SELECT *
            FROM rules
            WHERE id = %d
        ", $id);

        $result = mysqli_query(DPDatabase::get_connection(), $sql);
        if(!$result)
        {
            throw new UnexpectedValueException(mysqli_error(DPDatabase::get_connection()));
        }
        $this->table_row = mysqli_fetch_assoc($result);
    }

    // Get a specific RandomRule given its document/anchor/langcode
    public static function load_from_anchor($document, $anchor, $langcode='en')
    {
        $sql = sprintf("
            SELECT id
            FROM rules
            WHERE
                document = '%s' AND
                anchor = '%s' AND
                langcode = '%s'
        ", mysqli_real_escape_string(DPDatabase::get_connection(), $document),
            mysqli_real_escape_string(DPDatabase::get_connection(), $anchor),
            mysqli_real_escape_string(DPDatabase::get_connection(), $langcode));

        $result = mysqli_query(DPDatabase::get_connection(), $sql);
        if(!$result)
        {
            throw new UnexpectedValueException(mysqli_error(DPDatabase::get_connection()));
        }
        $row = mysqli_fetch_assoc($result);
        mysqli_free_result($result);
        return new RandomRule($row["id"]);
    }

    // Get a RandomRule object for a given document-langcode pair.
    public static function get_random($document, $langcode='en')
    {
        $sql = sprintf("
            SELECT id
            FROM rules
            WHERE
                document = '%s' AND
                langcode = '%s'
            ORDER BY RAND(NOW())
            LIMIT 1
        ", mysqli_real_escape_string(DPDatabase::get_connection(), $document),
            mysqli_real_escape_string(DPDatabase::get_connection(), $langcode));

        $result = mysqli_query(DPDatabase::get_connection(), $sql);
        if(!$result)
        {
            throw new UnexpectedValueException(mysqli_error(DPDatabase::get_connection()));
        }
        $row = mysqli_fetch_assoc($result);
        mysqli_free_result($result);
        if(!$row)
            return NULL;
        return new RandomRule($row["id"]);
    }

    // Return an array of RandomRule objects for a given document/langcode pair
    public static function get_rules($document, $langcode='en')
    {
        $sql = sprintf("
            SELECT id
            FROM rules
            WHERE
                document = '%s' AND
                langcode = '%s'
            ORDER BY id
        ", mysqli_real_escape_string(DPDatabase::get_connection(), $document),
            mysqli_real_escape_string(DPDatabase::get_connection(), $langcode));

        $result = mysqli_query(DPDatabase::get_connection(), $sql);
        if(!$result)
        {
            throw new UnexpectedValueException(mysqli_error(DPDatabase::get_connection()));
        }

        $rules = [];
        while($row = mysqli_fetch_assoc($result))
        {
            $rules[] = new RandomRule($row["id"]);
        }
        mysqli_free_result($result);
        return $rules;
    }

    // Return an associative array containing metadata about the rules in
    // the table. Useful primarily for the administartive interface.
    public static function get_summary()
    {
        $sql = "
            SELECT count(*) AS count, document, langcode
            FROM rules
            GROUP BY document, langcode
            ORDER BY langcode, document
        ";

        $result = mysqli_query(DPDatabase::get_connection(), $sql);

        $summary = [];
        while($row = mysqli_fetch_assoc($result))
        {
            $summary[] = [
                'document' => $row['document'],
                'langcode' => $row['langcode'],
                'count' => $row['count'],
            ];
        }
        mysqli_free_result($result);
        return $summary;
    }

    // Delete rules in the database matching a specific document/langcode pair
    public static function delete_rules($document, $langcode)
    {
        $sql = sprintf("
            DELETE
            FROM rules
            WHERE
                document = '%s' AND
                langcode = '%s'
        ", mysqli_real_escape_string(DPDatabase::get_connection(), $document),
            mysqli_real_escape_string(DPDatabase::get_connection(), $langcode));
        mysqli_query(DPDatabase::get_connection(), $sql);
    }

    // Reload rules by parsing a URL and reloading the rules found in it
    // This function supports the HTML rendered from the in-code documents in faq/
    // as well as the wiki-generated HTML for the guidelines at pgdp.net.
    public static function reload_rules($url, $document, $langcode)
    {
        // delete all existing random rules for this ($document, $langcode)
        RandomRule::delete_rules($document, $langcode);

        $fhandle = fopen($url, "rt");

        $anchor = $subject = $rule= "";
        $in_rule = false;
        while($line = fgets($fhandle))
        {
            // wiki syntax
            if(preg_match('/^<div id="(.*)"><\/div>$/', $line, $matches))
            {
                if($in_rule)
                {
                    RandomRule::_add_rule($document, $langcode, $url, $anchor, $subject, $rule);
                    $anchor = $subject = $rule= "";
                }
                $anchor = $matches[1];
                $in_rule = true;
                continue;
            }
            // code syntax
            if(preg_match('/<h3><a name="(.*)">(.*)<\/a><\/h3>/', $line, $matches))
            {
                if($in_rule)
                {
                    RandomRule::_add_rule($document, $langcode, $url, $anchor, $subject, $rule);
                    $anchor = $subject = $rule= "";
                }
                $anchor = $matches[1];
                $subject = $matches[2];
                $in_rule = true;
                continue;
            }
            // wiki syntax
            elseif(preg_match('/^<h3>.*>(.*)<.*<\/h3>/', $line, $matches))
            {
                $subject = $matches[1];
                continue;
            }
            // wiki and code syntax
            if(preg_match('/Back to top/', $line) || preg_match('/END RR/', $line))
            {
                RandomRule::_add_rule($document, $langcode, $url, $anchor, $subject, $rule);
                $anchor = $subject = $rule= "";
                $in_rule = false;
                continue;
            }
            if($in_rule)
            {
                $rule .= $line;
                continue;
            }
        }
        if($in_rule)
            RandomRule::_add_rule($document, $langcode, $url, $anchor, $subject, $rule);
        fclose($fhandle);
    }

    // Add a rule to the database
    private static function _add_rule($document, $langcode, $url, $anchor, $subject, $rule)
    {
        // skip "empty" rules, likely caused by parser errors
        if(!$anchor || !$subject || !$rule)
            return;

        // update rule URLs
        $rule = preg_replace_callback('/<a href="([^"]*)"[^>]*/',
            function ($match) use ($url) {
                if(startswith($match[1], '#'))
                {
                    return '<a href="' . $url . $match[1] . '"';
                }
                elseif(startswith($match[1], '/'))
                {
                    $url_parts = parse_url($url);
                    return '<a href="' . $url_parts["scheme"] . "://" . $url_parts["host"] . $match[1] . '"';
                }
                elseif(!startswith($match[1], 'http'))
                {
                    $base_url = dirname($url) . '/';
                    return '<a href="' . $base_url . $match[1] . '"';
                }
                else
                {
                    return $match[0];
                }
            },
            $rule
        );

        $sql = sprintf("
            INSERT INTO rules
            SET
                document = '%s',
                langcode = '%s',
                anchor = '%s',
                subject = '%s',
                rule = '%s'
        ", mysqli_real_escape_string(DPDatabase::get_connection(), $document),
            mysqli_real_escape_string(DPDatabase::get_connection(), $langcode),
            mysqli_real_escape_string(DPDatabase::get_connection(), $anchor),
            mysqli_real_escape_string(DPDatabase::get_connection(), $subject),
            mysqli_real_escape_string(DPDatabase::get_connection(), $rule));
        mysqli_query(DPDatabase::get_connection(), $sql);
    }
}
