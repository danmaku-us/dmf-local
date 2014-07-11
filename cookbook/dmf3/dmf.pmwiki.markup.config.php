<?php

if (!defined('PmWiki'))
    exit();

Markup_e("ConfigPageLoader", 'directives',
    '/ConfigPageLoader_CONFIRM_ConfigPageLoader/', "Keep(ConfigHTML())");

function GetAllDMFGroups()
{
    $arr = array();
    $manager = GroupConfigManager::GetInstance();
    foreach (listpages('/.*\.GroupFooter/') as $pagename) {
        $groupname = substr($pagename, 0, -12);
        if ($manager->IsDMFGroup($groupname)) {
            $arr[] = $groupname;
        }
    }
    return $arr;
}

function GetAllGroupConfigs()
{
    $classNames = get_declared_classes();
    $arr = array();
    foreach ($classNames as $className) {
        if (is_subclass_of($className, 'GroupConfig')) {
            $arr[] = $className;
        }
    }
    return $arr;
}

//生成配置文件
//格式(action, name, data = array())
function ConfigHTML()
{
    $xtpl = new XTemplateHelper(DMF_ROOT_PATH . '/res/configpage.tmpl');
    $groups = GetAllDMFGroups();
    $manager = GroupConfigManager::GetInstance();

    foreach ($groups as $group) {
        $cfg = $manager->$group;
        $xtpl->Parse('main.GroupConfiguration.Show.GroupItem', array(
            'GroupName' => $cfg->GetGroupName(),
            'ClassName' => get_class($cfg),
            'JsonPath' => $cfg->GetConfigPath()));
    }
    $xtpl->Parse('main.GroupConfiguration.Show');

    foreach (GetAllGroupConfigs() as $configClassName) {
        $xtpl->Parse('main.GroupConfiguration.Add.Item', array('Name' => $configClassName));
    }
    $xtpl->Parse('main.GroupConfiguration.Add');
    $xtpl->Parse('main.GroupConfiguration');

    $pm = PlayerManager::GetInstance();

    foreach ($pm->GetPlayers() as $player) {
        $arr2 = array(
            'Group' => $player->group,
            'PlayerId' => $player->id,
            'LocalVer' => $player->version,
            'ServerVer' => '这功能还没写');

        $xtpl->Parse('main.PlayerConfiguration.Show.Player', $arr2);
        //$xtpl->SetNull('main.PlayerConfiguration.Show.Player.Update');
    }
    $xtpl->Parse('main.PlayerConfiguration.Show');

    foreach ($groups as $group) {
        $default = $pm->GetDefault($group);
        foreach ($pm->GetPlayers($group) as $player) {
            $arr = array('PlayerId' => $player->id, 'Selected' => "");
            if ($player == $default)
                $arr['Selected'] = 'selected="selected"';
            $xtpl->Parse('main.PlayerConfiguration.Set.Group.Player', $arr);
            $arr['PlayerId'] = 'bi20121220';
            $xtpl->Parse('main.PlayerConfiguration.Set.Group.NewPlayer', $arr);
        }
        $xtpl->Parse('main.PlayerConfiguration.Set.Group');
    }
    $xtpl->Parse('main.PlayerConfiguration.Set');
    $xtpl->Parse('main.PlayerConfiguration');
    $xtpl->Parse('main');

    return $xtpl->ToString();
}
