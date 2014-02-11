<?php if (!defined('PmWiki')) exit();
abstract class CommentFormat extends BasicEnum {
    const D      = "XML_D";
    const DATA   = "XML_Data";
    const RAW    = "XML_DMF";
    const COMMENT= "XML_Comment";
    const ACFJSON= "JSON_Acfun";
    const DMF    = 'XML_DMF';
}