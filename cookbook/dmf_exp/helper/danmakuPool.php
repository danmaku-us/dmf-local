<?php if (!defined('PmWiki')) exit();

function GetPool($group, $dmid, $pool, $loadMode = LoadMode::lazy) {
    $group = Utils::GetGroup($group);
    if ($group === FALSE) {
        Utils::WriteLog('danmakuPool#GetPool()', "{$group} :: {$dmid} ::{$pool}:: 找不到指定组");
        return false;
    }
    return new DanmakuPoolBase($group, $dmid, $pool, $loadMode);
}

//基本无用
function StrToPool($str) {
    switch (strtolower($str)) {
        case "static"  :
            return PoolMode::S;
        case "dynamic" :
            return PoolMode::D;
        case "all"     :
            return PoolMode::A;
        default        :
            Utils::WriteLog('danmakuPool#StrToPool()', "找不到指定弹幕池{$str}");
            die($str);//Fix me
    }
}

function XmlAuth($group, $dmid, $auth) {
    $pn = Utils::GetDMRPageName($dmid, Utils::GetGroup($group));
    switch ($auth) {
        case XmlAuth::read:
            return CondAuth($pn, 'xmlread');
            break;
        case XmlAuth::edit:
            return CondAuth($pn, 'xmledit');
            break;
        case XmlAuth::admin:
            return CondAuth($pn, 'xmladmin');
            break;
    }
}