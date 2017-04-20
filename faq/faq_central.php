<?php
$relPath='../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'pg.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'site_news.inc');

# SITE-SPECIFIC
# pgdp.net/org have some documents in the wiki.
if(strpos($code_url, '://www.pgdp.'))
{
    $use_pgdp_urls = TRUE;
}
else
{
    $use_pgdp_urls = FALSE;
}

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
if($use_pgdp_urls)
{
    $urls = array(
        "en" => "https://www.pgdp.net/wiki/DP_Official_Documentation:General/New_Volunteer_Frequently_Asked_Questions",
        "fr" => 'https://www.pgdp.net/wiki/DP_Official_Documentation:General/French/Aper%C3%A7u_de_DP',
    );
}
else
{
    $urls = array(
        "en" => "ProoferFAQ.php",
    );
}
$intro->add_entry(new FAQEntry(
    _("Beginning Proofreader's FAQ"),
    $urls,
    _("Introduction to the site, general overview, beginner's questions.")
));
$intro->add_entry(new FAQEntry(
    _("Welcome Back, Alumni"),
    array(
        "en" => "longabsent.php",
    ),
    _("An overview of site changes from the last few years, including the distinction between proofreading and formatting.")
));
$intro->add_entry(new FAQEntry(
    _("Proofreading Interface Diagram"),
    array(
        "en" => "InterfaceDiagram.png",
    ),
    _("An overview flowchart which outlines the operation of the proofreading interface.")
));
$intro->add_entry(new FAQEntry(
    // TRANSLATORS: %s is the site abbreviation
    sprintf(_("%s Privacy Policy"), $site_abbreviation),
    array(
        "en" => "privacy.php",
    ),
    // TRANSLATORS: %s is the site name
    sprintf(_("The current version of the %s Privacy Policy."), $site_name)
));


// Proofreading
$proofing = new FAQSection(_("Proofreading"));
if($use_pgdp_urls)
{
    $urls = array(
        "en" => "https://www.pgdp.net/wiki/DP_Official_Documentation:Proofreading/Proofreading_Guidelines",
        "fr" => "https://www.pgdp.net/wiki/DP_Official_Documentation:Proofreading/French/Directives_de_Relecture_et_de_Correction",
        "pt" => "https://www.pgdp.net/wiki/DP_Official_Documentation:Proofreading/Portuguese/Regras_de_Revis%C3%A3o",
        "es" => "https://www.pgdp.net/wiki/DP_Official_Documentation:Proofreading/Spanish/Reglas_de_Revisi%C3%B3n",
        "nl" => "https://www.pgdp.net/wiki/DP_Official_Documentation:Proofreading/Dutch/Proeflees-Richtlijnen",
        "de" => "https://www.pgdp.net/wiki/DP_Official_Documentation:Proofreading/German/Korrekturlese-Richtlinien",
        "it" => "proofreading_guidelines_italian.php",
    );
}
else
{
    $urls = array(
        "en" => "proofreading_guidelines.php",
        "fr" => "proofreading_guidelines_francaises.php",
        "pt" => "proofreading_guidelines_portuguese.php",
        "es" => "proofreading_guidelines_spanish.php",
        "nl" => "proofreading_guidelines_dutch.php",
        "de" => "proofreading_guidelines_german.php",
        "it" => "proofreading_guidelines_italian.php",
    );
}
$proofing->add_entry(new FAQEntry(
    _("Proofreading Guidelines"),
    $urls,
    _("The details of the guidelines we use for proofreading documents.")
));
$proofing->add_entry(new FAQEntry(
    _("Proofreading Summary"),
    array(
        "en" => "proofing_summary.pdf",
        "fr" => "proofing_summary_french.pdf",
    ),
    _("Printable (.pdf) two-page summary of the most commonly needed proofreading standards from the Proofreading Guidelines, done as a big example!")
));
$proofing->add_entry(new FAQEntry(
    _("Proofreading Quizzes & Tutorials"),
    array(
        "en" => "../quiz/start.php?show_only=PQ",
    ),
    _("Try the proofreading quizzes and tutorials. They are a great walk through the basic Guidelines for beginners and an excellent refresher for old hands.")
));


// Creating and Managing Projects
$cp_pm = new FAQSection("Creating and Managing Projects");
if($use_pgdp_urls)
{
    $urls = array(
        "en" => "https://www.pgdp.net/wiki/DP_Official_Documentation:CP_and_PM/Project_Managing_FAQ",
        "fr" => "https://www.pgdp.net/wiki/DP_Official_Documentation:CP_and_PM/FAQ_gestion_de_projet",
        "pt" => "https://www.pgdp.net/wiki/DP_Official_Documentation:CP_and_PM/Gest%C3%A3o_de_Projectos",
    );
}
else
{
    $urls = array(
        "en" => "pm-faq.php",
    );
}
$cp_pm->add_entry(new FAQEntry(
    _("Project Managers' FAQ"),
    $urls,
    _("Information for new or aspiring Project Managers. Project Managers are people who manage the progress of a particular project (\"book\") through this site.")
));
if($use_pgdp_urls)
{
    $urls = array(
        "en" => "https://www.pgdp.net/wiki/DP_Official_Documentation:CP_and_PM/Content_Providing_FAQ",
        "fr" => "https://www.pgdp.net/wiki/DP_Official_Documentation:CP_and_PM/FAQ_fourniture_de_contenu",
        "pt" => "https://www.pgdp.net/wiki/DP_Official_Documentation:CP_and_PM/Fornecimento_de_Conte%C3%BAdos",
    );
}
else
{
    $urls = array(
        "en" => "cp.php",
    );
}
$cp_pm->add_entry(new FAQEntry(
    _("Content Providers' FAQ"),
    $urls,
    _("Overview for people who want to contribute material to be proofread on this site.")
));
$cp_pm->add_entry(new FAQEntry(
    _("Scanning FAQ"),
    array(
        "en" => "scanning.php",
    ),
    _("Basic information on scanners and how to use them, based on our experiences.")
));


// Formatting
$formatting = new FAQSection(_("Formatting"));
if($use_pgdp_urls)
{
    $urls = array(
        "en" => "https://www.pgdp.net/wiki/DP_Official_Documentation:Formatting/Formatting_Guidelines",
        "fr" => "https://www.pgdp.net/wiki/DP_Official_Documentation:Formatting/French/Directives_de_Formatage",
        "pt" => "https://www.pgdp.net/wiki/DP_Official_Documentation:Formatting/Portuguese/Regras_de_Formata%C3%A7%C3%A3o",
        "nl" => "https://www.pgdp.net/wiki/DP_Official_Documentation:Formatting/Dutch/Formatteer-Richtlijnen",
        "de" => "https://www.pgdp.net/wiki/DP_Official_Documentation:Formatting/German/Formatierungsrichtlinien",
        "it" => "formatting_guidelines_italian.php",
    );
}
else
{
    $urls = array(
        "en" => "document.php",
        "fr" => "formatting_guidelines_francaises.php",
        "pt" => "formatting_guidelines_portuguese.php",
        "nl" => "formatting_guidelines_dutch.php",
        "de" => "formatting_guidelines_german.php",
        "it" => "formatting_guidelines_italian.php",
    );
}
$formatting->add_entry(new FAQEntry(
    _("Formatting Guidelines"),
    $urls,
    _("The full details of the guidelines we use for formatting documents.")
));
$formatting->add_entry(new FAQEntry(
    _("Formatting Summary"),
    array(
        "en" => "formatting_summary.pdf",
    ),
    _("Printable (.pdf) two-page summary of the most commonly needed formatting standards from the Formatting Guidelines, done as a big example!")
));
$formatting->add_entry(new FAQEntry(
    _("Formatting Quiz"),
    array(
        "en" => "../quiz/start.php?show_only=FQ",
    ),
    _("Try the formatting quiz. It is a great walk through the basic Guidelines for beginners and an excellent refresher for old hands.")
));


// Tools
// TRANSLATORS: %s is the site abbreviation
$tools = new FAQSection(sprintf(_("%s Tools"), $site_abbreviation));
$tools->add_entry(new FAQEntry(
    _("DP Custom Proofreading Font"),
    array(
        "en" => "font_sample.php",
    ),
    _("Sample and download the custom DP font.")
));
$tools->add_entry(new FAQEntry(
    _("WordCheck FAQ"),
    array(
        "en" => "wordcheck-faq.php",
    ),
    _("Information on the WordCheck engine and interface.")
));
$tools->add_entry(new FAQEntry(
    _("Standard Proofreading Interface Help"),
    array(
        "en" => "prooffacehelp.php?i_type=0",
    ),
    _("Help for the Standard Proofreading Interface.")
));
$tools->add_entry(new FAQEntry(
    _("Enhanced Proofreading Interface Help"),
    array(
        "en" => "prooffacehelp.php?i_type=1",
    ),
    _("Help for the Enhanced Proofreading Interface.")
));


// Mentoring
$mentoring = new FAQSection(_("Mentoring"));
$mentoring->add_entry(new FAQEntry(
    _("Mentors' Page"),
    array(
        "en" => "$code_url/tools/proofers/for_mentors.php",
    ),
    _("A page detailing currently available mentor projects.")
));


// PP & PPV
$ppv = new FAQSection(_("Post-Processing and Verification"));
if($use_pgdp_urls)
{
    $urls = array(
        "en" => "https://www.pgdp.net/wiki/DP_Official_Documentation:PP_and_PPV/Post-Processing_FAQ",
        "fr" => "https://www.pgdp.net/wiki/French/FAQ_post-processing",
    );
}
else
{
    $urls = array(
        "en" => "post_proof.php",
    );
}
$ppv->add_entry(new FAQEntry(
    _("Post-Processing FAQ"),
    $urls,
    _("Information for new and aspiring Post-Processors. Post-Processors process a particular project after it has been proofread and formatted on this site (combining all the pages, making them consistent, fixing problems, and submitting it to Project Gutenberg).")
));
$ppv->add_entry(new FAQEntry(
    _("Post-Processing Verification Guidelines"),
    array(
        "en" => "ppv.php",
    ),
    _("Information for Post-Processing Verifiers.")
));


// Bugs & Development
$dev = new FAQSection(_("Suggestions, Bugs, and Development"));
$dev->add_entry(new FAQEntry(
    _("Translation FAQ"),
    array(
        "en" => "translate.php",
    ),
    _("Information on how site translations work, including how to install and edit them.")
));
$dev->add_entry(new FAQEntry(
    _("Task Center"),
    array(
        "en" => "$code_url/tasks.php",
    ),
    _("Here you will find a list of feature requests and bugs. You may add tasks after searching to see that the issue isn't already there.")
));
$dev->add_entry(new FAQEntry(
    _("Development Process"),
    array(
        "en" => "dev_process.php",
    ),
    _("Information and guidelines for developers.")
));


// Project Gutenberg
$pg = new FAQSection(_("Project Gutenberg"));
$pg->add_entry(new FAQEntry(
    _("Project Gutenberg FAQ"),
    array(
        "en" => $PG_faq_url,
    ),
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
        $user_iso = substr(get_desired_language(), 0, 2);
        foreach($this->entries as $entry)
        {
            // When we output the entry, link the title to the desired doc
            // in the user's language and include pointers to other language
            // options where available. If the document isn't available in
            // the user's language, don't include a link in the title and
            // instead just show all available language options.

            echo "<p>";
            if(isset($entry->urls[$user_iso]))
                echo "<a href='" . $entry->urls[$user_iso] . "'>" . html_safe($entry->title) . "</a>";
            else
                echo html_safe($entry->title);
            echo "<br>";
            echo "<span style='font-size: 0.8em;'>";
            $links = array();
            foreach($entry->urls as $iso => $url)
            {
                // If the document is available in the user's langauge it
                // has been used as a link in the title, so don't include
                // it in the list of available languages.
                if($iso == $user_iso)
                    continue;

                $links[] = "<a href='$url'>" . lang_name($iso) . "</a>";
            }
            if($links)
            {
                // Subtly alter the wording if the document was available in
                // the user's language.
                if(isset($entry->urls[$user_iso]))
                {
                    // TRANSLATORS: %s is a comma-separated list of language names that link to FAQs in that language
                    echo sprintf(_("Also available in: %s"), implode(", ", $links));
                }
                else
                {
                    // TRANSLATORS: %s is a comma-separated list of language names that link to FAQs in that language
                    echo sprintf(_("Available in: %s"), implode(", ", $links));
                }

                echo "<br>";
            }
            echo $entry->text;
            echo "</span></p>";
        }
    }
}

class FAQEntry
{
    function __construct($title, $urls, $text)
    {
        $this->title = $title;
        $this->urls = $urls;
        $this->text = $text;
    }
}

// vim: sw=4 ts=4 expandtab
