<?php
$relPath='../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'pg.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'site_news.inc');

$title = _("FAQ Central");

$theme_args = array("css_data" => "
table.faqs td {
    font-family: Tahoma, sans-serif;
}
div.faqheader {
    background: $theme[color_headerbar_bg];
    color: $theme[color_headerbar_font];
    font-weight: bold;
    vertical-align: middle;
    padding: 0.25em 0.5em;
}
table#faqtable {
    width: 100%;
    border: 0;
    padding: 1em;
}
table#faqtable td.column {
    width: 50%;
    vertical-align: top;
    padding-right: 1em;
}
");
output_header($title, NO_STATSBAR, $theme_args);

echo "<h1>$title</h1>";

// TRANSLATORS: %s is the site name
echo "<p>" . sprintf(_("This page contains links to all the Documentation and FAQ (Frequently Asked Questions) files about the %s website."), $site_name) . "</p>";

show_news_for_page("FAQ");

// Introduction section
$intro = new FAQSection(_("Introductory Information"));
$intro->add_entry(new FAQEntry(
    _("Beginning Proofreader's FAQ"),
   "ProoferFAQ.php",
    _("Introduction to the site, general overview, beginner's questions.")
));
$intro->add_entry(new FAQEntry(
    _("Welcome Back, Alumni"),
    "longabsent.php",
    _("An overview of site changes from the last few years, including the distinction between proofreading and formatting.")
));
$intro->add_entry(new FAQEntry(
    _("Proofreading Interface Diagram"),
    "InterfaceDiagram.png",
    _("An overview flowchart which outlines the operation of the proofreading interface.")
));
$intro->add_entry(new FAQEntry(
    // TRANSLATORS: %s is the site abbreviation
    sprintf(_("%s Privacy Policy"), $site_abbreviation),
    "privacy.php",
    // TRANSLATORS: %s is the site name
    sprintf(_("The current version of the %s Privacy Policy."), $site_name)
));


// Proofreading
$proofing = new FAQSection(_("Proofreading"));
$proofing->add_entry(new FAQEntry(
    _("Proofreading Guidelines"),
    "proofreading_guidelines.php",
    _("The details of the guidelines we use for proofreading documents."),
    array(
        "fr" => "proofreading_guidelines_francaises.php",
        "pt" => "proofreading_guidelines_portuguese.php",
        "es" => "proofreading_guidelines_spanish.php",
        "nl" => "proofreading_guidelines_dutch.php",
        "de" => "proofreading_guidelines_german.php",
        "it" => "proofreading_guidelines_italian.php",
    )
));
$proofing->add_entry(new FAQEntry(
    _("Proofreading Summary"),
    "proofing_summary.pdf",
    _("Printable (.pdf) two-page summary of the most commonly needed proofreading standards from the Proofreading Guidelines, done as a big example!"),
    array(
        "fr" => "proofing_summary_french.pdf",
    )
));
$proofing->add_entry(new FAQEntry(
    _("Proofreading Quizzes & Tutorials"),
    "../quiz/start.php?show_only=PQ",
    _("Try the proofreading quizzes and tutorials. They are a great walk through the basic Guidelines for beginners and an excellent refresher for old hands.")
));


// Creating and Managing Projects
$cp_pm = new FAQSection("Creating and Managing Projects");
$cp_pm->add_entry(new FAQEntry(
    _("Project Managers' FAQ"),
    "pm-faq.php",
    _("Information for new or aspiring Project Managers. Project Managers are people who manage the progress of a particular project (\"book\") through this site.")
));
$cp_pm->add_entry(new FAQEntry(
    _("Content Providers' FAQ"),
    "cp.php",
    _("Overview for people who want to contribute material to be proofread on this site.")
));
$cp_pm->add_entry(new FAQEntry(
    _("Scanning FAQ"),
    "scanning.php",
    _("Basic information on scanners and how to use them, based on our experiences.")
));


// Formatting
$formatting = new FAQSection(_("Formatting"));
$formatting->add_entry(new FAQEntry(
    _("Formatting Guidelines"),
    "document.php",
    _("The full details of the guidelines we use for formatting documents."),
    array(
        "fr" => "formatting_guidelines_francaises.php",
        "pt" => "formatting_guidelines_portuguese.php",
        "nl" => "formatting_guidelines_dutch.php",
        "de" => "formatting_guidelines_german.php",
        "it" => "formatting_guidelines_italian.php",
    )
));
$formatting->add_entry(new FAQEntry(
    _("Formatting Summary"),
    "formatting_summary.pdf",
    _("Printable (.pdf) two-page summary of the most commonly needed formatting standards from the Formatting Guidelines, done as a big example!")
));
$formatting->add_entry(new FAQEntry(
    _("Formatting Quiz"),
    "../quiz/start.php?show_only=FQ",
    _("Try the formatting quiz. It is a great walk through the basic Guidelines for beginners and an excellent refresher for old hands.")
));


// Tools
// TRANSLATORS: %s is the site abbreviation
$tools = new FAQSection(sprintf(_("%s Tools"), $site_abbreviation));
$tools->add_entry(new FAQEntry(
    _("DP Custom Proofreading Font"),
    "font_sample.php",
    _("Sample and download the custom DP font.")
));
$tools->add_entry(new FAQEntry(
    _("WordCheck FAQ"),
    "wordcheck-faq.php",
    _("Information on the WordCheck engine and interface.")
));
$tools->add_entry(new FAQEntry(
    _("Standard Proofreading Interface Help"),
    "prooffacehelp.php?i_type=0",
    _("Help for the Standard Proofreading Interface.")
));
$tools->add_entry(new FAQEntry(
    _("Enhanced Proofreading Interface Help"),
    "prooffacehelp.php?i_type=1",
    _("Help for the Enhanced Proofreading Interface.")
));


// Mentoring
$mentoring = new FAQSection(_("Mentoring"));
$mentoring->add_entry(new FAQEntry(
    _("Mentors' Page"),
    "$code_url/tools/proofers/for_mentors.php",
    _("A page detailing currently available mentor projects.")
));


// PP & PPV
$ppv = new FAQSection(_("Post-Processing and Verification"));
$ppv->add_entry(new FAQEntry(
    _("Post-Processing FAQ"),
    "post_proof.php",
    _("Information for new and aspiring Post-Processors. Post-Processors process a particular project after it has been proofread and formatted on this site (combining all the pages, making them consistent, fixing problems, and submitting it to Project Gutenberg).")
));
$ppv->add_entry(new FAQEntry(
    _("Post-Processing Verification Guidelines"),
    "ppv.php",
    _("Information for Post-Processing Verifiers.")
));


// Bugs & Development
$dev = new FAQSection(_("Suggestions, Bugs, and Development"));
$dev->add_entry(new FAQEntry(
    _("Translation FAQ"),
    "translate.php",
    _("Information on how site translations work, including how to install and edit them.")
));
$dev->add_entry(new FAQEntry(
    _("Task Center"),
    "$code_url/tasks.php",
    _("Here you will find a list of feature requests and bugs. You may add tasks after searching to see that the issue isn't already there.")
));
$dev->add_entry(new FAQEntry(
    _("Development Process"),
    "dev_process.php",
    _("Information and guidelines for developers.")
));


// Project Gutenberg
$pg = new FAQSection(_("Project Gutenberg"));
$pg->add_entry(new FAQEntry(
    _("Project Gutenberg FAQ"),
    $PG_faq_url,
    sprintf(_("The <i>massive</i> FAQ from <a href='%s''>Project Gutenberg</a>."), $PG_home_url)
));


// Assemble and output the table
$faq = new FAQ();
$faq->add_section($intro);
$faq->add_section($cp_pm);
$faq->add_section($mentoring);
$faq->add_section($dev);
$faq->add_section($pg);
$faq->add_section("NEW_COLUMN");
$faq->add_section($proofing);
$faq->add_section($formatting);
$faq->add_section($ppv);
$faq->add_section($tools);
$faq->output();

//----------------------------------------------------------------------------

class FAQ
{
    function __construct()
    {
        $this->sections = array();
    }

    function add_section($section)
    {
        $this->sections[] = $section;
    }

    function output()
    {
        echo "<table class='faqs' id='faqtable'>";
        // Start with one column
        echo "<tr>";
        echo "<td class='column'>";
        foreach($this->sections as $section)
        {
            if($section == "NEW_COLUMN")
            {
                echo "</td>";
                echo "<td class='column'>";
                continue;
            }
            echo "<div class='faqheader'>$section->title</div>";

            $section->output();
        }
        echo "</td>";
        echo "</tr>";
        echo "</table>";
    }
}

class FAQSection
{
    function __construct($title)
    {
        $this->title = $title;
        $this->entries = array();
    }

    function add_entry($entry)
    {
        $this->entries[] = $entry;
    }

    function output()
    {
        foreach($this->entries as $entry)
        {
            echo "<p><a href='$entry->url'>" . html_safe($entry->title) . "</a><br>";
            echo "<span style='font-size: 0.8em;'>";
            if($entry->alt_languages)
            {
                $links = array();
                foreach($entry->alt_languages as $iso => $url)
                {
                    $links[] = "<a href='$url'>" . lang_name($iso) . "</a>";
                }
                // TRANSLATORS: %s is a comma-separated list of language names that link to FAQs in that language
                echo sprintf(_("Also available in: %s"), implode(", ", $links));
                echo "<br>";
            }
            echo $entry->text;
            echo "</span></p>";
        }
    }
}

class FAQEntry
{
    function __construct($title, $url, $text, $alt_languages=NULL)
    {
        $this->title = $title;
        $this->url = $url;
        $this->text = $text;
        $this->alt_languages = $alt_languages;
    }
}

// vim: sw=4 ts=4 expandtab
