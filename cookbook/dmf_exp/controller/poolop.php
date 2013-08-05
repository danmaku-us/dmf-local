<?php if (!defined('PmWiki')) exit();
//PoolOp / command / group / dmid / params
//post move clear valid download

//弹幕操作接口
//返回HTML
class PoolOp extends K_Controller {
    const GoBack = "<script language='javascript'> setTimeout('history.go(-1)', 2000);</script>两秒后传送回家";
        
    public function PoolOp() {
        $this->Helper("danmakuPool");
        parent::__construct();
    }
    
	public function clear($group, $dmid, $pool)
	{
		$staPool = GetPool($group, $dmid, PoolMode::S);
		$dynPool = GetPool($group, $dmid, PoolMode::D);
		if (!XmlAuth($group, $dmid, XmlAuth::admin)) {
            Utils::WriteLog('PoolOp::clear()', "{$group} :: {$dmid} :: 权限不足");
            $this->display("越权访问。");
            return;
        }
        
		switch (strtolower($pool))
		{
			case "static":
				$staPool->Clear();
				$staPool->SaveAndDispose();
				Utils::WriteLog('PoolOp::clear()', "{$group} :: {$dmid} :: {$pool} :: Done!");
				break;
			case "dynamic":
				$dynPool->Clear();
				$dynPool->SaveAndDispose();
				Utils::WriteLog('PoolOp::clear()', "{$group} :: {$dmid} :: {$pool} :: Done!");
				break;
			case "all":
				$staPool->Clear();
                $dynPool->Clear();
                $staPool->SaveAndDispose();
				$dynPool->SaveAndDispose();
				Utils::WriteLog('PoolOp::clear()', "{$group} :: {$dmid} :: {$pool} ::Done!");
				break;
		}
        
		$this->display("和谐弹幕池 $pair 完毕。".self::GoBack);
	}
	
	
	public function loadxml($group, $dmid) // GET : format attach split
	{
        
        $gc = Utils::GetGroupConfig($group);

		$staPool = GetPool($group, $dmid, PoolMode::S);
		$dynPool = GetPool($group, $dmid, PoolMode::D);
		
        $staPool->MoveFrom($dynPool);
        $XML = $staPool->GetXML();
        unset($staPool);
		unset($dynPool);
		
		$format = is_null($_GET['format']) ? $gc->AllowedXMLFormat[0] : $_GET['format'];
		$format = strtolower($format);
		$chunksize = intval($_GET['split']);
		$attach = ($_GET['attach'] == 'true');
		$fileExt = ($format == 'json') ? "json" : "xml";
		
		$GetString = function (SimpleXMLElement $XML) use ($format) {
            if ($format == 'json') {
                return XMLConverter::ToJsonFormat($XML);
            } else {
                return XMLConverter::FromUniXML($format, $XML)->asXML();
            }
        };
		
        if ($chunksize == 0) {
            header("Content-type: text/plain");
            if ($attach) {
                header("Content-disposition: attachment; filename=\"{$group}_{$dmid}_{$format}.{$fileExt}\"");
            }
            echo $GetString($XML);
        } else {
            header("Content-type: application/octet-stream");
            header("Content-disposition: attachment; filename=\"{$group}_{$dmid}_{$format}_{$chunksize}.zip\"");
            $tempfile = tempnam(sys_get_temp_dir(), 'DMF');
            $zip = new ZipArchive();
            if ($zip->open($tempfile, ZipArchive::CREATE)!==TRUE) {
                exit("cannot open zip file <$filename>\n");
            }
            $chunks = array_chunk($XML->xpath('//comment'), $chunksize);
            unset($XML);
            foreach ( $chunks as $idx => $chunk ) {
                $XMLString = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n<comments>";
                foreach ($chunk as $item) {
                    $XMLString .= $item->asXML();
                }
                $XMLString .= '</comments>';
                $zip->addFromString("{$group}_{$dmid}_{$idx}.{$fileExt}", $GetString(simplexml_load_string($XMLString)));
            }
            $zip->Close();
            readfile($tempfile);
            unlink($tempfile);
        }
		
	}
	
	public function post($group, $dmid) // GET : pool append
	{
        
        if (!XmlAuth($group, $dmid, XmlAuth::edit)) {
            Utils::WriteLog('PoolOp::post()', "{$group} :: {$dmid} :: 权限不足");
            return;
        }
        
		//加载文件
		if ($this->Input->File->uploadfile['error'] != UPLOAD_ERR_OK)
		{
            Utils::WriteLog('PoolOp::post()', "{$group} :: {$dmid} :: 文件上传失败");
			$this->display("文件上传失败");
			return;
		}
	
		if ($xmldata === FALSE) 
		{
            Utils::WriteLog('PoolOp::post()', "{$group} :: {$dmid} :: XML非法");
            $this->display("XML文件非法，拒绝上传请求");
			return;
		}
		
        $pool = GetPool($group, $dmid, StrToPool($this->Input->Post->Pool));
		$gc = Utils::GetGroupConfig($group);
		$xmldata = $gc->UploadFilePreProcess(file_get_contents($this->Input->File->uploadfile['tmp_name']));
		$XMLObj = XMLConverter::ToUniXML($xmldata);
		unset($xmldata);
		
		$append = strtolower($this->Input->Post->Append) == 'true' ;
		if ($append) {
			$pool->MergeFrom($XMLObj);
		} else {
			$pool->SetXML($XMLObj);
		}
        
        $pool->SaveAndDispose();
        Utils::WriteLog('PoolOp::post()', "{$group} :: {$dmid} :: Success!");
		$this->display("非常抱歉，上传成功。".self::GoBack);
	}
	
	public function move($group, $dmid, $from, $to)
	{
        if (!XmlAuth($group, $dmid, XmlAuth::admin)) {
            Utils::WriteLog('PoolOp::clear()', "{$group} :: {$dmid} :: Unauthorized access!");
            $this->display("越权访问。");
            return;
        }
        
		$fromPool =  GetPool($group, $dmid, StrToPool($from));
		$toPool   =  GetPool($group, $dmid, StrToPool($to  ));
		
		$toPool->MoveFrom($fromPool);
		
		$fromPool->SaveAndDispose();
		$toPool->SaveAndDispose();
		Utils::WriteLog('PoolOp::move()', "{$group} :: {$dmid} :: 从 {$from} 移动到 {$to} 成功");
		$this->display("弹幕池移动： $from -> $to 完毕。".self::GoBack);
	}
	
	public function validate($group, $dmid, $pool = 'dynamic')
	{
		libxml_clear_errors();
		GetPool($group, $dmid, StrToPool($pool), LoadMode::inst);
		
		$errors = libxml_get_errors();
        if (empty($errors)) {
            $this->display("弹幕池{$pool}校验正常".self::GoBack);
            return;
        }
		$errorStr = "";
		foreach ($errors as $error) {
			$errorStr .= Utils::display_xml_error($error);
		}
		
		$this->display($errorStr);
	}
	
	private function display($msg)
	{
        $GLOBALS['MessagesFmt'] = $msg;
        $this->DisplayView('pmwiki_view', array('name' => 'API.XMLTool'));
	}
	
}
