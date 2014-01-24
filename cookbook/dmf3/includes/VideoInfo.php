<?php if (!defined('PmWiki')) exit();
final class VideoInfo extends ConfigJson
{
    /*
    $videosrc;
    $videotype;
    $?srclink
    $?partinfo;
    $?playerid;

    $pagename;
    $group;*/
    protected $currentpart= 1;
    protected $pagename;
    protected $group;
    protected $userplayer = null;


    public function __construct($arr, $pagename, $player = null, $partid = null)
    {
        $this->pagename = $pagename;
        $this->group    = PageVar($pagename, '$Group');
        parent::__construct($arr);

        $inst = PlayerManager::GetInstance();

        SDV($this->json['srclink']   , "");
        SDV($this->json['partinfo']  , array());
        SDV($this->json['playerid']  , "");

        $this->userplayer = 
            $inst->PlayerExists($player, $this->group)
            ? $player
            : null;

        $this->currentpart=
            is_numeric($partid)
                && ( intval($partid) >= 1)
                && $this->IsMultiPart()
            ? intval($partid)
            : 1;
    }
    
    // 没有分P数据的弹幕ID
    public function GetBaseCmtId()
    {
        switch($this->videotype) {
            case VideoSources::SINA:
                return $this->videosrc;
                break;
            case VideoSources::LOCAL:
            case VideoSources::URL:
                return PageVar($this->pagename, '$Name');
                break;
        }
    }

    // 有分P数据的弹幕ID
    public function GetCmtId()
    {
        $baseid = $this->GetBaseCmtId();
        return ($this->currentpart == 1) ? $baseid : "{$baseid}P{$this->currentpart}";
    }

    public function IsMultiPart()
    {
        return empty($this->partinfo);
    }

    // (type, Player)
    // type = default, current, other
    public function GetPlayerInfo()
    {
        $manager = PlayerManager::GetInstance();
        $players = $manager->GetPlayers($this->group);
        $arr = array(
            'current' => null,
            'others'  => array()
            );

        $currentPlayer = $this->GetCurrentPlayer();
        foreach ($players as $id => $player) {
            if ($player = $currentPlayer) {
                $arr['current'] = $player;
            } else {
                $arr['others'][$id] = $player;
            }
        }
        return $arr;
    }

    public function GetCurrentPlayer()
    {
        $manager = PlayerManager::GetInstance();

        //指定的
        if (!is_null($this->userplayer)) 
                return $manager->{$this->userplayer};

        //播放器默认
        if ($manager->PlayerExists($this->playerid)) {
            return $manager->{$this->playerid};
        }

        //配置默认
        return $manager->GetDefault($this->group);
    }

    public function Validate($arr)
    {
        //var_dump($this);exit;
        $fields = array(
            'videosrc',
            'videotype',
            );
        return $this->hasRequired($arr, $fields);
    }
}

