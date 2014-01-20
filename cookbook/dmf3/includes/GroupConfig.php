<?php if (!defined('PmWiki')) exit();
//{$groupName}.json
abstract class GroupConfig
{
    protected $groupName;
    protected $commentFormat;
    
    protected function __construct($groupName, $jsondata)
    {
        $this->groupName = $groupName;
        $this->commentFormats = $jsondata;
    }
        
    public static function GetVersion() { return static::$Version;}

    public function GetGroupName() { return $this->groupName; }
    
    public function GetCommentFormats() { return $this->commentFormats; }
    
    public function GetPrefix() { return $this->prefix; }
    
    public abstract function CmtUploadPreprocess($str);
    
    public abstract function GenerateFlashVarArr($source);
    
}