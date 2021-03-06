<?php if (!defined('PmWiki')) exit();
libxml_use_internal_errors(true);
define("DMF_ROOT_PATH", __DIR__."/dmf3");
define("DMF_PUB__PATH", "pub/dmf");

//TODO:只在admin权限下加载
include_once(DMF_ROOT_PATH."/includes/FirePHP/FirePHP.class.php");
include_once(DMF_ROOT_PATH."/includes/FirePHP/fb.php");
include_once(DMF_ROOT_PATH."/dmf.config.php");
if (file_exists(DMF_ROOT_PATH."/dmf.version.php")) 
    include_once(DMF_ROOT_PATH."/dmf.version.php");
    
//设置自动加载器
spl_autoload_register(function ($class) {
    $p = DMF_ROOT_PATH."/includes/{$class}.php";
    if (file_exists($p)) 
        include($p);
});

include_once(DMF_ROOT_PATH."/dmf.pmwiki.php");

//加载MVC
if ( !(bool)preg_match("/^\/([A-Z0-9\xa0-\xff\?].*)/", $_SERVER['REQUEST_URI'])
      && !($_SERVER['REQUEST_URI'] == "/") ) {
    $pagename = $_REQUEST['n'] = $_REQUEST['pagename'] = 'Main/HomePage';
    $action = 'mvc';
    include_once(DMF_ROOT_PATH."/mvc/dmf.mvc.php");
}

if (file_exists(DMF_ROOT_PATH."/dmf.test.php")) 
    include_once(DMF_ROOT_PATH."/dmf.test.php");
