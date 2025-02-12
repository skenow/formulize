<?php
/**
 * EditArea adapter for ImpressCMS
 *
 * @copyright	The ImpressCMS project http://www.xoops.org/
 * @license		http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author		MekDrop	<mekdrop@gmail.com>
 * @since		1.2
 * @package		sourceeditors
 */

global $xoopsConfig;

$current_path = __FILE__;
if ( DIRECTORY_SEPARATOR != "/" ) $current_path = str_replace( strpos( $current_path, "\\\\", 2 ) ? "\\\\" : DIRECTORY_SEPARATOR, "/", $current_path);
$root_path = dirname($current_path);

$xoopsConfig['language'] = preg_replace("/[^a-z0-9_\-]/i", "", $xoopsConfig['language']);
if (file_exists($root_path."/language/".$xoopsConfig['language'].".php")) {
	require_once($root_path."/language/".$xoopsConfig['language'].".php");
} else {
	require_once($root_path."/language/english.php");
}

return $config = array(
		//"name"	=>	"dhtmltextarea",
		"class"	=>	'IcmsSourceEditorEditArea',
		"file"	=>	$root_path.'/editarea.php',
		"title"	=>	_ICMS_SOURCEEDITOR_EDITAREA,
		"order"	=>	1,
		"nohtml"=>	1
	);
?>