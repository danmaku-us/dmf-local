<?php if (!defined('PmWiki')) exit();
abstract class PlayerInterface extends K_Controller {
    //$extId = {Group}-{PoolId}

    abstract function cmtload($extId);
    
    abstract function cmtpost($extId);

    //$pagename :: 目标

    protected function _cmtload($group, $poolId, $format)
    {
        
        $pool = new CommentPool($group, $poolId);
        $xmlstr = XMLConverter::FromInternalFormat($pool->GetXMLObj(), $format);
        die($xmlstr);
    }
    
    //通用的弹幕写入方式
    protected function _cmtpost($pagename, $cmt)
    {
    }
}