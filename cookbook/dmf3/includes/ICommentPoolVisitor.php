<?php if (!defined('PmWiki')) exit();
interface ICommentPoolVisitor
{
    //public function VisitCommentItem(SimpleXMLElement $elem);
    
    // $pool ( int -> SimpleXMLElement ) []
    // out (int -> SimpleXMLElement) ( output of array_filter)
    public function VisitCommentPool(array $pool);
}