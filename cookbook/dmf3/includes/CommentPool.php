<?php if (!defined('PmWiki')) exit();
final class CommentPool
{
	private $xmlobj;
	private $isStatic;

	public function __construct($cmtPoolId, $gConfig, $forceDynamic = false)
	{
		if ($forceDynamic) {
			$this->loadDynamic();
		} else {
			$this->loadStatic();
		}
	}

	public function Append()
	{

	}

	public function Clear()
	{

	}

	public function Search()
	{

	}

	public function Replace()
	{

	}

	public function Save()
	{

	}

	public function SaveStatic()
	{

	}

	private function isValid()
	{

	}

	private function hasCmtId($id)
	{

	}

	private function loadDynamic()
	{
		unset($this->xmlobj);
		
		$this->isStatic = false;
	}

	private function loadStatic()
	{
		if ($this->isStatic === false) {
			throw new Exception("动态模式下不允许覆盖", 1);
		}
		$this->isStatic = true;
	}


}