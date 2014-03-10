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
            $type = (string)$node['pooltype'];
            if (!empty($type)) {
                return $type == $typeId;
            } else {
                return InternalPoolType::DynId == $typeId;
            }
        };
    }
    
    public function Match(SimpleXMLElement $xmlobj)
    {
        $result = array();
        foreach ($xmlobj->comment as $commentNode) {
            if ($this->MatchNode($commentNode)) {
                $result[] = $commentNode;
            }
        }
        return $result;
    }
    
    public function MatchNode(SimpleXMLElement $node)
    {
        foreach ($this->funcs as $func) {
            if (!$func($node)) {
                return false;
            }
        }
        return true;
    }
}