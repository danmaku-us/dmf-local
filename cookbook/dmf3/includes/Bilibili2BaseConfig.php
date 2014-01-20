<?php if (!defined('PmWiki')) exit();
class Bilibili2BaseConfig extends GroupConfig
{
    static $Version = 0;
    
    public function __construct($groupName, $jsondata)
    {
        parent::__construct($groupName, $jsondata);
    }
    
    public function CmtUploadPreprocess($str) {
        return simplexml_load_string($str);
    }
    
	public function GenerateFlashVarArr($source)
	{
	}
        
}