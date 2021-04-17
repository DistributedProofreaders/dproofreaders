<?php
$relPath = '../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'faq.inc');
include_once($relPath.'pg.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'site_news.inc');

maybe_redirect_to_external_faq();

$title = _("FAQ Central");

output_header($title, NO_STATSBAR);

echo "<h1>$title</h1>\n\n";

// TRANSLATORS: %s is the site name
echo "<p>" . sprintf(_("This page contains links to all the Documentation and FAQ (Frequently Asked Questions) files about the %s website."), $site_name) . "</p>\n";

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
    _("The details of the guidelines we use for proofreading documents.")
));
$proofing->add_entry(new FAQEntry(
    _("Proofreading Summary"),
    "proofing_summary.pdf",
    _("Printable (.pdf) two-page summary of the most commonly needed proofreading standards from the Proofreading Guidelines, done as a big example!")
));
$proofing->add_entry(new FAQEntry(
    _("Proofreading Quizzes & Tutorials"),
    "$code_url/quiz/start.php?show_only=PQ",
    _("Try the proofreading quizzes and tutorials. They are a great walk through the basic Guidelines for beginners and an excellent refresher for old hands.")
));


// Creating and Managing Projects
$cp_pm = new FAQSection(_("Creating and Managing Projects"));
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
    "formatting_guidelines.php",
    _("The full details of the guidelines we use for formatting documents.")
));
$formatting->add_entry(new FAQEntry(
    _("Formatting Summary"),
    "formatting_summary.pdf",
    _("Printable (.pdf) two-page summary of the most commonly needed formatting standards from the Formatting Guidelines, done as a big example!")
));
$formatting->add_entry(new FAQEntry(
    _("Formatting Quiz"),
    "$code_url/quiz/start.php?show_only=FQ",
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
    sprintf(_("The <i>massive</i> FAQ from <a href='%s'>Project Gutenberg</a>."), $PG_home_url)
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
    public function __construct()
    {
        $this->sections = [];
    }

    public function add_section($section)
    {
        $this->sections[] = $section;
    }

    public function output()
    {
        echo "<table class='faqtable'>";
        // Start with one column
        echo "<tr>";
        echo "<td class='column'>";
        foreach ($this->sections as $section) {
            if ($section == "NEW_COLUMN") {
                echo "</td>";
                echo "<td class='column'>";
                continue;
            }
            echo "\n<h2>" . html_safe($section->title) . "</h2>\n";

            $section->output();
        }
        echo "</td>";
        echo "</tr>";
        echo "</table>";
    }
}

class FAQSection
{
    public function __construct($title)
    {
        $this->title = $title;
        $this->entries = [];
    }

    public function add_entry($entry)
    {
        $this->entries[] = $entry;
    }

    public function output()
    {
        $user_iso = substr(get_desired_language(), 0, 2);
        foreach ($this->entries as $entry) {
            // When we output the entry, link the title to the desired doc
            // in the user's language and include pointers to other language
            // options where available. If the document isn't available in
            // the user's language, don't include a link in the title and
            // instead just show all available language options.

            echo "\n";
            echo "<p>";
            if (isset($entry->urls['all'])) {
                echo "<a href='" . $entry->urls['all'] . "'>" . html_safe($entry->title) . "</a>";
            } elseif (isset($entry->urls[$user_iso])) {
                echo "<a href='" . $entry->urls[$user_iso] . "'>" . html_safe($entry->title) . "</a>";
            } else {
                echo html_safe($entry->title);
            }
            echo "<br>";
            $links = [];
            foreach ($entry->urls as $iso => $url) {
                // If the document is available in the user's langauge it
                // has been used as a link in the title, so don't include
                // it in the list of available languages.
                if ($iso == $user_iso || $iso == 'all') {
                    continue;
                }

                $links[] = "<a href='$url'>" . lang_name($iso) . "</a>";
            }
            if ($links) {
                echo "<span style='font-size: 0.8em;'>";
                // Subtly alter the wording if the document was available in
                // the user's language.
                if (isset($entry->urls[$user_iso])) {
                    // TRANSLATORS: %s is a comma-separated list of language names that link to FAQs in that language
                    echo sprintf(_("Also available in: %s"), implode(",\n ", $links));
                } else {
                    // TRANSLATORS: %s is a comma-separated list of language names that link to FAQs in that language
                    echo sprintf(_("Available in: %s"), implode(",\n ", $links));
                }

                echo "</span>";
                echo "<br>";
            }
            echo $entry->text;
            echo "</p>";
        }
    }
}

class FAQEntry
{
    public function __construct($title, $page, $text)
    {
        $this->title = $title;
        $this->text = $text;

        if (startswith($page, "http")) {
            $this->urls = ["all" => $page];
        } else {
            $this->urls = get_all_urls_for_faq($page);
        }

        // if we got an empty array back, just use whatever we were sent
        if (!$this->urls) {
            $this->urls = ["all" => $page];
        }
    }
}
