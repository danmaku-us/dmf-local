<?php if (!defined('PmWiki')) exit();
class XMLHelper
{
    private static function GetError($error)
    {
        $return = PHP_EOL;
        switch ($error->level) {
            case LIBXML_ERR_WARNING:
                $return .= "Warning $error->code<: ";
                break;
            case LIBXML_ERR_ERROR:
                $return .= "Error $error->code: ";
                break;
            case LIBXML_ERR_FATAL:
                $return .= "Fatal Error $error->code: ";
                break;
        }
        $return .= trim($error->message);
        if ($error->file) {
            $return .=    " in $error->file";
        }
        $return .= " on line $error->line".PHP_EOL;

        return $return;
    }

    public static function GetErrors() {
        $errors = libxml_get_errors();
        $errorText = "";
        foreach ($errors as $error) {
            $errorText .= PHP_EOL . self::GetError($error);
        }
        libxml_clear_errors();
    }
    
}