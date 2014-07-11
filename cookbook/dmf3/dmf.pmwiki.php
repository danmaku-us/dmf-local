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

SDV($WikiTitle, "弹幕塚");
SDV($PageLogoUrl, "$ScriptUrl/pub/dmf/logo.jpg");

$HandleAuth['xmlread'] = 'read';
$HandleAuth['xmledit'] = 'edit';
$HandleAuth['xmladmin'] = 'admin';
if ($LOCALVERSION) {
	$HandleAuth['dmpost'] = 'edit';
} else {
	$HandleAuth['dmpost'] = 'admin`';
}

include_once(DMF_ROOT_PATH."/dmf.pmwiki.markup.sidebar.php");
include_once(DMF_ROOT_PATH."/dmf.pmwiki.markup.playpage.php");
include_once(DMF_ROOT_PATH."/dmf.pmwiki.cmtpoolgroup.php");

//上传
$EnableUpload = 1;
$UploadMaxSize = 1000000;
$EnableUploadVersions=1;
SDV($UploadExts['xml'], 'text/xml');

//历史控制
SDV($DiffKeepDays, 7);
//AllGroupHeader
$GroupHeaderFmt =
    '(:include {$SiteGroup}.AllGroupHeader:)(:nl:)'
    .'(:include {$Group}.GroupHeader:)(:nl:)';
  
$HTMLHeaderFmt['javascripts'] = 
    "\n<script type=\"text/javascript\" src=\"/pub/min/?b=pub/dmf&amp;f="
    ."jquery-1.10.1.min.js,swfobject.js"
    ."\"></script>\n";

if ($action == "browse") {
    try {
        $DMF_g = PageVar($pagename, '$Group');
        $DMF_config = GroupConfigManager::GetInstance()->$DMF_g;
        foreach ($DMF_config->GetReferencedJS() as $DMF_jsfp) {
            $HTMLHeaderFmt['javascripts'] .=
                "<script type=\"text/javascript\" src=\"/{$DMF_jsfp}\"></script>\n";
        }
    } catch (Exception $e) {
        unset($DMF_g, $DMF_config, $DMF_jsfp);
    }
    unset($DMF_g, $DMF_config, $DMF_jsfp);
}

