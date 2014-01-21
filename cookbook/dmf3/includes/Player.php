<?php if (!defined('PmWiki')) exit();
//{$playerName}.json
//id = {$FileName}
//desc
//width
//height
//version
final class Player extends ConfigJson
{
	private $swffile;
	private $jsonfile;
	protected $id;

	public function __construct($swffile)
	{
		$this->jsonfile = $jsonFile = substr($swffile, 0, -3)."json";
		$json = json_decode(file_get_contents($jsonFile), true);
		parent::__construct($json);
		$this->id = basename($swffile, ".swf");
		$this->swffile = $swffile;

	}

	protected function Validate($json) {
		$fields = array("desc","width","height","version","dmfver");
		return $this->hasRequired($json, $fields);
	}

}