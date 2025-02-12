<?php
/**
* Manage of imagesets baseclass
*
* @copyright	http://www.xoops.org/ The XOOPS Project
* @copyright	XOOPS_copyrights.txt
* @copyright	http://www.impresscms.org/ The ImpressCMS Project
* @license	LICENSE.txt
* @package	core
* @since	XOOPS
* @author	http://www.xoops.org The XOOPS Project
* @author	modified by UnderDog <underdog@impresscms.org>
* @version	$Id: imageset.php 9520 2009-11-11 14:32:52Z pesianstranger $
*/

if (!defined('XOOPS_ROOT_PATH')) {
	exit();
}


/**
 *
 *
 * @package     kernel
 *
 * @author	    Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 */

/**
 * An imageset
 *
 * These sets are managed through a {@link XoopsImagesetHandler} object
 *
 * @package     kernel
 *
 * @author	    Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 */
class XoopsImageset extends XoopsObject
{
    /**
     * Constructor
     *
     */
  	function XoopsImageset()
  	{
  		$this->XoopsObject();
  		$this->initVar('imgset_id', XOBJ_DTYPE_INT, null, false);
  		$this->initVar('imgset_name', XOBJ_DTYPE_TXTBOX, null, true, 50);
  		$this->initVar('imgset_refid', XOBJ_DTYPE_INT, 0, false);
  	}
}



/**
* XOOPS imageset handler class.
* This class is responsible for providing data access mechanisms to the data source
* of XOOPS imageset class objects.
*
*
* @author  Kazumi Ono <onokazu@xoops.org>
*/
class XoopsImagesetHandler extends XoopsObjectHandler
{


    /**
     * Creates a new imageset
     *
  	 * @param bool $isNew is the new imageset new??
  	 * @return object $imgset {@link XoopsImageset} reference to the new imageset
     **/
    function &create($isNew = true)
    {
        $imgset = new XoopsImageset();
        if ($isNew) {
            $imgset->setNew();
        }
        return $imgset;
    }


    /**
     * retrieve a specific {@link XoopsImageset}
     *
  	 * @see XoopsImageset
  	 * @param integer $id imgsetID (imgset_id) of the imageset
  	 * @return object XoopsImageset reference to the image set
     **/
    function &get($id)
    {
      $id = intval($id);
      $imgset = false;
    	if ($id > 0) {
            $sql = "SELECT * FROM ".$this->db->prefix('imgset')." WHERE imgset_id='".$id."'";
            if (!$result = $this->db->query($sql)) {
                return $imgset;
            }
            $numrows = $this->db->getRowsNum($result);
            if ($numrows == 1) {
                $imgset = new XoopsImageset();
                $imgset->assignVars($this->db->fetchArray($result));
            }
        }
        return $imgset;
    }


    /**
     * Insert a new {@link XoopsImageset} into the database
     *
  	 * @param object XoopsImageset $imgset reference to the imageset to insert
  	 * @return bool TRUE if succesful
     **/
    function insert(&$imgset)
    {
        /**
        * @TODO: Change to if (!(class_exists($this->className) && $obj instanceof $this->className)) when going fully PHP5
        */
        if (!is_a($imgset, 'xoopsimageset')) {
            return false;
        }

        if (!$imgset->isDirty()) {
            return true;
        }
        if (!$imgset->cleanVars()) {
            return false;
        }
        foreach ($imgset->cleanVars as $k => $v) {
            ${$k} = $v;
        }
        if ($imgset->isNew()) {
            $imgset_id = $this->db->genId('imgset_imgset_id_seq');
            $sql = sprintf("INSERT INTO %s (imgset_id, imgset_name, imgset_refid) VALUES ('%u', %s, '%u')", $this->db->prefix('imgset'), intval($imgset_id), $this->db->quoteString($imgset_name), intval($imgset_refid));
        } else {
            $sql = sprintf("UPDATE %s SET imgset_name = %s, imgset_refid = '%u' WHERE imgset_id = '%u'", $this->db->prefix('imgset'), $this->db->quoteString($imgset_name), intval($imgset_refid), intval($imgset_id));
        }
        if (!$result = $this->db->query($sql)) {
            return false;
        }
        if (empty($imgset_id)) {
            $imgset_id = $this->db->getInsertId();
        }
    		$imgset->assignVar('imgset_id', $imgset_id);
        return true;
    }


    /**
     * delete an {@link XoopsImageset} from the database
     *
  	 * @param object XoopsImageset $imgset reference to the imageset to delete
  	 * @return bool TRUE if succesful
     **/
    function delete(&$imgset)
    {
        /**
        * @TODO: Change to if (!(class_exists($this->className) && $obj instanceof $this->className)) when going fully PHP5
        */
        if (!is_a($imgset, 'xoopsimageset')) {
            return false;
        }

        $sql = sprintf("DELETE FROM %s WHERE imgset_id = '%u'", $this->db->prefix('imgset'), intval($imgset->getVar('imgset_id')));
        if (!$result = $this->db->query($sql)) {
            return false;
        }
        $sql = sprintf("DELETE FROM %s WHERE imgset_id = '%u'", $this->db->prefix('imgset_tplset_link'), intval($imgset->getVar('imgset_id')));
        $this->db->query($sql);
        return true;
    }


    /**
     * retrieve array of {@link XoopsImageset}s meeting certain conditions
  	 * @param object $criteria {@link CriteriaElement} with conditions for the imagesets
  	 * @param bool $id_as_key should the imageset's imgset_id be the key for the returned array?
  	 * @return array {@link XoopsImageset}s matching the conditions
     **/
    function getObjects($criteria = null, $id_as_key = false)
    {
        $ret = array();
        $limit = $start = 0;
        $sql = 'SELECT DISTINCT i.* FROM '.$this->db->prefix('imgset'). ' i LEFT JOIN '.$this->db->prefix('imgset_tplset_link'). ' l ON l.imgset_id=i.imgset_id';
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $sql .= ' '.$criteria->renderWhere();
            $limit = $criteria->getLimit();
            $start = $criteria->getStart();
        }
        $result = $this->db->query($sql, $limit, $start);
        if (!$result) {
            return $ret;
        }
        while ($myrow = $this->db->fetchArray($result)) {
            $imgset = new XoopsImageset();
            $imgset->assignVars($myrow);
            if (!$id_as_key) {
                $ret[] =& $imgset;
            } else {
                $ret[$myrow['imgset_id']] =& $imgset;
            }
            unset($imgset);
        }
        return $ret;
    }




    /**
     * Links a {@link XoopsImageset} to a themeset (tplset)
  	 * @param int $imgset_id image set id to link
  	 * @param int $tplset_name theme set to link
  	 * @return bool TRUE if succesful FALSE if unsuccesful
     **/
    function linkThemeset($imgset_id, $tplset_name)
    {
        $imgset_id = intval($imgset_id);
        $tplset_name = trim($tplset_name);
        if ($imgset_id <= 0 || $tplset_name == '') {
            return false;
        }
        if (!$this->unlinkThemeset($imgset_id, $tplset_name)) {
            return false;
        }
        $sql = sprintf("INSERT INTO %s (imgset_id, tplset_name) VALUES ('%u', %s)", $this->db->prefix('imgset_tplset_link'), $imgset_id, $this->db->quoteString($tplset_name));
        $result = $this->db->query($sql);
        if (!$result) {
            return false;
        }
        return true;
    }



    /**
     * Unlinks a {@link XoopsImageset} from a themeset (tplset)
     *
  	 * @param int $imgset_id image set id to unlink
  	 * @param int $tplset_name theme set to unlink
  	 * @return bool TRUE if succesful FALSE if unsuccesful
     **/
    function unlinkThemeset($imgset_id, $tplset_name)
    {
        $imgset_id = intval($imgset_id);
        $tplset_name = trim($tplset_name);
        if ($imgset_id <= 0 || $tplset_name == '') {
            return false;
        }
        $sql = sprintf("DELETE FROM %s WHERE imgset_id = '%u' AND tplset_name = %s", $this->db->prefix('imgset_tplset_link'), $imgset_id, $this->db->quoteString($tplset_name));
        $result = $this->db->query($sql);
        if (!$result) {
            return false;
        }
        return true;
    }


    /**
     * get a list of {@link XoopsImageset}s matching certain conditions
  	 *
  	 * @param int $refid conditions to match
  	 * @param int $tplset conditions to match
  	 * @return array array of {@link XoopsImageset}s matching the conditions
     **/
    function getList($refid = null, $tplset = null)
    {
        $criteria = new CriteriaCompo();
        if (isset($refid)) {
            $criteria->add(new Criteria('imgset_refid', intval($refid)));
        }
        if (isset($tplset)) {
            $criteria->add(new Criteria('tplset_name', $tplset));
        }
        $imgsets =& $this->getObjects($criteria, true);
        $ret = array();
        foreach (array_keys($imgsets) as $i) {
            $ret[$i] = $imgsets[$i]->getVar('imgset_name');
        }
        return $ret;
    }
}


?>