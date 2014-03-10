<?php if (!defined('PmWiki')) exit();
final class DMFConfig
{
    const LocalVersion = true;
    const CMT_CacheDir          = './pub/dmf/commentcache';
    const CMT_PageGroup         = 'DMR';
    const CMT_PageEditForm      = 'DMR.EditForm';
    const CMT_UsePoolCache      = true;
    const CMT_PoolStorage       = CommentPoolStorageType::PmWiki;
    const CMT_PoolReadAuth      = 'read';
    const CMT_PoolEditAuth      = 'edit';
}
