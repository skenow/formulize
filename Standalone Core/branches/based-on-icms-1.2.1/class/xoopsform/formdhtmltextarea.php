<?php
/**
* All functions for DHTML text area are here.
*
* @copyright	http://www.xoops.org/ The XOOPS Project
* @copyright	XOOPS_copyrights.txt
* @copyright	http://www.impresscms.org/ The ImpressCMS Project
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package		XoopsForms
* @since		XOOPS
* @author		http://www.xoops.org The XOOPS Project
* @author	   Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
* @version		$Id: formdhtmltextarea.php 9549 2009-11-14 14:13:02Z pesianstranger $
*/

if (!defined('ICMS_ROOT_PATH')) {
	die("ImpressCMS root path not defined");
}


/**
 *
 *
 * @package	 kernel
 * @subpackage  form
 *
 * @author		Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 */
/**
 * base class
 */
include_once ICMS_ROOT_PATH."/class/xoopsform/formtextarea.php";

// Make sure you have included /include/xoopscodes.php, otherwise DHTML will not work properly!

/**
 * A textarea with xoopsish formatting and smilie buttons
 *
 * @author	Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 *
 * @package	 kernel
 * @subpackage  form
 */
class XoopsFormDhtmlTextArea extends XoopsFormTextArea {
	/**
	* Extended HTML editor definition
	*
	* Set this property value if you want the editor to delegate rendering to an external class.
	*
	* Note: this functionality is experimental, but feedback is welcome.
	* Note: the PM window doesn't use XoopsFormDhtmlTextArea, so no need to report it doesn't work here
	*
	* array( 'bundleId' ): For XOS components (2.3+)
	* array( 'className', 'classPath' ):  To create an instance of "className", declared in the file ICMS_ROOT_PATH . $classPath
	*
	* Example:
	* $htmlEditor = array( 'XoopsFormTinyeditorTextArea', '/class/xoopseditor/tinyeditor/formtinyeditortextarea.php' );
	*/
	var $htmlEditor = array();

	/**
   * Hidden text
	 * @var	string
	 * @access	private
	 */
	var $_hiddenText;

	/**
	 * Constructor
	 *
   * @param	string  $caption	Caption
   * @param	string  $name	   "name" attribute
   * @param	string  $value	  Initial text
   * @param	int	 $rows	   Number of rows
   * @param	int	 $cols	   Number of columns
   * @param	string  $hiddentext Hidden Text
	 */
	function XoopsFormDhtmlTextArea($caption, $name, $value, $rows=5, $cols=50, $hiddentext="xoopsHiddenText", $options = array() )
	{
		$this->XoopsFormTextArea($caption, $name, $value, $rows, $cols);
		$this->_hiddenText = $hiddentext;
		global $icmsConfig, $icmsUser,$icmsModule;

		$groups   = (is_object($icmsUser)) ? $icmsUser->getGroups() : ICMS_GROUP_ANONYMOUS;
 		$moduleid = (is_object($icmsModule) && $name != 'com_text') ? $icmsModule->mid() : 1;

		if (isset($options['editor']) && $options['editor'] != '' && $options['editor'] != $icmsConfig['editor_default']){
			$editor_default = $options['editor'];
		}else{
			$editor_default = $icmsConfig['editor_default'];
		}

		$gperm_handler =& xoops_gethandler('groupperm');
		if( file_exists( ICMS_EDITOR_PATH."/".$editor_default."/xoops_version.php" ) && $gperm_handler->checkRight('use_wysiwygeditor', $moduleid, $groups, 1, false)){
			include(ICMS_EDITOR_PATH."/".$editor_default."/xoops_version.php");
			$this->htmlEditor = array( $editorversion['class'], ICMS_EDITOR_PATH."/".$editorversion['dirname']."/".$editorversion['file'] );
		}

		if ( !empty( $this->htmlEditor ) ) {
			$options['name'] = $this->_name;
			$options['value'] = $this->_value;

			if ( count( $this->htmlEditor ) == 1 ) {
				$this->htmlEditor = XOS::create( $this->htmlEditor[0] );
			} else {
				list( $class, $path ) = $this->htmlEditor;
				include_once $path;
				if ( class_exists( $class ) ) {
					$this->htmlEditor = new $class( $options );
				} else {
					$this->htmlEditor = false;
				}
			}
		}
	}



	/**
	 * Prepare HTML for output
	 *
   * @return	string  HTML
	 */
	function render()
	{
		global $icmsConfigPlugins, $icmsConfigMultilang;
		$myts =& MyTextSanitizer::getInstance();
		$editor = false;
		if ( $this->htmlEditor && is_object( $this->htmlEditor ) ) {
			if ( !isset( $this->htmlEditor->isEnabled ) || $this->htmlEditor->isEnabled ) {
				$editor = true;
			}
		}
		if ( $editor ) {
			return $this->htmlEditor->render();
		}
		$ele_name = $this->getName();
		$ret = "<a name='moresmiley'></a>".
				"<img onmouseover='style.cursor=\"pointer\"' src='".ICMS_URL."/images/url.gif' alt='url' onclick='xoopsCodeUrl(\"".$ele_name."\", \"".htmlspecialchars(_ENTERURL, ENT_QUOTES)."\", \"".htmlspecialchars(_ENTERWEBTITLE, ENT_QUOTES)."\");' />&nbsp;".
				"<img onmouseover='style.cursor=\"pointer\"' src='".ICMS_URL."/images/email.gif' alt='email' onclick='javascript:xoopsCodeEmail(\"".$ele_name."\", \"".htmlspecialchars(_ENTEREMAIL, ENT_QUOTES)."\");' />&nbsp;".
				"<img onclick='javascript:xoopsCodeImg(\"".$ele_name."\", \"".htmlspecialchars(_ENTERIMGURL, ENT_QUOTES)."\", \"".htmlspecialchars(_ENTERIMGPOS, ENT_QUOTES)."\", \"".htmlspecialchars(_IMGPOSRORL, ENT_QUOTES)."\", \"".htmlspecialchars(_ERRORIMGPOS, ENT_QUOTES)."\");' onmouseover='style.cursor=\"pointer\"' src='".ICMS_URL."/images/imgsrc.gif' alt='imgsrc' />&nbsp;".
				"<img onmouseover='style.cursor=\"pointer\"' onclick='javascript:openWithSelfMain(\"".ICMS_URL."/class/xoopsform/formimage_browse.php?target=".$ele_name."&type=iman\",\"imgmanager\",985,470);' src='".ICMS_URL."/images/image.gif' alt='image' />&nbsp;";
				$jscript = '';
			foreach ($icmsConfigPlugins['sanitizer_plugins'] as $key) {
				$extension = $myts->icmsloadExtension($key);
				$func = "render_{$key}";
				if ( function_exists($func) ) {
					@list($encode, $js) = $func($ele_name);
					if (empty($encode)) continue;
					$ret .= $encode;
				}
			}
		  $ret .= "<img src='".ICMS_URL."/images/code.gif' onmouseover='style.cursor=\"pointer\"' alt='code' onclick='javascript:xoopsCodeCode(\"".$ele_name."\", \"".htmlspecialchars(_ENTERCODE, ENT_QUOTES)."\");' />&nbsp;".
		  "<img onclick='javascript:xoopsCodeQuote(\"".$ele_name."\", \"".htmlspecialchars(_ENTERQUOTE, ENT_QUOTES)."\");' onmouseover='style.cursor=\"pointer\"' src='".ICMS_URL."/images/quote.gif' alt='quote' /><br />\n";
				$easiestml_exist = ($icmsConfigMultilang['ml_enable'] == '1' && defined('EASIESTML_LANGS') && defined('EASIESTML_LANGNAMES'));
		  if ($easiestml_exist) {
			$easiestml_langs = explode( ',' , EASIESTML_LANGS ) ;
			$langlocalnames = explode( ',' , EASIESTML_LANGNAMES ) ;
				$langnames = explode( ',' , $icmsConfigMultilang['ml_names'] ) ;

			$code = '' ;
			$javascript = '' ;

			foreach( $easiestml_langs as $l => $lang ){
				$ret .= "<img onclick='javascript:icmsCode_languages(\"".$ele_name."\", \"".htmlspecialchars(sprintf(_ENTERLANGCONTENT, $langlocalnames[$l]), ENT_QUOTES)."\", \"".$lang."\");' onmouseover='style.cursor=\"pointer\"' src='".ICMS_URL."/images/flags/".$langnames[$l].".gif' alt='".$langlocalnames[$l]."' />&nbsp;";
				}
				$ret .= "<br />\n";
		  }

	$sizearray = array("xx-small", "x-small", "small", "medium", "large", "x-large", "xx-large");
		$ret .= "<select id='".$ele_name."Size' onchange='setVisible(\"".$this->_hiddenText."\");setElementSize(\"".$this->_hiddenText."\",this.options[this.selectedIndex].value);'>\n";
		$ret .= "<option value='SIZE'>"._SIZE."</option>\n";
		foreach ( $sizearray as $size ) {
			$ret .=  "<option value='$size'>$size</option>\n";
		}
		$ret .= "</select>\n";
		$fontarray = array("Arial", "Courier", "Georgia", "Helvetica", "Impact", "Tahoma", "Verdana");
		$ret .= "<select id='".$ele_name."Font' onchange='setVisible(\"".$this->_hiddenText."\");setElementFont(\"".$this->_hiddenText."\",this.options[this.selectedIndex].value);'>\n";
		$ret .= "<option value='FONT'>"._FONT."</option>\n";
		foreach ( $fontarray as $font ) {
			$ret .= "<option value='$font'>$font</option>\n";
		}
		$ret .= "</select>\n";
		$colorarray = array("00", "33", "66", "99", "CC", "FF");
		$ret .= "<select id='".$ele_name."Color' onchange='setVisible(\"".$this->_hiddenText."\");setElementColor(\"".$this->_hiddenText."\",this.options[this.selectedIndex].value);'>\n";
		$ret .= "<option value='COLOR'>"._COLOR."</option>\n";
		foreach ( $colorarray as $color1 ) {
			foreach ( $colorarray as $color2 ) {
				foreach ( $colorarray as $color3 ) {
					$ret .= "<option value='".$color1.$color2.$color3."' style='background-color:#".$color1.$color2.$color3.";color:#".$color1.$color2.$color3.";'>#".$color1.$color2.$color3."</option>\n";
				}
			}
		}
		$ret .= "</select><span id='".$this->_hiddenText."'>"._EXAMPLE."</span>\n";
		$ret .= "<br />\n";
		$ret .= "<img onclick='javascript:xoopsmake"._GLOBAL_LEFT."(\"".$ele_name."\", \"".htmlspecialchars((( defined('_ADM_USE_RTL') && _ADM_USE_RTL )?_ALRIGHTCON:_ALLEFTCON), ENT_QUOTES)."\");' onmouseover='style.cursor=\"pointer\"' src='".ICMS_URL."/images/align"._GLOBAL_LEFT.".gif' alt='align"._GLOBAL_LEFT."' />&nbsp;"."<img onclick='javascript:xoopsmakecenter(\"".$ele_name."\", \"".htmlspecialchars(_ALCENTERCON, ENT_QUOTES)."\");' onmouseover='style.cursor=\"pointer\"' src='".ICMS_URL."/images/aligncenter.gif' alt='aligncenter' />&nbsp;"."<img onclick='javascript:xoopsmake"._GLOBAL_RIGHT."(\"".$ele_name."\", \"".htmlspecialchars((( defined('_ADM_USE_RTL') && _ADM_USE_RTL )?_ALLEFTCON:_ALRIGHTCON), ENT_QUOTES)."\");' onmouseover='style.cursor=\"pointer\"' src='".ICMS_URL."/images/align"._GLOBAL_RIGHT.".gif' alt='align"._GLOBAL_RIGHT."' />&nbsp;"."<img onclick='javascript:setVisible(\"".$this->_hiddenText."\");makeBold(\"".$this->_hiddenText."\");' onmouseover='style.cursor=\"pointer\"' src='".ICMS_URL."/images/bold.gif' alt='bold' />&nbsp;<img onclick='javascript:setVisible(\"".$this->_hiddenText."\");makeItalic(\"".$this->_hiddenText."\");' onmouseover='style.cursor=\"pointer\"' src='".ICMS_URL."/images/italic.gif' alt='italic' />&nbsp;<img onclick='javascript:setVisible(\"".$this->_hiddenText."\");makeUnderline(\"".$this->_hiddenText."\");' onmouseover='style.cursor=\"pointer\"' src='".ICMS_URL."/images/underline.gif' alt='underline' />&nbsp;<img onclick='javascript:setVisible(\"".$this->_hiddenText."\");makeLineThrough(\"".$this->_hiddenText."\");' src='".ICMS_URL."/images/linethrough.gif' alt='linethrough' onmouseover='style.cursor=\"pointer\"' />&nbsp;&nbsp;<input type='text' id='".$ele_name."Addtext' size='20' />&nbsp;<input type='button' onclick='xoopsCodeText(\"".$ele_name."\", \"".$this->_hiddenText."\", \"".htmlspecialchars(_ENTERTEXTBOX, ENT_QUOTES)."\")' class='formButton' value='"._ADD."' /><br /><br /><textarea id='".$ele_name."' name='".$ele_name."' onselect=\"xoopsSavePosition('".$ele_name."');\" onclick=\"xoopsSavePosition('".$ele_name."');\" onkeyup=\"xoopsSavePosition('".$ele_name."');\" cols='".$this->getCols()."' rows='".$this->getRows()."'".$this->getExtra().">".$this->getValue()."</textarea><br />\n";
		$ret .= $this->_renderSmileys();
		return $ret;
	}



	/**
	 * Render Validation Javascript
	 *
   * @return	mixed  rendered validation javascript or empty string
	 */
	function renderValidationJS() {
		if ( $this->htmlEditor && is_object( $this->htmlEditor ) && method_exists( $this->htmlEditor, "renderValidationJS" ) ) {
			if ( !isset( $this->htmlEditor->isEnabled ) || $this->htmlEditor->isEnabled ) {
				return $this->htmlEditor->renderValidationJS();
			}
		}
		return '';
	}





	/**
	 * prepare HTML for output of the smiley list.
   *
	 * @return	string HTML
	 */
	function _renderSmileys()
	{
		$myts =& MyTextSanitizer::getInstance();
		$smiles =& $myts->getSmileys();
		$ret = '';
		$count = count($smiles);
		$ele_name = $this->getName();
		for ($i = 0; $i < $count; $i++) {
			$ret .= "<img onclick='xoopsCodeSmilie(\"".$ele_name."\", \" ".$smiles[$i]['code']." \");' onmouseover='style.cursor=\"pointer\"' src='".ICMS_UPLOAD_URL."/".htmlspecialchars($smiles[$i]['smile_url'], ENT_QUOTES)."' border='0' alt='' />";
		}
		$ret .= "&nbsp;[<a href='#moresmiley' onclick='javascript:openWithSelfMain(\"".ICMS_URL."/misc.php?action=showpopups&amp;type=smilies&amp;target=".$ele_name."\",\"smilies\",300,475);'>"._MORE."</a>]";
		return $ret;
	}
}

?>