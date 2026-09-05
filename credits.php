<?php
$relPath = "./pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');

$title = _("Credits");
output_header($title, SHOW_STATSBAR);

echo "<h1>$title</h1>";

echo "<p>" . sprintf(_("This site is powered by the <a href='%s'>dproofreaders</a> source code and licensed under the GPL v2."), "https://github.com/DistributedProofreaders/dproofreaders") . "</p>";

echo "<h2>" . _("Developers") . "</h2>";

echo "<p>" . sprintf(_("This software is brought to you by many volunteer developers across the world over many years. Recent developer contributions after the move to git can be seen on the project's <a href='%s'>Github Contributors page</a>."), "https://github.com/DistributedProofreaders/dproofreaders/graphs/contributors"). "</p>";

echo "<h2>" . _("Bundled Software"). "</h2>";

echo "<p>" . _("The following open source code is bundled with and used by the dproofreaders code.") . "</p>";

$credit_details = load_bundled_credit_details($code_dir);
output_credit_details($credit_details);

echo "<h2>" . _("Dependencies"). "</h2>";

echo "<p>" . _("The following open source dependencies are used by the dproofreaders code.") . "</p>";

class CreditDetails
{
    public function __construct(
        public readonly string $name,
        public readonly string $license,
        public readonly ?string $url,
        public readonly ?string $license_url,
        public readonly ?string $version,
    ) {
    }
}

$credit_details = array_merge(
    load_composer_credit_details(),
    load_npm_credit_details()
);
uksort($credit_details, strcasecmp(...));
output_credit_details($credit_details);

//----------------------------------------------------------------------------

/** @param array<string, CreditDetails> $credit_details */
function output_credit_details(array $credit_details): void
{
    echo "<ul>";
    foreach ($credit_details as $detail) {
        echo "<li>";
        echo "<b>";
        if ($detail->url) {
            echo "<a href='" . $detail->url . "'>" . html_safe($detail->name) . "</a>";
        } else {
            echo html_safe($detail->name);
        }
        // Only show the version if we're in testing mode.
        if (SiteConfig::get()->testing && isset($detail->version)) {
            echo " " . html_safe($detail->version);
        }
        echo "</b><br>";
        if (isset($detail->license_url)) {
            echo _("License") . ": <a href='" . $detail->license_url . "'>" . html_safe($detail->license) . "</a>";
        } else {
            echo _("License") . ": " . html_safe($detail->license);
        }
        echo "</li>";
    }
    echo "</ul>";
}

/** @return array<string, CreditDetails> */
function load_bundled_credit_details(string $code_dir): array
{
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($code_dir),
        RecursiveIteratorIterator::SELF_FIRST,
        RecursiveIteratorIterator::CATCH_GET_CHILD
    );
    $credit_details = [];
    foreach ($files as $file_info) {
        $file = $file_info->getPathname();
        if (basename($file) != "details.json") {
            continue;
        }
        $details = json_decode(file_get_contents($file), true);
        if (isAssoc($details)) {
            $details = [$details];
        }
        foreach ($details as $detail) {
            $credit_details[$detail["name"]] = new CreditDetails(
                name: $detail["name"],
                url: $detail["url"] ?? null,
                license: $detail["license"],
                license_url: $detail["license_url"] ?? null,
                version: $detail["version"] ?? null
            );
        }
    }

    uksort($credit_details, strcasecmp(...));
    return $credit_details;
}

/** @return array<string, CreditDetails> */
function load_composer_credit_details()
{
    global $code_dir;

    $packages = json_decode(file_get_contents("$code_dir/composer.lock"));
    $credit_details = [];
    foreach ($packages->packages as $index => $package) {
        $credit_details[$package->name] = new CreditDetails(
            name: $package->name,
            url: $package->homepage ?? null,
            license: join(", ", $package->license),
            license_url: null,
            version: $package->version ?? null
        );
    }

    return $credit_details;
}

/** @return array<string, CreditDetails> */
function load_npm_credit_details()
{
    global $code_dir;

    $packages = json_decode(file_get_contents("$code_dir/package-lock.json"), true);

    // first just load the immediate set of non-dev dependencies
    $dependencies = array_keys($packages["packages"][""]["dependencies"]);

    // now pull the license details
    $credit_details = [];
    foreach ($packages["packages"] as $name => $package) {
        $short_name = str_replace("node_modules/", "", $name);
        if (in_array($short_name, $dependencies)) {
            $credit_details[$short_name] = new CreditDetails(
                name: $short_name,
                url: null,
                license: $package["license"],
                license_url: null,
                version: $package["version"] ?? null,
            );
        }
    }

    return $credit_details;
}

/**
 * Detect if an array is associative or sequential
 *
 * From https://stackoverflow.com/questions/173400/how-to-check-if-php-array-is-associative-or-sequential
 * @param array<mixed, mixed>|list<mixed> $arr
 */
function isAssoc(array $arr): bool
{
    if ([] === $arr) {
        return false;
    }
    return array_keys($arr) !== range(0, count($arr) - 1);
}
