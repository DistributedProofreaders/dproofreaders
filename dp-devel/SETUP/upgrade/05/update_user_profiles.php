<?PHP
$relPath = '../../../pinc/';
include_once($relPath.'connect.inc');
new dbConnect();

echo "Altering 'user_profiles' table...\n";

mysql_query("
    ALTER TABLE user_profiles
        ALTER COLUMN v_twrap SET DEFAULT '0',
        ALTER COLUMN h_twrap SET DEFAULT '0'
") or die(mysql_error());

echo "\nDone!\n";
?>
