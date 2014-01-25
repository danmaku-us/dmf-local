<?php if (!defined('PmWiki')) exit();
//页面加载
Markup_e("PlayerPageLoader", 'fulltext',
    '/DMFPLAYERDATA_(.+)_DMFPLAYERDATA/ms',
    'DMF_PlayerPageLoader($m[1])');

function DMF_PlayerPageLoader($jsonText) {
    global $pagename;
    $json = json_decode($jsonText, true);
    
    if ($json === null) {
        return Keep("json解码失败，请手动修正错误");
    } else {
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
        return Keep(LoaderGenerateHTML($info));
    }
}


function LoaderGenerateHTML($info)
{
    $xtpl = new XTemplate(DMF_ROOT_PATH.'/playpage.tmpl');

    foreach ($info as $item) {
        $action = $item[0];
        $name   = $item[1];
        $data   = @$item[2];
        switch ($action) {
            case 'null':
                 $xtpl->set_null_block('', $name);
                break;
            case 'parse':
                foreach ($data as $k => $v) {
                    $xtpl->assign($k, $v);
                }
                $xtpl->parse($name);
                break;
            case 'assign':
                $xtpl->assign($name, $data);
                break;
            default:
                throw new Exception("未知方法'{$action}'", 1);
                break;
        }
    }

    return $xtpl->text();
}


//生成配置文件
//格式(action, name, data = array())
function LoaderGenerateConfig(VideoInfo $videocfg)
{
    global $isLocalVersion;

    $pagename = $videocfg->pagename;
    $group    = $videocfg->group;
    $gcfg     = GroupConfigManager::Get($group);

    $config = array();
    $builder = function ($action, $name, $data = array()) use (&$config) {
        switch($action) {
            case 'null':
                $config[] = array($action, $name);
                break;
            case 'parse':
                $config[] = array($action, $name, $data);
                break;
            case 'assign':
                $config[] = array($action, $name, $data);
                break;
            default:
                throw new Exception("未知方法'{$action}'", 1);
                break;
        }
    };

    //来源
    if ($isLocalVersion) {
        $builder('null', 'main.source');
    } else {
        $builder('parse', 'main.source', array('SOURCE' => $videocfg->srclink));
    }

    //显示tag
    //以后再改 
    $builder('assign', 'TAGS',strip_tags(MarkupToHTML($pagename, '(:includeTag:)'), "<a>") );
    if (CondAuth($pagename, 'edit')) {
        $builder('parse', "main.tagListEditable");
    } else {
        $builder('parse', "main.tagListNormal");
    }

    //调试信息
    $GLOBALS['MessagesFmt'][] = 
        sprintf("%s -> %s(%s)",
            $videocfg->GetCurrentPlayer()->desc,
            $videocfg->videotype,
            $videocfg->GetCmtId());
    $builder('parse', "main.messages", array('MESSAGES' => MarkupToHTML($pagename, "(:messages:)")));


    //调用播放器
    $player = $videocfg->GetCurrentPlayer();
    $playerParams = $gcfg->GenerateFlashVarArr($videocfg)->ToArray();
    foreach ($playerParams as $name => $value) {
        $builder('parse', "main.FlashVars", array('FLASHVARS' => array("Name" => $name, "Value" => $value)));
    }
    $builder('parse', "main.PlayerLoader", array(
        "URL"    => $player->url,
        "HEIGHT" => $player->height,
        "WIDTH"  => $player->width));


    //播放器选择
    $pinfo = $videocfg->GetPlayerInfo();
    $builder('parse', 'main.PlayerLoaderCurrent',
            array('NAME' => $pinfo['current']->desc));

    $isAdmin = CondAuth($pagename, 'admin');
    foreach ($pinfo['others'] as $id => $player) {
        $arr = array(
            'Name' => $player->desc,
            'URL'  => $player->url,
            'SetDefaultUrl' => "?Player={$id}?&action=setdef");
        $builder('parse',
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
        $builder('parse', "main.PARTDATA", array('PARTTEXT' => $linkedPartText));
    }

    //评论&描述
    $descText = MarkupToHTML($pagename, RetrieveAuthSection($pagename, '#comment#commentend'));
    $builder('parse', "main.DESC", array('DESCTEXT' => $descText));

    //弹幕控制
    $builder('assign', 'GROUP'      , $gcfg->GetGroupName());
    $builder('assign', 'DANMAKUID'  , $videocfg->GetCmtId());
    $builder('assign', 'SUID'       , $gcfg->GetPrefix()   );

    //下载
    foreach ($gcfg->GetCommentFormats() as $format) {
        $builder('parse', "main.DanmakuBar.Download.Format", array('FORMAT' => $format));
    }
    $builder('parse', "main.DanmakuBar.Download");

    //刷新播放器
    $builder('parse', "main.DanmakuBar.Refresh");

    //杂项
    if (CondAuth($pagename, 'edit')) {
        $builder('parse', "main.DanmakuBar.NewLine");
        $builder('parse', "main.DanmakuBar.Upload");
        $builder('parse', "main.DanmakuBar.DynamicPool");
        if ($videocfg->IsMultiPart()) $builder('parse', "main.DanmakuBar.PageOperation");
        $builder('parse', "main.DanmakuBar.PoolOperation");
    }
    $builder('parse', "main.DanmakuBar");
    $builder('parse', "main");

    return $config;
}
