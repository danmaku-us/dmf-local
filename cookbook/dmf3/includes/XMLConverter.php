<?php if (!defined('PmWiki')) exit();
abstract class XMLConverter {
    
    public static function ToInternalFormat(SimpleXMLElement $xmlobj) {
        $format = CommentFormat::GuessFormat($xmlobj);
        $xsl = self::GetXSLObj($format, CommentFormat::DMF);
        
        $proc = new XSLTProcessor();
        $proc->importStyleSheet($xsl);
        return $proc->transformToXML($XML);
    }
    
    public static function FromInternalFormat(SimpleXMLElement $XML, $format)
    {
        $xsl = self::GetXSLObj(CommentFormat::DMF, $format);

        $proc = new XSLTProcessor();
        $proc->importStyleSheet($xsl);
        return $proc->transformToXML($XML);
    }

    private static function GetXSLObj($fromFormat, $toFormat)
    {
            $from = CommentFormat::fromString($fromFormat);
            $to   = CommentFormat::fromString($toFormat);

            if ($from == 'dmf') {
                $file = DMF_ROOT_PATH."/res/xml_{$to}_from_dmf.xsl";
            } else if ($to == 'dmf') {
                $file = DMF_ROOT_PATH."/res/xml_{$from}_to_dmf.xsl";
            }

            if ( empty($file) || !file_exists($file) ) {
                FB::Error("找不到适合的xsl {$fromFormat} => {$toFormat}");
                throw new Exception("找不到适合的xsl {$fromFormat} => {$toFormat}");
            } else {
                return simplexml_load_file($file);
            }
    }
}