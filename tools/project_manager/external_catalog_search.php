<?php

// Searching for book records in an external catalog
// via Z39.50 protocol (implemented by yaz library).

$relPath = '../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'MARCRecord.inc');


$title_attrs = [
    // Bib-1 Use, Title (attribute 4)
    // "A word, phrase, character, or group of characters, normally appearing in an item,
    // that names the item or the work contained in it.
    // Matches 130, 21X-24X, 440, 490, 730, 740, 830, 840, subfield $t in 400, 410, 410, 600,
    // 610, 611, 700, 710, 711, 800, 810, 811"
    // That leads to broad matches, similar to the KTIL LoC advanced search, but
    // sometimes is necessary due to missing fields in MARC records.
    // https://catalog.loc.gov/vwebv/ui/en_US/htdocs/help/searchExamples.html#KTIL

    4 => _("Title (wide)"),
    // Bib-1 Use, Title Uniform (attribute 6)
    // "The particular title by which a work is to be identified for cataloging purposes.
    // Matches [MARC fields] 130, 240, 730; subfield $t in 700, 710, 711."
    // Seems to have the same behavior as the KTUT advanced search on the LoC website
    // https://catalog.loc.gov/vwebv/ui/en_US/htdocs/help/searchExamples.html#KTUT
    6 => _("Title (narrow)"),
];

$author_attrs = [
    // Bib-1 Use, Author-name-personal (attribute 1004)
    // "A person's real name, pseudonym, title of nobility nickname, or initials.
    // (Differs from attribute "Author-name" in that personal name subject
    // headings are not included.)
    // [Matches MARC fields] 100, 400, 700, 800"
    // Seems similar to KPNC advanced search on LoC website.
    // https://catalog.loc.gov/vwebv/ui/en_US/htdocs/help/searchExamples.html#KPNC
    1004 => _("Author name (personal)"),

    // Bib-1 Use, Author-name (attribute 1003).
    // "A personal or corporate author or a conference or meeting name.
    // (No subject name headings are included.)
    // [Matches MARC fields] 100, 110, 111, 400, 410, 411, 700, 710, 711, 800, 810, 811)"
    // That leads to broad matches, similar to the KNAM advanced search.
    // https://catalog.loc.gov/vwebv/ui/en_US/htdocs/help/searchExamples.html#KNAM
    1003 => _("Author name (all)"),
];

$serial_attrs = [
    9 => _('LCCN'), // Library of Congress Control Number
    8 => _('ISSN'), // International Standard Serial Number
    7 => _('ISBN'), // International Standard Book Number
];

$search_params = [
    'title' => ['type' => 'attr', 'attrs' => $title_attrs],
    'author' => ['type' => 'attr', 'attrs' => $author_attrs],
    'publisher' => ['type' => 'text', 'name' => _('Publisher')],
    'pubdate' => ['type' => 'text', 'name' => _('Publication Year (eg: 1912)')],
    'serial' => ['type' => 'attr', 'attrs' => $serial_attrs],
    'hide_nontext' => ['type' => 'checkbox', 'name' => _("Hide non-textual results")],
];

/** Build a URL query fragment from the populated $_REQUEST parameters  **/
function search_query_params(): string
{
    global $search_params;
    $params = [];
    foreach ($search_params as $k => $info) {
        $val = array_get($_REQUEST, $k, null);
        if (!empty($val)) {
            $params[$k] = $val;
            // Only set the attribute param if we searched
            // for something with that attribute.
            if ($info['type'] == 'attr') {
                $attr_val = array_get($_REQUEST, "{$k}_attr", null);
                if (!empty($attr_val)) {
                    $params["{$k}_attr"] = $attr_val;
                }
            }
        }
    }
    return http_build_query($params);
}

require_login();

$action = get_enumerated_param($_REQUEST, 'action', 'show_query_form', ['show_query_form', 'do_search_and_show_hits']);

if ($action == 'show_query_form') {
    show_query_form();
} elseif ($action == "do_search_and_show_hits") {
    do_search_and_show_hits();
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function show_query_form()
{
    global $search_params;

    $title = _("Create a Project");
    output_header($title);

    if (!function_exists('yaz_connect')) {
        echo "<p class='error'>";
        echo _("PHP is not compiled with YAZ support.  Please do so and try again.");
        echo "</p>";
        echo "<p>";
        echo sprintf(
            _("Until you do so, click <a href='%s'>here</a> for creating a new project."),
            'editproject.php?action=createnew'
        );
        echo "</p>";
        echo "<p>";
        echo sprintf(
            _("If you believe you should be seeing the Create Project page please contact a <a href='%s'>Site Administrator</a>"),
            "mailto:".$GLOBALS['site_manager_email_addr']
        );
        echo "</p>";
    } else {
        echo "<h1>$title</h1>";

        echo "<p>";
        echo _("Please put in as much information as possible to search for your project.  The more information the better but if not accurate enough may rule out results.");
        echo "</p>";

        $url = "external_catalog_search.php?" . search_query_params();
        echo "<form method='post' action='" . attr_safe($url) . "'>\n";
        echo "<input type='hidden' name='action' value='do_search_and_show_hits'>\n";
        echo "<table class='basic'>";

        foreach ($search_params as $label => $info) {
            echo "<tr>";
            if ($info['type'] == 'attr') {
                echo   "<th class='label'><select name='{$label}_attr'>\n";
                foreach ($info['attrs'] as $value => $attr_label) {
                    $selected = array_get($_REQUEST, "{$label}_attr", null) == $value ? " selected" : "";
                    echo   "<option value='$value'$selected>$attr_label</option>\n";
                }
                $value = attr_safe(array_get($_REQUEST, $label, ""));
                echo   "</select></th>";
                echo   "<td>";
                echo     "<input type='text' size='30' name='{$label}' maxlength='255' value='$value'>";
                echo   "</td>";
                echo "</tr>\n";
            } elseif ($info['type'] == 'text') {
                $value = attr_safe(array_get($_REQUEST, $label, ""));
                echo "<tr>";
                echo   "<th class='label'>{$info['name']}</th>";
                echo   "<td>";
                echo     "<input type='text' size='30' name='$label' maxlength='255' value='$value'>";
                echo   "</td>";
                echo "</tr>\n";
            } elseif ($info['type'] == 'checkbox') {
                $checked = !empty(array_get($_REQUEST, $label, "")) ? " checked" : "";
                echo "<tr><th colspan='2'>";
                echo "<input type='checkbox' $checked name='$label'>";
                echo $info['name'];
                echo "</th></tr>";
            }
        }


        echo "<tr>";
        echo   "<th colspan='2'>";
        echo     "<input type='submit' value='", attr_safe(_('Search')), "'>";
        echo   "</th>";
        echo "</tr>\n";

        echo "</table>";
        echo "</form>\n";
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function do_search_and_show_hits()
{
    output_header("Search Results");
    echo "<br>";
    $start = get_integer_param($_GET, 'start', 1, 1, null);
    $hide_nontext = !empty(@$_REQUEST['hide_nontext']);
    if (!empty($_GET['fq'])) {
        $fullquery = unserialize(base64_decode($_GET['fq']));
    } else {
        $fullquery = query_format();
    }

    global $external_catalog_locator;
    // We request UTF-8 character set, but according to the docs (and our testing)
    // most servers ignore this and return ISO-8859-1 anyway. The strings get
    // converted to UTF-8 via MARCRecord::__get() instead.
    $id = yaz_connect($external_catalog_locator, ["charset" => "UTF-8"]);
    yaz_syntax($id, "usmarc");
    yaz_element($id, "F");
    yaz_search($id, "rpn", trim(str_replace("\n", " ", $fullquery)));
    $extra_options = ["timeout" => 60];
    yaz_wait($extra_options);
    $errorMsg = yaz_error($id);

    echo "<details>\n";
    echo "<summary>Raw query</summary>\n";
    echo "<pre>$fullquery</pre>\n";
    echo "</details>\n";

    if (!empty($errorMsg)) {
        echo "<p class='error'>", _("The following error has occurred:"), " $errorMsg", "</p>";
        echo "<p>";
        $url = "editproject.php?action=createnew";
        echo sprintf(
            _("Please try again. If the problem recurs, please create your project manually by following this <a href='%s'>link</a>."),
            $url
        );
        echo "</p>";
        exit();
    }

    if (yaz_hits($id) == 0) {
        echo "<p class='warning'>", _("There were no results returned."), "</p>\n";
        echo "<p>", sprintf(_("Please search again or click '%s' to create the project manually."), _("No Matches")), "</p>\n";
    }

    // Read and parse results in a batch
    $total_hits = yaz_hits($id);
    $num_nontext = 0;
    $hits_per_page = 20; // Perhaps later this can be a PM preference or an option on the form.

    $marc_records = [];
    for ($i = 0; $i < $hits_per_page && $start + $i <= $total_hits; $i++) {
        $rec = yaz_record($id, $start + $i, "array");
        // if $rec isn't an array, then yaz_record() failed and we should
        // skip this record
        if (!is_array($rec)) { /** @phpstan-ignore-line */
            continue;
        }
        /** @phpstan-ignore-next-line */
        $marc_record = new MARCRecord();
        $marc_record->load_yaz_array($rec);
        if (!in_array($marc_record->get_type_of_record(), ["Language material", "Manuscript language material"])) {
            $num_nontext++;
            if ($hide_nontext) {
                continue;
            }
        }
        $marc_records[] = [$rec, $marc_record];
    }
    yaz_close($id);

    // Display results
    echo "<p>", sprintf(_("%d results returned."), $total_hits), "</p>\n";

    if ($total_hits > 0) {
        $encoded_fullquery = base64_encode(serialize($fullquery));
        $url_base = "external_catalog_search.php?action=do_search_and_show_hits&fq=$encoded_fullquery&" . search_query_params();

        // PHPStan up to at least 1.10.57 has a bug where it doesn't correctly detect
        // that $num_nontext is updated in the loop. We need to sprinkle several ignores
        // to get it to ignore this.
        // @phpstan-ignore-next-line
        $book_frag = ($hide_nontext && $num_nontext > 0) ? sprintf(_(" (%d non-textual hidden)"), $num_nontext) : "";

        if ($num_nontext < $hits_per_page) { /** @phpstan-ignore-line */
            echo "<p>", _("Please pick a result from below:"), "</p>\n";

            display_navbar($url_base, $start, $hits_per_page, $total_hits, $book_frag);

            // Display the results as a two column, multi-row table where each cell is
            // an input form radio button with a single subtable of label/value rows.
            echo "<form method='post' action='editproject.php'>\n";
            echo "<input type='hidden' name='action' value='create_from_marc_record'>\n";
            echo "<table style='width: 100%; border: 0;'>\n";

            $i = 1;
            foreach ($marc_records as [$r, $m]) { /** @phpstan-ignore-line */
                if ($i % 2 == 1) {
                    echo "<tr>";
                }
                // Radio button to select record
                echo "<td class='center-align top-align' style='width: 5%;'>";
                echo "<input style='margin-top:1.6em' type='radio' name='rec' value='".base64_encode(serialize($r))."'>";
                echo "</td>";

                // Subtable for record
                echo "<td class='left-align top-align' style='width: 45%;'>";

                // Compresss the record so we don't run over URL length limits
                $rec = base64url_encode(gzencode(serialize($r), 9));
                echo "<div style='text-align:right; font-size:small'>\n";
                echo   "<a style='text-decoration: none' target='_blank' href='marc_inspector.php?rec=$rec'>\n";
                echo     "inspect MARC <i class='fa fa-external-link-alt'></i>\n";
                echo   "</a>\n";
                echo "</div>\n";

                display_record_table($m, $hide_nontext);
                echo "<p>"; // vertical gap between records
                echo "</td>";
                if ($i % 2 != 1) {
                    echo "</tr>\n";
                }
                $i++;
            }

            if ($i % 2 != 1) { /** @phpstan-ignore-line */
                echo "</tr>\n";
            }
            echo "</table>";
        }
        // Always display the bottom navbar, even if all results on a page are filtered away
        display_navbar($url_base, $start, $hits_per_page, $total_hits, $book_frag);
    }

    // Button bar
    echo "<p class='center-align'>";
    if ($total_hits != 0) {
        echo "<input type='submit' value='", attr_safe(_("Create the Project")), "'>";
    }

    foreach ([
        [_('Refine Search'), 'external_catalog_search.php?action=show_query_form&' . search_query_params()],
        [_('Start New Search'), 'external_catalog_search.php?action=show_query_form'],
        [_('No Matches'), 'editproject.php?action=createnew'],
        [_('Quit'), 'projectmgr.php'],
    ] as [$label, $url]) {
        echo "&nbsp;";
        echo "<input type='button' value='", attr_safe($label), "' onclick='javascript:location.href=\"", attr_safe($url), "\";'>";
    }
    echo "</p>";
    echo "</form>";
}

function link_or_text(string $url_base, string $label, bool $show_link, int $start): string
{
    return $show_link ? "<a href='" . attr_safe("$url_base&start=$start") . "'>" . html_safe($label) . "</a>" : $label;
}

function display_navbar(string $url_base, int $start, int $hits_per_page, int $total_hits, string $book_frag): void
{
    // NB yaz indexes are 1-based: from 1 to $total_hits inclusive.
    // To spell out the arithmetic here (because it's easy to make off-by-one errors)
    // I'll use examples of start=51, hits_per_page=10, total_hits=100
    $next = $start + $hits_per_page;            // next page will be 61 to 70
    $prev = max(1, $start - $hits_per_page);    // prev page will be 41 to 50. If we start at <11, prev=1
    $last = $total_hits - ($hits_per_page - 1); // last page will be 91 to 100.
    $frags = [
        link_or_text($url_base, "First", $start > 1, 1),
        link_or_text($url_base, "Previous", $start > 1, $prev),
        sprintf(_("Results %d to %d of %d%s"), $start, min($total_hits, $next - 1), $total_hits, $book_frag),
        link_or_text($url_base, "Next", $next <= $total_hits, min($total_hits, $next)),
        link_or_text($url_base, "Last", $next <= $total_hits, $last),
    ];
    echo "<p class='center-align'>", implode(" | ", $frags), "</p>\n";
}

function display_record_table(MARCRecord $marc_record, bool $hide_nontext): void
{
    echo "<table class='basic' style='width: 100%;'>";
    $fields = [];
    if (!$hide_nontext) {
        $fields[] = [_("Type of Record"), $marc_record->type_of_record];
    }
    $fields = array_merge($fields, [
        [_("Title"),     $marc_record->title],
        [_("Author"),    $marc_record->author],
        [_("Publisher"), $marc_record->publisher],
        [_("Language"),  $marc_record->language],
        [_("LCCN"),      $marc_record->lccn],
        [_("ISBN"),      $marc_record->isbn],
    ]);
    foreach ($fields as [$label, $value]) {
        echo "<tr>";
        echo   "<th class='left-align top-align' style='width: 20%;'>{$label}:</th>";
        echo   "<td class='left-align top-align'>{$value}</td>";
        echo "</tr>\n";
    }
    echo "</table>";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function query_format()
{
    global $title_attrs, $author_attrs, $serial_attrs;
    // Build a Z39.50 Type-1 query.
    // See
    // https://www.loc.gov/z3950/agency/markup/09.html Type-1 and Type-101 Queries
    //
    //
    // A Type-1 query is in prefix polish notation and a (prettified) simple query
    // for editions of Snowcrash published >= 1992 might look like
    // ```
    //  @and
    //  @and
    //  @attr 1=6 "Snowcrash"
    //  @attr 1=1004 "Stephenson, Neal"
    //  @attr 2=4 @attr 1=31 1992
    // ```
    // Expressions like `@attr 1=1004` mean value 1004 from attribute set 1
    // Set 1 is "Bib-1 Use", and 1004 is the value "Author-name-personal"
    // https://www.loc.gov/z3950/agency/defns/bib1.html
    // https://www.loc.gov/z3950/agency/bib1.html

    $fullquery = [];

    if ($_REQUEST['title']) {
        $attr = get_enumerated_param($_REQUEST, 'title_attr', null, array_keys($title_attrs));
        $fullquery[] = sprintf('@attr 1=%s "%s"', $attr, $_REQUEST['title']);
    }
    if ($_REQUEST['author']) {
        // Convert author to "Surname, Forename" if it doesn't already contain a comma.
        $author = $_REQUEST['author'];
        if (!str_contains($author, ",")) {
            $p = strrpos($author, " ");
            if ($p !== false) {
                $author = substr($author, $p) . ", " . substr($author, 0, $p);
            }
        }
        $attr = get_enumerated_param($_REQUEST, 'author_attr', null, array_keys($author_attrs));
        $fullquery[] = sprintf('@attr 1=%s "%s"', $attr, trim($author));
    }
    if ($_REQUEST['serial']) {
        $attr = get_enumerated_param($_REQUEST, 'serial_attr', null, array_keys($serial_attrs));
        $serial = trim(str_replace("-", "", $_REQUEST['serial'])); // str_replace only needed for ISBN
        // Bib-1 Relation Equal; Bib-1 Use, ISBN/ISSN/LCCN
        $fullquery[] = sprintf('@attr 2=3 @attr 1=%s "%s"', $attr, $serial);
    }
    if ($_REQUEST['pubdate']) {
        // Bib-1 Relation Equal; Bib-1 Use, Date of publication
        $fullquery[] = sprintf('@attr 2=3 @attr 1=31 "%s"', $_REQUEST['pubdate']);
    }
    if ($_REQUEST['publisher']) {
        // Bib-1 Use, Publisher
        $fullquery[] = sprintf('@attr 1=1018 "%s"', $_REQUEST['publisher']);
    }
    $c = count($fullquery) - 1;
    for ($i = 0; $i < $c; $i++) {
        array_unshift($fullquery, "@and");
    }
    return implode("\n", $fullquery);
}
