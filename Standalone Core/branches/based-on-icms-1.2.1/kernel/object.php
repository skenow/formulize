<?php
/**
* Manage of original Xoops Objects
*
* @copyright	http://www.xoops.org/ The XOOPS Project
* @copyright	XOOPS_copyrights.txt
* @copyright	http://www.impresscms.org/ The ImpressCMS Project
* @license	LICENSE.txt
* @package	core
* @since	XOOPS
* @author	http://www.xoops.org The XOOPS Project
* @author	modified by UnderDog <underdog@impresscms.org>
* @version	$Id: object.php 9520 2009-11-11 14:32:52Z pesianstranger $
*/

/**
 * @package kernel
 * @copyright copyright &copy; 2000 XOOPS.org
 */

/**#@+
 * Xoops object datatype
 *
 **/
define('XOBJ_DTYPE_TXTBOX', 1);
define('XOBJ_DTYPE_TXTAREA', 2);
define('XOBJ_DTYPE_INT', 3);
define('XOBJ_DTYPE_URL', 4);
define('XOBJ_DTYPE_EMAIL', 5);
define('XOBJ_DTYPE_ARRAY', 6);
define('XOBJ_DTYPE_OTHER', 7);
define('XOBJ_DTYPE_SOURCE', 8);
define('XOBJ_DTYPE_STIME', 9);
define('XOBJ_DTYPE_MTIME', 10);
define('XOBJ_DTYPE_LTIME', 11);

define('XOBJ_DTYPE_SIMPLE_ARRAY', 101);
define('XOBJ_DTYPE_CURRENCY', 200);
define('XOBJ_DTYPE_FLOAT', 201);
define('XOBJ_DTYPE_TIME_ONLY', 202);
define('XOBJ_DTYPE_URLLINK', 203);
define('XOBJ_DTYPE_FILE', 204);
define('XOBJ_DTYPE_IMAGE', 205);
define('XOBJ_DTYPE_FORM_SECTION', 210);
define('XOBJ_DTYPE_FORM_SECTION_CLOSE', 211);
/**#@-*/

//include_once "xoopspluginloader.php";


/**
 * Base class for all objects in the Xoops kernel (and beyond) 
 * 
 * @author Kazumi Ono (AKA onokazu)
 * @copyright copyright &copy; 2000 XOOPS.org
 * @package kernel
 **/
class XoopsObject
{

    /**
     * holds all variables(properties) of an object
     * 
     * @var array
     * @access protected
     **/
    var $vars = array();

    /**
    * variables cleaned for store in DB
    * 
    * @var array
    * @access protected
    */
    var $cleanVars = array();

    /**
    * is it a newly created object?
    * 
    * @var bool
    * @access private
    */
    var $_isNew = false;

    /**
    * has any of the values been modified?
    * 
    * @var bool
    * @access private
    */
    var $_isDirty = false;

    /**
    * errors
    * 
    * @var array
    * @access private
    */
    var $_errors = array();

    /**
    * additional filters registered dynamically by a child class object
    * 
    * @access private
    */
    var $_filters = array();





    /**
    * constructor
    * 
    * normally, this is called from child classes only
    * @access public
    */
    function XoopsObject()
    {
    }



    /**#@+
    * used for new/clone objects
    * 
    * @access public
    */
    function setNew()
    {
        $this->_isNew = true;
    }
    function unsetNew()
    {
        $this->_isNew = false;
    }
    function isNew()
    {
        return $this->_isNew;
    }
    /**#@-*/



    /**#@+
    * mark modified objects as dirty
    * 
    * used for modified objects only
    * @access public
    */
    function setDirty()
    {
        $this->_isDirty = true;
    }
    function unsetDirty()
    {
        $this->_isDirty = false;
    }
    function isDirty()
    {
        return $this->_isDirty;
    }
    /**#@-*/




    /**
    * initialize variables for the object
    * 
    * @access public
    * @param string $key
    * @param int $data_type  set to one of XOBJ_DTYPE_XXX constants (set to XOBJ_DTYPE_OTHER if no data type ckecking nor text sanitizing is required)
    * @param mixed
    * @param bool $required  require html form input?
    * @param int $maxlength  for XOBJ_DTYPE_TXTBOX type only
    * @param string $option  does this data have any select options?
    */
    function initVar($key, $data_type, $value = null, $required = false, $maxlength = null, $options = '')
    {
        $this->vars[$key] = array('value' => $value, 'required' => $required, 'data_type' => $data_type, 'maxlength' => $maxlength, 'changed' => false, 'options' => $options);
    }

    /**
    * assign a value to a variable
    * 
    * @access public
    * @param string $key name of the variable to assign
    * @param mixed $value value to assign
    */
    function assignVar($key, $value)
    {
        if (isset($value) && isset($this->vars[$key])) {
            $this->vars[$key]['value'] =& $value;
        }
    }



    /**
    * assign values to multiple variables in a batch
    * 
    * @access private
    * @param array $var_array associative array of values to assign
    */
    function assignVars($var_arr)
    {
        foreach ($var_arr as $key => $value) {
            $this->assignVar($key, $value);
        }
    }

    /**
    * assign a value to a variable
    * 
    * @access public
    * @param string $key name of the variable to assign
    * @param mixed $value value to assign
    * @param bool $not_gpc
    */
    function setVar($key, $value, $not_gpc = false)
    {
        if (!empty($key) && isset($value) && isset($this->vars[$key])) {
            $this->vars[$key]['value'] =& $value;
            $this->vars[$key]['not_gpc'] = $not_gpc;
            $this->vars[$key]['changed'] = true;
            $this->setDirty();
        }
    }

    /**
    * assign values to multiple variables in a batch
    * 
    * @access private
    * @param array $var_arr associative array of values to assign
    * @param bool $not_gpc
    */
    function setVars($var_arr, $not_gpc = false)
    {
        foreach ($var_arr as $key => $value) {
            $this->setVar($key, $value, $not_gpc);
        }
    }

  	/**
  	* Assign values to multiple variables in a batch
  	*
  	* Meant for a CGI contenxt:
  	* - prefixed CGI args are considered save
  	* - avoids polluting of namespace with CGI args
  	*
  	* @access private
  	* @param array $var_arr associative array of values to assign
  	* @param string $pref prefix (only keys starting with the prefix will be set)
  	*/
  	function setFormVars($var_arr=null, $pref='xo_', $not_gpc=false) {
  		$len = strlen($pref);
  		foreach ($var_arr as $key => $value) {
  			if ($pref == substr($key,0,$len)) {
  				$this->setVar(substr($key,$len), $value, $not_gpc);
  			}
  		}
  	}


    /**
    * returns all variables for the object
    * 
    * @access public
    * @return array associative array of key->value pairs
    */
    function &getVars()
    {
        return $this->vars;
    }




  	/**
  	* Returns the values of the specified variables
  	*
  	* @param mixed $keys An array containing the names of the keys to retrieve, or null to get all of them
  	* @param string $format Format to use (see getVar)
  	* @param int $maxDepth Maximum level of recursion to use if some vars are objects themselves
  	* @return array associative array of key->value pairs
  	*/
  	function getValues( $keys = null, $format = 's', $maxDepth = 1 ) {
    	if ( !isset( $keys ) ) {
    		$keys = array_keys( $this->vars );
    	}
    	$vars = array();
    	foreach ( $keys as $key ) {
    		if ( isset( $this->vars[$key] ) ) {
    			if ( is_object( $this->vars[$key] ) && is_a( $this->vars[$key], 'XoopsObject' ) ) {
					if ( $maxDepth ) {
    					$vars[$key] = $this->vars[$key]->getValues( null, $format, $maxDepth - 1 );
					}
    			} else {
    				$vars[$key] = $this->getVar( $key, $format );
    			}
    		}
    	}
    	return $vars;
    }



    /**
    * returns a specific variable for the object in a proper format
    * 
    * @access public
    * @param string $key key of the object's variable to be returned
    * @param string $format format to use for the output
    * @return mixed formatted value of the variable
    */
    function getVar($key, $format = 's')
    {
        $ret = $this->vars[$key]['value'];
        switch ($this->vars[$key]['data_type']) {

        case XOBJ_DTYPE_TXTBOX:
            switch (strtolower($format)) {
            case 's':
            case 'show':
            case 'e':
            case 'edit':
                $ts =& MyTextSanitizer::getInstance();
                return $ts->htmlSpecialChars($ret);
                break 1;
            case 'p':
            case 'preview':
            case 'f':
            case 'formpreview':
                $ts =& MyTextSanitizer::getInstance();
                return $ts->htmlSpecialChars($ts->stripSlashesGPC($ret));
                break 1;
            case 'n':
            case 'none':
            default:
                break 1;
            }
            break;
        case XOBJ_DTYPE_TXTAREA:
            switch (strtolower($format)) {
            case 's':
            case 'show':
                $ts =& MyTextSanitizer::getInstance();
                $html = !empty($this->vars['dohtml']['value']) ? 1 : 0;
                $xcode = (!isset($this->vars['doxcode']['value']) || $this->vars['doxcode']['value'] == 1) ? 1 : 0;
                $smiley = (!isset($this->vars['dosmiley']['value']) || $this->vars['dosmiley']['value'] == 1) ? 1 : 0;
                $image = (!isset($this->vars['doimage']['value']) || $this->vars['doimage']['value'] == 1) ? 1 : 0;
                $br = (!isset($this->vars['dobr']['value']) || $this->vars['dobr']['value'] == 1) ? 1 : 0;
                return $ts->displayTarea($ret, $html, $smiley, $xcode, $image, $br);
                break 1;
            case 'e':
            case 'edit':
                return htmlspecialchars($ret, ENT_QUOTES);
                break 1;
            case 'p':
            case 'preview':
                $ts =& MyTextSanitizer::getInstance();
                $html = !empty($this->vars['dohtml']['value']) ? 1 : 0;
                $xcode = (!isset($this->vars['doxcode']['value']) || $this->vars['doxcode']['value'] == 1) ? 1 : 0;
                $smiley = (!isset($this->vars['dosmiley']['value']) || $this->vars['dosmiley']['value'] == 1) ? 1 : 0;
                $image = (!isset($this->vars['doimage']['value']) || $this->vars['doimage']['value'] == 1) ? 1 : 0;
                $br = (!isset($this->vars['dobr']['value']) || $this->vars['dobr']['value'] == 1) ? 1 : 0;
                return $ts->previewTarea($ret, $html, $smiley, $xcode, $image, $br);
                break 1;
            case 'f':
            case 'formpreview':
                $ts =& MyTextSanitizer::getInstance();
                return htmlspecialchars($ts->stripSlashesGPC($ret), ENT_QUOTES);
                break 1;
            case 'n':
            case 'none':
            default:
                break 1;
            }
            break;
        case XOBJ_DTYPE_ARRAY:
            $ret =& unserialize($ret);
            break;
        case XOBJ_DTYPE_SOURCE:
            switch (strtolower($format)) {
            case 's':
            case 'show':
                break 1;
            case 'e':
            case 'edit':
                return htmlspecialchars($ret, ENT_QUOTES);
                break 1;
            case 'p':
            case 'preview':
                $ts =& MyTextSanitizer::getInstance();
                return $ts->stripSlashesGPC($ret);
                break 1;
            case 'f':
            case 'formpreview':
                $ts =& MyTextSanitizer::getInstance();
                return htmlspecialchars($ts->stripSlashesGPC($ret), ENT_QUOTES);
                break 1;
            case 'n':
            case 'none':
            default:
                break 1;
            }
            break;
        default:
            if ($this->vars[$key]['options'] != '' && $ret != '') {
                switch (strtolower($format)) {
                case 's':
                case 'show':
          					$selected = explode('|', $ret);
                    $options = explode('|', $this->vars[$key]['options']);
                    $i = 1;
                    $ret = array();
                    foreach ($options as $op) {
                        if (in_array($i, $selected)) {
                            $ret[] = $op;
                        }
                        $i++;
                    }
                    return implode(', ', $ret);
                case 'e':
                case 'edit':
                    $ret = explode('|', $ret);
                    break 1;
                default:
                    break 1;
                }

            }
            break;
        }
        return $ret;
    }






    /**
     * clean values of all variables of the object for storage. 
     * also add slashes whereever needed
     * 
     * @return bool true if successful
     * @access public
     */
    function cleanVars()
    {
        $ts =& MyTextSanitizer::getInstance();
        $existing_errors = $this->getErrors();
        $this->_errors = array();
        foreach ($this->vars as $k => $v) {
    			$cleanv = $v['value'];
            if (!$v['changed']) {
            } else {
                $cleanv = is_string($cleanv) ? trim($cleanv) : $cleanv;
                switch ($v['data_type']) {
                case XOBJ_DTYPE_TXTBOX:
                    if ($v['required'] && $cleanv != '0' && $cleanv == '') {
                        $this->setErrors( sprintf( _XOBJ_ERR_REQUIRED, $k ) );
                        continue;
                    }
                    if (isset($v['maxlength']) && strlen($cleanv) > intval($v['maxlength'])) {
                        $this->setErrors( sprintf( _XOBJ_ERR_SHORTERTHAN, $k, intval( $v['maxlength'] ) ) );
                        continue;
                    }
                    if (!$v['not_gpc']) {
                        $cleanv = $ts->stripSlashesGPC($ts->censorString($cleanv));
                    } else {
                        $cleanv = $ts->censorString($cleanv);
                    }
                    break;
                case XOBJ_DTYPE_TXTAREA:
                    if ($v['required'] && $cleanv != '0' && $cleanv == '') {
                        $this->setErrors( sprintf( _XOBJ_ERR_REQUIRED, $k ) );
                        continue;
                    }
                    if (!$v['not_gpc']) {
                        $cleanv = $ts->stripSlashesGPC($ts->censorString($cleanv));
                    } else {
                        $cleanv = $ts->censorString($cleanv);
                    }
                    break;
                case XOBJ_DTYPE_SOURCE:
                    if (!$v['not_gpc']) {
                        $cleanv = $ts->stripSlashesGPC($cleanv);
                    } else {
                        $cleanv = $cleanv;
                    }
                    break;
                case XOBJ_DTYPE_INT:
                    $cleanv = intval($cleanv);
                    break;
                case XOBJ_DTYPE_EMAIL:
                    if ($v['required'] && $cleanv == '') {
                        $this->setErrors( sprintf( _XOBJ_ERR_REQUIRED, $k ) );
                        continue;
                    }
                    if ($cleanv != '' && !preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+([\.][a-z0-9-]+)+$/i",$cleanv)) {
                        $this->setErrors(_CORE_DB_INVALIDEMAIL);
                        continue;
                    }
                    if (!$v['not_gpc']) {
                        $cleanv = $ts->stripSlashesGPC($cleanv);
                    }
                    break;
                case XOBJ_DTYPE_URL:
                    if ($v['required'] && $cleanv == '') {
                        $this->setErrors( sprintf( _XOBJ_ERR_REQUIRED, $k ) );
                        continue;
                    }
                    if ($cleanv != '' && !preg_match("/^http[s]*:\/\//i", $cleanv)) {
                        $cleanv = 'http://' . $cleanv;
                    }
                    if (!$v['not_gpc']) {
                        $cleanv =& $ts->stripSlashesGPC($cleanv);
                    }
                    break;
                case XOBJ_DTYPE_ARRAY:
                    $cleanv = serialize($cleanv);
                    break;
                case XOBJ_DTYPE_STIME:
                case XOBJ_DTYPE_MTIME:
                case XOBJ_DTYPE_LTIME:
                    $cleanv = !is_string($cleanv) ? intval($cleanv) : strtotime($cleanv);
                    break;
                default:
                    break;
                }
            }
            $this->cleanVars[$k] =& $cleanv;
            unset($cleanv);
        }
        if (count($this->_errors) > 0) {
	        $this->_errors = array_merge($existing_errors, $this->_errors);
            return false;
        }
  	    $this->_errors = array_merge($existing_errors, $this->_errors);
        $this->unsetDirty();
        return true;
    }





    /**
     * dynamically register additional filter for the object
     * 
     * @param string $filtername name of the filter
     * @access public
     */
    function registerFilter($filtername)
    {
        $this->_filters[] = $filtername;
    }

    /**
     * load all additional filters that have been registered to the object
     * 
     * @access private
     */
    function _loadFilters()
    {
        //include_once XOOPS_ROOT_PATH.'/class/filters/filter.php';
        //foreach ($this->_filters as $f) {
        //    include_once XOOPS_ROOT_PATH.'/class/filters/'.strtolower($f).'php';
        //}
    }

    /**
     * create a clone(copy) of the current object
     * 
     * @access public
     * @return object clone
     */
    function &xoopsClone()
    {
        $class = get_class($this);
        $clone =& new $class();
        foreach ($this->vars as $k => $v) {
            $clone->assignVar($k, $v['value']);
        }
        // need this to notify the handler class that this is a newly created object
        $clone->setNew();
        return $clone;
    }

    /**
     * add an error 
     * 
     * @param string $value error to add
     * @access public
     */
    function setErrors($err_str)
    {
        $this->_errors[] = trim($err_str);
    }

    /**
     * return the errors for this object as an array
     * 
     * @return array an array of errors
     * @access public
     */
    function getErrors()
    {
        return $this->_errors;
    }

    /**
     * return the errors for this object as html
     * 
     * @return string html listing the errors
     * @access public
     */
    function getHtmlErrors()
    {
        $ret = '<h4>'._ERROR.'</h4>';
        if (!empty($this->_errors)) {
            foreach ($this->_errors as $error) {
                $ret .= $error.'<br />';
            }
        } else {
            $ret .= _NONE.'<br />';
        }
        return $ret;
    }
}





/**
* XOOPS object handler class.  
* This class is an abstract class of handler classes that are responsible for providing
* data access mechanisms to the data source of its corresponsing data objects
* @package kernel
* @abstract
*
* @author  Kazumi Ono <onokazu@xoops.org>
* @copyright copyright &copy; 2000 The XOOPS Project
*/
class XoopsObjectHandler
{

    /**
     * holds referenced to {@link XoopsDatabase} class object
  	 * 
  	 * @var object
  	 * @see XoopsDatabase
     * @access protected
     */
    var $db;

    // 
    /**
     * called from child classes only
  	 * 
  	 * @param object $db reference to the {@link XoopsDatabase} object
     * @access protected
     */
    function XoopsObjectHandler(&$db)
    {
        $this->db =& $db;
    }

    /**
     * creates a new object
     * 
     * @abstract
     */
    function &create()
    {
    }

    /**
     * gets a value object
     * 
  	 * @param int $int_id
     * @abstract
     */
    function &get($int_id)
    {
    }

    /**
     * insert/update object
     * 
  	 * @param object $object
     * @abstract
     */
    function insert(&$object)
    {
    }

    /**
     * delete object from database
     * 
  	 * @param object $object
     * @abstract
     */
    function delete(&$object)
    {
    }

}

?>