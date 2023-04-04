<?php
// This include file holds functions needed for various language issues.
// Functions defined here are used for interface locales and translations.
// iso_lang_list.inc deals with functions around project languages and
// dictionaries.

include_once($relPath.'lang_data.inc');
include_once($relPath.'iso_lang_list.inc');

function lang_code($language_code)
{
    global $locales;

    // see if there's a preferred variation
    $shortcode = $language_code;
    if (isset($locales[$shortcode]) ||
       isset($locales[$shortcode = substr($shortcode, 0, 5)]) ||
       isset($locales[$shortcode = substr($shortcode, 0, 2)])) {
        return $locales[$shortcode];
    }

    // if not, return one of the ones that's installed
    if (in_array($language_code, get_installed_locale_translations("enabled"))) {
        return $language_code;
    }

    return false;
}

// Return the 2-letter short code for the specified locale specifier.
// If no locale is passed in, the locale of the current user is used.
function short_lang_code($langcode = false)
{
    if ($langcode === false) {
        $langcode = get_desired_language();
    }

    return substr($langcode, 0, 2);
}

// Given a 2-letter language code, return the language name in that
// language.
function lang_name($langcode)
{
    global $lang_name_data;

    if (isset($lang_name_data[$langcode])) {
        return $lang_name_data[$langcode];
    }

    return locale_get_display_language($langcode, $langcode);
}

// Given a 2-letter language code, return the language name in English.
function eng_name($langcode)
{
    global $eng_name_data;

    if (isset($eng_name_data[$langcode])) {
        return $eng_name_data[$langcode];
    }

    return locale_get_display_language($langcode, "en");
}

// Given a 2-letter language code, return a name for that language both
// in English and in the native language.
function bilingual_name($langcode)
{
    $a = eng_name($langcode);
    $b = lang_name($langcode);

    if ($a == $b) {
        return $a;
    } elseif (!$a) {
        return $b;
    } else {
        return trim("$b ($a)");
    }
}

// Return a direction for a given language. Returns either:
// LTR for left-to-right languages
// RTL for right-to-left languages
function lang_direction($langcode = false)
{
    if ($langcode === false) {
        $langcode = get_desired_language();
    }

    switch (substr($langcode, 0, 2)) {
        case "ar":
        case "fa":
        case "he":
        case "ur": //surely more
            return "RTL";
            break;
        default:
            return "LTR";
            break;
    }
}

// Return all installed system locales
function get_installed_system_locales()
{
    global $charset;

    static $system_locales = [];
    if ($system_locales) {
        return $system_locales;
    }

    exec("locale -a", $system_locales);

    // if $charset is UTF-8, exclude all non-UTF-8 locales
    if ($charset == 'UTF-8') {
        $utf8_locales = [];
        foreach ($system_locales as $locale) {
            if (endswith($locale, '.utf8')) {
                $utf8_locales[] = substr($locale, 0, strpos($locale, '.utf8'));
            }
        }
        $system_locales = $utf8_locales;
    }

    return $system_locales;
}

// Get a list of all locale translations.
// $state can be one of:
//   "all" - all installed locale translations
//   "enabled" - locale translation is enabled
//   "disabled" - locale translation is disabled
function get_installed_locale_translations($state = "all")
{
    global $dyn_locales_dir;

    static $translation_cache = null;

    if ($translation_cache !== null) {
        return $translation_cache[$state];
    }

    $translation_cache = [
        "all" => [],
        "enabled" => [],
        "disabled" => [],
    ];

    $files = scandir($dyn_locales_dir);
    if (!$files) {
        return $translation_cache[$state];
    }

    foreach ($files as $file) {
        if (!is_dir("$dyn_locales_dir/$file")) {
            continue;
        }

        if (!in_array(substr($file, 0, 2), array_values(get_iso_639_3_to_2_mapping()))) {
            continue;
        }

        $translation_cache["all"][] = $file;

        if (is_file("$dyn_locales_dir/$file/enabled")) {
            $translation_cache["enabled"][] = $file;
        } else {
            $translation_cache["disabled"][] = $file;
        }
    }

    return $translation_cache[$state];
}

function is_locale_translation_enabled($locale)
{
    global $dyn_locales_dir;

    // Locale 'en_US' is always enabled because that's the default
    // language of all the internal strings. We have to check for
    // it because there is no translation file for it.
    if ($locale == 'en_US') {
        return true;
    }

    $enabled_locales = get_installed_locale_translations("enabled");
    return in_array($locale, $enabled_locales);
}

function get_valid_locale_for_translation($locale)
{
    // Fall back to en_US (English) if locale isn't set
    // or if we don't have a translation for that locale.
    if (!@$locale || !is_locale_translation_enabled($locale)) {
        $locale = "en_US";
    }
    return $locale;
}

function set_locale_translation_enabled($locale, $enable)
{
    global $dyn_locales_dir;

    $translation_base = "$dyn_locales_dir/$locale";
    $enabled_file = "$translation_base/enabled";

    if (!is_dir($translation_base)) {
        die(sprintf(_("Locale translation %s not installed"), $locale));
    }

    if ($enable && !is_file($enabled_file)) {
        touch($enabled_file);
    }

    if (!$enable && is_file($enabled_file)) {
        unlink($enabled_file);
    }
}

function get_locale_translation_selection_options()
// Return an associative array for all translations a user can select
// with keys being the locale and the value being the name of the
// language and the locale.
{
    $translations = get_installed_locale_translations("enabled");

    // push English (en_US) onto the list since that's the native version
    // of all the strings
    array_unshift($translations, "en_US");

    // build a list of option names so we can sort them for display
    $options = [];
    foreach ($translations as $locale) {
        $options[$locale] = bilingual_name(short_lang_code($locale)) . " - $locale";
    }
    asort($options);

    return $options;
}

function get_locale_matching_browser_accept_language($http_accept)
// This code is largely inspired by the excellent write-up here:
// https://www.dyeager.org/2008/10/getting-browser-default-language-php.html
// accordingly:
//########################################################
// Copyright © 2008 Darrin Yeager                        #
// https://www.dyeager.org/                              #
// Licensed under BSD license.                           #
//   https://www.dyeager.org/downloads/license-bsd.txt   #
//########################################################
// On success it returns an installed locale. On failure it returns NULL.
{
    // split the languages into their respective parts
    $browser_accepts = explode(",", $http_accept);

    // pull out q-values
    $browser_langs = [];
    foreach ($browser_accepts as $language) {
        if (preg_match("/(.*);q=([0-1]{0,1}.\d{0,4})/i", $language, $matches)) {
            $browser_langs[$matches[1]] = (float)$matches[2];
        } else {
            $browser_langs[$language] = 1.0;
        }
    }


    // loop through each requested language sorted by highest q-value
    // and see if we have a locale that matches it
    arsort($browser_langs);
    foreach ($browser_langs as $browser_lang_code => $qvalue) {
        $test_locale = massage_browser_lang_code_to_locale($browser_lang_code);
        if (is_locale_translation_enabled($test_locale)) {
            return $test_locale;
        }
    }

    return null;
}

function massage_browser_lang_code_to_locale($browser_lang_code)
// Given a browser's accept-language string, return a locale string
// that most closely matches it.
{
    // system locales are always in the format xx_YY, but browsers can
    // send accepts in many different formats (eg: en, en-us, en-US)
    // so attempt to massage $browser_lang_code into a locale

    if (strlen($browser_lang_code) == 5) {
        $lang_code = strtolower(substr($browser_lang_code, 0, 2));
        $region_code = strtoupper(substr($browser_lang_code, 3, 2));
        return "{$lang_code}_{$region_code}";
    }

    // The following makes the rash, and horribly wrong, assumption that
    // a language's region will match that of the language. This holds true
    // for some common cases (it -> it_IT, br -> br_BR) but fails at one
    // of the most common (en -> en_US, en_CA, en_GB, ?). In fact, it's
    // impossible to derive this and it really requires a human to define
    // what locale should be presented as the default if just a language
    // is supplied. In the meantime, we're just going to special-case
    // en_EN to en_US since that's what the strings are natively :(
    if ($browser_lang_code == "en") {
        return "en_US";
    }

    if (strlen($browser_lang_code) == 2) {
        $lang_code = strtolower($browser_lang_code);
        $region_code = strtoupper($browser_lang_code);
        return "{$lang_code}_{$region_code}";
    }

    // give up
    return $browser_lang_code;
}

function lang_html_header($langcode = false)
{
    if (!$langcode) {
        return '';
    }

    $string = "lang='" . short_lang_code($langcode) . "'";
    if (lang_direction($langcode) == "RTL") {
        $string .= " dir='RTL'";
    }
    return $string;
}
