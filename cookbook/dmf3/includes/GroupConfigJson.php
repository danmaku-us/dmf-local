<?php if (!defined('PmWiki')) exit();
//{$groupName}.json
final class GroupConfigJson extends ConfigJson
{

	public function __construct($json)
	{
		parent::__construct($json);
	}

	public function Validate($json) {
		$fields = array("desc","targetConfig","targetMinVer","cmtFormats","videoFormats");
		return $this->hasRequired($json, $fields);
	}


}