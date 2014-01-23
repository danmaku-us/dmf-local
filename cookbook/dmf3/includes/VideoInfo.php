<?php if (!defined('PmWiki')) exit();
final class VideoInfo extends ConfigJson
{
	public function __construct($arr)
	{
        $this->videosrc;
        $this->videotype;
        $this->part;
        $this->player;

    }
	
    public function GetCmtId()
    {

    }

    public function GetPlayerLoader()
    {

    }

    public function GetControlBar()
    {
        
    }

	public function Validate($jsonarr)
	{
        $fields = array(
            );
        return $this->hasRequired($json, $fields);
	}
}