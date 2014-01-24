<?php if (!defined('PmWiki')) exit();

class FlashParams {
    private $params = array();
    
    public function FlashParams() {
        //Default params
        $this->addParam("menu", true);
        $this->addParam("allowscriptaccess", "always");
        $this->addParam("allowfullscreen", true);
        $this->addParam("bgcolor", "#FFFFFF");
        $this->addParam("autostart", false);
        $this->addParam("wmode", "direct");
        $this->addParam("allowFullscreenInteractive", true);        
    }
    
    private function _addVars($name, $value) {
        if (is_bool($value) || is_numeric($value)) {
            $this->params["{$name}"] = var_export($value, true);
        } else {
            $this->params["{$name}"] = '"'.$value.'"';
        }
    }
    
    public function addParam($name, $value) {
        $this->_addVars("params.{$name}", $value);
    }
    
    public function addVar($name, $value) {
        $this->_addVars("flashvars.{$name}", $value);
    }
    
    public function ToArray() {
        return $this->params;
    }
	
}