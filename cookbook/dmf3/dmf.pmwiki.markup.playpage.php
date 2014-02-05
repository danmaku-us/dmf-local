<?php if (!defined('PmWiki')) exit();
//页面加载
Markup_e("PlayerPageLoader", 'fulltext',
    '/DMFPLAYERDATA_(.+)_DMFPLAYERDATA/ms',
    'DMF_PlayerPageLoader($m[1])');

function DMF_PlayerPageLoader($jsonText) {
    global $pagename;
    $json = json_decode($jsonText, true);
    
    if ($json === null) 
        return Keep("json解码失败，请手动修正错误");
        
        
    $partidx =
        array_key_exists('part', $_GET)
        ? intval($_GET['part'])
        : null;
    $uplayer =
        array_key_exists('player', $_GET)
        ? strval($_GET['player'])
        : null;
    $config = new VideoInfo($json, $pagename, $uplayer, $partidx);
    $info = LoaderGenerateConfig($config);
    return Keep($info->ToString());
}

//生成配置文件
//格式(action, name, data = array())
function LoaderGenerateConfig(VideoInfo $videocfg)
{
    global $isLocalVersion;

    $pagename = $videocfg->pagename;
    $group    = $videocfg->group;
    $gcfg     = GroupConfigManager::Get($group);
        
    $xtpl = new XTemplateHelper(DMF_ROOT_PATH.'/playpage.tmpl');
    
    //来源
    if ($isLocalVersion) {
        $xtpl->SetNull('main.source');
    } else {
        $xtpl->Parse('main.source', array('SOURCE' => $videocfg->srclink));
    }

    //显示tag
    //以后再改 
    $xtpl->Assign('TAGS',strip_tags(MarkupToHTML($pagename, '(:includeTag:)'), "<a>") );
    if (CondAuth($pagename, 'edit')) {
        $xtpl->Parse("main.tagListEditable");
    } else {
        $xtpl->Parse("main.tagListNormal");
    }

    //调试信息
    $GLOBALS['MessagesFmt'][] = 
        sprintf("%s -> %s(%s)",
            $videocfg->GetCurrentPlayer()->desc,
            $videocfg->videotype,
            $videocfg->GetCmtId());
    $xtpl->Parse("main.messages", array('MESSAGES' => MarkupToHTML($pagename, "(:messages:)")));


    //调用播放器
    $player = $videocfg->GetCurrentPlayer();
    $playerParams = $gcfg->GenerateFlashVarArr($videocfg)->ToArray();
    foreach ($playerParams as $name => $value) {
        $xtpl->Parse("main.FlashVars", array('FLASHVARS' => array("Name" => $name, "Value" => $value)));
    }
    $xtpl->Parse("main.PlayerLoader", array(
        "URL"    => $player->url,
        "HEIGHT" => $player->height,
        "WIDTH"  => $player->width));


    //播放器选择
    $pinfo = $videocfg->GetPlayerInfo();
    $xtpl->Parse('main.PlayerLoaderCurrent',
            array('NAME' => $pinfo['current']->desc));

    $isAdmin = CondAuth($pagename, 'admin');
    foreach ($pinfo['others'] as $id => $player) {
        $arr = array(
            'Name' => $player->desc,
            'URL'  => $player->url,
            'SetDefaultUrl' => "?Player={$id}?&action=setdef");
       $xtpl->Parse(
                ($isAdmin)
                    ? "main.PlayerLoaderAdmin"
                    : "main.PlayerLoaderNormal",
                array('PLAYER' => $arr));
    }

    //分P信息
    $partText = MarkupToHTML($pagename, RetrieveAuthSection($pagename, '#partinfo#partend'));
    $linkedPartText = preg_replace(
            "|<dt>P([0-9]+)</dt>|",
            "<dt><a class='urllink' href='?Part=$1'>P$1</a></dt>",
            $partText);
    if (!empty($linkedPartText)) {
        $xtpl->Parse("main.PARTDATA", array('PARTTEXT' => $linkedPartText));
    }

    //评论&描述
    $descText = MarkupToHTML($pagename, RetrieveAuthSection($pagename, '#comment#commentend'));
    $xtpl->Parse("main.DESC", array('DESCTEXT' => $descText));

    //弹幕控制
    $xtpl->Assign('GROUP'      , $gcfg->GetGroupName());
    $xtpl->Assign('DANMAKUID'  , $videocfg->GetCmtId());
    $xtpl->Assign('SUID'       , $gcfg->GetPrefix()   );
    $xtpl->Parse("main.DanmakuBar.Script");
    //下载
    foreach ($gcfg->GetCommentFormats() as $format) {
        $xtpl->Parse("main.DanmakuBar.Download.Format", array('FORMAT' => $format));
    }
    $xtpl->Parse("main.DanmakuBar.Download");

    //刷新播放器
    $xtpl->Parse("main.DanmakuBar.Refresh");

    //杂项
    if (CondAuth($pagename, 'edit')) {
        $xtpl->Parse("main.DanmakuBar.NewLine");
        $xtpl->Parse("main.DanmakuBar.Upload");
        $xtpl->Parse("main.DanmakuBar.DynamicPool");
        if ($videocfg->IsMultiPart()) $xtpl->Parse("main.DanmakuBar.PageOperation");
        $xtpl->Parse("main.DanmakuBar.PoolOperation");
    }
    $xtpl->Parse("main.DanmakuBar");
    $xtpl->Parse("main");

    return $xtpl;
}
