<?php
$relPath='./pinc/';
include($relPath.'connect.inc');
new dbConnect();

$sql = "
    CREATE TABLE non_activated_users (
        id            varchar(50)  NOT NULL default '',
        real_name     varchar(100) NOT NULL default '',
        username      varchar(25)  NOT NULL default '',
        email         varchar(50)  NOT NULL default '',
        date_created  int(20)      NOT NULL default '0',
        email_updates varchar(4)   NOT NULL default '',
        u_intlang     varchar(5)            default 'en_EN',
        user_password varchar(32)  NOT NULL default '',
        PRIMARY KEY  (`username`)
    )
    COMMENT='Each row represents a not-yet-activated user, user_password is md5-hashed'
";
mysql_query($sql) or die( mysql_error() );
echo "<center>Table 'non_activated_users' created successfully.</center>";
?>