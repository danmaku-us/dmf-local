<?php if (!defined('PmWiki')) exit();
class b3pi extends K_Controller {
    private $GroupConfig;
    
    public function __construct() {
        $this->GroupConfig = Utils::GetGroupConfig("bilibili3");
        parent::__construct();
    }
    
	public function index()
	{
        die("unknown action");
	}
	
	public function nullResp()
	{
        die('');
	}
	
	public function msg()
	{
        die('<msg><item bgcolor="#CCCCCC"><![CDATA[<font color="#bbbbbb">TDGBLS</font>]]></item></msg>');
	}
	
	public function cloudfilter()
	{
        die('{"user":[],"keyword":[]}');
    }
    public function bpad()
    {
        $this->DisplayStatic('bilibili_pad.xml');
    }
    
    public function error()
	{
        $GLOBALS['MessagesFmt'] = '你知道的太多了，小心大表哥。';
        $this->DisplayView('pmwiki_view', array('name' => 'API.XMLTool'));
	}
	
	public function dad()
	{
        global $BilibiliAuthLevel;
        $this->Helper("danmakuPool");
        $data = array();
        if (empty($this->Input->Request->id)) {
            $data['ChatId'] = $this->Input->Request->id;
        } else {
            $data['ChatId'] = 0;
        }
        
        if (XmlAuth('Bilibili3', $this->Input->Request->id, XmlAuth::edit)) {
            $data['AuthLevelString'] = $BilibiliAuthLevel->Danmakuer;
        } else {
            $data['AuthLevelString'] = $BilibiliAuthLevel->DefaultLevel;
        }
        
        $this->DisplayView('bilibili_dad_xml', $data);
	}
	
	public function advanceComment()
	{
        $gc = Utils::GetGroupConfig('Bilibili3');
        if ($gc->BiliEnableSA)
        {
            die("<confirm>1</confirm><hasBuy>true</hasBuy>");
        } else {
            die("<confirm>0</confirm><hasBuy>false</hasBuy><accept>false</accept>");
        }
	}
	
	public function dmduration()
	{
        exit;
	}
	
	public function rec()
	{
        exit;
	}
	//关联视频
	public function playtag()
	{
        exit;
	}
	//弹幕举报
	public function dmreport()
	{
        exit;
	}
    //播放器接口 。弹幕错误汇报
	public function dmerror()
	{
        if ( empty($this->Input->Request->id) || empty($this->Input->Request->error) )
            exit;
        $str = "播放器汇报错误{$this->Input->Request->error}, 返回视频vid : {$this->Input->Request->id}";
        Utils::WriteLog('bpi::dmerror()', $str);
	}
	
	public function dmpost()
	{
        $this->Helper("playerInterface");
        
        if ($this->requireVars(
                $this->Input->Post,
                array("date", "playTime", "mode", "fontsize", "color", "pool", "message"))) {
            Abort("不允许直接访问");
        }
        
		$pool = ($this->Input->Post->mode == '8') ? 2 : 1; //mode = 8 时 pool 必须 = 2
        $builder = new DanmakuBuilder($this->Input->Post->message, $pool, 'deadbeef');
        $attrs = array(
                'playtime'  => $this->Input->Post->playTime,
                'mode'      => $this->Input->Post->mode,
                'fontsize'  => $this->Input->Post->fontsize,
                'color'     => $this->Input->Post->color);
		$builder->AddAttr($attrs);
		
        if (cmtSave($this->GroupConfig, $this->Input->Post->cid, $builder)) {
            echo mt_rand();
        } else {
            die("-55");
        }
	}

    
    // ************************* dmm ********************//
    
    
	public function update_comment_time()
	{   
        
        
        $targetTime = intval($this->Input->Request->time);
        $dmid = intval($this->Input->Request->dmid);
        $poolId = intval($this->Input->Request->cid);
        if (is_null($poolId)) die("2");
        
        $dynPool = GetPool('Bilibili3', $poolId, PoolMode::D);
        $query = new DanmakuXPathBuilder();
        $result = $dynPool->Find($query->CommentId($dmid));
        
        if (empty($result)) die("3");
        
        foreach ( $result as $danmaku ) {
            $danmaku->attr[0]["playtime"] = $targetTime;
        }
        $dynPool->Save()->Dispose();
        Utils::WriteLog('Dmm::update_comment_time()', "{$poolId} :: Pool->Save() :: Done!");
        die("0");
	}
	
	public function del()
	{
        
        if ($this->requireVars(
                $this->Input->Post,
                array("playerdel", "dm_inid"))) {
            Abort("不允许直接访问");
        }
        
        $poolId = $this->Input->Request->dm_inid;
        $dynPool = GetPool('Bilibili3', $poolId, PoolMode::D);
        $deleted = "";
        
        foreach (explode(",", $this->Input->Request->playerdel) as $id)
        {
            $query = new DanmakuXPathBuilder();
            $result = $dynPool->Find($query->CommentId($id));
            $matched = count($result);
            
            if ($matched == 1) {
                unset($result[0][0]);
                $deleted .= ", '{$id}'";
            } else {
                Utils::WriteLog('Dmm::del()', "Bilibili3 :: {$poolId} :: Unexcepted dmid {$id}, matched {$matched}");
                die("3");
            }
        }
        $dynPool->Save()->Dispose();
        
        Utils::WriteLog('Dmm::del()', "Bilibili3 :: {$poolId} :: Done!  \r\n{$deleted}");
        die("0");
	}

	public function move()
	{
        die("0");
	}
	
	public function credit()
	{
        die("0");
	}
	
	public function skip()
	{
        die("0");
	}
	
    private function dmid_to_idhash($dmid, $prefix = true)
    {
        $numb = $head ? substr(md5("DMR.B".$vid),0,4) : substr(md5($vid),0,4);
        return intval($numb, 16);
    }

    private function idhash_to_dmid($hash)
    {
        $pn = null;
        foreach ( ListPages("/DMR\.B/") as $page) {
            if ( dmid_to_idhash($page, false) == $hash ) {
                $pn = $page;
            }
        }
        
        if (is_null($pn)) return null;
        
        $dmid = pathinfo($pn, PATHINFO_EXTENSION);
        $dmid = substr($dmid, 1);
        return $dmid;
    }
}