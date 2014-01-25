<?php if (!defined('PmWiki')) exit();
//{$groupName}.json
abstract class GroupConfig
{
    protected $desc;
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
                
        $targetGroupName = basename($fp, ".json");
        return new $className($targetGroupName, $json);
    }
    
    public function GetGroupName() { return $this->groupName; }
    
    public function GetDesc() { return $this->config->desc; }
    
    public function GetCommentFormats() { return $this->cmtformats; }
    
    public function GetPrefix() { return $this->GetGroupName(); }
    
    public function GetConfigPath() {
        return DMF_PUB__PATH . "/groups/" . $this->groupName . ".json";
    }

    public function GetReferencedJS() {
        $arr = array();
        $dir = $this->getJavascriptDir();
        foreach ($this->javascripts as $jsfn) {
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