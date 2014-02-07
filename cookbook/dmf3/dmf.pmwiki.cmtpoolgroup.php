<?php if (!defined('PmWiki')) exit();

/*
 * TODO:
 *          这个方案会影响到其他页面编辑
 *          应该使用其他的action避免干扰
 */
if (PageVar($pagename, '$Group') == DMFConfig::Cmt_PageGroup) {
    $EnableNotify = 0;
    $EnablePostAuthorRequired = 0;
    array_unshift($EditFunctions,'ValidateXML');
    $PageEditForm = DMFConfig::Cmt_PageEditForm;
}

function ValidateXML($pagename,&$page,&$new)
{
	global $Now, $EnablePost, $MessagesFmt, $WorkDir;

	$SimXMLHeader = '<?xml version="1.0" encoding="UTF-8"?><comments>';
	$SimXMLFooter = '</comments>';
	
	if ($new['text'] == '') return;
	
	$test = simplexml_load_string($SimXMLHeader.$new['text'].$SimXMLFooter);
	if ($test === FALSE)
	{
		$ec = '文档已被保存，但检测到错误：<br />';
		foreach (libxml_get_errors() as $e)
		{
			$ec .= Utils::display_xml_error($e, $test);
		}
		$MessagesFmt = "<p class='editconflict'>$ec
			</p>\n";
		//$new['text'] = $page['text'];
	}
	return;
}