<?php if (!defined('PmWiki')) exit();
//{$playerName}.json
//id = {$FileName}
//desc
//width
//height
//version
final class Player extends ConfigJson
{
	protected $swffile;
	protected $jsonfile;
	protected $id;
    protected $group;
    protected $url;
    
	public function __construct($swffile)
	{
		$this->jsonfile = $jsonFile = substr($swffile, 0, -3)."json";
		$json = json_decode(file_get_contents($jsonFile), true);
		parent::__construct($json);
		$this->id = basename($swffile, ".swf");
		$this->swffile = $swffile;
        $this->group   = basename(dirname($swffile));
        $this->url     = "/{$this->swffile}";
	}

	protected function Validate($json) {
		$fields = array("desc","width","height","version","dmfver");
		return $this->hasRequired($json, $fields);
	}

}