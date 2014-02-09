<?php if (!defined('PmWiki')) exit();
abstract class PlayerInterface extends K_Controller {
    //通用的弹幕读取方式
    abstract protected function cmtload($group, $cmtid, $format);
    
    //通用的弹幕写入方式
    abstract protected function cmtpost($group, $cmtid);
}