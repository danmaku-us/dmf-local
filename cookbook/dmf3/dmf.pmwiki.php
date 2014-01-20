<?php if (!defined('PmWiki')) exit();
define("DMF_DWP_PATH", DMF_ROOT_PATH."/pmwiki-plugins");
//加载PmWiki插件
include_once(DMF_DWP_PATH."/adddeleteline2.php");
include_once(DMF_DWP_PATH."/HtmlMarkup.php");
include_once(DMF_DWP_PATH."/mkexpext.php");
include_once(DMF_DWP_PATH."/newpageboxplus.php");
$XESTagAuth = 'edit';
include_once(DMF_DWP_PATH."/tagpages.php");
include_once(DMF_DWP_PATH."/QueryExpr.php");

$HandleAuth['xmlread'] = 'read';
$HandleAuth['xmledit'] = 'edit';
$HandleAuth['xmladmin'] = 'admin';
if ($LOCALVERSION) {
	$HandleAuth['dmpost'] = 'edit';
} else {
	$HandleAuth['dmpost'] = 'admin`';
}

//页面加载
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

//上传
$EnableUpload = 1;
$UploadMaxSize = 1000000;
$EnableUploadVersions=1;
SDV($UploadExts['xml'], 'text/xml');

//AllGroupHeader
$GroupHeaderFmt =
  '(:include {$SiteGroup}.AllGroupHeader:)(:nl:)'
  .'(:include {$Group}.GroupHeader:)(:nl:)';



