<?php if (!defined('PmWiki')) exit();
class BilibiliCommonInterface extends PlayerInterface{
    protected function cmtload($group, $cmtid, $format) {
    
    }
    protected function cmtpost($group, $cmtid) {
    
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
            CommentPool::CanWrite($group, $poolId) ?
            BilibiliAuthLevel::Danmaku_VIP :
            BilibiliAuthLevel::GUEST       ;

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