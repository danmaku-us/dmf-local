<?php if (!defined('PmWiki')) exit();
class DummyController extends K_Controller {
    public function __construct() {
        parent::__construct();
    }
    
    public function emtpy() {
        die("");
    }
    
    public function emptyJsonArray() {
        die("[]");
    }
    
    public function emptyJsonObject() {
        die("{}");
    }
   
    public function emptyXML($root) {
        die("<{$root} />");
    }
    
}