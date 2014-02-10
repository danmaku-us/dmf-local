<?php if (!defined('PmWiki')) exit();
class DummyResponse extends K_Controller {
    public function __construct() {
        parent::__construct();
    }
    
    public function emptyText() {
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