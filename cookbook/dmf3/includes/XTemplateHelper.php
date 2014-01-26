<?php if (!defined('PmWiki')) exit();
final class XTemplateHelper
{
    private $config = array();
    private $tmplPath;
    public function __construct($tmplPath){
        $this->tmplPath = $tmplPath;
    }
    
    public function Parse($name, array $data = array())
    {
        $this->config[] = array('parse', $name, $data);
    }
    
    public function SetNull($name)
    {
        $this->config[] = array('null', $name);
    }
    
    public function Assign($name, $value)
    {
        $this->config[] = array('assign', $name, $value);
    }
    
    public function ToString()
    {
        $xtpl = new XTemplate($this->tmplPath);

        foreach ($this->config as $item) {
            $action = $item[0];
            $name   = $item[1];
            $data   = @$item[2];
            switch ($action) {
                case 'null':
                     $xtpl->set_null_block('', $name);
                    break;
                case 'parse':
                    foreach ($data as $k => $v) {
                        $xtpl->assign($k, $v);
                    }
                    $xtpl->parse($name);
                    break;
                case 'assign':
                    $xtpl->assign($name, $data);
                    break;
                default:
                    throw new Exception("未知方法'{$action}'", 1);
                    break;
            }
        }
        
        return $xtpl->text();
    }
    
}