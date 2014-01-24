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

    public function PlayerExists($playerid, $groupName = "")
    {
        return
            array_key_exists($playerid, $this->playerInstances)
            && ( !empty($groupName) && ($this->$playerid->group = $groupName));
    }

    // 返回数组$arr['playerid'] = Player
    public function GetPlayers($groupName)
    {

        $arr = array();
        foreach ($this->playerInstances as $name => $player) {
            if ($player->group == $groupName) {
                $arr[$name] = $player;
            }
        }
    }

	public function GetDefault($groupName)
	{
		return $this->playerInstances["abcd"];
	}

    public function __get($playerid)
    {
        if ($this->PlayerExists($playerid)) {
            return $this->playerInstance[$playerid];
        } else {
            throw new Exception("找不到播放器{$playerid}");
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

}