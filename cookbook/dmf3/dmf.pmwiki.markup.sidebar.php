<?php

if (!defined('PmWiki'))
    exit();
Markup_e("SideBarLoader", 'directives',
    '/DMFSideBarLoader_CONFIRM_DMFSideBarLoader/', "DMF_SideBarLoader()");

function DMF_SideBarLoader()
{
    $manager = GroupConfigManager::GetInstance();
    $sb = "";
    foreach ($manager->ToArray() as $Group => $Config) {
        $sb .= "* &nbsp;[[{$Group}/HomePage|{$Group} {$Config->GetDesc()}]]\r\n";
    }
    return $sb;
}
