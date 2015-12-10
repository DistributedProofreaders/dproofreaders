<?php
// This configuration file controls which languages are installed and
// configured for this site.

// Directory under faq/ for files that are translated in their entirety
// instead of via gettext().
// Note that this currently isn't used as-such (each language has their own
// proofreading guidelines for instance) and either needs to be
// implemented or purged.
$lang_dir_data=array(
);

// The URI under $forums_url for a specific language's forum. This will
// be included in the menu bar for users of that language. See theme.inc.
// i.e.: if the forum URL for the French team is:
//     http://www.pgdp.net/phpBB3/viewtopic.php?t=4321
// the array entry would be:
//     "fr" => "viewtopic.php?t=4321",
$lang_forum_data=array(
);

// List of phpBB2 interface languages installed (not needed for phpBB3).
// Note that currently this is only used to set the default forum
// language when a new user is created.
$phpbb2langs=array(
);

// Array mapping language short language codes to locales when guessing
// a user's language based solely on their browser. For instance,
// if you want to use Canadian English as a default over US English,
// use: "en" => "en_CA".
// Note that this is only used when guessing based on browser. Users can
// override this by setting their preferences.
$locales=array(
    "en"=>"en_US",
);

// Language names in the language's native form. This overrides the ones
// set in pinc/iso_639_loc_list.inc. Consult that file for array syntax
// and coding restrictions.
$lang_name_data=array(
);

// Language names in their English form. This overrides the ones
// set in pinc/iso_639_list.inc. Consult that file for array syntax.
$eng_name_data=array(
);

// vim: sw=4 ts=4 expandtab
