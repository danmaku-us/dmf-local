<?php if (!defined('PmWiki')) exit();
abstract class InternalPoolType extends BasicEnum {
    const All = "InternalPool_All";
    const Sta = "InternalPool_Sta";
    const Dyn = "InternalPool_Dyn";
    
    const DynId = "0";
    const StaId = "1";
    
    public static function ToId($pooltype)
    {
        switch ($pooltype) {
            case self::Sta:
                return self::StaId;
            case self::Dyn:
                return self::DynId;
            default:
                FB::Error("Unknown type {$pooltype}");
        }
    }

    public static function FromId($pooltypeid)
    {
        switch ($pooltypeid) {
            case self::StaId:
                return self::Sta;
            case self::DynId:
                return self::Dyn;
        }
    }
    
}