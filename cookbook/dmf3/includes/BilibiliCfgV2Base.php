<?php if (!defined('PmWiki')) exit();
class BilibiliCfgV2Base extends GroupConfig
{
    protected $cmtFormats   = array("d", "data", "raw");
    protected $videoFormats = array("sina", "local", "url");
    protected $javascripts  = array("jq.bilibili.js", "page.arc.js");
    //继承
    //$config;
    //$config->desc;
    //$config->targetconfig;
    //$groupName;
    public function __construct($groupName, GroupConfigJson $config)
    {
        parent::__construct($groupName, $config);
    }
    
    public function CmtUploadPreprocess($str) {
        return simplexml_load_string($str);
    }
    
	public function GenerateFlashVarArr(VideoInfo $videocfg)
	{
        $params = new FlashParams();;
        switch ($videocfg->videotype) {
            case VideoSources::SINA:
                $params->addVar('vid', $videocfg->GetCmtId());
                break;
            case VideoSources::LOCAL:
            case VideoSources::URL:
                $params->addVar('id'  , $videocfg->GetCmtId());
                $params->addVar('file', $videocfg->videosrc);
                break;
            default:
                $t = VideoSources::LOCAL;
                throw new Exception("未知视频类型{$videocfg->videotype} :: {$t}");
        }
        return $params;
	}
        
}