
CREATE TABLE `txn_proof` (
`txn_id` int( 20 ) NOT NULL AUTO_INCREMENT ,
`txn_time` int( 20 ) NOT NULL ,
`txn_code` char( 16 ) NOT NULL ,
`username` varchar( 25 ) NOT NULL ,
`projectid` varchar( 22 ) NOT NULL ,
`fileid` varchar( 20 ) default NULL ,
PRIMARY KEY ( `txn_id` ) ,
KEY `txn_time` ( `txn_time` ) ,
KEY `username` ( `username` ) ,
KEY `projectid` ( `projectid` , `fileid` ) 
) TYPE = MYISAM ;
