<?php
// This file deals with language names and codes that are used with projects.
// This includes languages assigned to projects and mapping those languages to
// system dictionaries used with WordCheck. The functions in language.inc
// deal with localizations/translations and system locales.

// Returns an associative array of those languages with a dictionary that is
// installed on the system
//   $array[$langcode]=$language;
function get_languages_with_dictionaries()
{
    global $aspell_prefix;

    $returnArray = [];

    foreach (glob("$aspell_prefix/lib/aspell/*.multi") as $filename) {
        // dictionaries need to map to a two- or three-letter language code
        if (preg_match("#/(\w{2,3})\.multi\$#", $filename, $matches)) {
            $langname = langname_for_langcode($matches[1]);
            if ($langname) {
                $returnArray[$matches[1]] = $langname;
            }
        }
    }

    return $returnArray;
}

// Given a langcode, return the Unicode script that language is written in.
// Will return "Latin" if the langcode is not known.
function get_script_for_langcode($langcode)
{
    // This, decidedly incomplete, mapping was created by taking the list of
    // available aspell 0.60 dictionaries and running the "Native Name" through
    // utf8_string_scripts().
    $langcode_script_map = [
        "am" => "Ethiopic",
        "ar" => "Arabic",
        "be" => "Cyrillic",
        "bg" => "Cyrillic",
        "el" => "Greek",
        "fa" => "Arabic",
        "grc" => "Greek",
        "gu" => "Gujarati",
        "he" => "Hebrew",
        "hi" => "Devanagari",
        "hy" => "Armenian",
        "kn" => "Kannada",
        "ml" => "Malayalam",
        "mn" => "Cyrillic",
        "mr" => "Devanagari",
        "or" => "Oriya",
        "pa" => "Gurmukhi",
        "ru" => "Cyrillic",
        "sr" => "Cyrillic",
        "ta" => "Tamil",
        "te" => "Telugu",
        "uk" => "Cyrillic",
        "uz" => "Cyrillic",
        "yi" => "Hebrew",
    ];

    return $langcode_script_map[$langcode] ?? "Latin";
}

// The 3-letter language *CODES* in the table below are mostly those
// defined in the ISO-639-2 standard, with these exceptions:
//   1. Code "qsr" which is not defined in that standard at all and
//      is used in our convention to denote "Serbian (Latin)"
//      ("qsr" is in the range qaa-qtz reserved for local use in the
//      standard).
//
//   2. Code "pdc" is referenced in some (but not all) web-site versions
//      of ISO-639-3 as representing Pennsylvania Dutch. We will use that
//      for DP.
//
// For those languages having two different 3-letter codes in ISO-639-2
// (a bibliographic code, and a terminology code), both codes are given
// separated with a slash.
//
// As regards language *NAMES*, there are many differences with ISO.
// For instance in case of language variants in the standard, we sometimes
// have duplicate entries, i.e. where ISO says
//     "Adygei; Adyghe"
// we have:
//     array("lang_name" => "Adygei", "lang_code" => "ady"),
//     array("lang_name" => "Adyghe", "lang_code" => "ady"),
// and sometimes we rename the language completely, e.g. when ISO has
//     "Official Aramaic (700-300 BCE); Imperial Aramaic (700-300 BCE)"
// we have the simple
//     array("lang_name" => "Aramaic", "lang_code" => "arc"),
//
// We also have other differences in the language names, e.g. where
// ISO has
//     "Afro-Asiatic languages"
// we have:
//     array("lang_name" => "Afro-Asiatic (Other)", "lang_code" => "afa"),
//
// This is a reason why using the raw ISO entries as a language name
// is not a good idea, since nobody wants a ridiculously long name like
//     "Official Aramaic (700-300 BCE); Imperial Aramaic (700-300 BCE)"
// and why approaches such as the isocodes projects
//     (http://alioth.debian.org/projects/pkg-isocodes/)
// which try to create gettext packages to translate English names into
// foreign names will not work properly. To properly translate these
// language names the sane solution is to use codes instead of names
// and then have a mapping of language codes to language names in the
// desired locale.

function get_iso_language_list()
{
    return [
        ["lang_name" => "English", "lang_code" => "eng"],
        ["lang_name" => "Dutch", "lang_code" => "dut/nld"],
        ["lang_name" => "Esperanto", "lang_code" => "epo"],
        ["lang_name" => "Finnish", "lang_code" => "fin"],
        ["lang_name" => "French", "lang_code" => "fre/fra"],
        ["lang_name" => "German", "lang_code" => "ger/deu"],
        ["lang_name" => "Italian", "lang_code" => "ita"],
        ["lang_name" => "Latin", "lang_code" => "lat"],
        ["lang_name" => "Portuguese", "lang_code" => "por"],
        ["lang_name" => "Spanish", "lang_code" => "spa"],
        ["lang_name" => "Swedish", "lang_code" => "swe"],
        ["lang_name" => "Abkhazian", "lang_code" => "abk"],
        ["lang_name" => "Achinese", "lang_code" => "ace"],
        ["lang_name" => "Acoli", "lang_code" => "ach"],
        ["lang_name" => "Adangme", "lang_code" => "ada"],
        ["lang_name" => "Adygei", "lang_code" => "ady"],
        ["lang_name" => "Adyghe", "lang_code" => "ady"],
        ["lang_name" => "Afar", "lang_code" => "aar"],
        ["lang_name" => "Afrihili", "lang_code" => "afh"],
        ["lang_name" => "Afrikaans", "lang_code" => "afr"],
        ["lang_name" => "Afro-Asiatic (Other)", "lang_code" => "afa"],
        ["lang_name" => "Akan", "lang_code" => "aka"],
        ["lang_name" => "Akkadian", "lang_code" => "akk"],
        ["lang_name" => "Albanian", "lang_code" => "alb/sqi"],
        ["lang_name" => "Aleut", "lang_code" => "ale"],
        ["lang_name" => "Algonquian languages", "lang_code" => "alg"],
        ["lang_name" => "Altaic (Other)", "lang_code" => "tut"],
        ["lang_name" => "Amharic", "lang_code" => "amh"],
        ["lang_name" => "Apache languages", "lang_code" => "apa"],
        ["lang_name" => "Arabic", "lang_code" => "ara"],
        ["lang_name" => "Aragonese", "lang_code" => "arg"],
        ["lang_name" => "Aramaic", "lang_code" => "arc"],
        ["lang_name" => "Arapaho", "lang_code" => "arp"],
        ["lang_name" => "Araucanian", "lang_code" => "arn"],
        ["lang_name" => "Arawak", "lang_code" => "arw"],
        ["lang_name" => "Armenian", "lang_code" => "arm/hye"],
        ["lang_name" => "Artificial (Other)", "lang_code" => "art"],
        ["lang_name" => "Asturian", "lang_code" => "ast"],
        ["lang_name" => "Assamese", "lang_code" => "asm"],
        ["lang_name" => "Athapascan languages", "lang_code" => "ath"],
        ["lang_name" => "Australian languages", "lang_code" => "aus"],
        ["lang_name" => "Austronesian (Other)", "lang_code" => "map"],
        ["lang_name" => "Avaric", "lang_code" => "ava"],
        ["lang_name" => "Avestan", "lang_code" => "ave"],
        ["lang_name" => "Awadhi", "lang_code" => "awa"],
        ["lang_name" => "Aymara", "lang_code" => "aym"],
        ["lang_name" => "Azerbaijani", "lang_code" => "aze"],
        ["lang_name" => "Bable", "lang_code" => "ast"],
        ["lang_name" => "Balinese", "lang_code" => "ban"],
        ["lang_name" => "Baltic (Other)", "lang_code" => "bat"],
        ["lang_name" => "Baluchi", "lang_code" => "bal"],
        ["lang_name" => "Bambara", "lang_code" => "bam"],
        ["lang_name" => "Bamileke languages", "lang_code" => "bai"],
        ["lang_name" => "Banda", "lang_code" => "bad"],
        ["lang_name" => "Bantu (Other)", "lang_code" => "bnt"],
        ["lang_name" => "Basa", "lang_code" => "bas"],
        ["lang_name" => "Bashkir", "lang_code" => "bak"],
        ["lang_name" => "Basque", "lang_code" => "baq/eus"],
        ["lang_name" => "Batak (Indonesia)", "lang_code" => "btk"],
        ["lang_name" => "Beja", "lang_code" => "bej"],
        ["lang_name" => "Belarusian", "lang_code" => "bel"],
        ["lang_name" => "Bemba", "lang_code" => "bem"],
        ["lang_name" => "Bengali", "lang_code" => "ben"],
        ["lang_name" => "Berber (Other)", "lang_code" => "ber"],
        ["lang_name" => "Bhojpuri", "lang_code" => "bho"],
        ["lang_name" => "Bihari", "lang_code" => "bih"],
        ["lang_name" => "Bikol", "lang_code" => "bik"],
        ["lang_name" => "Bini", "lang_code" => "bin"],
        ["lang_name" => "Bislama", "lang_code" => "bis"],
        ["lang_name" => "Blin", "lang_code" => "byn"],
        ["lang_name" => "Bokmål, Norwegian", "lang_code" => "nob"],
        ["lang_name" => "Bosnian", "lang_code" => "bos"],
        ["lang_name" => "Braj", "lang_code" => "bra"],
        ["lang_name" => "Breton", "lang_code" => "bre"],
        ["lang_name" => "Buginese", "lang_code" => "bug"],
        ["lang_name" => "Bulgarian", "lang_code" => "bul"],
        ["lang_name" => "Buriat", "lang_code" => "bua"],
        ["lang_name" => "Burmese", "lang_code" => "bur/mya"],
        ["lang_name" => "Caddo", "lang_code" => "cad"],
        ["lang_name" => "Carib", "lang_code" => "car"],
        ["lang_name" => "Castilian", "lang_code" => "spa"],
        ["lang_name" => "Catalan", "lang_code" => "cat"],
        ["lang_name" => "Caucasian (Other)", "lang_code" => "cau"],
        ["lang_name" => "Cebuano", "lang_code" => "ceb"],
        ["lang_name" => "Celtic (Other)", "lang_code" => "cel"],
        ["lang_name" => "Central American Indian (Other)", "lang_code" => "cai"],
        ["lang_name" => "Chagatai", "lang_code" => "chg"],
        ["lang_name" => "Chamic languages", "lang_code" => "cmc"],
        ["lang_name" => "Chamorro", "lang_code" => "cha"],
        ["lang_name" => "Chechen", "lang_code" => "che"],
        ["lang_name" => "Cherokee", "lang_code" => "chr"],
        ["lang_name" => "Chewa", "lang_code" => "nya"],
        ["lang_name" => "Cheyenne", "lang_code" => "chy"],
        ["lang_name" => "Chibcha", "lang_code" => "chb"],
        ["lang_name" => "Chichewa", "lang_code" => "nya"],
        ["lang_name" => "Chinese", "lang_code" => "chi/zho"],
        ["lang_name" => "Chinook jargon", "lang_code" => "chn"],
        ["lang_name" => "Chipewyan", "lang_code" => "chp"],
        ["lang_name" => "Choctaw", "lang_code" => "cho"],
        ["lang_name" => "Chuang", "lang_code" => "zha"],
        ["lang_name" => "Church Slavic", "lang_code" => "chu"],
        ["lang_name" => "Church Slavonic", "lang_code" => "chu"],
        ["lang_name" => "Chuukese", "lang_code" => "chk"],
        ["lang_name" => "Chuvash", "lang_code" => "chv"],
        ["lang_name" => "Coptic", "lang_code" => "cop"],
        ["lang_name" => "Cornish", "lang_code" => "cor"],
        ["lang_name" => "Corsican", "lang_code" => "cos"],
        ["lang_name" => "Cree", "lang_code" => "cre"],
        ["lang_name" => "Creek", "lang_code" => "mus"],
        ["lang_name" => "Creoles and pidgins (Other)", "lang_code" => "crp"],
        ["lang_name" => "Creoles and pidgins (English)", "lang_code" => "cpe"],
        ["lang_name" => "Creoles and pidgins (French)", "lang_code" => "cpf"],
        ["lang_name" => "Creoles and pidgins (Portuguese)", "lang_code" => "cpp"],
        ["lang_name" => "Crimean Tatar", "lang_code" => "crh"],
        ["lang_name" => "Crimean Turkish", "lang_code" => "crh"],
        ["lang_name" => "Croatian", "lang_code" => "scr/hrv"],
        ["lang_name" => "Cushitic (Other)", "lang_code" => "cus"],
        ["lang_name" => "Czech", "lang_code" => "cze/ces"],
        ["lang_name" => "Dakota", "lang_code" => "dak"],
        ["lang_name" => "Danish", "lang_code" => "dan"],
        ["lang_name" => "Dargwa", "lang_code" => "dar"],
        ["lang_name" => "Dayak", "lang_code" => "day"],
        ["lang_name" => "Delaware", "lang_code" => "del"],
        ["lang_name" => "Dinka", "lang_code" => "din"],
        ["lang_name" => "Divehi", "lang_code" => "div"],
        ["lang_name" => "Dogri", "lang_code" => "doi"],
        ["lang_name" => "Dogrib", "lang_code" => "dgr"],
        ["lang_name" => "Dravidian (Other)", "lang_code" => "dra"],
        ["lang_name" => "Duala", "lang_code" => "dua"],
        ["lang_name" => "Dutch", "lang_code" => "dut/nld"],
        ["lang_name" => "Dutch, Middle (ca. 1050-1350)", "lang_code" => "dum"],
        ["lang_name" => "Dyula", "lang_code" => "dyu"],
        ["lang_name" => "Dzongkha", "lang_code" => "dzo"],
        ["lang_name" => "Efik", "lang_code" => "efi"],
        ["lang_name" => "Egyptian (Ancient)", "lang_code" => "egy"],
        ["lang_name" => "Ekajuk", "lang_code" => "eka"],
        ["lang_name" => "Elamite", "lang_code" => "elx"],
        ["lang_name" => "English", "lang_code" => "eng"],
        ["lang_name" => "English, Middle (1100-1500)", "lang_code" => "enm"],
        ["lang_name" => "English, Old (ca.450-1100)", "lang_code" => "ang"],
        ["lang_name" => "Erzya", "lang_code" => "myv"],
        ["lang_name" => "Esperanto", "lang_code" => "epo"],
        ["lang_name" => "Estonian", "lang_code" => "est"],
        ["lang_name" => "Ewe", "lang_code" => "ewe"],
        ["lang_name" => "Ewondo", "lang_code" => "ewo"],
        ["lang_name" => "Fang", "lang_code" => "fan"],
        ["lang_name" => "Fanti", "lang_code" => "fat"],
        ["lang_name" => "Faroese", "lang_code" => "fao"],
        ["lang_name" => "Fijian", "lang_code" => "fij"],
        ["lang_name" => "Finnish", "lang_code" => "fin"],
        ["lang_name" => "Finno-Ugrian (Other)", "lang_code" => "fiu"],
        ["lang_name" => "Fon", "lang_code" => "fon"],
        ["lang_name" => "French", "lang_code" => "fre/fra"],
        ["lang_name" => "French, Middle (ca.1400-1600)", "lang_code" => "frm"],
        ["lang_name" => "French, Old (842-ca.1400)", "lang_code" => "fro"],
        ["lang_name" => "Frisian", "lang_code" => "fry"],
        ["lang_name" => "Friulian", "lang_code" => "fur"],
        ["lang_name" => "Fulah", "lang_code" => "ful"],
        ["lang_name" => "Ga", "lang_code" => "gaa"],
        ["lang_name" => "Gaelic", "lang_code" => "gla"],
        ["lang_name" => "Galician", "lang_code" => "glg"],
        ["lang_name" => "Ganda", "lang_code" => "lug"],
        ["lang_name" => "Gayo", "lang_code" => "gay"],
        ["lang_name" => "Gbaya", "lang_code" => "gba"],
        ["lang_name" => "Geez", "lang_code" => "gez"],
        ["lang_name" => "Georgian", "lang_code" => "geo/kat"],
        ["lang_name" => "German", "lang_code" => "ger/deu"],
        ["lang_name" => "German, Low", "lang_code" => "nds"],
        ["lang_name" => "German, Middle High (ca.1050-1500)", "lang_code" => "gmh"],
        ["lang_name" => "German, Old High (ca.750-1050)", "lang_code" => "goh"],
        ["lang_name" => "Germanic (Other)", "lang_code" => "gem"],
        ["lang_name" => "Gikuyu", "lang_code" => "kik"],
        ["lang_name" => "Gilbertese", "lang_code" => "gil"],
        ["lang_name" => "Gondi", "lang_code" => "gon"],
        ["lang_name" => "Gorontalo", "lang_code" => "gor"],
        ["lang_name" => "Gothic", "lang_code" => "got"],
        ["lang_name" => "Grebo", "lang_code" => "grb"],
        ["lang_name" => "Greek, Ancient (to 1453)", "lang_code" => "grc"],
        ["lang_name" => "Greek, Modern (1453-)", "lang_code" => "gre/ell"],
        ["lang_name" => "Guarani", "lang_code" => "grn"],
        ["lang_name" => "Gujarati", "lang_code" => "guj"],
        ["lang_name" => "Gwich´in", "lang_code" => "gwi"],
        ["lang_name" => "Haida", "lang_code" => "hai"],
        ["lang_name" => "Haitian", "lang_code" => "hat"],
        ["lang_name" => "Haitian Creole", "lang_code" => "hat"],
        ["lang_name" => "Hausa", "lang_code" => "hau"],
        ["lang_name" => "Hawaiian", "lang_code" => "haw"],
        ["lang_name" => "Hebrew", "lang_code" => "heb"],
        ["lang_name" => "Herero", "lang_code" => "her"],
        ["lang_name" => "Hiligaynon", "lang_code" => "hil"],
        ["lang_name" => "Himachali", "lang_code" => "him"],
        ["lang_name" => "Hindi", "lang_code" => "hin"],
        ["lang_name" => "Hiri Motu", "lang_code" => "hmo"],
        ["lang_name" => "Hittite", "lang_code" => "hit"],
        ["lang_name" => "Hmong", "lang_code" => "hmn"],
        ["lang_name" => "Hungarian", "lang_code" => "hun"],
        ["lang_name" => "Hupa", "lang_code" => "hup"],
        ["lang_name" => "Iban", "lang_code" => "iba"],
        ["lang_name" => "Icelandic", "lang_code" => "ice/isl"],
        ["lang_name" => "Ido", "lang_code" => "ido"],
        ["lang_name" => "Igbo", "lang_code" => "ibo"],
        ["lang_name" => "Ijo", "lang_code" => "ijo"],
        ["lang_name" => "Iloko", "lang_code" => "ilo"],
        ["lang_name" => "Inari Sami", "lang_code" => "smn"],
        ["lang_name" => "Indic (Other)", "lang_code" => "inc"],
        ["lang_name" => "Indo-European (Other)", "lang_code" => "ine"],
        ["lang_name" => "Indonesian", "lang_code" => "ind"],
        ["lang_name" => "Ingush", "lang_code" => "inh"],
        ["lang_name" => "Interlingua", "lang_code" => "ina"],
        ["lang_name" => "Interlingue", "lang_code" => "ile"],
        ["lang_name" => "Inuktitut", "lang_code" => "iku"],
        ["lang_name" => "Inupiaq", "lang_code" => "ipk"],
        ["lang_name" => "Iranian (Other)", "lang_code" => "ira"],
        ["lang_name" => "Irish", "lang_code" => "gle"],
        ["lang_name" => "Irish, Middle (900-1200)", "lang_code" => "mga"],
        ["lang_name" => "Irish, Old (to 900)", "lang_code" => "sga"],
        ["lang_name" => "Iroquoian languages", "lang_code" => "iro"],
        ["lang_name" => "Italian", "lang_code" => "ita"],
        ["lang_name" => "Japanese", "lang_code" => "jpn"],
        ["lang_name" => "Javanese", "lang_code" => "jav"],
        ["lang_name" => "Judeo-Arabic", "lang_code" => "jrb"],
        ["lang_name" => "Judeo-Persian", "lang_code" => "jpr"],
        ["lang_name" => "Kabardian", "lang_code" => "kbd"],
        ["lang_name" => "Kabyle", "lang_code" => "kab"],
        ["lang_name" => "Kachin", "lang_code" => "kac"],
        ["lang_name" => "Kalaallisut", "lang_code" => "kal"],
        ["lang_name" => "Kalmyk", "lang_code" => "xal"],
        ["lang_name" => "Kamba", "lang_code" => "kam"],
        ["lang_name" => "Kannada", "lang_code" => "kan"],
        ["lang_name" => "Kanuri", "lang_code" => "kau"],
        ["lang_name" => "Karachay-Balkar", "lang_code" => "krc"],
        ["lang_name" => "Kara-Kalpak", "lang_code" => "kaa"],
        ["lang_name" => "Karen", "lang_code" => "kar"],
        ["lang_name" => "Kashmiri", "lang_code" => "kas"],
        ["lang_name" => "Kashubian", "lang_code" => "csb"],
        ["lang_name" => "Kawi", "lang_code" => "kaw"],
        ["lang_name" => "Kazakh", "lang_code" => "kaz"],
        ["lang_name" => "Khasi", "lang_code" => "kha"],
        ["lang_name" => "Khmer", "lang_code" => "khm"],
        ["lang_name" => "Khoisan (Other)", "lang_code" => "khi"],
        ["lang_name" => "Khotanese", "lang_code" => "kho"],
        ["lang_name" => "Kikuyu", "lang_code" => "kik"],
        ["lang_name" => "Kimbundu", "lang_code" => "kmb"],
        ["lang_name" => "Kinyarwanda", "lang_code" => "kin"],
        ["lang_name" => "Kirghiz", "lang_code" => "kir"],
        ["lang_name" => "Komi", "lang_code" => "kom"],
        ["lang_name" => "Kongo", "lang_code" => "kon"],
        ["lang_name" => "Konkani", "lang_code" => "kok"],
        ["lang_name" => "Korean", "lang_code" => "kor"],
        ["lang_name" => "Kosraean", "lang_code" => "kos"],
        ["lang_name" => "Kpelle", "lang_code" => "kpe"],
        ["lang_name" => "Kru", "lang_code" => "kro"],
        ["lang_name" => "Kuanyama", "lang_code" => "kua"],
        ["lang_name" => "Kumyk", "lang_code" => "kum"],
        ["lang_name" => "Kurdish", "lang_code" => "kur"],
        ["lang_name" => "Kurukh", "lang_code" => "kru"],
        ["lang_name" => "Kutenai", "lang_code" => "kut"],
        ["lang_name" => "Kwanyama", "lang_code" => "kua"],
        ["lang_name" => "Ladino", "lang_code" => "lad"],
        ["lang_name" => "Lahnda", "lang_code" => "lah"],
        ["lang_name" => "Lamba", "lang_code" => "lam"],
        ["lang_name" => "Lao", "lang_code" => "lao"],
        ["lang_name" => "Latin", "lang_code" => "lat"],
        ["lang_name" => "Latvian", "lang_code" => "lav"],
        ["lang_name" => "Letzeburgesch", "lang_code" => "ltz"],
        ["lang_name" => "Lezghian", "lang_code" => "lez"],
        ["lang_name" => "Limburgish", "lang_code" => "lim"],
        ["lang_name" => "Lingala", "lang_code" => "lin"],
        ["lang_name" => "Lithuanian", "lang_code" => "lit"],
        ["lang_name" => "Lojban", "lang_code" => "jbo"],
        ["lang_name" => "Low German", "lang_code" => "nds"],
        ["lang_name" => "Low Saxon", "lang_code" => "nds"],
        ["lang_name" => "Lower Sorbian", "lang_code" => "dsb"],
        ["lang_name" => "Lozi", "lang_code" => "loz"],
        ["lang_name" => "Luba-Katanga", "lang_code" => "lub"],
        ["lang_name" => "Luba-Lulua", "lang_code" => "lua"],
        ["lang_name" => "Luiseno", "lang_code" => "lui"],
        ["lang_name" => "Lule Sami", "lang_code" => "smj"],
        ["lang_name" => "Lunda", "lang_code" => "lun"],
        ["lang_name" => "Luo (Kenya and Tanzania)", "lang_code" => "luo"],
        ["lang_name" => "Luxembourgish", "lang_code" => "ltz"],
        ["lang_name" => "Lushai", "lang_code" => "lus"],
        ["lang_name" => "Macedonian", "lang_code" => "mac/mkd"],
        ["lang_name" => "Madurese", "lang_code" => "mad"],
        ["lang_name" => "Magahi", "lang_code" => "mag"],
        ["lang_name" => "Maithili", "lang_code" => "mai"],
        ["lang_name" => "Makasar", "lang_code" => "mak"],
        ["lang_name" => "Malagasy", "lang_code" => "mlg"],
        ["lang_name" => "Malay", "lang_code" => "may/msa"],
        ["lang_name" => "Malayalam", "lang_code" => "mal"],
        ["lang_name" => "Maltese", "lang_code" => "mlt"],
        ["lang_name" => "Manchu", "lang_code" => "mnc"],
        ["lang_name" => "Mandar", "lang_code" => "mdr"],
        ["lang_name" => "Mandingo", "lang_code" => "man"],
        ["lang_name" => "Manipuri", "lang_code" => "mni"],
        ["lang_name" => "Manobo languages", "lang_code" => "mno"],
        ["lang_name" => "Manx", "lang_code" => "glv"],
        ["lang_name" => "Maori", "lang_code" => "mao/mri"],
        ["lang_name" => "Marathi", "lang_code" => "mar"],
        ["lang_name" => "Mari", "lang_code" => "chm"],
        ["lang_name" => "Marshallese", "lang_code" => "mah"],
        ["lang_name" => "Marwari", "lang_code" => "mwr"],
        ["lang_name" => "Masai", "lang_code" => "mas"],
        ["lang_name" => "Mayan languages", "lang_code" => "myn"],
        ["lang_name" => "Mende", "lang_code" => "men"],
        ["lang_name" => "Micmac", "lang_code" => "mic"],
        ["lang_name" => "Minangkabau", "lang_code" => "min"],
        ["lang_name" => "Miscellaneous languages", "lang_code" => "mis"],
        ["lang_name" => "Mohawk", "lang_code" => "moh"],
        ["lang_name" => "Moksha", "lang_code" => "mdf"],
        ["lang_name" => "Moldavian", "lang_code" => "mol"],
        ["lang_name" => "Mon-Khmer (Other)", "lang_code" => "mkh"],
        ["lang_name" => "Mongo", "lang_code" => "lol"],
        ["lang_name" => "Mongolian", "lang_code" => "mon"],
        ["lang_name" => "Mossi", "lang_code" => "mos"],
        ["lang_name" => "Multiple languages", "lang_code" => "mul"],
        ["lang_name" => "Munda languages", "lang_code" => "mun"],
        ["lang_name" => "Nahuatl", "lang_code" => "nah"],
        ["lang_name" => "Nauru", "lang_code" => "nau"],
        ["lang_name" => "Navaho", "lang_code" => "nav"],
        ["lang_name" => "Navajo", "lang_code" => "nav"],
        ["lang_name" => "Ndebele, North", "lang_code" => "nde"],
        ["lang_name" => "Ndebele, South", "lang_code" => "nbl"],
        ["lang_name" => "Ndonga", "lang_code" => "ndo"],
        ["lang_name" => "Neapolitan", "lang_code" => "nap"],
        ["lang_name" => "Nepali", "lang_code" => "nep"],
        ["lang_name" => "Newari", "lang_code" => "new"],
        ["lang_name" => "Nias", "lang_code" => "nia"],
        ["lang_name" => "Niger-Kordofanian (Other)", "lang_code" => "nic"],
        ["lang_name" => "Nilo-Saharan (Other)", "lang_code" => "ssa"],
        ["lang_name" => "Niuean", "lang_code" => "niu"],
        ["lang_name" => "Nogai", "lang_code" => "nog"],
        ["lang_name" => "Norse, Old", "lang_code" => "non"],
        ["lang_name" => "North American Indian (Other)", "lang_code" => "nai"],
        ["lang_name" => "Northern Sami", "lang_code" => "sme"],
        ["lang_name" => "North Ndebele", "lang_code" => "nde"],
        ["lang_name" => "Norwegian", "lang_code" => "nor"],
        ["lang_name" => "Norwegian Bokmål", "lang_code" => "nob"],
        ["lang_name" => "Norwegian Nynorsk", "lang_code" => "nno"],
        ["lang_name" => "Nubian languages", "lang_code" => "nub"],
        ["lang_name" => "Nyamwezi", "lang_code" => "nym"],
        ["lang_name" => "Nyanja", "lang_code" => "nya"],
        ["lang_name" => "Nyankole", "lang_code" => "nyn"],
        ["lang_name" => "Nynorsk, Norwegian", "lang_code" => "nno"],
        ["lang_name" => "Nyoro", "lang_code" => "nyo"],
        ["lang_name" => "Nzima", "lang_code" => "nzi"],
        ["lang_name" => "Occitan (post 1500)", "lang_code" => "oci"],
        ["lang_name" => "Ojibwa", "lang_code" => "oji"],
        ["lang_name" => "Old Bulgarian", "lang_code" => "chu"],
        ["lang_name" => "Old Church Slavonic", "lang_code" => "chu"],
        ["lang_name" => "Old Slavonic", "lang_code" => "chu"],
        ["lang_name" => "Oriya", "lang_code" => "ori"],
        ["lang_name" => "Oromo", "lang_code" => "orm"],
        ["lang_name" => "Osage", "lang_code" => "osa"],
        ["lang_name" => "Ossetian", "lang_code" => "oss"],
        ["lang_name" => "Ossetic", "lang_code" => "oss"],
        ["lang_name" => "Otomian languages", "lang_code" => "oto"],
        ["lang_name" => "Pahlavi", "lang_code" => "pal"],
        ["lang_name" => "Palauan", "lang_code" => "pau"],
        ["lang_name" => "Pali", "lang_code" => "pli"],
        ["lang_name" => "Pampanga", "lang_code" => "pam"],
        ["lang_name" => "Pangasinan", "lang_code" => "pag"],
        ["lang_name" => "Panjabi", "lang_code" => "pan"],
        ["lang_name" => "Papiamento", "lang_code" => "pap"],
        ["lang_name" => "Papuan (Other)", "lang_code" => "paa"],
        ["lang_name" => "Pennsylvania Dutch", "lang_code" => "pdc"],
        ["lang_name" => "Persian", "lang_code" => "per/fas"],
        ["lang_name" => "Persian, Old (ca.600-400)", "lang_code" => "peo"],
        ["lang_name" => "Philippine (Other)", "lang_code" => "phi"],
        ["lang_name" => "Phoenician", "lang_code" => "phn"],
        ["lang_name" => "Pohnpeian", "lang_code" => "pon"],
        ["lang_name" => "Polish", "lang_code" => "pol"],
        ["lang_name" => "Portuguese", "lang_code" => "por"],
        ["lang_name" => "Prakrit languages", "lang_code" => "pra"],
        ["lang_name" => "Provençal", "lang_code" => "oci"],
        ["lang_name" => "Provençal, Old (to 1500)", "lang_code" => "pro"],
        ["lang_name" => "Pushto", "lang_code" => "pus"],
        ["lang_name" => "Quechua", "lang_code" => "que"],
        ["lang_name" => "Raeto-Romance", "lang_code" => "roh"],
        ["lang_name" => "Rajasthani", "lang_code" => "raj"],
        ["lang_name" => "Rapanui", "lang_code" => "rap"],
        ["lang_name" => "Rarotongan", "lang_code" => "rar"],
        ["lang_name" => "Reserved for local user", "lang_code" => "qaa-qtz"],
        ["lang_name" => "Romance (Other)", "lang_code" => "roa"],
        ["lang_name" => "Romanian", "lang_code" => "rum/ron"],
        ["lang_name" => "Romany", "lang_code" => "rom"],
        ["lang_name" => "Rundi", "lang_code" => "run"],
        ["lang_name" => "Russian", "lang_code" => "rus"],
        ["lang_name" => "Salishan languages", "lang_code" => "sal"],
        ["lang_name" => "Samaritan Aramaic", "lang_code" => "sam"],
        ["lang_name" => "Sami languages (Other)", "lang_code" => "smi"],
        ["lang_name" => "Samoan", "lang_code" => "smo"],
        ["lang_name" => "Sandawe", "lang_code" => "sad"],
        ["lang_name" => "Sango", "lang_code" => "sag"],
        ["lang_name" => "Sanskrit", "lang_code" => "san"],
        ["lang_name" => "Santali", "lang_code" => "sat"],
        ["lang_name" => "Sardinian", "lang_code" => "srd"],
        ["lang_name" => "Sasak", "lang_code" => "sas"],
        ["lang_name" => "Saxon, Low", "lang_code" => "nds"],
        ["lang_name" => "Scots", "lang_code" => "sco"],
        ["lang_name" => "Scottish Gaelic", "lang_code" => "gla"],
        ["lang_name" => "Selkup", "lang_code" => "sel"],
        ["lang_name" => "Semitic (Other)", "lang_code" => "sem"],
        ["lang_name" => "Serbian", "lang_code" => "scc/srp"],
        ["lang_name" => "Serbian (Latin)", "lang_code" => "qsr"],
        ["lang_name" => "Serer", "lang_code" => "srr"],
        ["lang_name" => "Shan", "lang_code" => "shn"],
        ["lang_name" => "Shona", "lang_code" => "sna"],
        ["lang_name" => "Sichuan Yi", "lang_code" => "iii"],
        ["lang_name" => "Sidamo", "lang_code" => "sid"],
        ["lang_name" => "Sign languages", "lang_code" => "sgn"],
        ["lang_name" => "Siksika", "lang_code" => "bla"],
        ["lang_name" => "Sindhi", "lang_code" => "snd"],
        ["lang_name" => "Sinhalese", "lang_code" => "sin"],
        ["lang_name" => "Sino-Tibetan (Other)", "lang_code" => "sit"],
        ["lang_name" => "Siouan languages", "lang_code" => "sio"],
        ["lang_name" => "Skolt Sami", "lang_code" => "sms"],
        ["lang_name" => "Slave (Athapascan)", "lang_code" => "den"],
        ["lang_name" => "Slavic (Other)", "lang_code" => "sla"],
        ["lang_name" => "Slovak", "lang_code" => "slo/slk"],
        ["lang_name" => "Slovenian", "lang_code" => "slv"],
        ["lang_name" => "Sogdian", "lang_code" => "sog"],
        ["lang_name" => "Somali", "lang_code" => "som"],
        ["lang_name" => "Songhai", "lang_code" => "son"],
        ["lang_name" => "Soninke", "lang_code" => "snk"],
        ["lang_name" => "Sorbian languages", "lang_code" => "wen"],
        ["lang_name" => "Sotho, Northern", "lang_code" => "nso"],
        ["lang_name" => "Sotho, Southern", "lang_code" => "sot"],
        ["lang_name" => "South American Indian (Other)", "lang_code" => "sai"],
        ["lang_name" => "Southern Sami", "lang_code" => "sma"],
        ["lang_name" => "South Ndebele", "lang_code" => "nbl"],
        ["lang_name" => "Spanish", "lang_code" => "spa"],
        ["lang_name" => "Sukuma", "lang_code" => "suk"],
        ["lang_name" => "Sumerian", "lang_code" => "sux"],
        ["lang_name" => "Sundanese", "lang_code" => "sun"],
        ["lang_name" => "Susu", "lang_code" => "sus"],
        ["lang_name" => "Swahili", "lang_code" => "swa"],
        ["lang_name" => "Swati", "lang_code" => "ssw"],
        ["lang_name" => "Swedish", "lang_code" => "swe"],
        ["lang_name" => "Syriac", "lang_code" => "syr"],
        ["lang_name" => "Tagalog", "lang_code" => "tgl"],
        ["lang_name" => "Tahitian", "lang_code" => "tah"],
        ["lang_name" => "Tai (Other)", "lang_code" => "tai"],
        ["lang_name" => "Tajik", "lang_code" => "tgk"],
        ["lang_name" => "Tamashek", "lang_code" => "tmh"],
        ["lang_name" => "Tamil", "lang_code" => "tam"],
        ["lang_name" => "Tatar", "lang_code" => "tat"],
        ["lang_name" => "Telugu", "lang_code" => "tel"],
        ["lang_name" => "Tereno", "lang_code" => "ter"],
        ["lang_name" => "Tetum", "lang_code" => "tet"],
        ["lang_name" => "Thai", "lang_code" => "tha"],
        ["lang_name" => "Tibetan", "lang_code" => "tib/bod"],
        ["lang_name" => "Tigre", "lang_code" => "tig"],
        ["lang_name" => "Tigrinya", "lang_code" => "tir"],
        ["lang_name" => "Timne", "lang_code" => "tem"],
        ["lang_name" => "Tiv", "lang_code" => "tiv"],
        ["lang_name" => "Tlingit", "lang_code" => "tli"],
        ["lang_name" => "Tok Pisin", "lang_code" => "tpi"],
        ["lang_name" => "Tokelau", "lang_code" => "tkl"],
        ["lang_name" => "Tonga (Nyasa)", "lang_code" => "tog"],
        ["lang_name" => "Tonga (Tonga Islands)", "lang_code" => "ton"],
        ["lang_name" => "Tsimshian", "lang_code" => "tsi"],
        ["lang_name" => "Tsonga", "lang_code" => "tso"],
        ["lang_name" => "Tswana", "lang_code" => "tsn"],
        ["lang_name" => "Tumbuka", "lang_code" => "tum"],
        ["lang_name" => "Tupi languages", "lang_code" => "tup"],
        ["lang_name" => "Turkish", "lang_code" => "tur"],
        ["lang_name" => "Turkish, Ottoman", "lang_code" => "ota"],
        ["lang_name" => "Turkmen", "lang_code" => "tuk"],
        ["lang_name" => "Tuvalu", "lang_code" => "tvl"],
        ["lang_name" => "Tuvinian", "lang_code" => "tyv"],
        ["lang_name" => "Twi", "lang_code" => "twi"],
        ["lang_name" => "Udmurt", "lang_code" => "udm"],
        ["lang_name" => "Uighur", "lang_code" => "uig"],
        ["lang_name" => "Ukrainian", "lang_code" => "ukr"],
        ["lang_name" => "Umbundu", "lang_code" => "umb"],
        ["lang_name" => "Undetermined", "lang_code" => "und"],
        ["lang_name" => "Upper Sorbian", "lang_code" => "hsb"],
        ["lang_name" => "Urdu", "lang_code" => "urd"],
        ["lang_name" => "Uzbek", "lang_code" => "uzb"],
        ["lang_name" => "Vai", "lang_code" => "vai"],
        ["lang_name" => "Venda", "lang_code" => "ven"],
        ["lang_name" => "Vietnamese", "lang_code" => "vie"],
        ["lang_name" => "Volapük", "lang_code" => "vol"],
        ["lang_name" => "Votic", "lang_code" => "vot"],
        ["lang_name" => "Wakashan languages", "lang_code" => "wak"],
        ["lang_name" => "Walamo", "lang_code" => "wal"],
        ["lang_name" => "Walloon", "lang_code" => "wln"],
        ["lang_name" => "Waray", "lang_code" => "war"],
        ["lang_name" => "Washo", "lang_code" => "was"],
        ["lang_name" => "Welsh", "lang_code" => "wel/cym"],
        ["lang_name" => "Wolof", "lang_code" => "wol"],
        ["lang_name" => "Xhosa", "lang_code" => "xho"],
        ["lang_name" => "Yakut", "lang_code" => "sah"],
        ["lang_name" => "Yao", "lang_code" => "yao"],
        ["lang_name" => "Yapese", "lang_code" => "yap"],
        ["lang_name" => "Yiddish", "lang_code" => "yid"],
        ["lang_name" => "Yoruba", "lang_code" => "yor"],
        ["lang_name" => "Yupik languages", "lang_code" => "ypk"],
        ["lang_name" => "Zande", "lang_code" => "znd"],
        ["lang_name" => "Zapotec", "lang_code" => "zap"],
        ["lang_name" => "Zenaga", "lang_code" => "zen"],
        ["lang_name" => "Zhuang", "lang_code" => "zha"],
        ["lang_name" => "Zulu", "lang_code" => "zul"],
        ["lang_name" => "Zuni", "lang_code" => "zun"],
    ];
}

function langname_for_langcode3($langcode3)
// For the given three-letter code, return the corresponding language-name,
// or null if not found.
{
    foreach (get_iso_language_list() as $entry) {
        $entry_code = $entry['lang_code'];
        if (substr($entry_code, 0, 3) == $langcode3 || substr($entry_code, 4, 3) == $langcode3) {
            return $entry['lang_name'];
        }
    }
    return null;
}

function langcode3_for_langname($langname)
// For the given language-name, return the corresponding three-letter code,
// or null if not found.
{
    foreach (get_iso_language_list() as $entry) {
        $entry_name = $entry['lang_name'];
        if ($entry_name == $langname) {
            $lang_code = $entry['lang_code'];
            // Note that $lang_code may be a pair of
            // three-letter codes separated by a slash.
            // Not sure if it matters which we return,
            // but for simplicity, we always return the first.
            // I.e., we return the first 3 characters of $lang_code.
            return substr($lang_code, 0, 3);
        }
    }
    return null;
}

function langname_for_langcode2($langcode2)
// Given a two-letter language code, return the language name or NULL
{
    $langcode3 = array_search($langcode2, get_iso_639_3_to_2_mapping());
    if ($langcode3) {
        return langname_for_langcode3($langcode3);
    }
    return null;
}

function langcode2_for_langname($langname)
// Given a language name, return the two-letter code if available. If a two
// letter code isn't available, but a three-letter one is, return that. If
// the language is not found return NULL.
{
    $mapping = get_iso_639_3_to_2_mapping();
    $fallback_code = null;
    foreach (get_iso_language_list() as $entry) {
        $entry_name = $entry['lang_name'];
        if ($entry_name == $langname) {
            $fallback_code = substr($entry['lang_code'], 0, 3);
            foreach (explode("/", $entry['lang_code']) as $lang_code) {
                if (isset($mapping[$lang_code])) {
                    return $mapping[$lang_code];
                }
            }
        }
    }
    return $fallback_code;
}

function langname_for_langcode($langcode)
// Given a two- or three-letter language code return the language name or NULL.
{
    return langname_for_langcode2($langcode) ?? langname_for_langcode3($langcode);
}

// ====================================================================

// This array maps the bibliographical 3-letter codes to 2-letter codes
// for those languages having a 2-letter code in the ISO-639-1 list.
// It is used to map 3-letter codes to IETF-like language tags, since
// these language tags use the 2-letter code when present, or the
// 3-letter bibliographical codes for languages having no 2-letter code.

function get_iso_639_3_to_2_mapping()
{
    return [
        // This part extracted from ISO-639-2 downloaded on 2010-08-18 from
        // http://www.loc.gov/standards/iso639-2/php/code_list.php
        "aar" => "aa",  "abk" => "ab",  "afr" => "af",  "aka" => "ak",
        "alb" => "sq",  "amh" => "am",  "ara" => "ar",  "arg" => "an",
        "arm" => "hy",  "asm" => "as",  "ava" => "av",  "ave" => "ae",
        "aym" => "ay",  "aze" => "az",  "bak" => "ba",  "bam" => "bm",
        "baq" => "eu",  "bel" => "be",  "ben" => "bn",  "bih" => "bh",
        "bis" => "bi",  "bos" => "bs",  "bre" => "br",  "bul" => "bg",
        "bur" => "my",  "cat" => "ca",  "cha" => "ch",  "che" => "ce",
        "chi" => "zh",  "chu" => "cu",  "chv" => "cv",  "cor" => "kw",
        "cos" => "co",  "cre" => "cr",  "cze" => "cs",  "dan" => "da",
        "div" => "dv",  "dut" => "nl",  "dzo" => "dz",  "eng" => "en",
        "epo" => "eo",  "est" => "et",  "ewe" => "ee",  "fao" => "fo",
        "fij" => "fj",  "fin" => "fi",  "fre" => "fr",  "fry" => "fy",
        "ful" => "ff",  "geo" => "ka",  "ger" => "de",  "gla" => "gd",
        "gle" => "ga",  "glg" => "gl",  "glv" => "gv",  "gre" => "el",
        "grn" => "gn",  "guj" => "gu",  "hat" => "ht",  "hau" => "ha",
        "heb" => "he",  "her" => "hz",  "hin" => "hi",  "hmo" => "ho",
        "hun" => "hu",  "ibo" => "ig",  "ice" => "is",  "ido" => "io",
        "iii" => "ii",  "iku" => "iu",  "ile" => "ie",  "ina" => "ia",
        "ind" => "id",  "ipk" => "ik",  "ita" => "it",  "jav" => "jv",
        "jpn" => "ja",  "kal" => "kl",  "kan" => "kn",  "kas" => "ks",
        "kau" => "kr",  "kaz" => "kk",  "khm" => "km",  "kik" => "ki",
        "kin" => "rw",  "kir" => "ky",  "kom" => "kv",  "kon" => "kg",
        "kor" => "ko",  "kua" => "kj",  "kur" => "ku",  "lao" => "lo",
        "lat" => "la",  "lav" => "lv",  "lim" => "li",  "lin" => "ln",
        "lit" => "lt",  "ltz" => "lb",  "lub" => "lu",  "lug" => "lg",
        "mac" => "mk",  "mah" => "mh",  "mal" => "ml",  "mao" => "mi",
        "mar" => "mr",  "may" => "ms",  "mlg" => "mg",  "mlt" => "mt",
        "mol" => "mo",  "mon" => "mn",  "nau" => "na",  "nav" => "nv",
        "nbl" => "nr",  "nde" => "nd",  "ndo" => "ng",  "nep" => "ne",
        "nno" => "nn",  "nob" => "nb",  "nor" => "no",  "nya" => "ny",
        "oci" => "oc",  "oji" => "oj",  "ori" => "or",  "orm" => "om",
        "oss" => "os",  "pan" => "pa",  "per" => "fa",  "pli" => "pi",
        "pol" => "pl",  "por" => "pt",  "pus" => "ps",  "que" => "qu",
        "roh" => "rm",  "rum" => "ro",  "run" => "rn",  "rus" => "ru",
        "sag" => "sg",  "san" => "sa",  "scc" => "sr",  "scr" => "hr",
        "sin" => "si",  "slo" => "sk",  "slv" => "sl",  "sme" => "se",
        "smo" => "sm",  "sna" => "sn",  "snd" => "sd",  "som" => "so",
        "sot" => "st",  "spa" => "es",  "srd" => "sc",  "ssw" => "ss",
        "sun" => "su",  "swa" => "sw",  "swe" => "sv",  "tah" => "ty",
        "tam" => "ta",  "tat" => "tt",  "tel" => "te",  "tgk" => "tg",
        "tgl" => "tl",  "tha" => "th",  "tib" => "bo",  "tir" => "ti",
        "ton" => "to",  "tsn" => "tn",  "tso" => "ts",  "tuk" => "tk",
        "tur" => "tr",  "twi" => "tw",  "uig" => "ug",  "ukr" => "uk",
        "urd" => "ur",  "uzb" => "uz",  "ven" => "ve",  "vie" => "vi",
        "vol" => "vo",  "wel" => "cy",  "wln" => "wa",  "wol" => "wo",
        "xho" => "xh",  "yid" => "yi",  "yor" => "yo",  "zha" => "za",
        "zul" => "zu",

        // This part is further edited to suit our internal conventions.
        // "qsr" is our private code for Serbian with Latin alphabet
        "qsr" => "sr-latn",
        // "pdc" is listed in some versions of ISO-639-3 as being
        // Pennsylvania Dutch
        "pdc" => "pd",
    ];
}
