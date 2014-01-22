<?php if (!defined('PmWiki')) exit();
class BilibiliCfgV2Base extends GroupConfig
{
    static $Version = 0;
    
    public function __construct($groupName,GroupConfigJson $config)
    {
        parent::__construct($groupName, $config);
    }
    
    public function CmtUploadPreprocess($str) {
        return simplexml_load_string($str);
    }
    
	public function GenerateFlashVarArr($source)
	{
	}
        
}