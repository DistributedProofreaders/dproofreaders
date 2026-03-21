<?php

/**
 * @return string[]
 */
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

    // if it doesn't exist, see if the JSON version exists and if so load
    // and process it
    if (is_file("$basedir/dist/manifest.json")) {
        $raw_manifest = json_decode(file_get_contents("$basedir/dist/manifest.json"), true) ?? [];
        // webpack puts "auto" in the pathname, no idea why
        foreach ($raw_manifest as $filename => $path) {
            $manifest[$filename] = str_replace("auto", "dist", $path);
        }
        return $manifest;
    }

    // otherwise, just return an empty manifest
    return [];
}

/**
 * @param string[] $manifest
 */
function generate_cached_js_manifest(string $basedir, array $manifest): void
{
    if (!$manifest) {
        return;
    }

    if (!is_dir("$basedir/dist")) {
        throw new RuntimeException("Directory '$basedir/dist' does not exist.");
    }

    $serialized_manifest = serialize($manifest);
    $cached_php_file = <<<EOF
        <?php
        function get_cached_js_manifest(): array
        {
            return unserialize('$serialized_manifest');
        }
        EOF;

    file_put_contents("$basedir/dist/manifest.php", $cached_php_file);
}
