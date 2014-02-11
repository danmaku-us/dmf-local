<?php if (!defined('PmWiki')) exit();
final class CommentPool
{
    const Pool_Cached = 'Pool_Cached';
    const Pool_Paged  = 'Pool_Paged';
    const Pool_Error  = 'Pool_Error';

    private $storage;
	private $xmlobj;
	private $poolState = self::Pool_Cached;
	private $gCfg;
	
    // 总是读取缓存
    // 如果没有缓存不存在就读取文件
    // 如果文件不存在就扔异常
	public function __construct($group, $poolId)
	{

        $this->gCfg      = GroupConfigManager::Get($group);
        
        if (DMFConfig::CMT_UsePoolCache) {
            $this->loadCached($group, $poolId);
        } else {
            $this->loadLive($group, $poolId);
        }
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
    
    private function loadCached($group, $poolId)
    {
        $this->storage = 
            CommentPoolStorage::GetStorage($group, $poolId,
                CommentPoolStorageType::Cached);
        
        $this->xmlobj = $this->storage->Get();
        
        if ($this->xmlobj === FALSE) {
            $this->loadLive($group, $poolId);
        }
    }
    
    private function loadLive($group, $poolId)
    {
        $this->storage = 
            CommentPoolStorage::GetStorage($group, $poolId,
                DMFConfig::CMT_PoolStorage);
        $this->xmlobj = $this->storage->Get();
    }
    
}