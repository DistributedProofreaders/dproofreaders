<?php
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

global $lang_list;
$lang_list=array(
    array("lang_name" => "English", "lang_code" => "eng"),
    array("lang_name" => "Dutch", "lang_code" => "dut/nld"),
    array("lang_name" => "Esperanto", "lang_code" => "epo"),
    array("lang_name" => "Finnish", "lang_code" => "fin"),
    array("lang_name" => "French", "lang_code" => "fre/fra"),
    array("lang_name" => "German", "lang_code" => "ger/deu"),
    array("lang_name" => "Italian", "lang_code" => "ita"),
    array("lang_name" => "Latin", "lang_code" => "lat"),
    array("lang_name" => "Portuguese", "lang_code" => "por"),
    array("lang_name" => "Spanish", "lang_code" => "spa"),
    array("lang_name" => "Swedish", "lang_code" => "swe"),
    array("lang_name" => "Abkhazian", "lang_code" => "abk"),
    array("lang_name" => "Achinese", "lang_code" => "ace"),
    array("lang_name" => "Acoli", "lang_code" => "ach"),
    array("lang_name" => "Adangme", "lang_code" => "ada"),
    array("lang_name" => "Adygei", "lang_code" => "ady"),
    array("lang_name" => "Adyghe", "lang_code" => "ady"),
    array("lang_name" => "Afar", "lang_code" => "aar"),
    array("lang_name" => "Afrihili", "lang_code" => "afh"),
    array("lang_name" => "Afrikaans", "lang_code" => "afr"),
    array("lang_name" => "Afro-Asiatic (Other)", "lang_code" => "afa"),
    array("lang_name" => "Akan", "lang_code" => "aka"),
    array("lang_name" => "Akkadian", "lang_code" => "akk"),
    array("lang_name" => "Albanian", "lang_code" => "alb/sqi"),
    array("lang_name" => "Aleut", "lang_code" => "ale"),
    array("lang_name" => "Algonquian languages", "lang_code" => "alg"),
    array("lang_name" => "Altaic (Other)", "lang_code" => "tut"),
    array("lang_name" => "Amharic", "lang_code" => "amh"),
    array("lang_name" => "Apache languages", "lang_code" => "apa"),
    array("lang_name" => "Arabic", "lang_code" => "ara"),
    array("lang_name" => "Aragonese", "lang_code" => "arg"),
    array("lang_name" => "Aramaic", "lang_code" => "arc"),
    array("lang_name" => "Arapaho", "lang_code" => "arp"),
    array("lang_name" => "Araucanian", "lang_code" => "arn"),
    array("lang_name" => "Arawak", "lang_code" => "arw"),
    array("lang_name" => "Armenian", "lang_code" => "arm/hye"),
    array("lang_name" => "Artificial (Other)", "lang_code" => "art"),
    array("lang_name" => "Asturian", "lang_code" => "ast"),
    array("lang_name" => "Assamese", "lang_code" => "asm"),
    array("lang_name" => "Athapascan languages", "lang_code" => "ath"),
    array("lang_name" => "Australian languages", "lang_code" => "aus"),
    array("lang_name" => "Austronesian (Other)", "lang_code" => "map"),
    array("lang_name" => "Avaric", "lang_code" => "ava"),
    array("lang_name" => "Avestan", "lang_code" => "ave"),
    array("lang_name" => "Awadhi", "lang_code" => "awa"),
    array("lang_name" => "Aymara", "lang_code" => "aym"),
    array("lang_name" => "Azerbaijani", "lang_code" => "aze"),
    array("lang_name" => "Bable", "lang_code" => "ast"),
    array("lang_name" => "Balinese", "lang_code" => "ban"),
    array("lang_name" => "Baltic (Other)", "lang_code" => "bat"),
    array("lang_name" => "Baluchi", "lang_code" => "bal"),
    array("lang_name" => "Bambara", "lang_code" => "bam"),
    array("lang_name" => "Bamileke languages", "lang_code" => "bai"),
    array("lang_name" => "Banda", "lang_code" => "bad"),
    array("lang_name" => "Bantu (Other)", "lang_code" => "bnt"),
    array("lang_name" => "Basa", "lang_code" => "bas"),
    array("lang_name" => "Bashkir", "lang_code" => "bak"),
    array("lang_name" => "Basque", "lang_code" => "baq/eus"),
    array("lang_name" => "Batak (Indonesia)", "lang_code" => "btk"),
    array("lang_name" => "Beja", "lang_code" => "bej"),
    array("lang_name" => "Belarusian", "lang_code" => "bel"),
    array("lang_name" => "Bemba", "lang_code" => "bem"),
    array("lang_name" => "Bengali", "lang_code" => "ben"),
    array("lang_name" => "Berber (Other)", "lang_code" => "ber"),
    array("lang_name" => "Bhojpuri", "lang_code" => "bho"),
    array("lang_name" => "Bihari", "lang_code" => "bih"),
    array("lang_name" => "Bikol", "lang_code" => "bik"),
    array("lang_name" => "Bini", "lang_code" => "bin"),
    array("lang_name" => "Bislama", "lang_code" => "bis"),
    array("lang_name" => "Blin", "lang_code" => "byn"),
    array("lang_name" => "Bokmål, Norwegian", "lang_code" => "nob"),
    array("lang_name" => "Bosnian", "lang_code" => "bos"),
    array("lang_name" => "Braj", "lang_code" => "bra"),
    array("lang_name" => "Breton", "lang_code" => "bre"),
    array("lang_name" => "Buginese", "lang_code" => "bug"),
    array("lang_name" => "Bulgarian", "lang_code" => "bul"),
    array("lang_name" => "Buriat", "lang_code" => "bua"),
    array("lang_name" => "Burmese", "lang_code" => "bur/mya"),
    array("lang_name" => "Caddo", "lang_code" => "cad"),
    array("lang_name" => "Carib", "lang_code" => "car"),
    array("lang_name" => "Castilian", "lang_code" => "spa"),
    array("lang_name" => "Catalan", "lang_code" => "cat"),
    array("lang_name" => "Caucasian (Other)", "lang_code" => "cau"),
    array("lang_name" => "Cebuano", "lang_code" => "ceb"),
    array("lang_name" => "Celtic (Other)", "lang_code" => "cel"),
    array("lang_name" => "Central American Indian (Other)", "lang_code" => "cai"),
    array("lang_name" => "Chagatai", "lang_code" => "chg"),
    array("lang_name" => "Chamic languages", "lang_code" => "cmc"),
    array("lang_name" => "Chamorro", "lang_code" => "cha"),
    array("lang_name" => "Chechen", "lang_code" => "che"),
    array("lang_name" => "Cherokee", "lang_code" => "chr"),
    array("lang_name" => "Chewa", "lang_code" => "nya"),
    array("lang_name" => "Cheyenne", "lang_code" => "chy"),
    array("lang_name" => "Chibcha", "lang_code" => "chb"),
    array("lang_name" => "Chichewa", "lang_code" => "nya"),
    array("lang_name" => "Chinese", "lang_code" => "chi/zho"),
    array("lang_name" => "Chinook jargon", "lang_code" => "chn"),
    array("lang_name" => "Chipewyan", "lang_code" => "chp"),
    array("lang_name" => "Choctaw", "lang_code" => "cho"),
    array("lang_name" => "Chuang", "lang_code" => "zha"),
    array("lang_name" => "Church Slavic", "lang_code" => "chu"),
    array("lang_name" => "Church Slavonic", "lang_code" => "chu"),
    array("lang_name" => "Chuukese", "lang_code" => "chk"),
    array("lang_name" => "Chuvash", "lang_code" => "chv"),
    array("lang_name" => "Coptic", "lang_code" => "cop"),
    array("lang_name" => "Cornish", "lang_code" => "cor"),
    array("lang_name" => "Corsican", "lang_code" => "cos"),
    array("lang_name" => "Cree", "lang_code" => "cre"),
    array("lang_name" => "Creek", "lang_code" => "mus"),
    array("lang_name" => "Creoles and pidgins (Other)", "lang_code" => "crp"),
    array("lang_name" => "Creoles and pidgins (English)", "lang_code" => "cpe"),
    array("lang_name" => "Creoles and pidgins (French)", "lang_code" => "cpf"),
    array("lang_name" => "Creoles and pidgins (Portuguese)", "lang_code" => "cpp"),
    array("lang_name" => "Crimean Tatar", "lang_code" => "crh"),
    array("lang_name" => "Crimean Turkish", "lang_code" => "crh"),
    array("lang_name" => "Croatian", "lang_code" => "scr/hrv"),
    array("lang_name" => "Cushitic (Other)", "lang_code" => "cus"),
    array("lang_name" => "Czech", "lang_code" => "cze/ces"),
    array("lang_name" => "Dakota", "lang_code" => "dak"),
    array("lang_name" => "Danish", "lang_code" => "dan"),
    array("lang_name" => "Dargwa", "lang_code" => "dar"),
    array("lang_name" => "Dayak", "lang_code" => "day"),
    array("lang_name" => "Delaware", "lang_code" => "del"),
    array("lang_name" => "Dinka", "lang_code" => "din"),
    array("lang_name" => "Divehi", "lang_code" => "div"),
    array("lang_name" => "Dogri", "lang_code" => "doi"),
    array("lang_name" => "Dogrib", "lang_code" => "dgr"),
    array("lang_name" => "Dravidian (Other)", "lang_code" => "dra"),
    array("lang_name" => "Duala", "lang_code" => "dua"),
    array("lang_name" => "Dutch", "lang_code" => "dut/nld"),
    array("lang_name" => "Dutch, Middle (ca. 1050-1350)", "lang_code" => "dum"),
    array("lang_name" => "Dyula", "lang_code" => "dyu"),
    array("lang_name" => "Dzongkha", "lang_code" => "dzo"),
    array("lang_name" => "Efik", "lang_code" => "efi"),
    array("lang_name" => "Egyptian (Ancient)", "lang_code" => "egy"),
    array("lang_name" => "Ekajuk", "lang_code" => "eka"),
    array("lang_name" => "Elamite", "lang_code" => "elx"),
    array("lang_name" => "English", "lang_code" => "eng"),
    array("lang_name" => "English, Middle (1100-1500)", "lang_code" => "enm"),
    array("lang_name" => "English, Old (ca.450-1100)", "lang_code" => "ang"),
    array("lang_name" => "Erzya", "lang_code" => "myv"),
    array("lang_name" => "Esperanto", "lang_code" => "epo"),
    array("lang_name" => "Estonian", "lang_code" => "est"),
    array("lang_name" => "Ewe", "lang_code" => "ewe"),
    array("lang_name" => "Ewondo", "lang_code" => "ewo"),
    array("lang_name" => "Fang", "lang_code" => "fan"),
    array("lang_name" => "Fanti", "lang_code" => "fat"),
    array("lang_name" => "Faroese", "lang_code" => "fao"),
    array("lang_name" => "Fijian", "lang_code" => "fij"),
    array("lang_name" => "Finnish", "lang_code" => "fin"),
    array("lang_name" => "Finno-Ugrian (Other)", "lang_code" => "fiu"),
    array("lang_name" => "Fon", "lang_code" => "fon"),
    array("lang_name" => "French", "lang_code" => "fre/fra"),
    array("lang_name" => "French, Middle (ca.1400-1600)", "lang_code" => "frm"),
    array("lang_name" => "French, Old (842-ca.1400)", "lang_code" => "fro"),
    array("lang_name" => "Frisian", "lang_code" => "fry"),
    array("lang_name" => "Friulian", "lang_code" => "fur"),
    array("lang_name" => "Fulah", "lang_code" => "ful"),
    array("lang_name" => "Ga", "lang_code" => "gaa"),
    array("lang_name" => "Gaelic", "lang_code" => "gla"),
    array("lang_name" => "Galician", "lang_code" => "glg"),
    array("lang_name" => "Ganda", "lang_code" => "lug"),
    array("lang_name" => "Gayo", "lang_code" => "gay"),
    array("lang_name" => "Gbaya", "lang_code" => "gba"),
    array("lang_name" => "Geez", "lang_code" => "gez"),
    array("lang_name" => "Georgian", "lang_code" => "geo/kat"),
    array("lang_name" => "German", "lang_code" => "ger/deu"),
    array("lang_name" => "German, Low", "lang_code" => "nds"),
    array("lang_name" => "German, Middle High (ca.1050-1500)", "lang_code" => "gmh"),
    array("lang_name" => "German, Old High (ca.750-1050)", "lang_code" => "goh"),
    array("lang_name" => "Germanic (Other)", "lang_code" => "gem"),
    array("lang_name" => "Gikuyu", "lang_code" => "kik"),
    array("lang_name" => "Gilbertese", "lang_code" => "gil"),
    array("lang_name" => "Gondi", "lang_code" => "gon"),
    array("lang_name" => "Gorontalo", "lang_code" => "gor"),
    array("lang_name" => "Gothic", "lang_code" => "got"),
    array("lang_name" => "Grebo", "lang_code" => "grb"),
    array("lang_name" => "Greek, Ancient (to 1453)", "lang_code" => "grc"),
    array("lang_name" => "Greek, Modern (1453-)", "lang_code" => "gre/ell"),
    array("lang_name" => "Guarani", "lang_code" => "grn"),
    array("lang_name" => "Gujarati", "lang_code" => "guj"),
    array("lang_name" => "Gwich´in", "lang_code" => "gwi"),
    array("lang_name" => "Haida", "lang_code" => "hai"),
    array("lang_name" => "Haitian", "lang_code" => "hat"),
    array("lang_name" => "Haitian Creole", "lang_code" => "hat"),
    array("lang_name" => "Hausa", "lang_code" => "hau"),
    array("lang_name" => "Hawaiian", "lang_code" => "haw"),
    array("lang_name" => "Hebrew", "lang_code" => "heb"),
    array("lang_name" => "Herero", "lang_code" => "her"),
    array("lang_name" => "Hiligaynon", "lang_code" => "hil"),
    array("lang_name" => "Himachali", "lang_code" => "him"),
    array("lang_name" => "Hindi", "lang_code" => "hin"),
    array("lang_name" => "Hiri Motu", "lang_code" => "hmo"),
    array("lang_name" => "Hittite", "lang_code" => "hit"),
    array("lang_name" => "Hmong", "lang_code" => "hmn"),
    array("lang_name" => "Hungarian", "lang_code" => "hun"),
    array("lang_name" => "Hupa", "lang_code" => "hup"),
    array("lang_name" => "Iban", "lang_code" => "iba"),
    array("lang_name" => "Icelandic", "lang_code" => "ice/isl"),
    array("lang_name" => "Ido", "lang_code" => "ido"),
    array("lang_name" => "Igbo", "lang_code" => "ibo"),
    array("lang_name" => "Ijo", "lang_code" => "ijo"),
    array("lang_name" => "Iloko", "lang_code" => "ilo"),
    array("lang_name" => "Inari Sami", "lang_code" => "smn"),
    array("lang_name" => "Indic (Other)", "lang_code" => "inc"),
    array("lang_name" => "Indo-European (Other)", "lang_code" => "ine"),
    array("lang_name" => "Indonesian", "lang_code" => "ind"),
    array("lang_name" => "Ingush", "lang_code" => "inh"),
    array("lang_name" => "Interlingua", "lang_code" => "ina"),
    array("lang_name" => "Interlingue", "lang_code" => "ile"),
    array("lang_name" => "Inuktitut", "lang_code" => "iku"),
    array("lang_name" => "Inupiaq", "lang_code" => "ipk"),
    array("lang_name" => "Iranian (Other)", "lang_code" => "ira"),
    array("lang_name" => "Irish", "lang_code" => "gle"),
    array("lang_name" => "Irish, Middle (900-1200)", "lang_code" => "mga"),
    array("lang_name" => "Irish, Old (to 900)", "lang_code" => "sga"),
    array("lang_name" => "Iroquoian languages", "lang_code" => "iro"),
    array("lang_name" => "Italian", "lang_code" => "ita"),
    array("lang_name" => "Japanese", "lang_code" => "jpn"),
    array("lang_name" => "Javanese", "lang_code" => "jav"),
    array("lang_name" => "Judeo-Arabic", "lang_code" => "jrb"),
    array("lang_name" => "Judeo-Persian", "lang_code" => "jpr"),
    array("lang_name" => "Kabardian", "lang_code" => "kbd"),
    array("lang_name" => "Kabyle", "lang_code" => "kab"),
    array("lang_name" => "Kachin", "lang_code" => "kac"),
    array("lang_name" => "Kalaallisut", "lang_code" => "kal"),
    array("lang_name" => "Kalmyk", "lang_code" => "xal"),
    array("lang_name" => "Kamba", "lang_code" => "kam"),
    array("lang_name" => "Kannada", "lang_code" => "kan"),
    array("lang_name" => "Kanuri", "lang_code" => "kau"),
    array("lang_name" => "Karachay-Balkar", "lang_code" => "krc"),
    array("lang_name" => "Kara-Kalpak", "lang_code" => "kaa"),
    array("lang_name" => "Karen", "lang_code" => "kar"),
    array("lang_name" => "Kashmiri", "lang_code" => "kas"),
    array("lang_name" => "Kashubian", "lang_code" => "csb"),
    array("lang_name" => "Kawi", "lang_code" => "kaw"),
    array("lang_name" => "Kazakh", "lang_code" => "kaz"),
    array("lang_name" => "Khasi", "lang_code" => "kha"),
    array("lang_name" => "Khmer", "lang_code" => "khm"),
    array("lang_name" => "Khoisan (Other)", "lang_code" => "khi"),
    array("lang_name" => "Khotanese", "lang_code" => "kho"),
    array("lang_name" => "Kikuyu", "lang_code" => "kik"),
    array("lang_name" => "Kimbundu", "lang_code" => "kmb"),
    array("lang_name" => "Kinyarwanda", "lang_code" => "kin"),
    array("lang_name" => "Kirghiz", "lang_code" => "kir"),
    array("lang_name" => "Komi", "lang_code" => "kom"),
    array("lang_name" => "Kongo", "lang_code" => "kon"),
    array("lang_name" => "Konkani", "lang_code" => "kok"),
    array("lang_name" => "Korean", "lang_code" => "kor"),
    array("lang_name" => "Kosraean", "lang_code" => "kos"),
    array("lang_name" => "Kpelle", "lang_code" => "kpe"),
    array("lang_name" => "Kru", "lang_code" => "kro"),
    array("lang_name" => "Kuanyama", "lang_code" => "kua"),
    array("lang_name" => "Kumyk", "lang_code" => "kum"),
    array("lang_name" => "Kurdish", "lang_code" => "kur"),
    array("lang_name" => "Kurukh", "lang_code" => "kru"),
    array("lang_name" => "Kutenai", "lang_code" => "kut"),
    array("lang_name" => "Kwanyama", "lang_code" => "kua"),
    array("lang_name" => "Ladino", "lang_code" => "lad"),
    array("lang_name" => "Lahnda", "lang_code" => "lah"),
    array("lang_name" => "Lamba", "lang_code" => "lam"),
    array("lang_name" => "Lao", "lang_code" => "lao"),
    array("lang_name" => "Latin", "lang_code" => "lat"),
    array("lang_name" => "Latvian", "lang_code" => "lav"),
    array("lang_name" => "Letzeburgesch", "lang_code" => "ltz"),
    array("lang_name" => "Lezghian", "lang_code" => "lez"),
    array("lang_name" => "Limburgish", "lang_code" => "lim"),
    array("lang_name" => "Lingala", "lang_code" => "lin"),
    array("lang_name" => "Lithuanian", "lang_code" => "lit"),
    array("lang_name" => "Lojban", "lang_code" => "jbo"),
    array("lang_name" => "Low German", "lang_code" => "nds"),
    array("lang_name" => "Low Saxon", "lang_code" => "nds"),
    array("lang_name" => "Lower Sorbian", "lang_code" => "dsb"),
    array("lang_name" => "Lozi", "lang_code" => "loz"),
    array("lang_name" => "Luba-Katanga", "lang_code" => "lub"),
    array("lang_name" => "Luba-Lulua", "lang_code" => "lua"),
    array("lang_name" => "Luiseno", "lang_code" => "lui"),
    array("lang_name" => "Lule Sami", "lang_code" => "smj"),
    array("lang_name" => "Lunda", "lang_code" => "lun"),
    array("lang_name" => "Luo (Kenya and Tanzania)", "lang_code" => "luo"),
    array("lang_name" => "Luxembourgish", "lang_code" => "ltz"),
    array("lang_name" => "Lushai", "lang_code" => "lus"),
    array("lang_name" => "Macedonian", "lang_code" => "mac/mkd"),
    array("lang_name" => "Madurese", "lang_code" => "mad"),
    array("lang_name" => "Magahi", "lang_code" => "mag"),
    array("lang_name" => "Maithili", "lang_code" => "mai"),
    array("lang_name" => "Makasar", "lang_code" => "mak"),
    array("lang_name" => "Malagasy", "lang_code" => "mlg"),
    array("lang_name" => "Malay", "lang_code" => "may/msa"),
    array("lang_name" => "Malayalam", "lang_code" => "mal"),
    array("lang_name" => "Maltese", "lang_code" => "mlt"),
    array("lang_name" => "Manchu", "lang_code" => "mnc"),
    array("lang_name" => "Mandar", "lang_code" => "mdr"),
    array("lang_name" => "Mandingo", "lang_code" => "man"),
    array("lang_name" => "Manipuri", "lang_code" => "mni"),
    array("lang_name" => "Manobo languages", "lang_code" => "mno"),
    array("lang_name" => "Manx", "lang_code" => "glv"),
    array("lang_name" => "Maori", "lang_code" => "mao/mri"),
    array("lang_name" => "Marathi", "lang_code" => "mar"),
    array("lang_name" => "Mari", "lang_code" => "chm"),
    array("lang_name" => "Marshallese", "lang_code" => "mah"),
    array("lang_name" => "Marwari", "lang_code" => "mwr"),
    array("lang_name" => "Masai", "lang_code" => "mas"),
    array("lang_name" => "Mayan languages", "lang_code" => "myn"),
    array("lang_name" => "Mende", "lang_code" => "men"),
    array("lang_name" => "Micmac", "lang_code" => "mic"),
    array("lang_name" => "Minangkabau", "lang_code" => "min"),
    array("lang_name" => "Miscellaneous languages", "lang_code" => "mis"),
    array("lang_name" => "Mohawk", "lang_code" => "moh"),
    array("lang_name" => "Moksha", "lang_code" => "mdf"),
    array("lang_name" => "Moldavian", "lang_code" => "mol"),
    array("lang_name" => "Mon-Khmer (Other)", "lang_code" => "mkh"),
    array("lang_name" => "Mongo", "lang_code" => "lol"),
    array("lang_name" => "Mongolian", "lang_code" => "mon"),
    array("lang_name" => "Mossi", "lang_code" => "mos"),
    array("lang_name" => "Multiple languages", "lang_code" => "mul"),
    array("lang_name" => "Munda languages", "lang_code" => "mun"),
    array("lang_name" => "Nahuatl", "lang_code" => "nah"),
    array("lang_name" => "Nauru", "lang_code" => "nau"),
    array("lang_name" => "Navaho", "lang_code" => "nav"),
    array("lang_name" => "Navajo", "lang_code" => "nav"),
    array("lang_name" => "Ndebele, North", "lang_code" => "nde"),
    array("lang_name" => "Ndebele, South", "lang_code" => "nbl"),
    array("lang_name" => "Ndonga", "lang_code" => "ndo"),
    array("lang_name" => "Neapolitan", "lang_code" => "nap"),
    array("lang_name" => "Nepali", "lang_code" => "nep"),
    array("lang_name" => "Newari", "lang_code" => "new"),
    array("lang_name" => "Nias", "lang_code" => "nia"),
    array("lang_name" => "Niger-Kordofanian (Other)", "lang_code" => "nic"),
    array("lang_name" => "Nilo-Saharan (Other)", "lang_code" => "ssa"),
    array("lang_name" => "Niuean", "lang_code" => "niu"),
    array("lang_name" => "Nogai", "lang_code" => "nog"),
    array("lang_name" => "Norse, Old", "lang_code" => "non"),
    array("lang_name" => "North American Indian (Other)", "lang_code" => "nai"),
    array("lang_name" => "Northern Sami", "lang_code" => "sme"),
    array("lang_name" => "North Ndebele", "lang_code" => "nde"),
    array("lang_name" => "Norwegian", "lang_code" => "nor"),
    array("lang_name" => "Norwegian Bokmål", "lang_code" => "nob"),
    array("lang_name" => "Norwegian Nynorsk", "lang_code" => "nno"),
    array("lang_name" => "Nubian languages", "lang_code" => "nub"),
    array("lang_name" => "Nyamwezi", "lang_code" => "nym"),
    array("lang_name" => "Nyanja", "lang_code" => "nya"),
    array("lang_name" => "Nyankole", "lang_code" => "nyn"),
    array("lang_name" => "Nynorsk, Norwegian", "lang_code" => "nno"),
    array("lang_name" => "Nyoro", "lang_code" => "nyo"),
    array("lang_name" => "Nzima", "lang_code" => "nzi"),
    array("lang_name" => "Occitan (post 1500)", "lang_code" => "oci"),
    array("lang_name" => "Ojibwa", "lang_code" => "oji"),
    array("lang_name" => "Old Bulgarian", "lang_code" => "chu"),
    array("lang_name" => "Old Church Slavonic", "lang_code" => "chu"),
    array("lang_name" => "Old Slavonic", "lang_code" => "chu"),
    array("lang_name" => "Oriya", "lang_code" => "ori"),
    array("lang_name" => "Oromo", "lang_code" => "orm"),
    array("lang_name" => "Osage", "lang_code" => "osa"),
    array("lang_name" => "Ossetian", "lang_code" => "oss"),
    array("lang_name" => "Ossetic", "lang_code" => "oss"),
    array("lang_name" => "Otomian languages", "lang_code" => "oto"),
    array("lang_name" => "Pahlavi", "lang_code" => "pal"),
    array("lang_name" => "Palauan", "lang_code" => "pau"),
    array("lang_name" => "Pali", "lang_code" => "pli"),
    array("lang_name" => "Pampanga", "lang_code" => "pam"),
    array("lang_name" => "Pangasinan", "lang_code" => "pag"),
    array("lang_name" => "Panjabi", "lang_code" => "pan"),
    array("lang_name" => "Papiamento", "lang_code" => "pap"),
    array("lang_name" => "Papuan (Other)", "lang_code" => "paa"),
    array("lang_name" => "Pennsylvania Dutch", "lang_code" => "pdc"),
    array("lang_name" => "Persian", "lang_code" => "per/fas"),
    array("lang_name" => "Persian, Old (ca.600-400)", "lang_code" => "peo"),
    array("lang_name" => "Philippine (Other)", "lang_code" => "phi"),
    array("lang_name" => "Phoenician", "lang_code" => "phn"),
    array("lang_name" => "Pohnpeian", "lang_code" => "pon"),
    array("lang_name" => "Polish", "lang_code" => "pol"),
    array("lang_name" => "Portuguese", "lang_code" => "por"),
    array("lang_name" => "Prakrit languages", "lang_code" => "pra"),
    array("lang_name" => "Provençal", "lang_code" => "oci"),
    array("lang_name" => "Provençal, Old (to 1500)", "lang_code" => "pro"),
    array("lang_name" => "Pushto", "lang_code" => "pus"),
    array("lang_name" => "Quechua", "lang_code" => "que"),
    array("lang_name" => "Raeto-Romance", "lang_code" => "roh"),
    array("lang_name" => "Rajasthani", "lang_code" => "raj"),
    array("lang_name" => "Rapanui", "lang_code" => "rap"),
    array("lang_name" => "Rarotongan", "lang_code" => "rar"),
    array("lang_name" => "Reserved for local user", "lang_code" => "qaa-qtz"),
    array("lang_name" => "Romance (Other)", "lang_code" => "roa"),
    array("lang_name" => "Romanian", "lang_code" => "rum/ron"),
    array("lang_name" => "Romany", "lang_code" => "rom"),
    array("lang_name" => "Rundi", "lang_code" => "run"),
    array("lang_name" => "Russian", "lang_code" => "rus"),
    array("lang_name" => "Salishan languages", "lang_code" => "sal"),
    array("lang_name" => "Samaritan Aramaic", "lang_code" => "sam"),
    array("lang_name" => "Sami languages (Other)", "lang_code" => "smi"),
    array("lang_name" => "Samoan", "lang_code" => "smo"),
    array("lang_name" => "Sandawe", "lang_code" => "sad"),
    array("lang_name" => "Sango", "lang_code" => "sag"),
    array("lang_name" => "Sanskrit", "lang_code" => "san"),
    array("lang_name" => "Santali", "lang_code" => "sat"),
    array("lang_name" => "Sardinian", "lang_code" => "srd"),
    array("lang_name" => "Sasak", "lang_code" => "sas"),
    array("lang_name" => "Saxon, Low", "lang_code" => "nds"),
    array("lang_name" => "Scots", "lang_code" => "sco"),
    array("lang_name" => "Scottish Gaelic", "lang_code" => "gla"),
    array("lang_name" => "Selkup", "lang_code" => "sel"),
    array("lang_name" => "Semitic (Other)", "lang_code" => "sem"),
    array("lang_name" => "Serbian", "lang_code" => "scc/srp"),
    array("lang_name" => "Serbian (Latin)", "lang_code" => "qsr"),
    array("lang_name" => "Serer", "lang_code" => "srr"),
    array("lang_name" => "Shan", "lang_code" => "shn"),
    array("lang_name" => "Shona", "lang_code" => "sna"),
    array("lang_name" => "Sichuan Yi", "lang_code" => "iii"),
    array("lang_name" => "Sidamo", "lang_code" => "sid"),
    array("lang_name" => "Sign languages", "lang_code" => "sgn"),
    array("lang_name" => "Siksika", "lang_code" => "bla"),
    array("lang_name" => "Sindhi", "lang_code" => "snd"),
    array("lang_name" => "Sinhalese", "lang_code" => "sin"),
    array("lang_name" => "Sino-Tibetan (Other)", "lang_code" => "sit"),
    array("lang_name" => "Siouan languages", "lang_code" => "sio"),
    array("lang_name" => "Skolt Sami", "lang_code" => "sms"),
    array("lang_name" => "Slave (Athapascan)", "lang_code" => "den"),
    array("lang_name" => "Slavic (Other)", "lang_code" => "sla"),
    array("lang_name" => "Slovak", "lang_code" => "slo/slk"),
    array("lang_name" => "Slovenian", "lang_code" => "slv"),
    array("lang_name" => "Sogdian", "lang_code" => "sog"),
    array("lang_name" => "Somali", "lang_code" => "som"),
    array("lang_name" => "Songhai", "lang_code" => "son"),
    array("lang_name" => "Soninke", "lang_code" => "snk"),
    array("lang_name" => "Sorbian languages", "lang_code" => "wen"),
    array("lang_name" => "Sotho, Northern", "lang_code" => "nso"),
    array("lang_name" => "Sotho, Southern", "lang_code" => "sot"),
    array("lang_name" => "South American Indian (Other)", "lang_code" => "sai"),
    array("lang_name" => "Southern Sami", "lang_code" => "sma"),
    array("lang_name" => "South Ndebele", "lang_code" => "nbl"),
    array("lang_name" => "Spanish", "lang_code" => "spa"),
    array("lang_name" => "Sukuma", "lang_code" => "suk"),
    array("lang_name" => "Sumerian", "lang_code" => "sux"),
    array("lang_name" => "Sundanese", "lang_code" => "sun"),
    array("lang_name" => "Susu", "lang_code" => "sus"),
    array("lang_name" => "Swahili", "lang_code" => "swa"),
    array("lang_name" => "Swati", "lang_code" => "ssw"),
    array("lang_name" => "Swedish", "lang_code" => "swe"),
    array("lang_name" => "Syriac", "lang_code" => "syr"),
    array("lang_name" => "Tagalog", "lang_code" => "tgl"),
    array("lang_name" => "Tahitian", "lang_code" => "tah"),
    array("lang_name" => "Tai (Other)", "lang_code" => "tai"),
    array("lang_name" => "Tajik", "lang_code" => "tgk"),
    array("lang_name" => "Tamashek", "lang_code" => "tmh"),
    array("lang_name" => "Tamil", "lang_code" => "tam"),
    array("lang_name" => "Tatar", "lang_code" => "tat"),
    array("lang_name" => "Telugu", "lang_code" => "tel"),
    array("lang_name" => "Tereno", "lang_code" => "ter"),
    array("lang_name" => "Tetum", "lang_code" => "tet"),
    array("lang_name" => "Thai", "lang_code" => "tha"),
    array("lang_name" => "Tibetan", "lang_code" => "tib/bod"),
    array("lang_name" => "Tigre", "lang_code" => "tig"),
    array("lang_name" => "Tigrinya", "lang_code" => "tir"),
    array("lang_name" => "Timne", "lang_code" => "tem"),
    array("lang_name" => "Tiv", "lang_code" => "tiv"),
    array("lang_name" => "Tlingit", "lang_code" => "tli"),
    array("lang_name" => "Tok Pisin", "lang_code" => "tpi"),
    array("lang_name" => "Tokelau", "lang_code" => "tkl"),
    array("lang_name" => "Tonga (Nyasa)", "lang_code" => "tog"),
    array("lang_name" => "Tonga (Tonga Islands)", "lang_code" => "ton"),
    array("lang_name" => "Tsimshian", "lang_code" => "tsi"),
    array("lang_name" => "Tsonga", "lang_code" => "tso"),
    array("lang_name" => "Tswana", "lang_code" => "tsn"),
    array("lang_name" => "Tumbuka", "lang_code" => "tum"),
    array("lang_name" => "Tupi languages", "lang_code" => "tup"),
    array("lang_name" => "Turkish", "lang_code" => "tur"),
    array("lang_name" => "Turkish, Ottoman", "lang_code" => "ota"),
    array("lang_name" => "Turkmen", "lang_code" => "tuk"),
    array("lang_name" => "Tuvalu", "lang_code" => "tvl"),
    array("lang_name" => "Tuvinian", "lang_code" => "tyv"),
    array("lang_name" => "Twi", "lang_code" => "twi"),
    array("lang_name" => "Udmurt", "lang_code" => "udm"),
    array("lang_name" => "Uighur", "lang_code" => "uig"),
    array("lang_name" => "Ukrainian", "lang_code" => "ukr"),
    array("lang_name" => "Umbundu", "lang_code" => "umb"),
    array("lang_name" => "Undetermined", "lang_code" => "und"),
    array("lang_name" => "Upper Sorbian", "lang_code" => "hsb"),
    array("lang_name" => "Urdu", "lang_code" => "urd"),
    array("lang_name" => "Uzbek", "lang_code" => "uzb"),
    array("lang_name" => "Vai", "lang_code" => "vai"),
    array("lang_name" => "Venda", "lang_code" => "ven"),
    array("lang_name" => "Vietnamese", "lang_code" => "vie"),
    array("lang_name" => "Volapük", "lang_code" => "vol"),
    array("lang_name" => "Votic", "lang_code" => "vot"),
    array("lang_name" => "Wakashan languages", "lang_code" => "wak"),
    array("lang_name" => "Walamo", "lang_code" => "wal"),
    array("lang_name" => "Walloon", "lang_code" => "wln"),
    array("lang_name" => "Waray", "lang_code" => "war"),
    array("lang_name" => "Washo", "lang_code" => "was"),
    array("lang_name" => "Welsh", "lang_code" => "wel/cym"),
    array("lang_name" => "Wolof", "lang_code" => "wol"),
    array("lang_name" => "Xhosa", "lang_code" => "xho"),
    array("lang_name" => "Yakut", "lang_code" => "sah"),
    array("lang_name" => "Yao", "lang_code" => "yao"),
    array("lang_name" => "Yapese", "lang_code" => "yap"),
    array("lang_name" => "Yiddish", "lang_code" => "yid"),
    array("lang_name" => "Yoruba", "lang_code" => "yor"),
    array("lang_name" => "Yupik languages", "lang_code" => "ypk"),
    array("lang_name" => "Zande", "lang_code" => "znd"),
    array("lang_name" => "Zapotec", "lang_code" => "zap"),
    array("lang_name" => "Zenaga", "lang_code" => "zen"),
    array("lang_name" => "Zhuang", "lang_code" => "zha"),
    array("lang_name" => "Zulu", "lang_code" => "zul"),
    array("lang_name" => "Zuni", "lang_code" => "zun"),
);

function langname_for_langcode3( $langcode3 )
// For the given three-letter code, return the corresponding language-name,
// or null if not found.
{
    global $lang_list;
    foreach( $lang_list as $entry )
    {
        $entry_code = $entry['lang_code'];
        if ( substr($entry_code, 0, 3) == $langcode3 || substr($entry_code, 4, 3) == $langcode3 )
            return $entry['lang_name'];
    }
    return NULL;
}

function langcode3_for_langname( $langname )
// For the given language-name, return the corresponding three-letter code,
// or null if not found.
{
    global $lang_list;
    foreach( $lang_list as $entry )
    {
        $entry_name = $entry['lang_name'];
        if ( $entry_name == $langname )
        {
            $lang_code = $entry['lang_code'];
            // Note that $lang_code may be a pair of
            // three-letter codes separated by a slash.
            // Not sure if it matters which we return,
            // but for simplicity, we always return the first.
            // I.e., we return the first 3 characters of $lang_code.
            return substr($lang_code, 0, 3);
        }
    }
    return NULL;
}


// ====================================================================

// This array maps the bibliographical 3-letter codes to 2-letter codes
// for those languages having a 2-letter code in the ISO-639-1 list. 
// It is used to map 3-letter codes to IETF-like language tags, since
// these language tags use the 2-letter code when present, or the
// 3-letter bibliographical codes for languages having no 2-letter code.

$iso_639_1_list_ = array (
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
    "pdc" => "pd"
);

function langtag_for_langcode3( $langcode3 )
// For a given 3-letter code according to the list in pinc/iso_lang_list.inc,
// return a language tag conforming to the IETF language tag standard 
// as specified in RFC 5646 (http://tools.ietf.org/html/rfc5646).
// (the language tag is in lowercase since language tags are case
// insensitive and keeping them all in lowercase simplifies matching)
{
    global $iso_639_1_list_;
    // return the 2-letter code if it exists, or the 3-letter code.
    if (array_key_exists($langcode3, $iso_639_1_list_))
    {
        return $iso_639_1_list_[$langcode3];
    }
    return $langcode3;
}

function langcode3_for_langtag( $langtag )
// The opposite of langtag_for_langcode3(). Convert a language tag
// to a 3-letter code according to the list in pinc/iso_lang_list.inc,
// or NULL if no match was found.
{
    global $iso_639_1_list_;
    $langtag = strtolower($langtag);
    $key = array_search($langtag, $iso_639_1_list_);
    if ($key) 
    {
        // key found in the 3-letter code => lang tag table. 
        // Return the 3-letter code.
        return $key;
    }
    elseif(strlen($langtag) != 3)
    {
        return NULL;
    }
    else
    {
        // else search the tag as a 3-letter code.
        $name = langname_for_langcode3($langtag);
        if (isset($name))
            return $langtag;
        else
            return NULL;
    }
}

// vim: sw=4 ts=4 expandtab
?>
