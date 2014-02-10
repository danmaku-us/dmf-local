<?php if (!defined('PmWiki')) exit();
final class CommentPool
{
    const Pool_Cached = 'Pool_Cached';
    const Pool_Paged  = 'Pool_Paged';
    const Pool_Error  = 'Pool_Error';

    private $poolpage;
    private $cacheFilePath;
	private $xmlobj;
	private $poolState = self::Pool_Cached;
	private $gCfg;
    
    // 总是读取缓存
    // 如果没有缓存不存在就读取文件
    // 如果文件不存在就扔异常
	public function __construct($group, $poolId)
	{

        $this->gCfg      = GroupConfigManager::Get($group);
        $this->poolpage  = self::GetPageName($this->gCfg->GetGroupName(), $poolId);
        $this->cacheFile = self::GetCahceFilePath($this->gCfg->GetGroupName(), $poolId);
        
        if (DMFConfig::CMT_UsePoolCache) {
            $this->loadCached();
        } else {
            $this->loadPaged();
        }
	}
	
	public static function GetCahceFilePath($group, $poolId) {
	}
	
	public static function GetPageName($group, $poolId) {
        $gcfg = GroupConfigManager::Get($group);
        
        $prefix = $gcfg->GetPoolPageNamePrefix();
        return DMFConfig::CMT_PageGroup.".{$prefix}{$poolId}";
	}
	
    public static function CanRead($group, $poolId) {
        return CondAuth(self::GetPageName($group, $poolId), 'xmlread');
    }
    
    public static function CanWrite($group, $poolId) {
        return CondAuth(self::GetPageName($group, $poolId), 'xmledit');
    }

	public function Append()
	{

	}

	public function Clear()
	{

	}

	public function Search(CommentQuery $q)
	{

	}

	public function Replace()
	{

	}
	
	public function NextId()
	{
        /*
        $xml = simplexml_load_string($x); // assume xml in $x
        $ids = $xml->xpath("//element/@id");
        $newid = max(array_map(
            function($a) {
                list(, $id) = explode("_", $a);
                return intval($id); }
            , $ids)) + 1;
        $newid = "example_$newid";
        echo $newid;
        */
	}

    public function GetXMLObj() {
        return $this->xmlobj;
    }

	public function Save($dropHistory = false)
	{
        //write to Page
        
        //write to xmlfile
	}

	private function Validate()
	{
        //format check
        //simple syntax check
        //groupconfig check
	}

	private function hasCmtId($id)
	{

	}
    
    private function loadCached()
    {
        //不能从Paged转换到Cached
        if ($this->poolState <> self::Pool_Cached ) {
            throw new Exception("不能切换到缓存版本");
        }
        //文件不存在或者不允许就切换
        if (!file_exists($this->cacheFilePath) || (DMFConfig::CMT_UsePoolCache == FALSE)) {
            return $this->loadPaged();
        }

        $this->simplexml_load_file($this->cacheFilePath);
    }

    const XMLHeader = '<?xml version="1.0" encoding="utf-8"?><DMFCmtPool version="0">';
    const XMLFooter = '</DMFCmtPool>';

    private function loadPaged()
    {
        $page = RetrieveAuthPage($this->poolpage, DMFConfig::CMT_PoolReadAuth, FALSE, READPAGE_CURRENT);
        $this->xmlobj = simplexml_load_string(self::XMLHeader.$page['text'].self::XMLFooter);
        if ($this->xmlobj !== FALSE) {
            $this->poolState = self::Pool_Paged;
        } else {
            $this->poolState = self::Pool_Error;
            FB::Error("{$this->poolpage}XML格式非法");
            $errorXML = 
                self::XMLHeader.
                '<comment cmtid="-1" poolid="-1" sendtime="-1" user="-1" >
                    <text>弹幕池加载失败，请验证弹幕池。</text>
                    <playtime>0</playtime>
                    <mode>0</mode>
                    <fontsize>50</fontsize>
                    <color>0</fontsize>
                    <attr/>
                </comment>'.
                self::XMLFooter;
            $this->xmlobj = simplexml_load_string($errorXML);
        }
    }


}