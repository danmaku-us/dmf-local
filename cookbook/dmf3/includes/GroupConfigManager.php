<?php if (!defined('PmWiki')) exit();
//DMF_PUB__PATH/goups/{$groupName}.json
class GroupConfigManager extends Singleton {
    private $configInstance = array();
    
    protected function __construct()
    {
        parent::__construct();
        $files = PathUtils::FindFiles(DMF_PUB__PATH."/groups/", "*.json");
        foreach ($files as $file) {
            $this->fromFile($file);
        }
    }
    
    public function IsDMFGroup($groupname)
    {
        return array_key_exists($groupname, $this->configInstance);
    }
    
    
    public function FindByGroupName($name)
    {
        return $this->$name;
    }
    
    public function ToArray()
    {
        return $this->configInstance;
    }
    
    public static function Get($groupName) {
        $inst = self::GetInstance();
        return $inst->$groupName;
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
    
    private function fromFile($fp)
    {
        $config = GroupConfig::FromConfigFile($fp);
        
        $this->configInstance[$config->GetGroupName()] = $config;
    }
    
    
}

