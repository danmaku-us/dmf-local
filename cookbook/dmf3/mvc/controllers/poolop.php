<?php if (!defined('PmWiki')) exit();
//PoolOp / command / group / dmid / params
//post move clear valid download
//TODO:
//弹幕操作接口
//返回JSON
class PoolOp extends K_Controller {
    const GoBack = "<script language='javascript'> setTimeout('history.go(-1)', 2000);</script>两秒后传送回家";
        
    public function PoolOp() {
        parent::__construct();
    }
    
	public function clear($group, $poolId, $pooltype)
	{
        $pool = new CommentPool($group, $poolId);
        $pool->Clear($pooltype);
        $pool->Save(true);
        $this->returnJson(0, "操作成功完成", "");
	}
	
	// GET : format attach split
	public function downloadxml($group, $poolId)
	{
        $format = CommentFormat::fromString(
                    basename($this->Input->Get->format) );
        $attach = basename($this->Input->Get->attach) == "true";
        $splitSize  = intval($this->Input->Get->split);
        
        if ($format === FALSE) {
            throw new Exception("未知目标格式", -1);
        }
        
        $pool = new CommentPool($group, $poolId);
        
        if ($splitSize) {
            //分段
        } else {
            //不分段
            die(XMLConverter::FromInternalFormat($pool->GetXMLObj,
                                                    $format));
        }
	}
	
	//TODO:
	public function post($group, $poolId) //GET : pool append
	{
        $pool = InternalPoolType::fromString(
                    basename($this->Input->Get->pool));
        $append  = intval($this->Input->Get->append);
        
        if ($pool === InternalPoolType::All) {
            throw new Exception("非法弹幕池", -1);
        }
        
        $pool = new CommentPool($group, $poolId);
        
        if ($splitSize) {
            //分段
        } else {
            //不分段
            die(XMLConverter::FromInternalFormat($pool->GetXMLObj,
                                                    $format));
        }
	}
	
    //TODO:
	public function merge($group, $poolId, $from, $to)
	{
        $pool = new CommentPool($group, $poolId);
        $pool->Move($from, $to);
        $pool->Save(true);
        $this->returnJson(0, "操作成功完成", "");
	}

    //TODO:
	public function validate($group, $poolId, $pool = 'dynamic')
	{
        $this->returnJson(-1, "未实现", "");
	}

    //TODO:
	public function randomize($group, $poolId, $pool = 'dynamic')
	{
        $this->returnJson(-1, "未实现", "");
	}
    
    private function errorHandler($errno, $errstr, $errfile, $errline)
    {
        if (0 == error_reporting()) {
            return;
        }
        throw new ErrorException($message, 0, $code, $file, $line);
    }
    
    private function exceptionHandler($e)
    {
        $code = $e->getCode();
        $errormsg = $e->getMessage();
        $msg  = $msg . PHP_EOL.
                $e->getFile(). " : "    .
                $e->getLine(). PHP_EOL  .
                $e->getTraceAsString();
        $msg = nl2br($msg, true);
        
        $this->returnJson($code, $errormsg, $msg);
    }
	
	private function returnJson($code, $errormsg, $msg)
	{
        $arr = array(
            'code'      => $code,
            'errormsg'  => nl2br($errormsg, true),
            'msg'       => nl2br($msg, true));
            
        die(json_encode($arr, JSON_FORCE_OBJECT));
	}
	
}
