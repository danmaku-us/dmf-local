<?php if (!defined('PmWiki')) exit();
abstract class BilibiliAuthLevel extends BasicEnum {
    const GUEST         = '9999';
    const LIMITED       = '9999';
    const REGISTERED    = '5000';
    const NORMAL        = '10000,1001';
    const VIP           = '30000,25000';
    const Danmaku_VIP   = '20000';
}