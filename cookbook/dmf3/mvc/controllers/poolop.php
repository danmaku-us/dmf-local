<?php if (!defined('PmWiki')) exit();
//PoolOp / command / group / dmid / params
//post move clear valid download

//弹幕操作接口
//返回HTML
class PoolOp extends K_Controller {
    const GoBack = "<script language='javascript'> setTimeout('history.go(-1)', 2000);</script>两秒后传送回家";
        
    public function PoolOp() {
        parent::__construct();
    }
    
	public function clear($group, $dmid, $pool)
	{
        die('{"code":-1, "errormsg":"errormsg", "msg":"反正不科学"}');
	}
	
	
	public function downloadxml($group, $dmid) // GET : format attach split
	{
        return '<i/>';
	}
	
	public function post($group, $dmid) // GET : pool append
	{
        die('{"code":-1, "errormsg":"errormsg", "msg":"反正不科学"}');
	}
	
	public function merge($group, $dmid, $from, $to)
	{
        die('{"code":-1, "errormsg":"errormsg", "msg":"反正不科学"}');
	}
	
	public function validate($group, $dmid, $pool = 'dynamic')
	{
        die('{"code":-1, "errormsg":"errormsg", "msg":"反正不科学"}');
	}
	
	public function randomize($group, $dmid, $pool = 'dynamic')
	{
        die('{"code":-1, "errormsg":"errormsg", "msg":"反正不科学"}');
	}

	
	private function display($msg)
	{
        $GLOBALS['MessagesFmt'] = $msg;
        $this->DisplayView('pmwiki_view', array('name' => 'API.XMLTool'));
	}
	
}
