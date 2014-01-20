<?php if (!defined('PmWiki')) exit();
class ConfigManager extends Singleton {
    private $configInstance = array();
    
    protected function __construct()
    {
        parent::__construct();
        $files = find(DMF_PUB__PATH."/groups/", "*.json");
        foreach ($files as $file) {
            $this->fromFile($file);
        }
    }
    
    public function ToArray() {
        return $this->configInstance;
    }
    
    
    private function fromFile($fp)
    {
        if (!file_exists($fp)) {
            throw new Exception("Config file not found: {$fp}.");
        }

        $json = new ConfigJson(json_decode(file_get_contents($fp), true));
        $className = "{$json->targetConfig}BaseConfig";

        //版本检查
        $version = intval($className::GetVersion());
        $reqver  = intval($json->targetMinVer);
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
            throw new Exception("找不到");
        }
    }
    
}

function find($dir, $pattern){
    // escape any character in a string that might be used to trick
    // a shell command into executing arbitrary commands
    $dir = escapeshellcmd($dir);
    // get a list of all matching files in the current directory
    $files = glob("$dir/$pattern");
    // find a list of all directories in the current directory
    // directories beginning with a dot are also included
    foreach (glob("$dir/{.[^.]*,*}", GLOB_BRACE|GLOB_ONLYDIR) as $sub_dir){
        $arr   = find($sub_dir, $pattern);  // resursive call
        $files = array_merge($files, $arr); // merge array with files from subdirectory
    }
    // return all found files
    return $files;
}