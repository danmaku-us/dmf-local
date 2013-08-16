<?php if (!defined('PmWiki')) exit();
//Acfun (新) 播放器接口
class Anpi extends K_Controller {
    private $GroupConfig;
    
    public function __construct() {
        $this->GroupConfig = Utils::GetGroupConfig("AcfunN1");
        parent::__construct();
    }
    
    public function getlogo()
    {
        die(base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAIAAACQd1PeAAAAAXNSR0IArs4c6QAA'.
            'AARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAAMSURBVBhXY/j/'.
            '/z8ABf4C/qc1gYQAAAAASUVORK5CYII='));
    }
    
    public function dmpost()
    {
        $this->Helper("playerInterface");
        if ($this->requireVars(
                $this->Input->Post,
                array("islock", "color", "text", "size", "mode", "stime", "timestamp", "poolid"))) {
            Abort("不允许直接访问");
        }
        
        $builder = new DanmakuBuilder($this->Input->Post->text, 0, 'deadbeef');
        $attrs = array(
                'playtime'  => $this->Input->Post->stime,
                'mode'      => $this->Input->Post->mode,
                'fontsize'  => $this->Input->Post->size,
                'color'     => $this->Input->Post->color);
		$builder->AddAttr($attrs);

        if (cmtSave($this->GroupConfig, $this->Input->Post->poolid, $builder)) {
            die('DMF_Local :: anpi :: dmpost() :: success!');
        } else {
            die('DMF_Local :: anpi :: dmpost() :: page fail!');
        }
        
    }
    
    public function dmdelete()
    {
        $this->Helper("playerInterface");
        if ($this->requireVars(
                $this->Input->Post,
                array("islock", "color", "text", "size", "mode", "stime", "timestamp", "poolid"))) {
            Abort("不允许直接访问");
        }

		$key = $this->hashCmt(
            $this->Input->Post->text,
            $this->Input->Post->color,
            $this->Input->Post->size,
            $this->Input->Post->mode,
            $this->Input->Post->stime);
        $dmid = basename($this->Input->Post->poolid);
		$dynPool = GetPool('AcfunN1', $dmid, PoolMode::D);
        foreach ($dynPool->GetXML()->comment as $node)
		{
			$K = $this->hashCmt( $node->text, $node->attr[0]["color"],$node->attr[0]["fontsize"],$node->attr[0]["mode"],$node->attr[0]["playtime"]);
			if ($K == $key) {
				echo 'Found!'.$node->text."\r\n";
				unset($node[0][0]);
				break;
			}
		}
		
        $dynPool->SaveAndDispose();
    }
    
	private function hashCmt($text, $color, $size, $mode, $stime)
	{
		return md5("$text$color$size$mode$stime");
	}
	
    public function ujson()
    {
        die('[]');
    }
    
    public function badwords()
    {
        die('[]');
    }
    
    public function adsjson()
    {
        die('');
    }
}