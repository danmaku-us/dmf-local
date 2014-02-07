<?php if (!defined('PmWiki')) exit();
class Dmf extends K_Controller {

    public function getLocalUploads() {
        global $FarmD;
        
        if (!DMFConfig::LocalVersion) {
            exit;
        }
        
        $AllowedFileType = array("flv", "mp4", "m4a", "m4r", "m4v", "hlv", "mp3");
        $BaseUrl = 'http://localhost/uploads/LocalVideo/';
        $D = $FarmD."/uploads/LocalVideo";
        
        $localD = opendir($D);
        while ( ($file = readdir($localD)) !== false ) {
            $ext = pathinfo("$D$file", PATHINFO_EXTENSION);
            if ( in_array(strtolower($ext), $AllowedFileType) ){
                $files[basename($file)] = $BaseUrl.$file;
            }
        }
        header('Content-type: application/javascript');
        die("var files=".json_encode($files).";");
    }
}
