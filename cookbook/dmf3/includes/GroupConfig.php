<?php if (!defined('PmWiki')) exit();
//DMF_PUB__PATH/goups/{$groupName}.json
abstract class GroupConfig
{
    protected $desc;
    protected $groupName;
    
    protected function __construct($groupName, array $config)
    {

        $this->groupName  = $groupName;
        $this->desc = $config['desc'];
    }
    
    private static function IsValidConfig($config) {
        return
            is_array($config)
            && array_key_exists('desc', $config)
            && array_key_exists('targetconfig', $config);
    }
    
    public static function FromConfigFile($fp) {
        if (!file_exists($fp)) {
            throw new Exception("Config file not found: {$fp}.");
        }

        $jsonarr = json_decode(file_get_contents($fp), true);
        if (!self::IsValidConfig($jsonarr)) {
            throw new Exception(
                "Bad config format!: ".var_export($jsonarr, true));
        }
        
        $className = "{$jsonarr['targetconfig']}Base"; 
        $targetGroupName = basename($fp, ".json");
        return new $className($targetGroupName, $jsonarr);
    }
    
    public function GetGroupName() { return $this->groupName; }
    
    public function GetDesc() { return $this->desc; }
    
    public function GetCommentFormats() { return $this->cmtFormats; }
    
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
    
    public abstract function CmtUploadPreprocess(string $str);
    
    public abstract function GenerateFlashVarArr(VideoInfo $source);
    
    private function getJavascriptDir() {
        return DMF_PUB__PATH . "/javascripts/" . substr(get_class($this), 0, -4);
    }
}