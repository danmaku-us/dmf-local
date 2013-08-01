<?php if (!defined('PmWiki')) exit();
class Twodland1GroupConfig extends GroupConfig
{
    protected function __construct()
    {
        parent::__construct();
        $this->GroupString = 'Twodland1';
        $this->AllowedXMLFormat = array('comments', 'raw');
        $this->SUID = 'D';
        $this->XMLFolderPath = './uploads/Twodland1';
        $this->PlayersSet->Load($this->GroupString);
    }
    
    public function UploadFilePreProcess($str) {
        return simplexml_load_string($str);
    }
    
	public function GenerateFlashVarArr(VideoPageData $vdp)
	{   
        $p = $vdp->Player;
        $playerParams = new FlashParams($p->playerUrl, $p->width, $p->height);
        $playerParams->addVar('vid', $source->DanmakuId);
        $playerParams->addVar('dir',strtoupper($vdp->VideoType->getType()));
        
        if (strtoupper($vdp->VideoType->getType()) == "NOR") {
            $type = 'sina';
            $part = "<vid>{$vdp->DanmakuId}</vid>";
        } else {
            $type = 'other';
            $url = urldecode($vdp->VideoStr);
            $part = "<url>$url</url>";
        }
        $contents = <<<CONT
<?xml version="1.0" encoding="UTF-8"?>
<parts>
  <part name="DMF本地版" smooth="1" type="$type">
    $part
  </part>
</parts>
CONT;
        $targetFile = './static/page/'.md5($vdp->DanmakuId).'.xml';
        file_put_contents($targetFile, $contents, LOCK_EX);
		return $playerParams;
	}
	
    public function __get($name) {
        return $this->$name;
    }
    
}
