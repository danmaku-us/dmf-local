<?php if (!defined('PmWiki')) exit();
final class CommentPool
{
    private $pagename;
    private $cacheFile;
	private $xmlobj;
	private $isCached;
	private $gCfg;
    
    // 总是读取缓存
    // 如果没有缓存不存在就读取文件
    // 如果文件不存在就扔异常
	public function __construct($cmtPoolId, $gConfig)
	{
        $this->cacheFile = "";
        $this->pagename  = "";
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
        $xml = simplexml_load_string($x); // assume XML in $x
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

	public function Save()
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
    
    private function loadCached(){}
    private function loadPaged(){}
}