<?php if (!defined('PmWiki')) exit();

function GetAllDMFGroups() {
    $arr = array();
    $manager = ConfigManager::GetInstance();
    foreach (listpages('/.*\.GroupFooter/') as $pagename) {
        $groupname = substr($pagename, 0, -12);
        $manager->IsDMFGroup($groupname);
        $arr[] = $groupname;
    }
    return $arr;
}




//生成配置文件
//格式(action, name, data = array())
function LoaderGenerateConfig()
{
    global $isLocalVersion;

    return $config;
}
