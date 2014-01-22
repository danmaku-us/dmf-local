<?php if (!defined('PmWiki')) exit();
//{$groupName}.json
final class GroupConfigJson extends ConfigJson
{

	public function __construct($json)
	{
		parent::__construct($json);
	}

	public function Validate($json) {
		$fields = array(
                    "desc",
                    "targetconfig",
                    "targetvermin",
                    "dmfspecvermin",
                    "cmtformats",
                    "videoformats",
                    "javascripts");
		return $this->hasRequired($json, $fields);
	}


}