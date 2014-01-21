<?php if (!defined('PmWiki')) exit();
final class PlayerManager extends Singleton {
	private $playerInstances = array();

	public function __construct()
	{
		$dir = DMF_PUB__PATH."/players/";
		$swfs = PathUtils::FindFiles($dir, "*.swf");
		foreach ($swfs as $swffile) {
			$this->fromFile($swffile);
		}
	}

	private function fromFile($swffile)
	{
		$p = new Player($swffile);

		//版本检查
		if (DMF_VERSION < $p->version) {
			throw new Exception("Player version mismatch : {$fp}.");
		}

		$this->playerInstances[strtolower($p->id)] = $p;
	}

	public function GetDefault($groupName)
	{

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