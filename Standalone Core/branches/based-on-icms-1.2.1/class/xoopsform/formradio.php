<?php
/**
* Creates a form radiobutton attribute (base class)
*
* @copyright	http://www.xoops.org/ The XOOPS Project
* @copyright	XOOPS_copyrights.txt
* @copyright	http://www.impresscms.org/ The ImpressCMS Project
* @license	LICENSE.txt
* @package	XoopsForms
* @since	XOOPS
* @author	http://www.xoops.org The XOOPS Project
* @author	modified by UnderDog <underdog@impresscms.org>
* @version	$Id: formradio.php 8662 2009-05-01 09:04:30Z pesianstranger $
*/

if (!defined('ICMS_ROOT_PATH')) {
	die("ImpressCMS root path not defined");
}

/**
 * 
 * 
 * @package     kernel
 * @subpackage  form
 * 
 * @author	    Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 */
/**
 * A Group of radiobuttons
 * 
 * @author	Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 * 
 * @package		kernel
 * @subpackage	form
 */
class XoopsFormRadio extends XoopsFormElement {

	/**
   * Array of Options
	 * @var	array	
	 * @access	private
	 */
	var $_options = array();

	/**
   * Pre-selected value
	 * @var	string	
	 * @access	private
	 */
	var $_value = null;

	/**
   * HTML to separate the elements
	 * @var	string  
	 * @access  private
	 */
	var $_delimeter;

	/**
	 * Constructor
	 * 
	 * @param	string	$caption	Caption
	 * @param	string	$name		"name" attribute
	 * @param	string	$value		Pre-selected value
	 */
	function XoopsFormRadio($caption, $name, $value = null, $delimeter = ""){
		$this->setCaption($caption);
		$this->setName($name);
		if (isset($value)) {
			$this->setValue($value);
		}
		$this->_delimeter = $delimeter;
	}

	/**
	 * Get the "value" attribute
	 * 
	 * @param	bool    $encode To sanitizer the text?
	 * @return	string
	 */
	function getValue($encode = false) {
		return ( $encode && $this->_value !== null ) ? htmlspecialchars($this->_value, ENT_QUOTES) : $this->_value;
	}

	/**
	 * Set the pre-selected value
	 * 
	 * @param	$value	string
	 */
	function setValue($value){
		$this->_value = $value;
	}

	/**
	 * Add an option
	 * 
	 * @param	string	$value	"value" attribute - This gets submitted as form-data.
	 * @param	string	$name	"name" attribute - This is displayed. If empty, we use the "value" instead.
	 */
	function addOption($value, $name = "") {
		if ( $name != "" ) {
			$this->_options[$value] = $name;
		} else {
			$this->_options[$value] = $value;
		}
	}

	/**
	 * Adds multiple options
	 * 
	 * @param	array	$options	Associative array of value->name pairs.
	 */
	function addOptionArray($options){
		if ( is_array($options) ) {
			foreach ( $options as $k => $v ) {
				$this->addOption($k, $v);
			}
		}
	}

	/**
	 * Get an array with all the options
	 *
	 * @param	int     $encode     To sanitizer the text? potential values: 0 - skip; 1 - only for value; 2 - for both value and name
   * @return	array   Associative array of value->name pairs
	 */
	function getOptions($encode = false) {
    	if (!$encode) {
        	return $this->_options;
    	}
    	$value = array();
    	foreach ($this->_options as $val => $name) {
		    $value[ $encode ? htmlspecialchars($val, ENT_QUOTES) : $val ] = ($encode > 1) ? htmlspecialchars($name, ENT_QUOTES) : $name;
    	}
    	return $value;
	}

	/**
	 * Get the delimiter of this group
	 * 
	 * @param	bool    $encode To sanitizer the text?
   * @return	string  The delimiter
	 */
	function getDelimeter($encode = false) {
		return $encode ? htmlspecialchars(str_replace('&nbsp;', ' ', $this->_delimeter)) : $this->_delimeter;
	}

	/**
	 * Prepare HTML for output
	 * 
	 * @return	string	HTML
	 */
	function render() {
		$ret = "";
		$ele_name = $this->getName();
		$ele_value = $this->getValue();
		$ele_options = $this->getOptions();
		$ele_extra = $this->getExtra();
		$ele_delimeter = $this->getDelimeter();
		foreach ( $ele_options as $value => $name ) {
			$ret .= "<input type='radio' name='".$ele_name."' value='".htmlspecialchars($value, ENT_QUOTES)."'";
			if ( $value == $ele_value ) {
				$ret .= " checked='checked'";
			}
			$ret .= $ele_extra." />".$name.$ele_delimeter."\n";
		}
		return $ret;
	}
}

?>