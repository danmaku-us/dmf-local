<?php if (!defined('PmWiki')) exit();
abstract class PathUtils
{
	static function FindFiles($dir, $pattern)
	{
	    // escape any character in a string that might be used to trick
	    // a shell command into executing arbitrary commands
	    $dir = escapeshellcmd($dir);
	    // get a list of all matching files in the current directory
	    $files = glob("$dir/$pattern");
	    // find a list of all directories in the current directory
	    // directories beginning with a dot are also included
	    foreach (glob("$dir/{.[^.]*,*}", GLOB_BRACE|GLOB_ONLYDIR) as $sub_dir){
	        $arr   = self::FindFiles($sub_dir, $pattern);  // resursive call
	        $files = array_merge($files, $arr); // merge array with files from subdirectory
	    }
	    // return all found files
	    return $files;
	}
}