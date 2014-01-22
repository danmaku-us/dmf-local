<?php if (!defined('PmWiki')) exit();
class ConfigManager extends Singleton {
    private $configInstance = array();
    
    protected function __construct()
    {
        parent::__construct();
        $files = PathUtils::FindFiles(DMF_PUB__PATH."/groups/", "*.json");
        foreach ($files as $file) {
            $this->fromFile($file);
        }
    }
    
    public function FindByGroupName($name)
    {
        return $this->$name;
    }
    
    public function ToArray()
    {
        return $this->configInstance;
    }
    
    
    private function fromFile($fp)
    {
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
        
        $groupName = basename($fp, ".json");
        $this->configInstance[strtolower($groupName)] = new $className($groupName, $json);
    }
    
    public function __get($groupName)
    {
        $key = strtolower($groupName);
        if (array_key_exists($key, $this->configInstance)) {
            return $this->configInstance[$key];
        } else {
            throw new Exception("找不到{$groupName}");
        }
    }
    
}

