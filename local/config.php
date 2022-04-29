<?php if (!defined('PmWiki')) exit();

$AuthUser['Admin'] = crypt('/n/n/n/n');
$AuthUser['@admins'] = array('Admin', 'dm.mikufans@gmail.com');
$DefaultPasswords['edit'] = 'id:*';
$DefaultPasswords['admin'] = array('@admins');
$DefaultPasswords['upload'] = array('@admins');
$HandleAuth['delete'] = 'admin';

include_once("$FarmD/cookbook/openid/KAuth.php");
include_once("$FarmD/scripts/authuser.php");


$EnablePostAttrClearSession = 0;
$Skin = 'pmwikiGPT';
$MarkupCss = true;
$EnableIMSCaching = 0;
$EnableRelativePageVars = 1;
$EnableUndefinedTemplateVars = 0;
$EnablePostAuthorRequired = 1;
$EnableDiffInline = 0;
$FarmPubDirUrl = 'http://'.$_SERVER['HTTP_HOST'].'/pub';
$EnablePathInfo = 1;
$ScriptUrl = "http://".$_SERVER['HTTP_HOST'];
$HTMLPNewline = '<br />'; 
$SearchPatterns['default'][] = '!^PmWiki\\.!';

//调试
if (CondAuth($pagename, 'admin')) $EnableDiag = 1;
//添加属性
include_once("$FarmD/scripts/forms.php");
$InputAttrs[] = 'onclick';
$InputAttrs[] = 'onsubmit';
$InputAttrs[] = 'onchange';
$InputAttrs[] = 'target';
$InputAttrs[] = 'onkeyup';
$InputAttrs[] = 'maxlength';

# 页面储存
$WikiDir = new PageStore('$FarmD/wiki.d/{$Group}/{$FullName}');
$WikiLibDirs = array( &$WikiDir,
	new PageStore('$FarmD/pub/dmf/dmflib.d/{$Group}/$FullName'),
	new PageStore('$FarmD/wikilib.d/$FullName')
);
# END

# 附件
$EnableUpload = 1;
$UploadMaxSize = 1000000;
$EnableUploadVersions=1;
# END

# i18n
include_once($FarmD.'/scripts/xlpage-utf-8.php');
XLPage('ZhCn','PmWikiZhCn.XLPage');
if(date_default_timezone_get() != "Asia/Shanghai") date_default_timezone_set("Asia/Shanghai");
# END

include_once($FarmD.'/cookbook/expirediff.php');
include_once($FarmD.'/scripts/guiedit.php');
include_once($FarmD.'/cookbook/bbcode.php');
include_once($FarmD.'/cookbook/pagetoc.php');
include_once($FarmD.'/cookbook/deletepage.php');
include_once($FarmD."/cookbook/PageGenerationTime.php");
include_once($FarmD."/cookbook/CreatedBy.php");

if ($action=='diff') {
    $DiffCountPerPage = 10;
    include_once("$FarmD/cookbook/limitdiffsperpage2.php");
}

$WikiStyleCSS[] = 'line-height';

if (empty($Author) && !empty($AuthId)) $Author = $AuthId;
$RecentChangesFmt = array(
  '$SiteGroup.AllRecentChanges' => 
    '* [[{$Group}.{$Name}]]  . . . $CurrentTime $[by] $Author: [=$ChangeSummary=]',
  '$Group.RecentChanges' =>
    '* [[{$Group}/{$Name}]]  . . . $CurrentTime $[by] $Author: [=$ChangeSummary=]');

//include_once("$FarmD/scripts/urlapprove.php");
$UrlLinkFmt = "<a class='urllink' href='\$LinkUrl' >\$LinkText</a>";

#include($FarmD."/cookbook/dmf3.php");