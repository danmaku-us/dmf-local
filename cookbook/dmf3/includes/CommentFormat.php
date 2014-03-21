<?php if (!defined('PmWiki')) exit();
abstract class CommentFormat extends BasicEnum {
    const D      = "XML_D";
    const DATA   = "XML_Data";
    const RAW    = "XML_DMF";
    const COMMENT= "XML_Comment_2DLand";
    const ACFJSON= "JSON_Acfun";
    const DMF    = 'XML_DMF';
    
    public static function GuessFormat(SimpleXMLElement $xml)
    {
        switch($xml->getName()) {
            case "DMFComment":
                return self::DMF;
            case "i":
                return self::D;
            case "data":
                return self::DATA;
            case "comments":
                return self::COMMENT;
            default:
                return FALSE;
        }
    }
    
}