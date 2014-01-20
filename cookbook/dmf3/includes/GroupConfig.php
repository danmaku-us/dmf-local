<?php if (!defined('PmWiki')) exit();
//{$groupName}.json
abstract class GroupConfig
{
    protected $groupName;
    protected $configJson;
    
    protected function __construct($groupName, $jsondata)
    {
        $this->groupName  = $groupName;
        $this->configJson = $jsondata;
    }
        
    public static function GetVersion() { return static::$Version;}

    public function GetGroupName() { return $this->groupName; }
    public function GetDesc() { return $this->configJson->desc; }
    
    public function GetCommentFormats() {
        return $this->configJson->cmtFormats;
    }
    
    public function GetPrefix() {
        return $this->GetGroupName();
    }
    
    public abstract function CmtUploadPreprocess($str);
    
    public abstract function GenerateFlashVarArr($source);
    
}