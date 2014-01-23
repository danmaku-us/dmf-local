<?php if (!defined('PmWiki')) exit();
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