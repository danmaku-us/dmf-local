<?php if (!defined('PmWiki')) exit();
class BilibiliCommonInterface extends PlayerInterface{

    public function cmtload($extId) {
        list($group, $poolId) = explode('-', $extId, 2);
        parent::_cmtload($group, $poolId, CommentFormat::D);
    }
    
    public function cmtpost($extId) {
        list($group, $poolId) = explode('-', $extId, 2);
        $pn = CommentPool::GetPageName($group, $poolId);
    }
    
    //static response
    public function msg()
    {
        $this->DisplayStatic('bilibili_msg.xml');
    }
    
	public function cloudfilter()
	{
        die('{"user":[],"keyword":[]}');
    }
    
    public function bpad()
    {
        $this->DisplayStatic('bilibili_pad.xml');
    }
    
    public function advCmt()
	{
        die("<confirm>1</confirm><hasBuy>true</hasBuy>");
	}
    
    //$id
    public function play()
    {
        list($group, $poolId) = explode('-', $this->Input->Get->id, 2);
        $gcfg = GroupConfigManager::Get($group);
        
        $data = array();
        
        $data['ChatId'] = 
            empty($poolId) ?
            $poolId : 0 ;
            
        $data['AuthLevelString'] = 
            CommentPoolStorage::CanWrite($group, $poolId) ?
            BilibiliAuthLevel::Danmaku_VIP :
            BilibiliAuthLevel::GUEST       ;
        header("Content-Type:text/plain; charset=utf-8");
        $this->DisplayView('bilibili_dad_xml', $data);
    }
    
    public function dad()
    {
        $this->play();
    }
    
    public function dmerror()
    {
        
    }
    
    public function del()
    {
    
    }
    
    public function updcmttime()
    {
    
    }
    
    public function dmm()
    {
    
    }
    
}