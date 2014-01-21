<?php if (!defined('PmWiki')) exit();
abstract class ConfigJson
{
	protected $json = array();

	//@param $json JSON输入，decode时需要为ASSOC_ARRAY格式
	public function __construct($json)
	{
		if (!is_array($json)) {
			throw new Exception("Bad input!");
		}

		$result = $this->Validate($json);
		if ($result !== true) {
			throw new Exception("Validate fail on {$result}");
		}
		$this->json = $json;
	}

	//检查输入的json是否符合规范。
	//返回bool
	protected abstract function Validate($json);

	public function __get($key)
	{
		if (array_key_exists($key, $this->json)) {
			return $this->json[$key];
		} else if (property_exists($this, $key)) {
			return $this->$key;
		} else {
			throw new Exception("找不到属性{$key}");
		}
	}

	protected function hasRequired($arr, $keys)
	{
		foreach ($keys as $key) {
			if (!array_key_exists($key, $arr)) {
				return $key;
			}
		}
		return true;
	}

}