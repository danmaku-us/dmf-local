<?php if (!defined('PmWiki')) exit();

class a4pi extends K_Controller {
    public function getlogo()
    {
        die(base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAIAAACQd1PeAAAAAXNSR0IArs4c6QAA'.
            'AARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAAMSURBVBhXY/j/'.
            '/z8ABf4C/qc1gYQAAAAASUVORK5CYII='));
    }
    
    public function dmpost()
    {
        $this->Helper(playerInterface);
        if (CmtPostArgChk()) {Abort("不允许直接访问");}
        
        $builder = new DanmakuBuilder($this->Input->Post->text, 0, 'deadbeef');
        $attrs = array(
                'playtime'  => $this->Input->Post->stime,
                'mode'      => $this->Input->Post->mode,
                'fontsize'  => $this->Input->Post->size,
                'color'     => $this->Input->Post->color);
		$builder->AddAttr($attrs);
		$xml = (string)$builder;
		
        //准备写入PmWiki
        $vid = basename($this->Input->Post->poolid);
        $_pagename = 'DMR.A4P'.$vid;
		$auth = 'edit';
        $page = @RetrieveAuthPage($_pagename, $auth, false, 0);
		
        if (!$page) die("-55");
        
        $page['text'] .= $xml;
        WritePage($_pagename, $page);
        die('DMF_Local :: a4pi :: dmpost() :: success!');
    }
    
    public function dmdelete()
    {
        $this->Helper(playerInterface);
        if (CmtPostArgChk()) {Abort("不允许直接访问");}

		$key = $this->hashCmt(
            $this->Input->Post->text,
            $this->Input->Post->color,
            $this->Input->Post->size,
            $this->Input->Post->mode,
            $this->Input->Post->stime);
        $vid = basename($this->Input->Post->poolid);
        
		$dynPool = new DanmakuPoolBase(Utils::GetIOClass('Acfun4p', $vid, 'dynamic'));
        foreach ($dynPool->GetXML()->comment as $node)
		{
			$K = $this->hashCmt( $node->text, $node->attr[0]["color"],$node->attr[0]["fontsize"],$node->attr[0]["mode"],$node->attr[0]["playtime"]);
			if ($K == $key) {
				echo 'Found!'.$node->text."\r\n";
				unset($node[0][0]);
				break;
			}
		}
		
        $dynPool->Save()->Dispose();
    }
    
    public function getvideobyid($pageid)
    {
        $pid = basename($pageid);
        $source = new VideoPageData("Acfun4p.{$pid}");
        
        $arr["aid"] = $pid;
        $arr["uid"] = 1;
        $arr["vinfo"] = array("checked" => 2);
        $arr["cid"] = "";
        $arr["vid"] = "";
        $arr["vtype"] = "";
        
        switch (strtoupper($source->VideoType->getType()))
	    {
	        case "NOR":
	            $arr['vid'] = $source->DanmakuId;
	            $arr['cid'] = $source->DanmakuId;
	            $arr['vtype'] = "sina";
	        break;
	        
			case "QQ":
                $arr["vid"]   = $source->DanmakuId;
				$arr["cid"]   = $source->DanmakuId;
				$arr["vtype"] = "qq";
	        break;
	        
			case "TD":
                $arr["vid"]   = $source->DanmakuId;
				$arr["cid"]   = $source->DanmakuId;
				$arr["vtype"] = "tudou";
	        break;

			case "YK":
                $arr["vid"]   = $source->DanmakuId;
				$arr["cid"]   = $source->DanmakuId;
				$arr["vtype"] = "youku";
	        break;
	        
			case "URL":
			case "BURL":
			case "LINK":
			case "BLINK":
			case "LOCAL":
				$arr["vid"]   = rawurldecode($source->VideoStr);
				$arr["file"]   = rawurldecode($source->VideoStr);
				$arr["cid"]   = $source->DanmakuId;
				$arr["vtype"] = "url";
	        break;

			default:
				echo "$source->VideoType->getType(): $source->DanmakuId : $source->VideoStr";
				assert(false);
	        break;
	    }
	    echo json_encode($arr);
	    exit;
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