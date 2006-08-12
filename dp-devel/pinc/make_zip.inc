<?php
include_once($relPath.'v_site.inc');

// code cribbed from http://www.zend.com/zend/spotlight/creating-zip-files1.php
// and http://codewalkers.com/getcode.php?id=65

// example usage:
// $zipfile = new zipfile();   
// $zipfile -> add_dir("dir/");  // add the subdirectory ... important! 
// $zipfile -> add_file($filedata, "dir/file.txt");   
// header("Content-type: application/octet-stream");   
// header("Content-disposition: attachment; filename=test.zip");   
// echo $zipfile -> file();  
// In fact it seems to work just fine if you don't add the directory,
// and just do  $zipfile -> add_file($filedata, "file.txt"); 

class zipfile 
{
    var $datasec = array();  // stores compressed data and headers
    var $ctrl_dir = array(); // stores central index information
    var $eof_ctrl_dir = "\x50\x4b\x05\x06\x00\x00\x00\x00"; // Pre-set binary data defining end of central index
    var $old_offset = 0; 
    
    // add a directory to the archive. Need to do this before adding
    // any files in the directory
    function add_dir($name) {
        $name = str_replace("\\", "/", $name); // make sure there are no nasty backslashes

        $fr = "\x50\x4b\x03\x04";
        $fr .= "\x0a\x00";    // ver needed to extract 
        $fr .= "\x00\x00";    // gen purpose bit flag 
        $fr .= "\x00\x00";    // compression method 
        $fr .= "\x00\x00\x00\x00"; // last mod time and date 

        // V unsigned long integer
        // v unsigned short integer
        $fr .= pack("V", 0); // crc32 
        $fr .= pack("V", 0); //compressed filesize 
        $fr .= pack("V", 0); //uncompressed filesize 
        $fr .= pack("v", strlen($name) ); //length of pathname 
        $fr .= pack("v", 0 ); //extra field length 
        $fr .= $name;   
        // end of "local file header" segment 

        // "data descriptor" segment (optional but necessary if archive is not served as file) 
        $fr .= pack("V", 0); //crc32 
        $fr .= pack("V", 0); //compressed filesize 
        $fr .= pack("V", 0); //uncompressed filesize 

        // add this entry to array 
        $this -> datasec[] = $fr;  

        $new_offset = strlen(implode("", $this->datasec));  

        // now add to central record 
        $cdrec = "\x50\x4b\x01\x02";  
        $cdrec .="\x00\x00";    // version made by 
        $cdrec .="\x0a\x00";    // version needed to extract 
        $cdrec .="\x00\x00";    // gen purpose bit flag 
        $cdrec .="\x00\x00";    // compression method 
        $cdrec .="\x00\x00\x00\x00"; // last mod time & date 
        $cdrec .= pack("V", 0); // crc32 
        $cdrec .= pack("V", 0); //compressed filesize 
        $cdrec .= pack("V", 0); //uncompressed filesize 
        $cdrec .= pack("v", strlen($name) ); //length of filename 
        $cdrec .= pack("v", 0 ); //extra field length    
        $cdrec .= pack("v", 0 ); //file comment length 
        $cdrec .= pack("v", 0 ); //disk number start 
        $cdrec .= pack("v", 0 ); //internal file attributes 

        // these two lines appear in both sources, but are explained in neither
        $ext = "\x00\x00\x10\x00";  
        $ext = "\xff\xff\xff\xff";
   
        $cdrec .= pack("V", 16 ); //external file attributes  - 'directory' bit set 
        $cdrec .= pack("V", $this -> old_offset ); //relative offset of local header 
        $cdrec .= $name;   
        // optional extra field, file comment goes here 
        // save to array 
        $this -> ctrl_dir[] = $cdrec;   
        $this -> old_offset = $new_offset;  // update the offset
        return;
    }

    // adds "file" to archive    
    // $data - file contents 
    // $name - name of file in archive. Should include path.
    function add_file($data, $name) {
        $name = str_replace("\\", "/", $name);  // remove unwanted backslashes  
        $unc_len = strlen($data); // uncompressed length
        $crc = crc32($data);    // checksum
        $zdata = gzcompress($data); // compress
        $zdata = substr ($zdata, 2, -4); // fix crc bug
        $c_len = strlen($zdata); // compressed length

        $fr = "\x50\x4b\x03\x04";  
        $fr .= "\x14\x00";      // ver needed to extract 
        $fr .= "\x00\x00";      // gen purpose bit flag 
        $fr .= "\x08\x00";      // compression method 
        $fr .= "\x00\x00\x00\x00"; // last mod time and date 

        // "data descriptor" segment (optional but necessary if archive is not served as file) 
        $fr .= pack("V",$crc);  //crc32 
        $fr .= pack("V",$c_len); // compressed filesize 
        $fr .= pack("V",$unc_len); // uncompressed filesize 
        $fr .= pack("v", strlen($name) ); //length of filename 
        $fr .= pack("v", 0 );   // extra field length 
        $fr .= $name;   
        // end of "local file header" segment 

        // "file data" segment 
        $fr .= $zdata;   

        // "data descriptor" segment (optional but necessary if archive is not served as file) 
        $fr .= pack("V",$crc);  // crc32 
        $fr .= pack("V",$c_len); // compressed filesize 
        $fr .= pack("V",$unc_len); // uncompressed filesize 

        // add this entry to array 
        $this -> datasec[] = $fr;  

        $new_offset = strlen(implode("", $this->datasec));  

        // now add to central directory record 
        $cdrec = "\x50\x4b\x01\x02";  
        $cdrec .="\x00\x00";    // version made by 
        $cdrec .="\x14\x00";    // version needed to extract 
        $cdrec .="\x00\x00";    // gen purpose bit flag 
        $cdrec .="\x08\x00";    // compression method 
        $cdrec .="\x00\x00\x00\x00"; // last mod time & date 
        $cdrec .= pack("V",$crc); // crc32 
        $cdrec .= pack("V",$c_len); // compressed filesize 
        $cdrec .= pack("V",$unc_len); // uncompressed filesize 
        $cdrec .= pack("v", strlen($name) ); // length of filename 
        $cdrec .= pack("v", 0 ); // extra field length    
        $cdrec .= pack("v", 0 ); // file comment length 
        $cdrec .= pack("v", 0 ); // disk number start 
        $cdrec .= pack("v", 0 ); // internal file attributes 
        $cdrec .= pack("V", 32 ); // external file attributes - 'archive' bit set 

        $cdrec .= pack("V", $this -> old_offset ); // relative offset of local header 
        $this -> old_offset = $new_offset;  

        $cdrec .= $name;   
        // optional extra field, file comment goes here 
        // save to central directory 
        $this -> ctrl_dir[] = $cdrec;   
    }  

    function file() { 
        // dump out file    
        $data = implode("", $this -> datasec);   
        $ctrldir = implode("", $this -> ctrl_dir);   
        $lengthdata = pack("v", sizeof($this -> ctrl_dir)); // total no of entries "on this disk" 
        $lengthdata .= pack("v", sizeof($this -> ctrl_dir)); // total no of entries overall 
        $lengthdata .= pack("V", strlen($ctrldir)); // size of central dir 
        $lengthdata .= pack("V", strlen($data)); // offset to start of central dir 
        $lengthdata .= "\x00\x00"; // .zip file comment length 

        return    
            $data.   
            $ctrldir.   
            $this -> eof_ctrl_dir.   
            $lengthdata;
    }  
}
// vim: sw=4 ts=4 expandtab
?>