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

include_once("admin_header.php");
include_once(XOOPS_ROOT_PATH . "/modules/formulize/include/functions.php");



if(!isset($_POST['title'])){
	$title = isset ($_GET['title']) ? $_GET['title'] : '';
}else {
	$title = $_POST['title'];
}
if(!isset($_POST['ele_id'])){
	$ele_id = isset ($_GET['ele_id']) ? $_GET['ele_id'] : '';
}else {
	$ele_id = $_POST['ele_id'];
}
/* // commented due to title now being identical with id_form
	$sql=sprintf("SELECT id_form FROM ".$xoopsDB->prefix("formulize_id")." WHERE desc_form='%s'",$title);
	$res = mysql_query ( $sql ) or die('Erreur SQL !<br>'.$requete.'<br>'.icms::$xoopsDB->error());

if ( $res ) {
  while ( $row = mysql_fetch_row ( $res ) ) {
    $id_form = $row[0];
  }
}
*/

$id_form = $title;
$form_handler = icms_getModuleHandler('forms', 'formulize');
$formObject = $form_handler->get(intval($id_form));
if($formObject->getVar('lockedform')) {
	redirect_header("formindex.php",3,_NO_PERM);
}

if( !empty($_POST) ){
	foreach( $_POST as $k => $v ){
		if(get_magic_quotes_gpc()) {
			if(is_array($v)) {
				foreach($v as $vk=>$vv) {
					if(is_array($vv)) {
						foreach($vv as $vvk=>$vvv) {
							$v[$vk][$vvk] = stripslashes($vvv);	
						}
					} else {
						$v[$vk] = stripslashes($vv);	
					}
				}
			} else {
				$v = stripslashes($v);
			}
		}
		${$k} = $v;
	}
}elseif( !empty($_GET) ){
	foreach( $_GET as $k => $v ){
		if(get_magic_quotes_gpc()) {
			if(is_array($v)) {
				foreach($v as $vk=>$vv) {
					if(is_array($vv)) {
						foreach($vv as $vvk=>$vvv) {
							$v[$vk][$vvk] = stripslashes($vvv);	
						}
					} else {
						$v[$vk] = stripslashes($vv);	
					}
				}
			} else {
				$v = stripslashes($v);
			}
		}
		${$k} = $v;
	}
}

$ele_id = !empty($ele_id) ? intval($ele_id) : 0;
$myts =& MyTextSanitizer::getInstance();

$refreshing = (( $_POST['submit'] == _AM_ELE_ADD_OPT_SUBMIT && intval($_POST['addopt']) > 0 ) OR isset($_POST['subformrefresh']) OR isset($_POST['addcon'])) ? true : false;

if($refreshing) {
  include XOOPS_ROOT_PATH . "/modules/formulize/admin/elements_save.php";
  
	$op = 'edit';
}


switch($op){
	// convert option added for textboxes July 1 2006, by JWE.  Happy Canada Day.
	// updated March 27 2009 to include radio buttons and checkboxes
	case 'convert':
		$element =& $formulize_mgr->get($ele_id);
		$ele_type = $element->getVar('ele_type');
		$new_ele_value = array();
		if($ele_type == "text") { // converting to textarea
			$ele_value = $element->getVar('ele_value');
			$new_ele_value[0] = $ele_value[2]; // default value
			$new_ele_value[1] = $xoopsModuleConfig['ta_rows'];
			$new_ele_value[2] = $ele_value[0]; // width become cols
			$new_ele_value[3] = $ele_value[4]; // preserve any association that is going on
			$element->setVar('ele_value', $new_ele_value);
			$element->setVar('ele_type', "textarea");
			if( !$formulize_mgr->insert($element, true) ){ // true causes a forced insert (necessary when there is no POST)
				xoops_cp_header();
				echo $element->getHtmlErrors();
			} else {
				redirect_header("index.php?title=$title", 3, _AM_ELE_CONVERTED_TO_TEXTAREA);
			}
		} elseif($ele_type=="textarea") {
			$ele_value = $element->getVar('ele_value');
			$new_ele_value[0] = $ele_value[2]; // cols become width
			$new_ele_value[1] = $xoopsModuleConfig['t_max'];
			$new_ele_value[2] = $ele_value[0]; // default value
			$new_ele_value[3] = 0; // allow anything (do not restrict to just numbers)
			$new_ele_value[4] = $ele_value[3]; // preserve any association that is going on
			$element->setVar('ele_value', $new_ele_value);
			$element->setVar('ele_type', "text");
			if( !$formulize_mgr->insert($element, true) ){ // true causes a forced insert (necessary when there is no POST)
				xoops_cp_header();
				echo $element->getHtmlErrors();
			} else {
				redirect_header("index.php?title=$title", 3, _AM_ELE_CONVERTED_TO_TEXTBOX);
			}
		} elseif($ele_type=="radio") {
			$element->setVar('ele_type', "checkbox"); // just need to change type, ele_value format is the same
			if( !$formulize_mgr->insert($element, true) ){ // true causes a forced insert (necessary when there is no POST)
				xoops_cp_header();
				echo $element->getHtmlErrors();
			} else {
				include_once XOOPS_ROOT_PATH . "/modules/formulize/class/data.php";
				$data_handler = new formulizeDataHandler($element->getVar('id_form'));
				if(!$data_handler->convertRadioDataToCheckbox($element)) {
					redirect_header("index.php?title=$title", 6, _AM_ELE_CHECKBOX_DATA_NOT_READY);
				} else {
					redirect_header("index.php?title=$title", 3, _AM_ELE_CONVERTED_TO_CHECKBOX);
				}
			}
		} elseif($ele_type=="checkbox") {
			$element->setVar('ele_type', "radio");  // just need to change type, ele_value format is the same
			if( !$formulize_mgr->insert($element, true) ){ // true causes a forced insert (necessary when there is no POST)
				xoops_cp_header();
				echo $element->getHtmlErrors();
			} else {
				include_once XOOPS_ROOT_PATH . "/modules/formulize/class/data.php";
				$data_handler = new formulizeDataHandler($element->getVar('id_form'));
				if(!$data_handler->convertCheckboxDataToRadio($element)) {
					redirect_header("index.php?title=$title", 6, _AM_ELE_RADIO_DATA_NOT_READY);
				} else {
					redirect_header("index.php?title=$title", 3, _AM_ELE_CONVERTED_TO_RADIO);
				}
			}
		} else {
			redirect_header("index.php?title=$title", 3, _AM_ELE_CANNOT_CONVERT);
		}
		break;
	case 'edit':
		xoops_cp_header();
		if( !empty($ele_id) ){
      if(!isset($element)) {
        $element = $formulize_mgr->get($ele_id);
      }
			$ele_type = $element->getVar('ele_type');
			$form_title = $clone ? _AM_ELE_CREATE : sprintf(_AM_ELE_EDIT, $element->getVar('ele_caption'));
			$editing = $clone ? 0 : 1;
		}else{
      if(!isset($element)) {
  			$editing = 0;
  			$element =& $formulize_mgr->create();
  			$form_title = _AM_ELE_CREATE;
      } else {
        $editing = 1;
        $ele_id = $element->getVar('ele_id');
        $form_title = sprintf(_AM_ELE_EDIT, $element->getVar('ele_caption'));
      }
		}
		$form = new XoopsThemeForm($form_title, 'form_ele', 'elements.php?title='.$title.'&op=edit&ele_id='.$ele_id);
		$form->addElement(new xoopsFormHidden('clone', intval($clone))); // will be pickedup from GET the first time through and then propogate through POST on subsequent page loads
		// if( empty($addopt) ){// no longer need to have two different initialization processes, since we're saving even on refreshes now
			$nb_fichier = 0;
			// no longer make cloned captions have the word copy at the end, since we add it when saving if the caption is not unique
			// $ele_caption = $clone ? sprintf(_AM_COPIED, $element->getVar('ele_caption', 'f')) : $element->getVar('ele_caption', 'f');
			$ele_caption = $element->getVar('ele_caption', 'f');
			if ($ele_type=='sep' && substr(0, 7, $ele_caption)=='{SEPAR}') { 
				$ele_caption = new XoopsFormText(_AM_ELE_CAPTION, 'ele_caption', 50, 4096, '{SEPAR}'.$ele_caption); }
			else { $ele_caption = new XoopsFormText(_AM_ELE_CAPTION, 'ele_caption', 50, 4096, $ele_caption); }
      $value = $element->getVar('ele_value');
      $ele_colhead_default = $element->getVar('ele_colhead', 'f');
			$ele_desc_default = $element->getVar('ele_desc', 'f');
			$ele_handle_default = $element->getVar('ele_handle', 'f');
			// merge in the uitext if there is any -- aug 25 2007
			$uitext = $element->getVar('ele_uitext');
			if(is_array($uitext) AND count($uitext) > 0) { 
				if($ele_type == "select") {
					$value[2] = formulize_mergeUIText($value[2], $uitext);
				} else {
					$value = formulize_mergeUIText($value, $uitext);
				}
			}

		$form->addElement($ele_caption, 1);

		if($ele_type != "subform" AND $ele_type != "grid" AND $ele_type != "ib" AND $ele_type != "areamodif") {
			// column heading added June 25 2006 -- jwe
			$ele_colhead = new XoopsFormText(_AM_ELE_COLHEAD, 'ele_colhead', 50, 255, $ele_colhead_default);
			$ele_colhead->setDescription(_AM_ELE_COLHEAD_HELP);
			$form->addElement($ele_colhead);
		
			// handle added April 19 2008 as part of new db structure
			$ele_handle_default = $clone ? "" : $ele_handle_default;
			$ele_handle = new XoopsFormText(_AM_ELE_HANDLE, 'ele_handle', 50, 50, $ele_handle_default);
			$ele_handle->setDescription(_AM_ELE_HANDLE_HELP);
			$form->addElement($ele_handle); 
		
			// descriptive text added June 6 2006 -- jwe
			if($ele_type != "ib") {
				$ele_desc = new XoopsFormTextArea(_AM_ELE_DESC, 'ele_desc', $ele_desc_default, 5, 35);
				$ele_desc->setDescription(_AM_ELE_DESC_HELP);
				$form->addElement($ele_desc);
			}
		}

		$req = false;
		$useDisable = false;
		switch($ele_type){
			case 'subform':
				include 'ele_subform.php';
			break;
			case 'text':
				include 'ele_text.php';
				$req = true;
				$useDisable = true;
				break;
			case 'textarea':
				include 'ele_tarea.php';
				$req = true;
				$useDisable = true;
			break;
			case 'areamodif':
				include 'ele_modif.php';
			break;
			case 'ib': // added June 20 2005
				include 'ele_insertbreak.php';
			break;
			case 'select':
				include 'ele_select.php';
        $req = true;
				$useDisable = true;
			break;
			case 'checkbox':
				include 'ele_check.php';
				$useDisable = true;
			break;
			case 'radio':
				include 'ele_radio.php';
        $req = true;
				$useDisable = true;
			break;
			case 'yn':
				include 'ele_yn.php';
				$useDisable = true;
			break;
			case 'date':
				include 'ele_date.php';
				$useDisable = true;
        $req = true;
			break;
			case 'sep':
				include 'ele_sep.php';
			break;
			case 'grid':
				include 'ele_grid.php';
			break;
			case 'upload':
				include 'ele_upload.php';
			break;
			// "derived columns" added March 27 2007
			case 'derived':
				include 'ele_derived.php';
			break;
			case 'colorpick':
				$useDisable = true;
				break;
			default:
				if(file_exists(XOOPS_ROOT_PATH."/modules/formulize/class/".$ele_type.".php")) {
					$elementTypeHandler = icms_getModuleHandler($ele_type);
					$form = $elementTypeHandler->adminUI($form, $value, $ele_id); // form is the form object, $value is the element-specific values that we have loaded, $ele_id is the element id
				}
				break;
		}
		if( $req ){
			$ele_req = new XoopsFormCheckBox(_AM_ELE_REQ, 'ele_req', $element->getVar('ele_req'));
			$ele_req->addOption(1, ' ');
      $form->addElement($ele_req);
		}

		// replaced - start - August 18 2005 - jpc
		/*$display = !empty($ele_id) ? $element->getVar('ele_display') : 1;
		$ele_display = new XoopsFormCheckBox(_AM_ELE_DISPLAY, 'ele_display', $display);
		$ele_display->addOption(1, ' ');
		$form->addElement($ele_display);*/

		$display = !empty($ele_id) ? $element->getVar('ele_display') : "all";
        $display = ($display == "0") ? "none" : $display;
        $display = ($display == "1") ? "all" : $display;

        $displayIsGroupList = false;
		if(substr($display, 0, 1) == ",")
        {
	        $displayIsGroupList = true;
	        $displayGroupList = explode(",", $display);
			$ele_display = new XoopsFormSelect(_AM_ELE_DISPLAY, 'ele_display', $displayGroupList, 10, true);
        }
        else
        {
			$ele_display = new XoopsFormSelect(_AM_ELE_DISPLAY, 'ele_display', $display, 10, true);
        } 
		$ele_display->setDescription(_AM_FORM_DISPLAY_EXTRA);
	

	    $fs_member_handler =& xoops_gethandler('member');
	    $fs_xoops_groups =& $fs_member_handler->getGroups();

        $ele_display->addOption("all", _AM_FORM_DISPLAY_ALLGROUPS);     
        $ele_display->addOption("none", _AM_FORM_DISPLAY_NOGROUPS);     


			$fs_count = count($fs_xoops_groups);
			for($i = 0; $i < $fs_count; $i++) 
			{
					$ele_display->addOption($fs_xoops_groups[$i]->getVar('groupid'), $fs_xoops_groups[$i]->getVar('name'));     
			}
						$form->addElement($ele_display);

			if($useDisable) {
								
								// create second group list for disabled option
								
						$disabled = !empty($ele_id) ? $element->getVar('ele_disabled') : "none";
						$disabled = ($disabled == "0") ? "none" : $disabled;
						$disabled = ($disabled == "1") ? "all" : $disabled;
		
						$disabledIsGroupList = false;
				if(substr($disabled, 0, 1) == ",")
						{
							$disabledIsGroupList = true;
							$disabledGroupList = explode(",", $disabled);
					$ele_disabled = new XoopsFormSelect(_AM_ELE_DISABLED, 'ele_disabled', $disabledGroupList, 10, true);
						}
						else
						{
					$ele_disabled = new XoopsFormSelect(_AM_ELE_DISABLED, 'ele_disabled', $disabled, 10, true);
						} 
				$ele_disabled->setDescription(_AM_FORM_DISABLED_EXTRA);
		
						$ele_disabled->addOption("all", _AM_FORM_DISABLED_ALLGROUPS);     
						$ele_disabled->addOption("none", _AM_FORM_DISABLED_NOGROUPS);     
		
					$fs_count = count($fs_xoops_groups);
					for($i = 0; $i < $fs_count; $i++) 
					{
							$ele_disabled->addOption($fs_xoops_groups[$i]->getVar('groupid'), $fs_xoops_groups[$i]->getVar('name'));     
					}
						
								
				$form->addElement($ele_disabled);
				// replaced - end - August 18 2005 - jpc
			}

		// added conditional option, Oct 21 2009
		$elementFilterSettings = $element->getVar('ele_filtersettings');
		$elementFilterSettingsToSend = count($elementFilterSettings) > 0 ? $elementFilterSettings : "";
		$elementFilterUI = formulize_createFilterUI($elementFilterSettingsToSend, "elementfilter", $id_form, "form_ele");
		$form->addElement(new XoopsFormLabel(_AM_ELE_ELEMENTCONDITIONS, $elementFilterUI)); //$elementFilterUI->render()));

		if($ele_type == "radio" OR $ele_type == "text" OR $ele_type == "textarea" OR $ele_type == "yn") {
			// added by jwe Nov 7 2005, a checkbox to indicate if the element should be included as a hidden element, even when the user does not have permission to view (ie: it is hidden by the display option above)
			$fhide = !empty($ele_id) ? $element->getVar('ele_forcehidden') : 0;
			$forcehidden = new XoopsFormCheckBox(_AM_FORM_FORCEHIDDEN, "fhide", $fhide);
			$forcehidden->addOption(1, ' ');
			$forcehidden->setDescription(_AM_FORM_FORCEHIDDEN_DESC);
			$form->addElement($forcehidden);
		}
		if($ele_type != "subform" AND $ele_type != "grid" AND $ele_type != "ib" AND $ele_type != "areamodif") {
			// added private option July 15 2006, jwe
			$priv = !empty($ele_id) ? $element->getVar('ele_private') : 0;
			$private = new XoopsFormCheckBox(_AM_FORM_PRIVATE, "private", $priv);
			$private->addOption(1, ' ');
			$private->setDescription(_AM_FORM_PRIVATE_DESC);
			$form->addElement($private);
		}
		// add encrypted checkbox -- July 15 2009
		$encryptState = !empty($ele_id) ? $element->getVar('ele_encrypt') : 0;
		$encrypt = new XoopsFormCheckBox(_AM_FORM_ENCRYPT, "encrypt", $encryptState);
		$encrypt->addOption(1, ' ');
		$encrypt->setDescription(_AM_FORM_ENCRYPT_DESC);
		$form->addElement($encrypt);
		
		
		// data type controls ... added May 31 2009, jwe 
    // only do it for existing elements where the datatype choice is relevant
		// do not do it for encrypted elements
		if(($ele_type == "text" OR $ele_type == "textarea" OR $ele_type == "select" OR $ele_type == "radio" OR $ele_type == "checkbox" OR $ele_type == "derived" OR (isset($elementTypeHandler) AND $elementTypeHandler->needsDataType)) AND !$encryptState) {
                        if(!empty($ele_id)) {
                                // get the current type...
                                global $xoopsDB;
				$elementDataSQL = "SHOW COLUMNS FROM ".$xoopsDB->prefix("formulize_".$formObject->getVar('form_handle'))." LIKE '".$element->getVar('ele_handle')."'";
                                $elementDataRes = $xoopsDB->queryF($elementDataSQL);
                                $elementDataArray = $xoopsDB->fetchArray($elementDataRes);
                                $defaultTypeComplete = $elementDataArray['Type'];
                                $parenLoc = strpos($defaultTypeComplete, "(");
                                if($parenLoc) {
                                        $defaultType = substr($defaultTypeComplete,0,$parenLoc);
                                        $lengthOfSizeValues = strlen($defaultTypeComplete)-($parenLoc+2);
                                        $defaultTypeSize = substr($defaultTypeComplete,($parenLoc+1),$lengthOfSizeValues);
                                        if($defaultType == "decimal") {
                                                $sizeParts = explode(",", $defaultTypeSize);
                                                $defaultTypeSize = $sizeParts[1]; // second part of the comma separated value is the number of decimal places declaration
                                        }
                                } else {
                                        $defaultType = $defaultTypeComplete;
                                        $defaultTypeSize = '';
                                }
                                //print "defaultType: $defaultType<br>";
                                //print "defaultTypeSize: $defaultTypeSize<br>";
                                $form->addElement(new XoopsFormHidden('element_default_datatype', $defaultType));
                                $form->addElement(new XoopsFormHidden('element_default_datatypesize', $defaultTypeSize));
                        } else {
                                $defaultType = 'text';
                                $defaultTypeSize = '';
                        }
                        // setup the UI for the options...
                        $dataTypeTray = new XoopsFormElementTray(_AM_FORM_DATATYPE_CONTROLS, '<br>');
                        $dataTypeTray->setDescription(_AM_FORM_DATATYPE_CONTROLS_DESC);
                        $textType = new XoopsFormRadio('', 'element_datatype', $defaultType);
                        $textDataTypeLabel = (empty($ele_id) AND ($ele_type == 'text')) ? _AM_FORM_DATATYPE_TEXT_NEWTEXT : _AM_FORM_DATATYPE_TEXT;
                        $textType->addOption('text', $textDataTypeLabel);
                        $intType = new XoopsFormRadio('', 'element_datatype', $defaultType);
                        $intType->addOption('int', _AM_FORM_DATATYPE_INT);
                        $decimalType = new XoopsFormRadio('', 'element_datatype', $defaultType);
                        $decimalTypeSizeDefault = ($defaultTypeSize AND $defaultType == "decimal") ? $defaultTypeSize : 2;
                        $decimalTypeSize = new XoopsFormText('', 'element_datatype_decimalsize', 2, 2, $decimalTypeSizeDefault);
												$decimalTypeSize->setExtra(" style=\"width: 2em;\" "); // style to force width necessary to compensate for silly forced 60% textbox widths in ICMS admin side
                        $decimalType->addOption('decimal', _AM_FORM_DATATYPE_DECIMAL1.$decimalTypeSize->render()._AM_FORM_DATATYPE_DECIMAL2);
                        $varcharType = new XoopsFormRadio('', 'element_datatype', $defaultType);
                        $varcharTypeSizeDefault = ($defaultTypeSize AND $defaultType == 'varchar') ? $defaultTypeSize : 255;
                        $varcharTypeSize = new XoopsFormText('', 'element_datatype_varcharsize', 3, 3, $varcharTypeSizeDefault);
												$varcharTypeSize->setExtra(" style=\"width: 3em;\" ");
                        $varcharType->addOption('varchar', _AM_FORM_DATATYPE_VARCHAR1.$varcharTypeSize->render()._AM_FORM_DATATYPE_VARCHAR2);
                        $charType = new XoopsFormRadio('', 'element_datatype', $defaultType);
                        $charTypeSizeDefault = ($defaultTypeSize AND $defaultType == 'char') ? $defaultTypeSize : 255;
                        $charTypeSize = new XoopsFormText('', 'element_datatype_charsize', 3, 3, $charTypeSizeDefault);
												$charTypeSize->setExtra(" style=\"width: 3em;\" ");
                        $charType->addOption('char', _AM_FORM_DATATYPE_CHAR1.$charTypeSize->render()._AM_FORM_DATATYPE_CHAR2);
                        if($defaultType != "text" AND $defaultType != "int" AND $defaultType != "decimal" AND $defaultType != "varchar" AND $defaultType != "char") {
                                $otherType = new XoopsFormRadio('', 'element_datatype', $defaultType);
                                $otherType->addOption($defaultType, _AM_FORM_DATATYPE_OTHER.$defaultType);
                                $dataTypeTray->addElement($otherType);
                        }
                        $dataTypeTray->addElement($textType);
                        $dataTypeTray->addElement($intType);
                        $dataTypeTray->addElement($decimalType);
                        $dataTypeTray->addElement($varcharType);
                        $dataTypeTray->addElement($charType);
                        $form->addElement($dataTypeTray);
                }
		
			
		$highorder = formulize_getElementHighOrder($id_form);
		
		$order = !empty($ele_id) ? $element->getVar('ele_order') : $highorder;
		$order = $clone ? $highorder : $order;
		$ele_order = new XoopsFormText(_AM_ELE_ORDER, 'ele_order', 4, 4, $order);

		// need to add hidden element to indicate if the order has been modified (detectable by javascript)
		// then listen for that flag, and if order has not been modified, check again when saving to see if this is in fact the right order number, since multiple clicks on the clone link at the same time will result in multiple windows with the same order number in the box, and we don't want to have to manually alter those orders after saving -- re: OACAS HR survey project, January 22, 2007

		$ele_order->setExtra("onchange='javascript:window.document.form_ele.ele_order_changed.value=1;'");
		$ele_order_changed = new XoopsFormHidden('ele_order_changed', $editing);

		$form->addElement($ele_order);
		$form->addElement($ele_order_changed);
		
		$submit = new XoopsFormButton('', 'submit', _AM_SAVE, 'submit');
		$cancel = new XoopsFormButton('', 'cancel', _CANCEL, 'button');
		// behaviour of cancel button changed to actually physically go back to the editing elements page -- jwe 01/05/05
		//$cancel->setExtra('onclick="javascript:history.go(-1);"');
		$cancelExtra = "onclick='javascript:location.href=\"../admin/index.php?title=$title\"'";
		$cancelExtra = str_replace(" ", "%20", $cancelExtra);
		$cancel->setExtra($cancelExtra);
		$tray = new XoopsFormElementTray('');
		$tray->addElement($submit);
		$tray->addElement($cancel);
		$form->addElement($tray);
		
		$hidden_op = new XoopsFormHidden('op', 'save');
		$hidden_type = new XoopsFormHidden('ele_type', $ele_type);
		$form->addElement($hidden_op);
		$form->addElement($hidden_type);
		if( !empty($ele_id) && !$clone ){
			$hidden_id = new XoopsFormHidden('ele_id', $ele_id);
			$form->addElement($hidden_id);
		}
		$form->display();

	break;
	case 'delete':
		if( empty($ele_id) ){
			redirect_header("index.php?title=$title", 0, _AM_ELE_SELECT_NONE);
		}
		if( empty($_POST['ok']) ){
			xoops_cp_header();
			xoops_confirm(array('op' => 'delete', 'ele_id' => $ele_id, 'ok' => 1), 'elements.php?title='.$title.'', _AM_ELE_CONFIRM_DELETE);
		}else{
			$element =& $formulize_mgr->get($ele_id);
      $ele_type = $element->getVar('ele_type');
			$formulize_mgr->delete($element);
      if($ele_type != "areamodif" AND $ele_type != "ib" AND $ele_type != "sep" AND $ele_type != "subform" AND $ele_type != "grid") {
        $formulize_mgr->deleteData($element); //added aug 14 2005 by jwe  
      }
			redirect_header("index.php?title=$title", 0, _AM_DBUPDATED);
		}
	break;
	case 'save':
      include XOOPS_ROOT_PATH . "/modules/formulize/admin/elements_save.php";
			header("Location: " . XOOPS_URL . "/modules/formulize/admin/index.php?title=$title");
			redirect_header("index.php?title=$title", 1, _AM_DBUPDATED);
	break;
/*	default:
		xoops_cp_header();
	//	OpenTable();
		echo "<h4>"._AM_ELE_CREATE."</h4>
		<ul>
		<li><a href='elements.php?op=edit&amp;ele_type=text'>"._AM_ELE_TEXT."</a></li>
		<li><a href='elements.php?op=edit&amp;ele_type=textarea'>"._AM_ELE_TAREA."</a></li>
		<li><a href='elements.php?op=edit&amp;ele_type=select'>"._AM_ELE_SELECT."</a></li>
		<li><a href='elements.php?op=edit&amp;ele_type=checkbox'>"._AM_ELE_CHECK."</a></li>
		<li><a href='elements.php?op=edit&amp;ele_type=radio'>"._AM_ELE_RADIO."</a></li>
		<li><a href='elements.php?op=edit&amp;ele_type=yn'>"._AM_ELE_YN."</a></li>
		</ul>"
		;
	//	CloseTable();
	break;*/
}
include 'footer.php';
xoops_cp_footer();


function addOption($id1, $id2, $text, $type='check', $checked=null){
	$d = new XoopsFormText('', $id1, 40, 255, $text);
	if( $type == 'check' ){
		$c = new XoopsFormCheckBox('', $id2, $checked);
		$c->addOption(1, ' ');
	}
	else{
		if($checked!==null) {
			$c = new XoopsFormRadio('', 'checked', $checked);
		} else {
			$c = new XoopsFormRadio('', 'checked', 7845689); // need to set some number value that will never be actually used, as the selected value, to ensure that no default selection is shown.  The first radio button option will have 0 as its value and so null or other normal empty values will cause the 0 to be selected.  So this way, nothing, not even 0, will match the selection value.  Crude but effective.
		}
		$c->addOption($id2, ' ');
	}
	$t = new XoopsFormElementTray('');
	$t->addElement($c);
	$t->addElement($d);
	return $t;
}

function addOptionsTray(){
	$t = new XoopsFormText('', 'addopt', 3, 2);
	$l = new XoopsFormLabel('', sprintf(_AM_ELE_ADD_OPT, $t->render()));
	$b = new XoopsFormButton('', 'submit', _AM_ELE_ADD_OPT_SUBMIT, 'submit');
	$r = new XoopsFormElementTray('');
	$r->addElement($l);
	$r->addElement($b);
	return $r;
}

function formulize_getElementHighOrder($id_form) {
	global $xoopsDB;
	// added by jwe 01/06/05 -- get the current highest order value for the form, and add up to 5 to it to reach the nearest mod 5 value
	$highorderq = "SELECT MAX(ele_order) FROM " . $xoopsDB->prefix("formulize") . " WHERE id_form=$id_form";
	$reshighorderq = $xoopsDB->query($highorderq);
	$rowhighorderq = $xoopsDB->fetchRow($reshighorderq);
	$highorder = $rowhighorderq[0]+1;
	while($highorder % 5 != 0)
	{
		$highorder++;
	}
	return $highorder;
}


// THIS FUNCTION TAKES A SERIES OF VALUES TYPED IN FORM RADIO BUTTONS, CHECKBOXES OR SELECTBOX OPTIONS, AND CHECKS TO SEE IF THEY WERE ENTERED WITH A UITEXT INDICATOR, AND IF SO, SPLITS THEM INTO THEIR ACTUAL VALUE PLUS THE UI TEXT AND RETURNS BOTH
// $values should be an array of all the options, so $ele_value for radio and checkboxes, $ele_value[2] for selectboxes
function formulize_extractUIText($values) {
	include_once XOOPS_ROOT_PATH . "/modules/formulize/include/functions.php";
	// values are the text that was typed in
	// keys should remain unchanged
	$uitext = array();
	foreach($values as $key=>$value) {
		//print "<br>original value: $value<br>";
		//print "key: $key<br>";
		if(strstr($value, "|") AND substr(trans($value), 0, 7) != "{OTHER|") { // check for the pressence of the uitext deliminter, the "pipe" character
			$pipepos = strpos($value, "|");
			//print "pipe found: $pipepos<br>";
			$uivalue = substr($value, $pipepos+1);
			//print "uivalue: $uivalue<br>";
			$value = substr($value, 0, $pipepos);
			//print "value: $value<br>";
			$values[$key] = $value;
			$uitext[$value] = $uivalue;
		} else {
			$values[$key] = $value;
		}
	}
	return array(0=>$values, 1=>$uitext);
}

// THIS FUNCTION TAKES THE VALUES USED IN THE DB, PLUS THE UITEXT FOR THOSE VALUES, AND CONSTRUCTS AN ARRAY SUITABLE FOR USE WHEN EDITING ELEMENTS, SO THE UITEXT IS VISIBLE INLINE WITH THE VALUES, SEPARATED BY A PIPE (|)
function formulize_mergeUIText($values, $uitext) {
  if(strstr($values, "#*=:*")) { return $values; } // don't alter linked selectbox properties
	$newvalues = array();
	foreach($values as $key=>$value) {
		if(isset($uitext[$key])) {
			$newvalues[$key . "|" . $uitext[$key]] = $value;
		} else {
			$newvalues[$key] = $value;
		}
	}
	return $newvalues;
}
	
?>