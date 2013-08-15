<?php if (!defined('PmWiki')) exit();
$route['(^member\/dmm)'] = empty($_REQUEST['mode']) ? "bpi/error" : "bpi/".$_REQUEST['mode'];
$route['(^bpi\/dmm)'] = empty($_REQUEST['mode']) ? "bpi/error" : "bpi/".$_REQUEST['mode'];
$route['(^b3pi\/dmm)'] = empty($_REQUEST['mode']) ? "b3pi/error" : "b3pi/".$_REQUEST['mode'];
$route['(^b3pi\//dmm)'] = empty($_REQUEST['mode']) ? "b3pi/error" : "b3pi/".$_REQUEST['mode'];
$route['(^newflvplayer\/pad\.xml)'] = "bpi/bpad" ;
$route['(^poolop\/loadxml\/twodland1.*)'] = "poolop/loadxml/twodland1/{$_REQUEST['vid']}";
$route['(^dpi\/getconfigxml\/([^\/]+)\/([^\/]*))'] = "dpi/getconfigxml/$2/$3/";

foreach ($route as $k => $v) {
    Router::addRule($k, $v);
}