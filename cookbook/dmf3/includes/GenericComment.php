<?php if (!defined('PmWiki')) exit();
//分化出GenericComment和SpecializedComment两大类用于初始化

final class GenericComment
{
	private $meta = array(
		'id' 		=> 'int',
		'userhash' 	=> 'string',
		'sendtime' 	=> 'int',
		'text' 		=> 'string',
		'mode' 		=> 'int',
		'color' 	=> 'int',
		'playtime' 	=> 'float',
		'fontsize' 	=> 'int'
	);

	private $obj  = array();

	public function __construct($config, SimpleXMLElement  $arr)
	{
		$this->meta = array_merge($config, $this->meta);
		foreach ($this->meta as $k => $type) {
			$this->obj[$k] = $this->getDefaultValue($type);
		}
		$this->init($arr);
	}

	public function __get($k)
	{
		if (!array_key_exists($k, $this->obj)) {
			throw new Exception("找不到属性 {$k}", 1);
		}
		return $thi->obj[$k];
	}

	public function __set($k, $v)
	{
		if (!array_key_exists($k, $this->meta)) {
			throw new Exception("找不到转换模式 : {$k}", 1);
		}
		$this->obj[$k] = $this->tryConvert($v, $this->meta[$k]);
	}

	//TODO:改成simplexmlelement
	private function init($arr)
	{
		foreach ($arr as $k => $v) {
			$this->$k = $v;
		}
	}

	private function getDefaultValue($type)
	{
		switch ($type) {
			case 'int':
				return 0;
			case 'string':
				return "";
			case 'float':
				return 0.0;
			default:
				throw new Exception("未知类型{$type}");
		}
	}

	private function tryConvert($val, $type)
	{
		switch ($type) {
			case 'int':
				$i = intval($val);
				if ("$val" == "$i") {
					return $i;
				} else {
					throw new Exception("不能把{$val}转换到{$type}");
				}
			case 'string':
				return strval($val);
			case 'float':
				$f = floatval($val);
				if ("$val" == "$f") {
					return $f;
				} else {
					throw new Exception("不能把{$val}转换到{$type}");
				}
			default:
				throw new Exception("未知类型{$type}");
		}
	}
}