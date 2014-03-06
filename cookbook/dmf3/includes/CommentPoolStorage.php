<?php if (!defined('PmWiki')) exit();
//用于从不同的储存路径读取数据

abstract class CommentPoolStorage
{
    const XMLHeader = '<?xml version="1.0" encoding="utf-8"?><DMFCmtPool version="0">';
    const XMLFooter = '</DMFCmtPool>';
    const XMLEmpty  = '<DMFCmtPool />';
    
    protected $group;
    protected $poolId;
    
    protected function __construct($group, $poolId)
    {
        $this->group = $group;
        $this->poolId= $poolId;
    }
    
    abstract public function Get();
    abstract public function Put(SimpleXMLElement $xmlObj, $genHistory = true);
    
    public static function CanRead($group, $poolId)
    {
        $pn = PagedPoolStorage::GetPageName($group, $poolId);
        return CondAuth($pn, 'read');
    }
    
    public static function CanWrite($group, $poolId)
    {
        $pn = PagedPoolStorage::GetPageName($group, $poolId);
        return CondAuth($pn, 'edit');
    }
    
    public static function GetEmptyObj()
    {
        return simplexml_load_string(self::XMLHeader . self::XMLFooter);
    }
    
    protected static function GetErrorObj($errmsg)
    {
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
        return simplexml_load_string($errorXML);
    }
    
    public static function GetStorage($group, $poolId, $type)
    {
        switch ($type) {
            case CommentPoolStorageType::Cached:
                return new CachedPoolStorage($group, $poolId);
            case CommentPoolStorageType::PmWiki:
                return new PagedPoolStorage($group, $poolId);
            case CommentPoolStorageType::SQLite:
                return new SQLitePoolStorage($group, $poolId);
        }
    }
}

final class CachedPoolStorage extends CommentPoolStorage
{
    private $filePath;
    public function CachedPoolStorage($group, $poolId)
    {
        parent::__construct($group, $poolId);
        $this->filePath = $this->GetCahceFilePath();
    }
    
	private function GetCahceFilePath() {
        return DMFConfig::CMT_CacheDir."/{$this->group}/{$this->poolId}";
	}
	
    public function Get()
    {
        if (!file_exists($this->filePath)) {
            return FALSE;
        }

        return simplexml_load_file($this->filePath);
    }
    
    public function Put(SimpleXMLElement $xmlObj, $genHistory = true)
    {
        file_put_contents($this->filePath, $xmlObj->asXML());
    }

}


//静态储存在$page['staticpool']内，无版本控制
final class PagedPoolStorage extends CommentPoolStorage
{
    private $pagename;

    public function PagedPoolStorage($group, $poolId)
    {
        parent::__construct($group, $poolId);
        $this->pagename = self::GetPageName($group, $poolId);
    }

	public static function GetPageName($group, $poolId) {
        $gcfg = GroupConfigManager::Get($group);
        
        $prefix = $gcfg->GetPoolPageNamePrefix();
        return DMFConfig::CMT_PageGroup.".{$prefix}{$poolId}";
	}

    public function Get()
    {
        $page = RetrieveAuthPage($this->pagename, DMFConfig::CMT_PoolReadAuth, FALSE, READPAGE_CURRENT);
        $dyn  = $page['text'];
        $static= $page['staticpool'];
        $xmlobj = simplexml_load_string(self::XMLHeader.$static.$dyn.self::XMLFooter);
        if ($xmlobj !== FALSE) {
            return array(true, $xmlobj);
        } else {
            FB::Error("{$this->pagename}XML格式非法");
            return array(false,
                    CommentPoolStorage::GetErrorObj("{$this->pagename}XML格式非法"));
        }
    }
    
    public function Put(SimpleXMLElement $xmlObj, $genHistory = true)
    {
        $old = $new = RetrieveAuthPage($this->pagename, 
                    DMFConfig::CMT_PoolReadAuth, FALSE, READPAGE_CURRENT);
        $new['text'] = "";
        $new['staticpool'] = "";
        
        foreach ($xmlObj->comment as $node) {
            if ( (string) $node["poolid"] == "0" ) {
                $new['text'] .= $node->asXML();
            } else {
                $new['staticpool'] .= $node->asXML();
            }
        }
        
        if ($genHistory) {
            $ret = UpdatePage($this->pagename, $old, $new);
        } else {
            WritePage($this->pagename, $new);
            $ret = true; // WritePage没有返回码
        }
        
        if ($ret) {
            FB::info("{$this->pagename} 写入成功");
        } else {
            FB::error("{$this->pagename} 写入失败");
        }
        
        return $ret;
    }
}

//TODO:
//TEXT直接储存
// pool        : int id, text group, text poolId, enum poolType, text mtime
// poolHistory : int id, text group, text poolId, enum poolType, text mtime
abstract class SQLitePoolStorage extends CommentPoolStorage
{
}