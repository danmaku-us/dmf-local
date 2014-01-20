<?php if (!defined('PmWiki')) exit();
//{$groupName}.json
final class ConfigJson
{
	private $json = array();

	//@param $json JSON输入，decode时需要为ASSOC_ARRAY格式
	public function __construct($json)
	{
		$fields = array("desc","targetConfig","targetMinVer","cmtFormats","videoFormats");
		$valid = $this->hasRequired($json, $fields);
		if (!$valid) {
			throw new Exception("Error Processing Config Json");
		}

		$this->json = $json;
		//foreach ($json as $key => $value) {
		//	$this->json[strtolower($key)] = $value;
		//}
	}

	public function __get($key)
	{
		if (!array_key_exists($key, $this->json)) {
			throw new Exception("找不到属性{$key}");
			
		}
		return $this->json[$key];
	}

	private function hasRequired($arr, $keys)
	{
		foreach ($keys as $key) {
			if (!array_key_exists($key, $arr)) {
				return false;
			}
		}
		return true;
	}

}