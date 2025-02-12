<?php
/**
* Contains the classes responsible for displaying a tree table filled with IcmsPersistableObject
*
* @copyright	The ImpressCMS Project http://www.impresscms.org/
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package		IcmsPersistableObject
* @since		1.1
* @author		marcan <marcan@impresscms.org>
* @version		$Id: icmspersistabletreetable.php 8545 2009-04-11 10:32:12Z icmsunderdog $
*/

if (!defined('ICMS_ROOT_PATH')) die("ImpressCMS root path not defined");

include_once(ICMS_ROOT_PATH . "/kernel/icmspersistabletable.php");

/**
* IcmsPersistableTreeTable base class
*
* Base class representing a table for displaying IcmsPersistableObject tree objects
*
* @copyright	The ImpressCMS Project http://www.impresscms.org/
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package		IcmsPersistableObject
* @since		1.1
* @author		marcan <marcan@impresscms.org>
* @version		$Id: icmspersistabletreetable.php 8545 2009-04-11 10:32:12Z icmsunderdog $

 */
class IcmsPersistableTreeTable extends IcmsPersistableTable {

	function IcmsPersistableTreeTable(&$objectHandler, $criteria=false, $actions=array('edit', 'delete'), $userSide=false)
	{
		$this->IcmsPersistableTable($objectHandler, $criteria, $actions, $userSide);
		$this->_isTree = true;
	}
	/**
	 * Get children objects given a specific category_pid
	 *
	 * @var int $category_pid id of the parent which children we want to retreive
	 * @return array of IcmsPersistableObject
	 */
	function getChildrenOf($category_pid=0) {
		return isset($this->_objects[$category_pid]) ? $this->_objects[$category_pid] : false;
	}

	function createTableRow($object, $level=0) {

		$aObject = array();

		$i=0;

		$aColumns = array();
		$doWeHaveActions = false;

		foreach ($this->_columns as $column) {

			$aColumn = array();

			if ($i==0) {
				$class = "head";
			} elseif ($i % 2 == 0) {
				$class = "even";
			} else {
				$class = "odd";
			}

			if ($column->_customMethodForValue && method_exists($object, $column->_customMethodForValue)) {
				$method = $column->_customMethodForValue;
				$value = $object->$method();
			} else {
				/**
				 * If the column is the identifier, then put a link on it
				 */
				if ($column->getKeyName() == $this->_objectHandler->identifierName) {
					$value = $object->getItemLink();
				} else {
					$value = $object->getVar($column->getKeyName());
				}
			}

			$space = '';
			if($column->getKeyName() == $this->_objectHandler->identifierName){
				for ($i = 0; $i < $level; $i++) {
					$space = $space . '--';
				}
		}

			if ($space != '') {
				$space .= '&nbsp;';
			}

			$aColumn['value'] = $space . $value;
			$aColumn['class'] = $class;
			$aColumn['width'] = $column->getWidth();
			$aColumn['align'] = $column->getAlign();
			$aColumn['key'] = $column->getKeyName();

			$aColumns[] = $aColumn;
			$i++;
		}

		$aObject['columns'] = $aColumns;

		$class = $class == 'even' ? 'odd' : 'even';
		$aObject['class'] = $class;

		$actions = array();

		// Adding the custom actions if any
		foreach ($this->_custom_actions as $action) {
			if (method_exists($object, $action)) {
				$actions[] = $object->$action();
			}
		}

		include_once ICMS_ROOT_PATH . "/kernel/icmspersistablecontroller.php";
		$controller = new IcmsPersistableController($this->_objectHandler);

		if (in_array('edit', $this->_actions)) {
			$actions[] = $controller->getEditItemLink($object, false, true);
		}
		if (in_array('delete', $this->_actions)) {
			$actions[] = $controller->getDeleteItemLink($object, false, true);
		}
		$aObject['actions'] = $actions;

		$this->_tpl->assign('icms_actions_column_width', count($actions) * 30);
		$aObject['id'] = $object->id();
		$this->_aObjects[] = $aObject;

		$childrenObjects = $this->getChildrenOf($object->id());

		$this->_hasActions =$this->_hasActions  ? true : count($actions) > 0;

		if ($childrenObjects) {
			$level++;
			foreach ($childrenObjects as $subObject) {
				$this->createTableRow($subObject, $level);
			}
		}
	}

	function createTableRows() {
		$this->_aObjects = array();

		if (count($this->_objects) > 0) {

			foreach ($this->getChildrenOf() as $object) {
				$this->createTableRow($object);
			}

			$this->_tpl->assign('icms_persistable_objects', $this->_aObjects);
		} else {
			$colspan = count($this->_columns) + 1;
			$this->_tpl->assign('icms_colspan', $colspan);
		}
	}

	function fetchObjects() {
		$ret = $this->_objectHandler->getObjects($this->_criteria, 'parentid');
		return $ret;

	}
}

?>