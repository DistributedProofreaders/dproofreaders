<?php
$relPath="./pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');

$title = _("Credits");
output_header($title);

echo "<h1>$title</h1>";

echo "<h2>" . _("Developers") . "</h2>";

echo "<p>" . sprintf(_("This software brought to you by many volunteer developers across the world and years. Recent developer contribution after the move to git can be seen on the project's <a href='%s'>Github Contributors page</a>."), "https://github.com/DistributedProofreaders/dproofreaders/graphs/contributors"). "</p>";

echo "<h2>" . _("Open Source"). "</h2>";

echo "<p>" . _("The following open source code is used and bundled with the DP code.") . "</p>";

echo "<ul>";
$dir_iter = new PermissiveRecursiveDirectoryIterator($code_dir);
$files = new RecursiveIteratorIterator($dir_iter);
foreach($files as $file_info)
{
    $file = $file_info->getPathname();
    if(basename($file) != "details.json")
        continue;
    $details = json_decode(file_get_contents($file), TRUE);
    echo "<li>";
    echo "<b>" . $details["name"] . "</b><br>";
    echo "<a href='" . $details["url"] . "'>" . $details["url"] . "</a><br>";
    echo "<a href='" . $details["license_url"] . "'>" . $details["license"] . "</a>";
    echo "</li>";
}
echo "</ul>";

#----------------------------------------------------------------------------

# Ignore exceptions when iterating over the directory, such as permission
# errors from SETUP/
# from antennen at https://www.php.net/manual/en/class.recursivedirectoryiterator.php
class PermissiveRecursiveDirectoryIterator extends RecursiveDirectoryIterator {
    function getChildren() {
        try {
            return new PermissiveRecursiveDirectoryIterator($this->getPathname());
        } catch(UnexpectedValueException $e) {
            return new RecursiveArrayIterator(array());
        }
    }
}
