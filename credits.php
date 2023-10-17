<?php
$relPath = "./pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');

$title = _("Credits");
output_header($title);

echo "<h1>$title</h1>";

echo "<p>" . sprintf(_("This site is powered by the <a href='%s'>dproofreaders</a> source code and licensed under the GPL v2."), "https://github.com/DistributedProofreaders/dproofreaders") . "</p>";

echo "<h2>" . _("Developers") . "</h2>";

echo "<p>" . sprintf(_("This software brought to you by many volunteer developers across the world over many years. Recent developer contributions after the move to git can be seen on the project's <a href='%s'>Github Contributors page</a>."), "https://github.com/DistributedProofreaders/dproofreaders/graphs/contributors"). "</p>";

echo "<h2>" . _("Bundled Software"). "</h2>";

echo "<p>" . _("The following open source code is bundled with and used by the dproofreaders code.") . "</p>";

$credit_details = load_bundled_credit_details($code_dir);
output_credit_details($credit_details);

echo "<h2>" . _("Dependencies"). "</h2>";

echo "<p>" . _("The following open source dependencies are used by the dproofreaders code.") . "</p>";

$credit_details = load_composer_credit_details($code_dir);
output_credit_details($credit_details);

//----------------------------------------------------------------------------

function output_credit_details($credit_details)
{
    echo "<ul>";
    foreach ($credit_details as $detail) {
        echo "<li>";
        echo "<b>";
        if ($detail["url"]) {
            echo "<a href='" . $detail["url"] . "'>" . html_safe($detail["name"]) . "</a>";
        } else {
            echo html_safe($detail["name"]);
        }
        echo "</b><br>";
        if (isset($detail["license_url"])) {
            echo _("License") . ": <a href='" . $detail["license_url"] . "'>" . html_safe($detail["license"]) . "</a>";
        } else {
            echo _("License") . ": " . html_safe($detail["license"]);
        }
        echo "</li>";
    }
    echo "</ul>";
}

function load_bundled_credit_details($code_dir)
{
    $credit_details = [];
    $dir_iter = new PermissiveRecursiveDirectoryIterator($code_dir);
    $files = new RecursiveIteratorIterator($dir_iter);
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
            $credit_details[$detail["name"]] = $detail;
        }
    }

    uksort($credit_details, "strcasecmp");
    return $credit_details;
}

function load_composer_credit_details()
{
    global $code_dir;

    $packages = json_decode(file_get_contents("$code_dir/composer.lock"));
    foreach ($packages->packages as $index => $package) {
        $credit_details[$package->name] = [
            "name" => $package->name,
            "url" => $package->homepage ?? null,
            "license" => join(", ", $package->license),
        ];
    }

    uksort($credit_details, "strcasecmp");
    return $credit_details;
}

/**
 * A permissive recursive directory iterator
 *
 * Ignore exceptions when iterating over the directory, such as permission
 * errors from `SETUP/`.
 *
 * From antennen at https://www.php.net/manual/en/class.recursivedirectoryiterator.php
 */
class PermissiveRecursiveDirectoryIterator extends RecursiveDirectoryIterator
{
    public function getChildren()
    {
        try {
            return new PermissiveRecursiveDirectoryIterator($this->getPathname());
        } catch (UnexpectedValueException $e) {
            return new RecursiveArrayIterator([]);
        }
    }
}

/**
 * Detect if an array is associative or sequential
 *
 * From https://stackoverflow.com/questions/173400/how-to-check-if-php-array-is-associative-or-sequential
 */
function isAssoc(array $arr)
{
    if ([] === $arr) {
        return false;
    }
    return array_keys($arr) !== range(0, count($arr) - 1);
}
