<?PHP
/*
# List of hypenated words that should NOT be de-hyphenated when at
# the end of a line. (common compound words and hyphenated prefixes, first half)
# Lazy, this really should be using "dictionary" files instead of inlined data
# NEW LIST: Frequency analyzed from the Moby Project data, top 300 or so in the
# categories: descriptive, animal, body, colors, directions, emotions, numerics,
# places, plants, true hyphenated prefixes, times, and weather. Objects and
# verbs were excluded due to low frequencies.
*/
$nojoin = array( "afore", "after", "age", "air", "all", "alpha", "anti", "arch", "arm", "auto", 
"baby", "back", "belly", "beta", "bird", "black", "blood", "blue", "brain", 
"bright", "broad", "brow", "brown", "buck", "bull", "care", "chicken", "child", 
"clear", "close", "cloud", "cold", "contra", "corn", "count", "counter",
"country", "court", "cow", "cross", "crow", "day", "dead", "deep", "demi", "dim", 
"dog", "double", "down", "dream", "dry", "dull", "ear", "east", "eight",
"eighty", "end", "equal", "eve", "ever", "extra", "eye", "false", "far", 
"father", "fear", "feather", "field", "fifty", "fire", "first", "fish", 
"five", "flat", "flower", "fly", "foot", "for", "fore", "forty", "four", 
"fourth", "free", "front", "frost", "full", "gay", "gold", "golden", "good", 
"goose", "grass", "gray", "great", "green", "grey", "ground", "hair", "half", 
"hand", "hard", "head", "heart", "heat", "heaven", "high", "hog", "home", 
"horse", "hot", "house", "hundred", "hyper", "ice", "ill", "inter", "intra", 
"ivory", "joy", "land", "large", "last", "late", "left", "life", "light", 
"lion", "long", "love", "low", "many", "mean", "merry", "meta", "middle", 
"mill", "mind", "moon", "morning", "mother", "mouse", "multi", "multiple", 
"near", "neo", "never", "new", "nigh", "night", "nine", "ninety", "non", 
"north", "nose", "off", "old", "one", "open", "out", "over", "pale", "pan", 
"par", "passion", "pear", "pearl", "pleasant", "pleasure", "plum", "point", 
"post", "pro", "proto", "proud", "pseudo", "public", "purple", "quarter", 
"quasi", "quick", "rain", "red", "rib", "right", "river", "road", "rock", 
"rose", "rough", "round", "rude", "sad", "sand", "scar", "scare", "sea", 
"second", "self", "semi", "seven", "seventy", "sharp", "short", "side", "silk", 
"silver", "single", "six", "sixty", "skin", "sky", "slow", "small", "smooth", 
"snake", "snow", "sober", "soft", "sole", "son", "soon", "sorrow", "soul", 
"south", "spider", "spring", "square", "stag", "star", "state", "stern", "stone", 
"storm", "straight", "sub", "sugar", "summer", "sun", "super", "supra", "swan", 
"sweet", "tempest", "ten", "tender", "terror", "thick", "thin", "third", 
"thirty", "thought", "thousand", "three", "thrice", "through", "thunder", "time", 
"to", "toad", "tongue", "top", "town", "tree", "tri", "triple", "true", "twelve", 
"twenty", "twice", "two", "ultra", "under", "vice", "violet", "wall", "water", 
"well", "west", "white", "wide", "wind", "wing", "winter", "wish", "woe", "wolf", 
"wonder", "wood", "world", "worm", "wry", "year", "yellow"
);
?>