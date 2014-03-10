<?php if (!defined('PmWiki')) exit();

final class CommentQuery
{
    private $funcs = array();
    
    public function CommentQuery()
    {
    }
    
    public function Ids($ids)
    {
        $this->funcs[] = function ($node) use($ids) {
            return in_array((string)$node['cmtid'], $ids);
        };
    }
    
    public function PoolType($typeId) {
        $this->funcs[] = function ($node) use($typeId) {
            return (string)$node['pooltype'] == $typeId;
        };
    }
    
    public function Match(SimpleXMLElement $node)
    {
        foreach ($this->funcs as $func) {
            if (!$func($node)) {
                return false;
            }
        }
        return true;
    }
}