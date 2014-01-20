<?php if (!defined('PmWiki')) exit();
define("DMF_ROOT_PATH", __DIR__);
define("DMF_PUB__PATH", "./pub/dmf/");

include_once(DMF_ROOT_PATH."/include/FirePHP/fb.php");
include_once(DMF_ROOT_PATH."/dmf.config.php");
if (file_exists(DMF_ROOT_PATH."dmf.version.php")) 
    include_once(DMF_ROOT_PATH."dmf.version.php");
    
//设置自动加载器
function __autoload($class)
{
    $parts = explode('\\', $class);
    require end($parts) . '.php';
}

$HandleAuth['xmlread'] = 'read';
$HandleAuth['xmledit'] = 'edit';
$HandleAuth['xmladmin'] = 'admin';
if ($LOCALVERSION) {
	$HandleAuth['dmpost'] = 'edit';
} else {
	$HandleAuth['dmpost'] = 'admin`';
}


Markup("PlayerPageLoader", 'directives',
    '/DMFPLAYERDATA_(.*)_DMFPLAYERDATA/ms',
    'DMF_PlayerPageDisplay()');

function PlayerPageLoader($jsonText) {
    $json = json_decode($jsonText);
    
    if ($json === null) {
        //加载失败
        //来个错误页？
    } else {
        return Keep(LoaderGenerateHTML($json));
    }
}

function LoaderGenerateHTML($json) {
    
}

include_once(DMF_ROOT_PATH."/dmf.pmwiki.php");
include_once(DMF_ROOT_PATH."/mvc/dmf.mvc.php");