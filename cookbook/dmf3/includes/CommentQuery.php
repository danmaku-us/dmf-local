<?php if (!defined('PmWiki')) exit();
//TODO:感觉还是xpath好。。。
final class CommentQuery implements ICommentPoolVisitor
{
    const EQU = 'CommentQueryOperator_EQU';
    const NEQ = 'CommentQueryOperator_NEQ';
    const LSS = 'CommentQueryOperator_LSS';
    const LEQ = 'CommentQueryOperator_LEQ';
    const GTR = 'CommentQueryOperator_GTR';
    const GEQ = 'CommentQueryOperator_GEQ';
    
    const CTS = 'CommentQueryOperator_CTS'; // String contains
    const CTI = 'CommentQueryOperator_CTI'; // String contains i
    
    //function (SimpleXMLElement -> bool)
    private $query = array();
    
    public function __construct()
    {
        $this->query[] = function ($elem) {return true;};
    }
    
    public function PoolId($op, $val);
    public function SendTime($op, $val);
    public function UserContains($val);
    public function UserEquals($val);
    
    public function PlayTime($op, $val);
    public function Mode($op, $val);
    public function FontSize($op, $val);
    public function Color($op, $val);
    
    public function TextContains($str);
    public function TextEquals($str);
    
    public function VisitCommentPool(array $pool)
    {
    }
    
    private function buildQueryNum($name, $op, $val)
    {
        $this->query[] = function ($elem) use ($name, $op, $val) {
            $v = self::getVale($elem, $name);
            return self::num_cond($v, $op, $val);
        };
    }
    
    private function buildQueryStr($name, $op, $val)
    {
        $this->query[] = function ($elem) use ($name, $op, $val) {
            $v = self::getVale($elem, $name);
            return self::str_cond($v, $op, $val);
        };
    }
    
    private static function getValue($elem, $name) {
        switch ($name) {
            case "cmtid":
                return (string) $elem['cmtid'];
            case "poolid":
                return (string) $elem['poolid'];
            case "sendtime":
                return (string) $elem['sendtime'];
            case "user":
                return (string) $elem['user'];
            case "text":
                return (string) $elem->text;
            case "playtime":
                return (string) $elem->playtime;
            case "mode":
                return (string) $elem->mode;
            case "fontsize":
                return (string) $elem->fontsize;
            case "color":
                return (string) $elem->color;
            default:
                FB::fatal("未知数据项{$name}");
                throw new Exception("未知数据项{$name}");
        }
    }
    
    private static function num_cond($var1, $op, $var2) {
        switch ($op) {
            case self::EQU: return $var1 == $var2;
            case self::NEQ: return $var1 != $var2;
            case self::GEQ: return $var1 >= $var2;
            case self::LEQ: return $var1 <= $var2;
            case self::GTR: return $var1 >  $var2;
            case self::LSS: return $var1 <  $var2;
            default:        throw new Exception("Unknown num comp op {$op}";
        }
    }
    
    private static function str_cond($var1, $op, $var2) {
        switch ($op) {
            case self::EQU: return $var1 == $var2;
            case self::NEQ: return $var1 != $var2;
            case self::CTS: return strpos ($var1, $var2) !== FALSE;
            case self::CTI: return stripos($var1, $var2) !== FALSE;
            default:        throw new Exception("Unknown str comp op {$op}";
        }
    }
}