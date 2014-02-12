<?php if (!defined('PmWiki')) exit();
final class CommentItem
{
    private $cmtId;
    private $poolId;
    private $sendTime;
    private $user;
    
    private $text;
    
    private $playTime;
    private $mode;
    private $fontsize;
    private $color;
    
    private $attrExt;

    public function CommentItem() {}
    
    public function FromXMLNode(SimpleXMLElement $xml) {
        $this->cmtId    = (string) $xml['cmtid'];
        $this->poolId   = (string) $xml['poolid'];
        $this->sendTime = (string) $xml['sendtime'];
        $this->user     = (string) $xml['user'];
        
        $this->text     = (string) $xml->text;
        
        $this->playTime = (string) $xml->attr['playtime'];
        $this->mode     = (string) $xml->attr['mode'];
        $this->fontSize = (string) $xml->attr['fontsize'];
        $this->color    = (string) $xml->attr['color'];
        
        //TODO ： unused attr;
        $this->attrExt  = $xml->attributes();
        
    }
    
    public function ToXMLNode(SimpleXMLElement &$xml) {
        $xml->addChild('comment');
        $cmt->addAttribute('cmtid'      , $this->cmtId      );
        $cmt->addAttribute('poolid'     , $this->poolId     );
        $cmt->addAttribute('sendtime'   , $this->sendTime   );
        $cmt->addAttribute('user'       , $this->user       );
        
        $attr = $cmt->addChild('attr');
        $attr->addAttribute('playtime'  , $this->playTime   );
        $attr->addAttribute('mode'      , $this->mode       );
        $attr->addAttribute('fontsize'  , $this->fontSize   );
        $attr->addAttribute('color'     , $this->color      );
        
        $cmt->addChild('attrext');
        
        $cmt->addChild('text', 
            htmlspecialchars($this->text, ENT_NOQUOTES));
    }
    
    public function __get($name) {
        return $this->$name;
    }
    
}