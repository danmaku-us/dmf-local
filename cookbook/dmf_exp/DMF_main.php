<?php if (!defined('PmWiki')) exit();

$WikiTitle = "弹幕塚 ~少壮不努力，一生在内地~";
$PageLogoUrl = "$ScriptUrl/pub/dmf/logo.jpg";

$EnableBlocklist = 10;
$EnableWhyBlocked = 1; 
$EnablePostAuthorRequired = 1;
$DMF_DynamicPairLogging = TRUE;

if (($_GET['action'] != 'setdef') && ($_GET['action'] != 'GetFlvUrl')) {$EnableNotify = 1;}
$NotifySquelch = 60;
$NotifyDelay = 300;
$NotifyItemFmt = " * \$FullName . . . \$PostTime by \$Author : \n \$PageUrl"; 
$EnableNotifySubjectEncode = 1;
$NotifyHeaders = "Content-type: text/plain; charset=$Charset"; 

if ( $action=="sitemap" ) {
	$RssMaxItems=50000;
	$RssSourceSize=0;
	$RssDescSize=0;
	$action="rss";
	SDVA($FeedTrailOpt, array('trail' => $pagename, 'count' => 50000));
}
include_once("$FarmD/scripts/feeds.php");

$VisitorsLoggingDirectory = "./uploads/VisitorsLog";
$VisitorsLoggingFileName = "%Y-%m-%d.txt";
$VisitorsLoggingPurgeAfterDays = 0;
$VisitorsLoggingFormat = '%Date %Time %RemoteAddr:pad %Action:pad '
    . '%HttpHost %WikiGroup.%WikiPage '
    . '"%HttpReferer" "%HttpUserAgent"' . "\n";
$VisitorsLoggingDateFormat = '%Y-%m-%d';
$VisitorsLoggingTimeFormat = '%H:%M:%S';
$VisitorsLoggingIgnoreList = array('127.0.0.1');
include_once("$FarmD/cookbook/visitorslogging1337.php");