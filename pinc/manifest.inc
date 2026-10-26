<?php

/** @return array<string, string> */
function get_js_manifest(string $basedir = ""): array
{
    global $code_dir;

    if (!$basedir) {
        $basedir = $code_dir;
    }

    // first, see if the PHP version of our manifest file exists, and if so
    // load it and get the opcache-cached version without hitting the disk
    if (is_file("$basedir/dist/manifest.php")) {
        require_once("$basedir/dist/manifest.php");
        return get_cached_js_manifest(); // @phpstan-ignore function.notFound
    }

    // If there's no JSON manifest, just return an empty manifest
    if (!is_file("$basedir/dist/manifest.json")) {
        return [];
    }

    // Otherwise load and process the JSON version
    if (($contents = file_get_contents("$basedir/dist/manifest.json")) === false) {
        throw new RuntimeException("Failed to read manifest.json");
    }
    $raw_manifest = json_decode($contents, true, flags: JSON_THROW_ON_ERROR);
    // webpack puts "auto" in the pathname, no idea why
    foreach ($raw_manifest as $filename => $path) {
        $manifest[$filename] = str_replace("auto", "dist", $path);
    }
    return $manifest;
}

/** @param array<string, string> $manifest */
function generate_cached_js_manifest(string $basedir, array $manifest): void
{
    if (!$manifest) {
        return;
    }

    if (!is_dir("$basedir/dist")) {
        throw new RuntimeException("Directory '$basedir/dist' does not exist.");
    }

    // We know the manifest is just an array<string, string> so generate a
    // literal array directly
    $lines = [
        "<?php",
        "/** @return array<string, string> */",
        "function get_cached_js_manifest(): array",
        "{",
        "    return [",
    ];
    foreach ($manifest as $filename => $path) {
        $lines[] = "        " . var_export($filename, true) . " => " . var_export($path, true) . ",";
    }
    $lines[] = "    ];";
    $lines[] = "}";

    if (file_put_contents("$basedir/dist/manifest.php", join("\n", $lines)) === false) {
        throw new RuntimeException("Failed to write '$basedir/dist/manifest.php'.");
    }
}
