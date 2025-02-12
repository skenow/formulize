<?php
/**
* Handles all tree functions within ImpressCMS
*
* @copyright	http://www.xoops.org/ The XOOPS Project
* @copyright	XOOPS_copyrights.txt
* @copyright	http://www.impresscms.org/ The ImpressCMS Project
* @license	LICENSE.txt
* @package	core
* @since	XOOPS
* @author	http://www.xoops.org The XOOPS Project
* @author	modified by UnderDog <underdog@impresscms.org>
* @version	$Id: xoopstree.php 8656 2009-05-01 01:01:39Z skenow $
*/

/**
 * Class XoopsTree
 * @package XoopsTree 
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since XOOPS
 * @author Kazumi Ono (AKA onokazu)
 */ 
class XoopsTree
{
  /** @var string table with parent-child structure */
	var $table;
  /** @var string name of unique id for records in table $table */   
	var $id;
  /** @var string name of parent id used in table $table */    
	var $pid;
  /** @var string specifies the order of query results */	
  var $order;
  /** @var string name of a field in table $table which will be used when  selection box and paths are generated */	
  var $title;
  /** @var object an instance of the database object */	
	var $db;


	/**
  * Constructor of class XoopsTree
	* Sets the names of table, unique id, and parent id
	* @param string $table_name Name of table containing the parent-child structure
	* @param string $id_name Name of the unique id field in the table 
	* @param $pid_name Name of the parent id field in the table
	**/
	function XoopsTree($table_name, $id_name, $pid_name)
	{
		$this->db =& Database::getInstance();
		$this->table = $table_name;
		$this->id = $id_name;
		$this->pid = $pid_name;
	}

	/**
  * Returns an array of first child objects for a given id($sel_id) 
	* @param integer $sel_id
	* @param string $order Sort field for the list 
	* @return array $arr
	**/
	function getFirstChild($sel_id, $order='')
	{
		$sel_id = intval($sel_id);
		$arr =array();
		$sql = 'SELECT * FROM '.$this->table.' WHERE '.$this->pid.'="'.$sel_id.'"';
		if ( $order != '' ) {
			$sql .= ' ORDER BY '.$order;
		}
		$result = $this->db->query($sql);
		$count = $this->db->getRowsNum($result);
		if ( $count==0 ) {
			return $arr;
		}
		while ( $myrow=$this->db->fetchArray($result) ) {
			array_push($arr, $myrow);
		}
		return $arr;
	}

	/**
  * Returns an array of all FIRST child ids of a given id($sel_id) 
	* @param integer $sel_id
	* @return array $idarray
	**/
	function getFirstChildId($sel_id)
	{
		$sel_id = intval($sel_id);
		$idarray =array();
		$result = $this->db->query('SELECT '.$this->id.' FROM '.$this->table.' WHERE '.$this->pid.'="'.$sel_id.'"');
		$count = $this->db->getRowsNum($result);
		if ( $count == 0 ) {
			return $idarray;
		}
		while ( list($id) = $this->db->fetchRow($result) ) {
			array_push($idarray, $id);
		}
		return $idarray;
	}

	/**
  * Returns an array of ALL child ids for a given id($sel_id) 
	* @param integer $sel_id
	* @param string $order Sort field for the list
	* @param array $idarray
	* @return array $idarray
	**/
	function getAllChildId($sel_id, $order='', $idarray = array())
	{
		$sel_id = intval($sel_id);
		$sql = 'SELECT '.$this->id.' FROM '.$this->table.' WHERE '.$this->pid.'="'.$sel_id.'"';
		if ( $order != '' ) {
			$sql .= ' ORDER BY '.$order;
		}
		$result=$this->db->query($sql);
		$count = $this->db->getRowsNum($result);
		if ( $count==0 ) {
			return $idarray;
		}
		while ( list($r_id) = $this->db->fetchRow($result) ) {
			array_push($idarray, $r_id);
			$idarray = $this->getAllChildId($r_id,$order,$idarray);
		}
		return $idarray;
	}

	/**
  * Returns an array of ALL parent ids for a given id($sel_id) 
	* @param integer $sel_id
	* @param string $order
	* @param array $idarray
	* @return array $idarray
	**/
	function getAllParentId($sel_id, $order='', $idarray = array())
	{
		$sel_id = intval($sel_id);
		$sql = 'SELECT '.$this->pid.' FROM '.$this->table.' WHERE '.$this->id.'="'.$sel_id.'"';
		if ( $order != '' ) {
			$sql .= ' ORDER BY '.$order;
		}
		$result=$this->db->query($sql);
		list($r_id) = $this->db->fetchRow($result);
		if ( $r_id == 0 ) {
			return $idarray;
		}
		array_push($idarray, $r_id);
		$idarray = $this->getAllParentId($r_id,$order,$idarray);
		return $idarray;
	}

	/**
  * Generates path from the root id to a given id($sel_id)
	* the path is delimited with "/"
	* @param integer $sel_id
	* @param string $title
	* @param string $path
	* @return string $path
	*/
	function getPathFromId($sel_id, $title, $path='')
	{
		$sel_id = intval($sel_id);
		$result = $this->db->query('SELECT '.$this->pid.', '.$title.' FROM '.$this->table.' WHERE '.$this->id.'="'.$sel_id.'"');
		if ( $this->db->getRowsNum($result) == 0 ) {
			return $path;
		}
		list($parentid,$name) = $this->db->fetchRow($result);
		$myts =& MyTextSanitizer::getInstance();
		$name = $myts->makeTboxData4Show($name);
		$path = '/'.$name.$path.'';
		if ( $parentid == 0 ) {
			return $path;
		}
		$path = $this->getPathFromId($parentid, $title, $path);
		return $path;
	}

	/**
  * Makes a nicely ordered selection box
	* @param string $title Field containing the items to display in the list
	* @param string $order Sort order of the options
	* @param integer $preset_id is used to specify a preselected item	
	* @param integer $none set to 1 to add an option with value 0 
	* @param string $sel_name Name of the select element
	* @param string $onchange	Action to take when the selection is changed
	**/
	function makeMySelBox($title,$order='',$preset_id=0, $none=0, $sel_name='', $onchange="")
	{
		if ( $sel_name == "" ) {
			$sel_name = $this->id;
		}
		$myts =& MyTextSanitizer::getInstance();
		echo "<select name='".$sel_name."'";
		if ( $onchange != "" ) {
			echo " onchange='".$onchange."'";
		}
		echo ">\n";
		$sql = "SELECT ".$this->id.", ".$title." FROM ".$this->table." WHERE ".$this->pid."='0'";
		if ( $order != "" ) {
			$sql .= " ORDER BY $order";
		}
		$result = $this->db->query($sql);
		if ( $none ) {
			echo "<option value='0'>----</option>\n";
		}
		while ( list($catid, $name) = $this->db->fetchRow($result) ) {
			$sel = "";
			if ( $catid == $preset_id ) {
				$sel = " selected='selected'";
			}
			echo "<option value='$catid'$sel>$name</option>\n";
			$sel = "";
			$arr = $this->getChildTreeArray($catid, $order);
			foreach ( $arr as $option ) {
				$option['prefix'] = str_replace(".","--",$option['prefix']);
				$catpath = $option['prefix']."&nbsp;".$myts->makeTboxData4Show($option[$title]);
				if ( $option[$this->id] == $preset_id ) {
					$sel = " selected='selected'";
				}
				echo "<option value='".$option[$this->id]."'$sel>$catpath</option>\n";
				$sel = "";
			}
		}
		echo "</select>\n";
	}


  /**
  * Generates nicely formatted linked path from the root id to a given id
  * @param integer $sel_id
  * @param string $title
  * @param string $funcURL
  * @param string $path
  * @param string $separator Allows custom designation of separator in linked path
  * $return string $path
  **/
  function getNicePathFromId($sel_id, $title, $funcURL, $path='', $separator=_BRDCRMB_SEP)
  {
    $path = !empty($path) ? $separator.$path : $path;
    $sel_id = intval($sel_id);
    $sql = 'SELECT '.$this->pid.', '.$title.' FROM '.$this->table.' WHERE '.$this->id.'="'.$sel_id.'"';
    $result = $this->db->query($sql);
    if ( $this->db->getRowsNum($result) == 0 ) {
        return $path;
    }
    list($parentid,$name) = $this->db->fetchRow($result);
    $myts =& MyTextSanitizer::getInstance();
    $name = $myts->makeTboxData4Show($name);
    $path = '<a href="'.$funcURL.'&amp;'.$this->id.'='.$sel_id.'">'.$name.'</a>'.$path."";
    if ( $parentid == 0 ) {
        return $path;
    }
    $path = $this->getNicePathFromId($parentid, $title, $funcURL, $path, $separator);
    return $path;
  }

	/**
  * Generates id path from the root id to a given id 
	* the path is delimited with "/"
	* @param integer $sel_id
	* @param string $path
	* @return string $path
	**/
	function getIdPathFromId($sel_id, $path="")
	{
		$sel_id = intval($sel_id);
		$result = $this->db->query('SELECT '.$this->pid.' FROM '.$this->table.' WHERE '.$this->id.'="'.$sel_id.'"');
		if ( $this->db->getRowsNum($result) == 0 ) {
			return $path;
		}
		list($parentid) = $this->db->fetchRow($result);
		$path = '/'.$sel_id.$path.'';
		if ( $parentid == 0 ) {
			return $path;
		}
		$path = $this->getIdPathFromId($parentid, $path);
		return $path;
	}

  /**
  * @param integer $sel_id
  * @param string $order
  * @param array $parray
  * @return array $parray
  **/
	function getAllChild($sel_id=0, $order='', $parray = array())
	{
		$sel_id = intval($sel_id);
		$sql = 'SELECT * FROM '.$this->table.' WHERE '.$this->pid.'="'.$sel_id.'"';
		if ( $order != '' ) {
			$sql .= ' ORDER BY '.$order;
		}
		$result = $this->db->query($sql);
		$count = $this->db->getRowsNum($result);
		if ( $count == 0 ) {
			return $parray;
		}
		while ( $row = $this->db->fetchArray($result) ) {
			array_push($parray, $row);
			$parray=$this->getAllChild($row[$this->id],$order,$parray);
		}
		return $parray;
	}

  /**
  * @param integer $sel_id
  * @param string $order
  * @param array $parray
  * @param string $r_prefix
  * @return array $parray
  **/
	function getChildTreeArray($sel_id=0,$order='',$parray = array(),$r_prefix='')
	{
		$sel_id = intval($sel_id);
		$sql = 'SELECT * FROM '.$this->table.' WHERE '.$this->pid.'="'.$sel_id.'"';
		if ( $order != '' ) {
			$sql .= ' ORDER BY '.$order;
		}
		$result = $this->db->query($sql);
		$count = $this->db->getRowsNum($result);
		if ( $count == 0 ) {
			return $parray;
		}
		while ( $row = $this->db->fetchArray($result) ) {
			$row['prefix'] = $r_prefix.'.';
			array_push($parray, $row);
			$parray = $this->getChildTreeArray($row[$this->id],$order,$parray,$row['prefix']);
		}
		return $parray;
	}
}
?>