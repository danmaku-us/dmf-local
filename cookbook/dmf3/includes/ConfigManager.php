<?php if (!defined('PmWiki')) exit();
class ConfigManager extends Singleton {
    private $configInstance = array();
    
    protected function __construct()
    {
        parent::__construct();
    }
    
    private function create($groupName)
    {
        $fp = DMF_ROOT_PATH."/groups/{$groupName}.json";
        if (!file_exists($fp)) {
            throw new Exception("Config file not found: {$fp}.");
        }

        $json = new ConfigJson(json_decode(file_get_contents($fp)));
        $className = "{$json->targetConfig}BaseConfig";

        //版本检查
        $version = intval($className::version);
        $reqver  = intval($json->targetMinVer);
        if ($version < $reqver) {
            throw new Exception("Baseclass version mismatch : {$fp}.");
        }

        $this->configInstance[strtolower($groupName)] = new $className($groupName, $json);
        return $this->Get($groupName);
    }
    
    public function Get($groupName)
    {
        $key = strtolower($groupName);
        if (array_key_exists($key, $this->configInstance)) {
            return $this->configInstance[$key];
        } else {
            return $this->create($groupName);
        }
    }
    
    
}