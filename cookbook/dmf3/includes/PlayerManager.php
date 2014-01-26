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
        $exists = array_key_exists($playerid, $this->playerInstances);
        
        if (empty($groupName)) {
            return $exists;
        } else {
            if ($exists) {
                $player = $this->{$playerid};
                $groupMatches = $player->group == $groupName;
                return $groupMatches;
            } else {
                return false;
            }
        }
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
        return $arr;
    }

	public function GetDefault($groupName)
	{
        $path = DMF_PUB__PATH."/players/{$groupName}/default.json";
        if (!file_exists($path)) {
            throw new Exception("!!!!!");
        }
        $cfg = json_decode(file_get_contents($path));
        if ($cfg === NULL) {    
            throw new Exception("Bad default.json!");
        }
        $id = $cfg->playerid;
        if (!$this->PlayerExists($id, $groupName)) {
            throw new Exception("找不到默认播放器{$id}");
        } else {
            return $this->playerInstances[$id];
        }
	}

    public function __get($playerid)
    {
        if (array_key_exists($playerid, $this->playerInstances)) {
            return $this->playerInstances[$playerid];
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