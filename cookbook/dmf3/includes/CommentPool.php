<?php if (!defined('PmWiki')) exit();
//TODO:
final class CommentPool
{
    const Pool_Cached = 'Pool_Cached';
    const Pool_Live   = 'Pool_Live';
    const Pool_Error  = 'Pool_Error';

    private $storage;
	private $xmlobj;
	private $poolState = self::Pool_Cached;
	private $gCfg;
	
    private $group;
    private $poolId;

    // 总是读取缓存
    // 如果没有缓存不存在就读取文件
    // 如果文件不存在就扔异常
	public function __construct($group, $poolId)
	{
        $this->group     = $group;
        $this->poolId    = $poolId;
        $this->gCfg      = GroupConfigManager::Get($group);
        
        if (DMFConfig::CMT_UsePoolCache) {
            $this->loadCached();
        } else {
            $this->loadLive();
        }
	}
	
	//附加弹幕
	//TODO:
	public function Append()
	{
        $this->requireLive();
	}
    
    //清空弹幕池
	public function Clear($pooltype = InternalPoolType::All)
	{
        $this->requireLive();
        switch ($pooltype) {
            case InternalPoolType::All:
                $this->xmlobj = CommentPoolStorage::GetEmptyObj();
                return true;
            case InternalPoolType::Sta:
            case InternalPoolType::Dyn:
                $id = InternalPoolType::ToId($pooltype);
                $query = new CommentQuery();
                $query->PoolType($id);
                foreach ($query->Match($this->xmlobj) as $commentNode) {
                    unset($commentNode[0][0]);
                }
                return true;
            default:
                FB::Error("Unknown pool {$pooltype} at CommentPool::Clear()");
                return false;
        }
	}

	public function Search(CommentQuery $q)
	{
        return $q->Match($this->xmlobj);
	}
    
    //TODO:
	public function Replace()
	{
        $this->requireLive();

	}
	
	//从静态<-->动态
	public function Move($fromtype, $totype)
	{
        $from = InternalPoolType::ToId($fromtype);
        $to   = InternalPoolType::ToId($totype);
        $query = new CommentQuery();
        $query->PoolType($from);
        
        foreach ($query->Match($this->xmlobj) as $commentNode) {
            $commentNode['pooltype'] = $to;
        }
        
	}
	
	public function Save($genHistory = false)
	{
        $this->requireLive();
        switch ($this->poolState) {
            case self::Pool_Cached:
                assert(false);
                break;
            case self::Pool_Live:
                $this->storage->Put($this->xmlobj, $genHistory);
                $cached = 
                    CommentPoolStorage::GetStorage(
                        $this->group,
                        $this->poolId,
                        CommentPoolStorageType::Cached);
                $cached->Put($this->xmlobj);
                break;
            case self::Pool_Error:
                FB::error("弹幕池{$this->group}::{$this->poolId}存在错误，拒绝写入");
                throw new Exception(
                    "弹幕池{$this->group}::{$this->poolId}存在错误，拒绝写入");
                break;
        }
	}

	private function Validate()
	{
        //直接进行xsd检查，如果文件不合法那么没法被加载的
        $dom = dom_import_simplexml($this->xmlobj);
        $res = $dom->schemaValidate(DMF_ROOT_PATH."/res/xml_dmf.xsd");
        if (!$res) {
            return XMLHelper::GetErrors();
        }
        
        //TODO:groupconfig check
        return true;
	}

	public function NextId()
	{
        $ids = $this->xmlobj->xpath("//comment/@cmtid");
        return max(array_map(
            function ($id) {
                return intval($id);
            })) + 1;
	}

    public function GetXMLObj() {
        return $this->xmlobj;
    }

	private function hasCmtId($id)
	{
        $id = intval($id);
        $matches = $this->xmlobj->xpath("//comment[@cmtid='{$id}']");
        return count($matches) != 0;
	}
    
    private function requireLive()
    {
        if ($this->poolState == self::Pool_Cached) {
            $this->loadLive();
            return $this->requireLive();
        }

        if ($this->poolState == self::Pool_Error) {
            $err = "弹幕池错误，不能完成请求";
            FB::Error($err);
            throw new Exception($err);
        }
    }
    
    private function loadCached()
    {
        if ($this->poolState != self::Pool_Cached) {
            $err = "不能从{$this->poolState}变更为Cached";
            FB:Error($err);
            throw new Exception($err);
        }

        if (!DMFConfig::CMT_UsePoolCache) {
            $this->loadLive();
        }

        $this->storage = 
            CommentPoolStorage::GetStorage($this->group, $this->poolId,
                CommentPoolStorageType::Cached);
        
        $this->xmlobj = $this->storage->Get();
        
        if ($this->xmlobj === FALSE) {
            $this->loadLive();
        }
    }
    
    private function loadLive()
    {
        $this->storage = 
            CommentPoolStorage::GetStorage($this->group, $this->poolId,
                DMFConfig::CMT_PoolStorage);
        list($state, $this->xmlobj) = $this->storage->Get();
        if ($state == TRUE) {
            $this->poolState = self::Pool_Live;
        } else {
            $this->poolState = self::Pool_Error;
        }
    }
    
}