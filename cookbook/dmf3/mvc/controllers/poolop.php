<?php if (!defined('PmWiki')) exit();
//PoolOp / command / group / dmid / params
//post move clear valid download
//TODO:
//弹幕操作接口
//返回HTML
class PoolOp extends K_Controller {
    const GoBack = "<script language='javascript'> setTimeout('history.go(-1)', 2000);</script>两秒后传送回家";
        
    public function PoolOp() {
        parent::__construct();
    }
    
	public function clear($group, $poolId, $pooltype)
	{
        $pool = new CommentPool($group, $poolId);
        $pool->Clear($pooltype);
        echo "清空以后<br />";
        die('{"code":0, "errormsg":"操作成功完成", "msg":""}');
	}
	
	
	public function downloadxml($group, $poolId) // GET : format attach split
	{
        return '<i/>';
	}
	
	public function post($group, $poolId) // GET : pool append
	{
        die('{"code":-1, "errormsg":"errormsg", "msg":"反正不科学"}');
	}
	
	public function merge($group, $poolId, $from, $to)
	{
        die('{"code":-1, "errormsg":"errormsg", "msg":"反正不科学"}');
	}
	
	public function validate($group, $poolId, $pool = 'dynamic')
	{
        die('{"code":-1, "errormsg":"errormsg", "msg":"反正不科学"}');
	}
	
	public function randomize($group, $poolId, $pool = 'dynamic')
	{
        die('{"code":-1, "errormsg":"errormsg", "msg":"反正不科学"}');
	}

	
	private function display($msg)
	{
        $GLOBALS['MessagesFmt'] = $msg;
        $this->DisplayView('pmwiki_view', array('name' => 'API.XMLTool'));
	}
	
}
