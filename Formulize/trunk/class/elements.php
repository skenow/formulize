<?php
###############################################################################
##     Formulize - ad hoc form creation and reporting module for XOOPS       ##
##                    Copyright (c) 2004 Freeform Solutions                  ##
##                Portions copyright (c) 2003 NS Tai (aka tuff)              ##
##                       <http://www.brandycoke.com/>                        ##
###############################################################################
##                    XOOPS - PHP Content Management System                  ##
##                       Copyright (c) 2000 XOOPS.org                        ##
##                          <http://www.xoops.org/>                          ##
###############################################################################
##  This program is free software; you can redistribute it and/or modify     ##
##  it under the terms of the GNU General Public License as published by     ##
##  the Free Software Foundation; either version 2 of the License, or        ##
##  (at your option) any later version.                                      ##
##                                                                           ##
##  You may not change or alter any portion of this comment or credits       ##
##  of supporting developers from this source code or any supporting         ##
##  source code which is considered copyrighted (c) material of the          ##
##  original comment or credit authors.                                      ##
##                                                                           ##
##  This program is distributed in the hope that it will be useful,          ##
##  but WITHOUT ANY WARRANTY; without even the implied warranty of           ##
##  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            ##
##  GNU General Public License for more details.                             ##
##                                                                           ##
##  You should have received a copy of the GNU General Public License        ##
##  along with this program; if not, write to the Free Software              ##
##  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA ##
###############################################################################
##  Author of this file: Freeform Solutions and NS Tai (aka tuff) and others ##
##  URL: http://www.brandycoke.com/                                          ##
##  Project: Formulize                                                       ##
###############################################################################

require_once XOOPS_ROOT_PATH.'/kernel/object.php';

global $xoopsDB;
define('formulize_TABLE', $xoopsDB->prefix("formulize"));

class formulizeformulize extends XoopsObject {
	
	var $isLinked;
	
	function formulizeformulize(){
		$this->XoopsObject();
	//	key, data_type, value, req, max, opt
		$this->initVar("id_form", XOBJ_DTYPE_INT, NULL, false);
		$this->initVar("ele_id", XOBJ_DTYPE_INT, NULL, false);
		$this->initVar("ele_type", XOBJ_DTYPE_TXTBOX, NULL, true, 100);
		$this->initVar("ele_caption", XOBJ_DTYPE_TXTAREA);
		// added descriptive text June 6 2006 -- jwe
		$this->initVar("ele_desc", XOBJ_DTYPE_TXTAREA);
		$this->initVar("ele_colhead", XOBJ_DTYPE_TXTBOX, NULL, false, 255);
		$this->initVar("ele_handle", XOBJ_DTYPE_TXTBOX, NULL, false, 255);
		$this->initVar("ele_order", XOBJ_DTYPE_INT);
		$this->initVar("ele_req", XOBJ_DTYPE_INT);
		$this->initVar("ele_value", XOBJ_DTYPE_ARRAY);
		$this->initVar("ele_uitext", XOBJ_DTYPE_ARRAY); // used for having an alternative text to display on screen, versus the actual value recorded in the database, for radio buttons, checkboxes and selectboxes
		$this->initVar("ele_delim", XOBJ_DTYPE_TXTBOX, NULL, true, 255);
		$this->initVar("ele_forcehidden", XOBJ_DTYPE_INT);
		$this->initVar("ele_private", XOBJ_DTYPE_INT);
 		// changed - start - August 19 2005 - jpc 		
		//$this->initVar("ele_display", XOBJ_DTYPE_INT);
		$this->initVar("ele_display", XOBJ_DTYPE_TXTBOX);
		// changed - end - August 19 2005 - jpc
		$this->initVar("ele_disabled", XOBJ_DTYPE_TXTBOX); // added June 17 2007 by jwe
		$this->initVar("ele_encrypt", XOBJ_DTYPE_INT); // added July 15 2009 by jwe
		$this->initVar("ele_filtersettings", XOBJ_DTYPE_ARRAY);
		
	}
	
}

class formulizeElementsHandler {
	var $db;
	function formulizeElementsHandler(&$db) {
		$this->db =& $db;
	}
	function &getInstance(&$db) {
		static $instance;
		if (!isset($instance)) {
			$instance = new formulizeElementsHandler($db);
		}
		return $instance;
	}
	function &create() {
		return new formulizeformulize();
	}

	function get($id){
		static $cachedElements = array();
		if(isset($cachedElements[$id])) {
			return $cachedElements[$id];
		}	
		if ($id > 0 AND is_numeric($id)) {
			$sql = 'SELECT * FROM '.formulize_TABLE.' WHERE ele_id='.$id;
			if (!$result = $this->db->query($sql)) {
				$cachedElements[$id] = false;
				return false;
			}
		} else {
			$sql = 'SELECT * FROM '.formulize_TABLE.' WHERE ele_handle="'.icms::$xoopsDB->escape($id).'"';
			if (!$result = $this->db->query($sql)) {
				$cachedElements[$id] = false;
				return false;
			}
		}
		$numrows = $this->db->getRowsNum($result);
		if ($numrows == 1) {
			// instantiate the right kind of element, depending on the type
			$array = $this->db->fetchArray($result);
			$ele_type = $array['ele_type'];
			if(file_exists(XOOPS_ROOT_PATH."/modules/formulize/class/".$ele_type."Element.php")) {
				$customTypeHandler = icms_getModuleHandler($ele_type."Element", 'formulize');
				$element = $customTypeHandler->create();
			} else {
				$element = new formulizeformulize();
			}
			$element->assignVars($array);
			$element->isLinked = false;
			if($element->getVar('ele_type')=="select") {
				$ele_value = $element->getVar('ele_value');
				if(!is_array($ele_value[2])) {
					$element->isLinked = strstr($ele_value[2], "#*=:*") ? true : false;
				}
			} 
			$cachedElements[$id] = $element;
			return $element;
		}
		return false;
	}

	function insert(&$element, $force = false){
        if( get_class($element) != 'formulizeformulize' AND is_subclass_of($element, 'formulizeformulize') == false){
            return false;
        }
        if( !$element->isDirty() ){
            return true;
        }
        if( !$element->cleanVars() ){
            return false;
        }
				foreach( $element->cleanVars as $k=>$v ){
					${$k} = $v;
				}
   		if( $element->isNew() || $ele_id == 0){
				$sql = sprintf("INSERT INTO %s (
				id_form, ele_type, ele_caption, ele_desc, ele_colhead, ele_handle, ele_order, ele_req, ele_value, ele_uitext, ele_delim, ele_display, ele_disabled, ele_forcehidden, ele_private, ele_encrypt, ele_filtersettings
				) VALUES (
				%u, %s, %s, %s, %s, %s, %u, %u, %s, %s, %s, %s, %s, %u, %u, %u, %s
				)",
				formulize_TABLE,
				$id_form,
				$this->db->quoteString($ele_type),
				$this->db->quoteString($ele_caption),
				$this->db->quoteString($ele_desc),
				$this->db->quoteString($ele_colhead),
				$this->db->quoteString($ele_handle),
				$ele_order,
				$ele_req,
				$this->db->quoteString($ele_value),
				$this->db->quoteString($ele_uitext),
				$this->db->quoteString($ele_delim),
				$this->db->quoteString($ele_display),
				$this->db->quoteString($ele_disabled),
				$ele_forcehidden,
				$ele_private,
				$ele_encrypt,
				$this->db->quoteString($ele_filtersettings)
			);            
            // changed - end - August 19 2005 - jpc
			}else{
            // changed - start - August 19 2005 - jpc
            $sql = sprintf("UPDATE %s SET
				ele_type = %s,
				ele_caption = %s,
				ele_desc = %s,
				ele_colhead = %s,
				ele_handle = %s,
				ele_order = %u,
				ele_req = %u,
				ele_value = %s,
				ele_uitext = %s,
				ele_delim = %s,
				ele_display = %s,
				ele_disabled = %s,
				ele_forcehidden = %u,
				ele_private = %u,
				ele_encrypt = %u,
				ele_filtersettings = %s
				WHERE ele_id = %u AND id_form = %u",
				formulize_TABLE,
				$this->db->quoteString($ele_type),
				$this->db->quoteString($ele_caption),
				$this->db->quoteString($ele_desc),
				$this->db->quoteString($ele_colhead),
				$this->db->quoteString($ele_handle),
				$ele_order,
				$ele_req,
				$this->db->quoteString($ele_value),
				$this->db->quoteString($ele_uitext),
				$this->db->quoteString($ele_delim),
				$this->db->quoteString($ele_display),
				$this->db->quoteString($ele_disabled),
				$ele_forcehidden,
				$ele_private,
				$ele_encrypt,
				$this->db->quoteString($ele_filtersettings),
				$ele_id,
				$id_form
			);
            // changed - end - August 19 2005 - jpc
 		}
		
	
        if( false != $force ){
            $result = $this->db->queryF($sql);
        }else{
            $result = $this->db->query($sql);
        }

		if( !$result ){
			print "Error: this element could not be saved in the database.  SQL: $sql<br>".icms::$xoopsDB->error();
			return false;
		}
		if( $ele_id == 0 ){ // only occurs for new elements
			$ele_id = $this->db->getInsertId();
			$element->setVar('ele_id', $ele_id);
			if(!$element->getVar('ele_handle')) { // set the handle same as the element id on new elements, as long as the handle wasn't actually passed in with the element
				$element->setVar('ele_handle', $ele_id);
				$this->insert($element); 
			}
		}
		if($ele_handle === "") {
			$form_handler =& icms_getModuleHandler('forms', 'formulize');
			$ele_handle = $ele_id;
      while(!$uniqueCheck = $form_handler->isHandleUnique($ele_handle, $ele_id)) {
        $ele_handle = $ele_handle . "_copy";
      }	    
			$element->setVar('ele_handle', $ele_handle); 
			$this->insert($element);
		}
		return $ele_id;
	}
	
	function delete(&$element, $force = false){
		
		if( strtolower(get_class($this)) != 'formulizeelementshandler') {
			return false;
		}

		global $xoopsDB;

		$sql = "DELETE FROM ".formulize_TABLE." WHERE ele_id=".$element->getVar("ele_id")."";
        if( false != $force ){
            $result = $this->db->queryF($sql);
        }else{
            $result = $this->db->query($sql);
        }
		// delete from frameworks table too -- added July 27 2006
		$sql = "DELETE FROM ". $xoopsDB->prefix('formulize_framework_elements') . " WHERE fe_element_id=".$element->getVar("ele_id");
	        if( false != $force ){
      	      $result = $this->db->queryF($sql);
	        }else{
      	      $result = $this->db->query($sql);
	        }
		return true;
	}

	// this function added by jwe Aug 14 2005 -- deletes the data associated with a particular element in a particular form
	function deleteData(&$element, $force = false){
		if( strtoupper(get_class($this)) != strtoupper('formulizeelementshandler')) {
			return false;
		}
		$form_handler =& icms_getModuleHandler('forms', 'formulize');
		if(!$deleteResult = $form_handler->deleteElementField($element->getVar('ele_id'))) {
			print "Error: could not drop field from data table";
			return false;
		}
		return true;
	}

	function &getObjects2($criteria = null, $id_form , $id_as_key = false){
		$ret = array();
		$limit = $start = 0;
//		awareness of $criteria added, Sept 1 2005, jwe
//		removal of ele_display=1 from next line and addition of the renderWhere line in the conditional below
		$sql = 'SELECT * FROM '.formulize_TABLE.' WHERE id_form='.$id_form;


		if( isset($criteria)) { 
			$sql .= $criteria->render() ? ' AND ('.$criteria->render().')' : '';
			if( $criteria->getSort() != '' ){
				$criteriaByClause = ' ORDER BY '.$criteria->getSort().' '.$criteria->getOrder();
			}
			$limit = $criteria->getLimit();
			$start = $criteria->getStart();
		}
		if(!isset($criteriaByClause)) {
			$sql .= " ORDER BY ele_order ASC";
		} else {
			$sql .= $criteriaByClause;
		}
		$result = $this->db->query($sql, $limit, $start);

		if( !$result ){
			return false;
		}
		while( $myrow = $this->db->fetchArray($result) ){
			// instantiate the right kind of element, depending on the type
			$ele_type = $myrow['ele_type'];
			if(file_exists(XOOPS_ROOT_PATH."/modules/formulize/class/".$ele_type."Element.php")) {
				$customTypeHandler = icms_getModuleHandler($ele_type."Element", 'formulize');
				$elements = $customTypeHandler->create();
			} else {
				$elements = new formulizeformulize();
			}
			$elements->assignVars($myrow);
			$elements->isLinked = false;
			if($elements->getVar('ele_type')=="select") {
				$ele_value = $elements->getVar('ele_value');
				if(!is_array($ele_value[2])) {
					$elements->isLinked = strstr($ele_value[2], "#*=:*") ? true : false;
				}
			}
			if( !$id_as_key ){
				$ret[] =& $elements;
			}else{
				$ret[$myrow['ele_id']] =& $elements;
			}
			unset($elements);
		}
		return $ret;
	}

	
    function getCount($criteria = null){
		$sql = 'SELECT COUNT(*) FROM '.formulize_TABLE;
		if( isset($criteria) ) { 
			$sql .= ' '.$criteria->renderWhere();
		}
		$result = $this->db->query($sql);
		if( !$result ){
			return 0;
		}
		list($count) = $xoopsDB->fetchRow($result);
		return $count;
	}
    
    function deleteAll($criteria = null){
    	global $xoopsDB;
		$sql = 'DELETE FROM '.formulize_TABLE;
		if( isset($criteria) ) { 
			$sql .= ' '.$criteria->renderWhere();
		}
		if( !$result = $this->db->query($sql) ){
			return false;
		}
		return true;
	}
	
	// this method returns the id number of the element with the next highest order, below the specified order, in the specified form
	function getPreviousElement($order, $fid) {
		global $xoopsDB;
		$sql = "SELECT ele_id FROM ".$xoopsDB->prefix("formulize")." WHERE ele_order < $order AND id_form = $fid ORDER BY ele_order DESC LIMIT 0,1";
		if($result = $xoopsDB->query($sql)) {
			$array = $xoopsDB->fetchArray($result);
			return $array['ele_id'];
		} else {
			return false;
		}
	}
	
	// this method is used by custom elements, to do final output from the "local" formatDataForList method, so the custom element developer can simply set booleans there, and they will be enforced here
	function formatDataForList($value) {
		global $myts;
		if($this->length == 0) {
			$this->length = 35;
		}
		if($this->striphtml !== false) { // want to do this all the time, no matter what, unless the user specifically turns it off, because it's a security precaution
			$value = $myts->htmlSpecialChars($value, ENT_QUOTES);
		}
		$value = printSmart(trans($value),$this->length);
		if($this->clickable) {
			$value = $myts->makeClickable($value);
		}
		return $value;
	}
	
}

?>