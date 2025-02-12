<?php
/**
* ImpressCMS Block Persistable Class for Configure
*
*
* @copyright 	The ImpressCMS Project <http://www.impresscms.org>
* @license		GNU General Public License (GPL) <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
* @since 		ImpressCMS 1.2
* @package Administration
* @version		$Id: blocksadmin.php 9517 2009-11-09 18:48:53Z Phoenyx $
* @author		Gustavo Pilla (aka nekro) <nekro@impresscms.org>
* @author		Rodrigo Pereira Lima (aka therplima) <therplima@impresscms.org>
*/

defined('ICMS_ROOT_PATH') or die('ImpressCMS root path not defined');

include_once ICMS_ROOT_PATH . '/kernel/block.php';

/**
 * System Block Configuration Object Class
 *
 * @since ImpressCMS 1.2
 * @author Gustavo Pilla (aka nekro) <nekro@impresscms.org>
 */
class SystemBlocksadmin extends IcmsBlock {

	/**
	 * Constructor
	 *
	 * @param SystemBlocksadminHandler $handler
	 */
	public function __construct(& $handler) {

		parent::__construct( $handler );

		$this->initNonPersistableVar('visiblein', XOBJ_DTYPE_OTHER, 'visiblein', false, false, false, true);

		$this->hideFieldFromForm('last_modified');
		$this->hideFieldFromForm('func_file');
		$this->hideFieldFromForm('show_func');
		$this->hideFieldFromForm('edit_func');
		$this->hideFieldFromForm('template');
		$this->hideFieldFromForm('dirname');
		$this->hideFieldFromForm('options');
		$this->hideFieldFromForm('bid');
		$this->hideFieldFromForm('mid');
		$this->hideFieldFromForm('func_num');
		$this->hideFieldFromForm('block_type');
		$this->hideFieldFromForm('isactive');

		$this->setControl('name', 'label');
		$this->setControl('visible', 'yesno');
		$this->setControl('bcachetime', array (
			'itemHandler' => 'blocksadmin',
			'method' => 'getBlockCacheTimeArray',
			'module' => 'system'
		));
		$this->setControl('side', array (
			'itemHandler' => 'blocksadmin',
			'method' => 'getBlockPositionArray',
			'module' => 'system'
		));
		$this->setControl('c_type', array (
			'itemHandler' => 'blocksadmin',
			'method' => 'getContentTypeArray',
			'module' => 'system'
		));

		$this->setControl('visiblein','page');
		$this->setControl('content', 'dhtmltextarea');
		$this->setControl('options','blockoptions');
	}

	public function getVar($key, $format = 's') {
		if ($format == 's' && in_array($key, array ( 'visible', 'mid','side'))) {
			return call_user_func(array ($this,	$key));
		}
		return parent :: getVar($key, $format);
	}

	private function weight(){
		$rtn = $this->getVar('weight','n');
		return $rtn;
	}

	private function visible(){
		if($this->getVar('visible','n') == 1)
			$rtn = '<a href="'.ICMS_URL.'/modules/system/admin.php?fct=blocksadmin&op=visible&bid='.$this->getVar('bid').'" title="'._VISIBLE.'" ><img src="'.ICMS_IMAGES_SET_URL.'/actions/button_ok.png" alt="'._VISIBLE.'"/></a>';
		else
			$rtn = '<a href="'.ICMS_URL.'/modules/system/admin.php?fct=blocksadmin&op=visible&bid='.$this->getVar('bid').'" title="'._VISIBLE.'" ><img src="'.ICMS_IMAGES_SET_URL.'/actions/button_cancel.png" alt="'._VISIBLE.'"/></a>';
		return $rtn;
	}

	private function mid(){
		$rtn = $this->handler->getModuleName( $this->getVar('mid','n') );
		return $rtn;
	}

	private function side(){
		$block_positions = $this->handler->getBlockPositions( true );
//		$form_object = new XoopsFormSelect( '', "side[".$this->getVar('bid','n')."]", $this->getVar('side','n'));
//		foreach ( array_keys( $block_positions ) as $j ) {
//			$form_object->addOption( $j, $block_positions[$j]);
//		}
//		$rtn = $form_object->render();
		$rtn = (defined($block_positions[$this->getVar('side','n')]['title'])) ? constant($block_positions[$this->getVar('side','n')]['title']) : $block_positions[$this->getVar('side','n')]['title'];
		return $rtn;
	}

	// Render Methods for Action Buttons
	public function getBlankLink() {
		return "<img src='" . ICMS_URL . "/images/blank.gif' width='22' alt=''  title='' />";
	}

	public function getUpActionLink(){
		$rtn = '<a href="'.ICMS_URL.'/modules/system/admin.php?fct=blocksadmin&op=up&bid='.$this->getVar('bid').'" title="'._UP.'" ><img src="'.ICMS_IMAGES_SET_URL.'/actions/up.png" alt="'._UP.'"/></a>';
		return $rtn;
	}

	public function getDownActionLink(){
		$rtn = '<a href="'.ICMS_URL.'/modules/system/admin.php?fct=blocksadmin&op=down&bid='.$this->getVar('bid').'" title="'._DOWN.'" ><img src="'.ICMS_IMAGES_SET_URL.'/actions/down.png" alt="'._DOWN.'"/></a>';
		return $rtn;
	}

	public function getCloneActionLink(){
		$rtn = '<a href="'.ICMS_URL.'/modules/system/admin.php?fct=blocksadmin&op=clone&bid='.$this->getVar('bid').'" title="'._CLONE.'" ><img src="'.ICMS_IMAGES_SET_URL.'/actions/editcopy.png" alt="'._CLONE.'"/></a>';
		return $rtn;
	}

	public function getEditActionLink(){
		$rtn = '<a href="'.ICMS_URL.'/modules/system/admin.php?fct=blocksadmin&op=mod&bid='.$this->getVar('bid').'" title="'._EDIT.'" ><img src="'.ICMS_IMAGES_SET_URL.'/actions/edit.png" alt="'._EDIT.'"/></a>';
		return $rtn;
	}

	public function getAdminViewItemLink(){
		$rtn = $this->getVar('title');
		return $rtn;
	}

	/**
	* getDeleteItemLink
	*
	* Overwrited Method
	*
	* @param string $onlyUrl
	* @param boolean $withimage
	* @param boolean $userSide
	* @return string
	*/
	public function getDeleteItemLink($onlyUrl=false, $withimage=true, $userSide=false){
		$ret = ICMS_URL. "/modules/system/admin.php?fct=blocksadmin&op=del&" . $this->handler->keyName . "=" . $this->getVar($this->handler->keyName);
		if ($onlyUrl) {
		  if($this->getVar('block_type') != 'C' && $this->getVar('block_type') != 'K') {
			return "";
		  } else {
			return $ret;
		  }
		} elseif($withimage) {
		  if($this->getVar('block_type') != 'C' && $this->getVar('block_type') != 'K') {
			return "<img src='" . ICMS_URL . "/images/blank.gif' width='22' alt=''  title='' />";
		  } else {
			return "<a href='" . $ret . "'><img src='" . ICMS_IMAGES_SET_URL . "/actions/editdelete.png' style='vertical-align: middle;' alt='" . _CO_ICMS_DELETE . "'  title='" . _CO_ICMS_DELETE . "' /></a>";
		  }
		}

		return "<a href='" . $ret . "'>" . $this->getVar($this->handler->identifierName) . "</a>";
	}



	/**
	 * Create the form for this object
	 *
	 * @return a {@link SmartobjectForm} object for this object
	 *
	 * @see IcmsPersistableObjectForm::IcmsPersistableObjectForm()
	 */
	function getForm($form_caption, $form_name, $form_action=false, $submit_button_caption = _CO_ICMS_SUBMIT, $cancel_js_action=false, $captcha=false){
		if( !$this->isNew() && $this->getVar('block_type') != 'C' ){
			$this->hideFieldFromForm('content');
			$this->hideFieldFromForm('c_type');
		}
		include_once ICMS_ROOT_PATH . "/class/icmsform/icmsform.php";
		$form = new IcmsForm($this, $form_name, $form_caption, $form_action, null, $submit_button_caption, $cancel_js_action, $captcha);
		return $form;
	}

	function getSideControl(){
		include_once ICMS_ROOT_PATH.'/class/xoopsformloader.php';
		$control = new XoopsFormSelect('','block_side[]',$this->getVar( 'side', 'e'));
		$positions = $this->handler->getBlockPositions( true );
		$block_positions = array();
		foreach ($positions as $k=>$position){
			$block_positions[$k] = defined($position['title'])?constant($position['title']):$position['title'];
		}
		$control->addOptionArray($block_positions);

		return $control->render();
	}

	function getWeightControl(){
		include_once ICMS_ROOT_PATH.'/class/xoopsformloader.php';
		$control = new XoopsFormText('','block_weight[]',5,10,$this->getVar( 'weight', 'e'));
		$control->setExtra('style="text-align:center;"');
		return $control->render();
	}

}

/**
 * System Block Configuration Object Handler Class
 *
 * @since ImpressCMS 1.2
 * @author Gustavo Pilla (aka nekro) <nekro@impresscms.org>
 */
class SystemBlocksadminHandler extends IcmsBlockHandler {

	private $block_positions;
	private $modules_name;

	public function __construct(& $db) {
		$this->IcmsPersistableObjectHandler($db, 'blocksadmin', 'bid', 'title', 'content', 'system');
		$this->table = $this->db->prefix('newblocks');

		$this->addPermission ( 'block_read', _CO_SYSTEM_BLOCKSADMIN_BLOCKRIGHTS, _CO_SYSTEM_BLOCKSADMIN_BLOCKRIGHTS_DSC );
	}

	public function getVisibleStatusArray(){
		$rtn = array();
		$rtn[1] = _VISIBLE;
		$rtn[0] = _INVISIBLE;
		return $rtn;
	}


//	public function getVisibleInArray(){
//		/* TODO: To be implemented... */
//  }

	public function getBlockPositionArray(){
		$block_positions = $this->getBlockPositions(true);
		$rtn = array();
		foreach ($block_positions as $k=>$v){
			$rtn[$k] = (defined($block_positions[$k]['title'])) ? constant($block_positions[$k]['title']) : $block_positions[$k]['title'];
		}
		return $rtn;
	}

	public function getContentTypeArray(){
		return array('H' => _AM_HTML, 'P' => _AM_PHP, 'S' => _AM_AFWSMILE, 'T' => _AM_AFNOSMILE);
	}

	public function getBlockCacheTimeArray(){
		$rtn = array('0' => _NOCACHE, '30' => sprintf(_SECONDS, 30), '60' => _MINUTE, '300' => sprintf(_MINUTES, 5), '1800' => sprintf(_MINUTES, 30), '3600' => _HOUR, '18000' => sprintf(_HOURS, 5), '86400' => _DAY, '259200' => sprintf(_DAYS, 3), '604800' => _WEEK, '2592000' => _MONTH);
		return $rtn;
	}

	public function getModulesArray($full = false){
		if( !count($this->modules_name) ){
			$icms_module_handler = xoops_gethandler('module');
			$installed_modules =& $icms_module_handler->getObjects();
			$this->modules_name[0]['name'] = _NONE;
			$this->modules_name[0]['dirname'] = '';
			foreach( $installed_modules as $module ){
				$this->modules_name[$module->getVar('mid')]['name'] = $module->getVar('name');
				$this->modules_name[$module->getVar('mid')]['dirname'] = $module->getVar('dirname');
			}
		}

		$rtn = $this->modules_name;
		if(!$full)
			foreach($this->modules_name as $key => $module)
				$rtn[$key] = $module['name'];
		return $rtn;
	}


	public function getModuleName($mid){
		if($mid == 0)
		return '';
		$modules = $this->getModulesArray();
		$rtn = $modules[$mid];
		return $rtn;
	}

	public function getModuleDirname($mid){
		$modules = $this->getModulesArray(true);
		$rtn = $modules[$mid]['dirname'];
		return $rtn;
	}

	public function upWeight( $bid ){
		$blockObj = $this->get($bid);
		$criteria = new CriteriaCompo();
		$criteria->setLimit(1);
		$criteria->setSort('weight');
		$criteria->setOrder('DESC');
		$criteria->add(new Criteria('side', $blockObj->vars['side']['value']));
		$criteria->add(new Criteria('weight', $blockObj->getVar('weight'), '<'));
		$sideBlocks = $this->getObjects($criteria);
		$weight = (is_array($sideBlocks) && count($sideBlocks) == 1) ? $sideBlocks[0]->getVar('weight') - 1 : $blockObj->getVar('weight') - 1;
		if ($weight < 0) $weight = 0;
		$blockObj->setVar('weight', $weight);
		$this->insert($blockObj, true);
	}

	public function downWeight( $bid ){
		$blockObj = $this->get($bid);
		$criteria = new CriteriaCompo();
		$criteria->setLimit(1);
		$criteria->setSort('weight');
		$criteria->setOrder('ASC');
		$criteria->add(new Criteria('side', $blockObj->vars['side']['value']));
		$criteria->add(new Criteria('weight', $blockObj->getVar('weight'), '>'));
		$sideBlocks = $this->getObjects($criteria);
		$weight = (is_array($sideBlocks) && count($sideBlocks) == 1) ? $sideBlocks[0]->getVar('weight') + 1 : $blockObj->getVar('weight') + 1;
		$blockObj->setVar('weight', $weight);
		$this->insert($blockObj, true);
	}

	public function changeVisible( $bid ){
		$blockObj = $this->get($bid);
		if($blockObj->getVar('visible' ,'n'))
		$blockObj->setVar('visible', 0);
		else
		$blockObj->setVar('visible', 1);
		$this->insert($blockObj, true);
	}

	/**
	 * BeforeSave event
	 *
	 * Event automatically triggered by IcmsPersistable Framework before the object is inserted or updated
	 * We also need to do the transformation in case of an insert to handle cloned blocks with options
	 *
	 * @param object $obj SystemBlocksadmin object
	 * @return true
	 */
	public function beforeSave(&$obj) {
		if (empty($_POST['options'])) return true;

		$options = "";
		ksort($_POST['options']);
		foreach ($_POST['options'] as $opt) {
			if ($options != "")	$options .= '|';
			$options .= $opt;
		}
		$obj->setVar('options', $options);
		return true;
	}
}
?>