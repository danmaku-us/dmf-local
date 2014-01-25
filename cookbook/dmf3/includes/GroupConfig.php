<?php if (!defined('PmWiki')) exit();
//{$groupName}.json
abstract class GroupConfig
{
    protected $groupName;
    protected $config;
    
    protected function __construct($groupName,GroupConfigJson $config)
    {
        $this->groupName  = $groupName;
        $this->config = $config;
    }
    
    public static function FromConfigFile($fp) {
        if (!file_exists($fp)) {
            throw new Exception("Config file not found: {$fp}.");
        }

        $json = new GroupConfigJson(json_decode(file_get_contents($fp), true));
        $className = "{$json->targetconfig}Base";
        
        //版本检查
        $version = intval($className::GetVersion());
        $reqver  = intval($json->targetvermin);
        if ($version < $reqver) {
            throw new Exception("Baseclass version mismatch : {$fp}.");
        }
        
        $targetGroupName = basename($fp, ".json");
        return new $className($targetGroupName, $json);
    }
    
    public static function GetVersion() { return static::$Version;}

    public function GetGroupName() { return $this->groupName; }
    
    public function GetDesc() { return $this->config->desc; }
    
    public function GetCommentFormats() { return $this->config->cmtformats; }
    
    public function GetPrefix() { return $this->GetGroupName(); }
    
    public function GetConfigFile() {}
    
    
    public function GetReferencedJS() {
        $arr = array();
        $dir = $this->getJavascriptDir();
        foreach ($this->config->javascripts as $jsfn) {
            $fp = "{$dir}/$jsfn";
            if (!file_exists($fp)) {
                throw new Exception("找不到引用的文件{$fp}");
            }
            $arr[]  = "{$dir}/$jsfn";
        }
        return $arr;
    }
    
    public abstract function CmtUploadPreprocess($str);
    
    public abstract function GenerateFlashVarArr(VideoInfo $source);
    
    private function getJavascriptDir() {
        return DMF_PUB__PATH . "/javascripts/" . substr(get_class($this), 0, -4);
    }
}