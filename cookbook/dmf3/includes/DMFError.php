<?php if (!defined('PmWiki')) exit();
abstract class DMFError extends BasicEnum {
    const NO_ERROR              =  0;
    const POOL_WRITE_AUTH_FAIL  = -1;
    const POOL_PAGE_WRITE_FAIL  = -2;
    const POOL_XML_BAD_FORMAT   = -3;
    const POOL_XML_BAD_SYNTAX   = -4;
    const POOL_XML_DUPLICATE_ID = -5;
    const POOL_CACHE_WRITE_FAIL = -6;
}