<?php

###############################################################################
##     Formulize - ad hoc form creation and reporting module for XOOPS       ##
##                    Copyright (c) 2004 Freeform Solutions                  ##
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
##  Author of this file: Freeform Solutions 					     ##
##  Project: Formulize                                                       ##
###############################################################################

//THIS FILE HANDLES THE DISPLAY OF FORMS.  FUNCTIONS CAN BE CALLED FROM ANYWHERE (INTENDED FOR PAGEWORKS MODULE)

global $xoopsConfig;
// load the formulize language constants if they haven't been loaded already
	if ( file_exists(XOOPS_ROOT_PATH."/modules/formulize/language/".$xoopsConfig['language']."/main.php") ) {
		include_once XOOPS_ROOT_PATH."/modules/formulize/language/".$xoopsConfig['language']."/main.php";
	} else {
		include_once XOOPS_ROOT_PATH."/modules/formulize/language/english/main.php";
	}

include_once XOOPS_ROOT_PATH."/modules/formulize/include/functions.php";

include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
include_once XOOPS_ROOT_PATH . "/include/functions.php";

// NEED TO USE OUR OWN VERSION OF THE CLASS, TO GET ELEMENT NAMES IN THE TR TAGS FOR EACH ROW
class formulize_themeForm extends XoopsThemeForm {
	/**
	 * Insert an empty row in the table to serve as a seperator.
	 *
	 * @param	string  $extra  HTML to be displayed in the empty row.
	 * @param	string	$class	CSS class name for <td> tag
	 * @name	string	$name	name of the element being inserted, which we keep so we can then put the right id tag into its row
	 */
	public function insertBreakFormulize($extra = '', $class= '', $name) {
		$class = ($class != '') ? " class='$class'" : '';
		//Fix for $extra tag not showing
		if ($extra) {
			$extra = "<td colspan='2' $class>$extra</td>"; // removed tr from here and added it below when we know the right id name to give it
		} else {
			$extra = "<td colspan='2' $class>&nbsp;</td>"; // removed tr from here and added it below when we know the right id name to give it
		}
		$ibContents = $extra."<<||>>".$name; // can only assign strings or real element objects with addElement, not arrays
		$this->addElement($ibContents);
	}
	/**
	 * create HTML to output the form as a theme-enabled table with validation.
	 *
	 * @return	string
	 */
	public function render() {
		$ele_name = $this->getName();
		$ret = "<form id='" . $ele_name
				. "' name='" . $ele_name
				. "' action='" . $this->getAction()
				. "' method='" . $this->getMethod()
				. "' onsubmit='return xoopsFormValidate_" . $ele_name . "();'" . $this->getExtra() . ">
			<div class='xo-theme-form'>
			<table width='100%' class='outer' cellspacing='1'>
			<tr><th colspan='2'>" . $this->getTitle() . "</th></tr>
		";
		$hidden = '';
		list($ret, $hidden) = $this->_drawElements($this->getElements(), $ret, $hidden);
		$ret .= "</table>\n$hidden\n</div>\n</form>\n";
		$ret .= $this->renderValidationJS(true);
		return $ret;
	}
	
	public function renderValidationJS( $withtags = true ) {
		$js = "";
		if ( $withtags ) {
			$js .= "\n<!-- Start Form Validation JavaScript //-->\n<script type='text/javascript'>\n<!--//\n";
		}
		$formname = $this->getName();
		$js .= "function xoopsFormValidate_{$formname}() { myform = window.document.{$formname};\n";
		$js .= $this->_drawValidationJS();
		$js .= "\nreturn true;\n}\n";
		if ( $withtags ) {
			$js .= "//--></script>\n<!-- End Form Vaidation JavaScript //-->\n";
		}
		return $js;
	}
	
	function _drawElements($elements, $ret, $hidden) {
		$class ='even';
		foreach ( $elements as $ele ) {
			if (!is_object($ele)) {// just plain add stuff if it's a literal string...
				if(strstr($ele, "<<||>>")) {
					$ele = explode("<<||>>", $ele);
					$ret .= "<tr id='formulize-".$ele[1]."'>".$ele[0]."</tr>";					
				} elseif(substr($ele, 0, 3) != "<tr") {
					$ret .= "<tr>$ele</tr>";	
				} else {
					$ret .= $ele;
				}
			} elseif ( !$ele->isHidden() ) {
				$ret .= "<tr id='formulize-".$ele->getName()."' valign='top' align='" . _GLOBAL_LEFT . "'><td class='head'>";
				if (($caption = $ele->getCaption()) != '') {
					$ret .=
					"<div class='xoops-form-element-caption" . ($ele->isRequired() ? "-required" : "" ) . "'>"
						. "<span class='caption-text'>{$caption}</span>"
						. "<span class='caption-marker'>*</span>"
						. "</div>";
				}
				if (($desc = $ele->getDescription()) != '') {
					$ret .= "<div class='xoops-form-element-help'>{$desc}</div>";
				}
				$ret .= "</td><td class='$class'>" . $ele->render() . "</td></tr>\n";
			} else {
				$hidden .= $ele->render();
			}
		}
		return array($ret, $hidden);
	}

	// need to check whether the element is a standard element, if if so, add the check for whether its row exists or not	
	function _drawValidationJS() {
		$fullJs = "";
		
		$elements = $this->getElements( true );
		foreach ( $elements as $elt ) {
			if ( method_exists( $elt, 'renderValidationJS' ) ) {
				if(substr($elt->getName(),0,3)=="de_") {
					$checkConditionalRow = true;
				} else {
					$checkConditionalRow = false;
				}
				$js = $elt->renderValidationJS();
				if($js AND $checkConditionalRow) {
					$fullJs .= "if(window.document.getElementById('formulize-".$elt->getName()."').style.display != 'none') {\n".$js."\n}\n\n";
				} elseif($js) {
					$fullJs .= "\n".$js."\n";
				}
			}
		}
		
		return $fullJs;
	}
	
}

// SPECIAL CLASS TO HANDLE SITUATIONS WHERE WE'RE RENDERING ONLY THE ROWS FOR THE FORM, NOT THE ENTIRE FORM 
class formulize_elementsOnlyForm extends formulize_themeForm {
	
	function render() {
		// just a slight modification of the render method so that we display only the elements and none of the extra form stuff
		$ele_name = $this->getName();
		$ret = "<div class='xo-theme-form'>
			<table width='100%' class='outer' cellspacing='1'>
			<tr><th colspan='2'>" . $this->getTitle() . "</th></tr>
		";
		$hidden = '';
		list($ret, $hidden) = $this->_drawElements($this->getElements(), $ret, $hidden);
		$ret .= "</table>\n$hidden\n</div>\n";
		return $ret;
	}
	//We need to render the validation code differently, without the opening/closing part of the validation function, since the form is embedded inside another..
	public function renderValidationJS() {
		return $this->_drawValidationJS();
	}
}

// this function gets the element that is linked from a form to its parent form
// returns the ele_ids from form table
// note: no enforcement of only one link to a parent form.  You can screw up your framework structure and this function will dutifully return several links to the same parent form
function getParentLinks($fid, $frid) {

	global $xoopsDB;

	$check1 = q("SELECT fl_key1, fl_key2 FROM " . $xoopsDB->prefix("formulize_framework_links") . " WHERE fl_form1_id='$fid' AND fl_frame_id = '$frid' AND fl_unified_display = '1' AND fl_relationship = '3'");
	$check2 = q("SELECT fl_key1, fl_key2 FROM " . $xoopsDB->prefix("formulize_framework_links") . " WHERE fl_form2_id='$fid' AND fl_frame_id = '$frid' AND fl_unified_display = '1' AND fl_relationship = '2'");
	foreach($check1 as $c) {
		$source[] = $c['fl_key2'];
		$self[] = $c['fl_key1'];
	}
	foreach($check2 as $c) {
		$source[] = $c['fl_key1'];
		$self[] = $c['fl_key2'];
	}

	$to_return['source'] = $source;
	$to_return['self'] = $self;

	return $to_return;

}


// this function returns the captions and values that are in the DB for an existing entry
// $elements is used to specify a shortlist of elements to display.  Used in conjunction with the array option for $formform
// $formulize_mgr is not required any longer!
function getEntryValues($entry, $formulize_mgr, $groups, $fid, $elements="", $mid, $uid, $owner, $groupEntryWithUpdateRights) {

	if(!$fid) { // fid is required
		return "";
	}
	
	if(!is_numeric($entry) OR !$entry) {
		return "";
	}

	static $cachedEntryValues = array();
	$serializedElements = serialize($elements);
	if(!isset($cachedEntryValues[$fid][$entry][$serializedElements])) {
	
		global $xoopsDB;
	
		if(!$mid) { $mid = getFormulizeModId(); }
	
		if(!$uid) {
			global $xoopsUser;
			$uid = $xoopsUser ? $xoopsUser->getVar("uid") : 0; // if there is no uid, then use the $xoopsUser uid if there is one, or zero for anons			
		}

		if(!$owner) {
			$owner = getEntryOwner($entry, $fid); // if there is no owner, then get the owner for this entry in this form
		}
		
		// viewquery changed in light of 3.0 data structure changes...
		//$viewquery = q("SELECT ele_caption, ele_value FROM " . $xoopsDB->prefix("formulize_form") . " WHERE id_req=$entry $element_query");
		// NEED TO CHECK THE FORM FOR ENCRYPTED ELEMENTS, AND ADD THEM AFTER THE * WITH SPECIAL ALIASES. tHEN IN THE LOOP, LOOK FOR THE ALIASES, AND SKIP PROCESSING THOSE ELEMENTS NORMALLY, BUT IF WHEN PROCESSING A NORMAL ELEMENT, IT IS IN THE LIST OF ENCRYPTED ELEMENTS, THEN GET THE ALIASED, DECRYPTED VALUE INSTEAD OF THE NORMAL ONE
		// NEED TO ADD RETRIEVING ENCRYPTED ELEMENT LIST FROM FORM OBJECT
		$form_handler =& icms_getModuleHandler('forms', 'formulize');
		$formObject = $form_handler->get($fid);
		$formHandles = $formObject->getVar('elementHandles');
		$formCaptions = $formObject->getVar('elementCaptions');
		$formEncryptedElements = $formObject->getVar('encryptedElements');
		$encryptedSelect = "";
		foreach($formEncryptedElements as $thisEncryptedElement) {
			$encryptedSelect .= ", AES_DECRYPT(`".$thisEncryptedElement."`, '".getAESPassword()."') as 'decrypted_value_for_".$thisEncryptedElement."'";
		}
		
		$viewquerydb = q("SELECT * $encryptedSelect FROM " . $xoopsDB->prefix("formulize_" . $formObject->getVar('form_handle')) . " WHERE entry_id=$entry");
		$viewquery = array();
		
		// need to parse the result based on the elements requested and setup the viewquery array for use later on
		$vqindexer = 0;
		foreach($viewquerydb[0] as $thisField=>$thisValue) {
			if(strstr($thisField, "decrypted_value_for_")) { continue; } // don't process these values normally, instead, we just refer to them later to grab the decrypted value, if this iteration is over an encrypted element.
			$includeElement = false;
			if(is_array($elements)) {
				if(in_array(array_search($thisField, $formHandles), $elements) AND $thisValue !== "") {
					$includeElement = true;
				}
			} elseif(!strstr($thisField, "creation_uid") AND !strstr($thisField, "creation_datetime") AND !strstr($thisField, "mod_uid") AND !strstr($thisField, "mod_datetime") AND !strstr($thisField, "entry_id") AND $thisValue !== "") {
				$includeElement = true;
			}
			if($includeElement) {
				$viewquery[$vqindexer]["ele_handle"] = $thisField;
				$viewquery[$vqindexer]["ele_caption"] = $formCaptions[array_search($thisField, $formHandles)];
				if(in_array($thisField, $formEncryptedElements)) {
					$viewquery[$vqindexer]["ele_value"] = $viewquerydb[0]["decrypted_value_for_".$thisField];
				} else {
					$viewquery[$vqindexer]["ele_value"] = $thisValue;	
				}
			}
			$vqindexer++;
		}
	
		// build query for display groups and disabled
		foreach($groups as $thisgroup) {
			$gq .= " OR ele_display LIKE '%,$thisgroup,%'";
			//$dgq .= " AND ele_disabled NOT LIKE '%,$thisgroup,%'"; // not sure that this is necessary
		}
	
		// exclude private elements unless the user has view_private_elements permission, or update_entry permission on a one-entry-per group entry
		$private_filter = "";
		$gperm_handler =& icms::handler('icms_member_groupperm');
		$view_private_elements = $gperm_handler->checkRight("view_private_elements", $fid, $groups, $mid);
	
		if(!$view_private_elements AND $uid != $owner AND !$groupEntryWithUpdateRights) {
			$private_filter = " AND ele_private=0";
		} 
	
		$allowedquery = q("SELECT ele_caption, ele_disabled, ele_handle FROM " . $xoopsDB->prefix("formulize") . " WHERE id_form=$fid AND (ele_display='1' $gq) $private_filter"); // AND (ele_disabled != 1 $dgq)"); // not sure that filtering for disabled elements is necessary
		$allowedDisabledStatus = array();
		$allowedhandles = array();
		foreach($allowedquery as $onecap) {
			$allowedhandles[] = $onecap['ele_handle'];
			$allowedDisabledStatus[$onecap['ele_handle']] = $onecap['ele_disabled'];
		}
	
		foreach($viewquery as $vq) {
			// check that this caption is an allowed caption before recording the value
			if(in_array($vq["ele_handle"], $allowedhandles)) {
				$prevEntry['handles'][] = $vq["ele_handle"];
				$prevEntry['captions'][] = $vq["ele_caption"];
				$prevEntry['values'][] = $vq["ele_value"];
				$prevEntry['disabled'][] = $allowedDisabledStatus[$vq['ele_handle']];
			}
		}
		$cachedEntryValues[$fid][$entry][$serializedElements] = $prevEntry;
	}
	return $cachedEntryValues[$fid][$entry][$serializedElements];
	
}


function displayForm($formframe, $entry="", $mainform="", $done_dest="", $button_text="", $settings="", $titleOverride="", $overrideValue="", $overrideMulti="", $overrideSubMulti="", $viewallforms=0, $profileForm=0, $printall=0, $screen=null) {  // nmc 2007.03.24 - added $printall

include_once XOOPS_ROOT_PATH.'/modules/formulize/include/functions.php';
include_once XOOPS_ROOT_PATH.'/modules/formulize/include/extract.php';
formulize_benchmark("Start of formDisplay.");

$formElementsOnly = false;
if($titleOverride == "formElementsOnly") {
	$titleOverride = "all";
	$formElementsOnly = true;
}
if(!is_numeric($titleOverride) AND $titleOverride != "" AND $titleOverride != "all") {
	$passedInTitle = $titleOverride; // we can pass in a text title for the form, and that will cause the $titleOverride "all" behaviour to be invoked, and meanwhile we will use this title for the top of the form
	$titleOverride = "all";
} 

//syntax:
//displayform($formframe, $entry, $mainform)
//$formframe is the id of the form OR title of the form OR name of the framework.  Can also be an array.  If it is an array, then flag 'formframe' is the $formframe variable, and flag 'elements' is an array of all the elements that are to be displayed.
//the array option is intended for displaying only part of a form at a time
//$entry is the numeric entry to display in the form -- if $entry is the word 'proxy' then it is meant to force a new form entry when the form is a single-entry form that the user already may have an entry in
//$mainform is the starting form to use, if this is a framework (can be specified by form id or by handle)
//$done_dest is the URL to go to after the form has been submitted
//Steps:
//1. identify form or framework
//2. if framework, check for unified display options
//3. if entry specified, then get data for that entry
//4. drawform with data if necessary

	global $xoopsDB, $xoopsUser, $myts;

	global $sfidsDrawn;
	if(!is_array($sfidsDrawn)) {
		$sfidsDrawn = array();
	}

	$groups = $xoopsUser ? $xoopsUser->getGroups() : array(0=>XOOPS_GROUP_ANONYMOUS);

	$original_entry = $entry; // flag used to tell whether the function was called with an actual entry specified, ie: we're supposed to be editing this entry, versus the entry being set by coming back form a sub_form or other situation.

	$mid = getFormulizeModId();

	$currentURL = getCurrentURL();

	// identify form or framework

	$elements_allowed = "";
	if(is_array($formframe)) {
		$elements_allowed = $formframe['elements'];
		$printViewPages = isset($formframe['pages']) ? $formframe['pages'] : "";
		$printViewPageTitles = isset($formframe['pagetitles']) ? $formframe['pagetitles'] : "";
		$formframetemp = $formframe['formframe'];
		unset($formframe);
		$formframe = $formframetemp;
	}

	list($fid, $frid) = getFormFramework($formframe, $mainform);

	if($_POST['deletesubsflag']) { // if deletion of sub entries requested
		foreach($_POST as $k=>$v) {
			if(strstr($k, "delbox")) {
				$subs_to_del[] = $v;
			}
		}
		if(count($subs_to_del) > 0) {
			
			deleteFormEntries($subs_to_del, intval($_POST['deletesubsflag'])); // deletesubsflag will be the sub form id
 			sendNotifications($_POST['deletesubsflag'], "delete_entry", $subs_to_del, $mid, $groups);
		}
	}

	if($_POST['parent_form']) { // if we're coming back from a subform
		$entry = $_POST['parent_entry'];
		$fid = $_POST['parent_form'];
	}

	if($_POST['go_back_form']) { // we just received a subform submission
		$entry = $_POST['sub_submitted'];
		$fid = $_POST['sub_fid'];
		$go_back['form'] = $_POST['go_back_form'];
		$go_back['entry'] = $_POST['go_back_entry'];
	}

	// set $entry in the case of a form_submission where we were editing an entry (just in case that entry is not what is used to call this function in the first place -- ie: we're on a subform and the mainform has no entry specified, or we're clicking submit over again on a single-entry form where we started with no entry)
	$entrykey = "entry" . $fid;
	if((!$entry OR $entry=="proxy") AND $_POST[$entrykey]) { // $entrykey will only be set when *editing* an entry, not on new saves
		$entry = $_POST[$entrykey];
	}
	
	// this is probably not necessary any more, due to architecture changes in Formulize 3
	// formulize_newEntryIds is set when saving data
	if(!$entry AND isset($GLOBALS['formulize_newEntryIds'][$fid])) {
		$entry = $GLOBALS['formulize_newEntryIds'][$fid][0];
	}

	$member_handler =& icms::handler('icms_member');
	$gperm_handler = &icms::handler('icms_member_groupperm');
	if($profileForm === "new") { 
		 // spoof the $groups array based on the settings for the regcode that has been validated by register.php
		$reggroupsq = q("SELECT reg_codes_groups FROM " . XOOPS_DB_PREFIX . "_reg_codes WHERE reg_codes_code=\"" . $GLOBALS['regcode'] . "\"");
		$groups = explode("&8(%$", $reggroupsq[0]['reg_codes_groups']);
		if($groups[0] === "") { unset($groups); } // if a code has no groups associated with it, then kill the null value that will be in position 0 in the groups array.
		$groups[] = XOOPS_GROUP_USERS;
		$groups[] = XOOPS_GROUP_ANONYMOUS;
	}	
	$uid = $xoopsUser ? $xoopsUser->getVar('uid') : '0';

	$single_result = getSingle($fid, $uid, $groups, $member_handler, $gperm_handler, $mid);
	$single = $single_result['flag'];
	// if we're looking at a single entry form with no entry specified and where the user has no entry of their own, or it's an anonymous user, then set the entry based on a cookie if one is present
	// want to do this check here and override $entry prior to the security check since we don't like trusting cookies!
	$cookie_entry = (isset($_COOKIE['entryid_'.$fid]) AND !$entry AND $single AND ($single_result['entry'] == "" OR intval($uid) === 0)) ? $_COOKIE['entryid_'.$fid] : "";
	include_once XOOPS_ROOT_PATH . "/modules/formulize/class/data.php";
	$data_handler = new formulizeDataHandler($fid);
	if($cookie_entry) { 
		// check to make sure the cookie_entry exists...
		//$check_cookie_entry = q("SELECT id_req FROM " . $xoopsDB->prefix("formulize_form") . " WHERE id_req=" . intval($cookie_entry));
		//if($check_cookie_entry[0]['id_req'] > 0) {
		if($data_handler->entryExists(intval($cookie_entry))) {
			$entry = $cookie_entry; 
		} else {
			$cookie_entry = "";
		}
	}
	$owner = ($cookie_entry AND $uid) ? $uid : getEntryOwner($entry, $fid); // if we're pulling a cookie value and there is a valid UID in effect, then assume this user owns the entry, otherwise, figure out who does own the entry
	$owner_groups = $data_handler->getEntryOwnerGroups($entry);

	if($single AND !$entry AND !$overrideMulti AND $profileForm !== "new") { // only adjust the active entry if we're not already looking at an entry, and there is no overrideMulti which can be used to display a new blank form even on a single entry form -- useful for when multiple anonymous users need to be able to enter information in a form that is "one per user" for registered users. -- the pressence of a cookie on the hard drive of a user will override other settings
		$entry = $single_result['entry'];
		$owner = getEntryOwner($entry, $fid);
		unset($owner_groups);
		//$owner_groups =& $member_handler->getGroupsByUser($owner, FALSE);
		$owner_groups = $data_handler->getEntryOwnerGroups($entry);
	}

	if($entry == "proxy") { $entry = ""; } // convert the proxy flag to the actual null value expected for new entry situations (do this after the single check!)
	$editing = is_numeric($entry); // will be true if there is an entry we're looking at already

	if(!$scheck = security_check($fid, $entry, $uid, $owner, $groups, $mid, $gperm_handler) AND !$viewallforms AND !$profileForm) {
		print "<p>" . _NO_PERM . "</p>";
		return;
	}

	// main security check passed, so let's initialize flags	
	$go_back['url'] = substr($done_dest, 0, 1) == "/" ? XOOPS_URL . $done_dest : $done_dest;

	// set these arrays for the one form, and they are added to by the framework if it is in effect
	$fids[0] = $fid;
	if($entry) {
		$entries[$fid][0] = $entry;
	} else {
		$entries[$fid][0] = "";
	}


	if($frid) { 
		$linkResults = checkForLinks($frid, $fids, $fid, $entries, $gperm_handler, $owner_groups, $mid, $member_handler, $owner); 
		unset($entries);
		unset($fids);

		$fids = $linkResults['fids'];
		$entries = $linkResults['entries'];
		$sub_fids = $linkResults['sub_fids'];
		$sub_entries = $linkResults['sub_entries'];
	}
 
	// need to handle submission of entries 
	$formulize_mgr =& icms_getModuleHandler('elements', 'formulize');

	$info_received_msg = 0;
	$info_continue = 0;
	if($entries[$fid][0]) { $info_continue = 1; }
	
	$update_own_entry = $gperm_handler->checkRight("update_own_entry", $fid, $groups, $mid);
	$update_other_entries = $gperm_handler->checkRight("update_other_entries", $fid, $groups, $mid);
	$add_own_entry = $gperm_handler->checkRight("add_own_entry", $fid, $groups, $mid);
	$add_proxy_entries = $gperm_handler->checkRight("add_proxy_entries", $fid, $groups, $mid);
	
	if($_POST['form_submitted'] AND $profileForm !== "new" AND ((!$entry AND ($add_own_entry OR $add_proxy_entries)) OR ($entry AND ($update_other_entries OR ($update_own_entry AND $uid = getEntryOwner($entry, $fid)))))) {
		$info_received_msg = "1"; // flag for display of info received message
		if(!isset($GLOBALS['formulize_readElementsWasRun'])) {
			include_once XOOPS_ROOT_PATH . "/modules/formulize/include/readelements.php";
		}
		$temp_entries = $GLOBALS['formulize_allWrittenEntryIds']; // set in readelements.php
		
		if(!$formElementsOnly AND ($single OR $_POST['target_sub'] OR ($entries[$fid][0] AND ($original_entry OR ($_POST[$entrykey] AND !$_POST['back_from_sub']))) OR $overrideMulti OR ($_POST['go_back_form'] AND $overrideSubMulti))) { // if we just did a submission on a single form, or we just edited a multi, then assume the identity of the new entry.  Can be overridden by values passed to this function, to force multi forms to redisplay the just-saved entry.  Back_from_sub is used to override the override, when we're saving after returning from a multi-which is like editing an entry since entries are saved prior to going to a sub. -- Sept 4 2006: adding an entry in a subform forces us to stay on the same page too! -- Dec 21 2011: added check for !$formElementsOnly so that when we're getting just the elements in the form, we ignore any possible overriding, since that is an API driven situation where the called entry is the only one we want to display, period.
			$entry = $temp_entries[$fid][0];
			$entries = $temp_entries;
			// also remove any fids that aren't part of the $temp_entries...added Oct 26 2011...checkforlinks now can return the mainform when we're on a sub!  It's smarter, but displayForm (and possibly other places) were not built to assume it was that smart.
			$writtenFids = array_keys($temp_entries);
			$fids = array_intersect($fids, $writtenFids);
			$owner = getEntryOwner($entry, $fid);
			unset($owner_groups);
			$owner_groups = $data_handler->getEntryOwnerGroups($entry);
			//$owner_groups =& $member_handler->getGroupsByUser($owner, FALSE);
			$info_continue = 1;
		} elseif(!$_POST['target_sub']) { // as long as the form was submitted and we're not going to a sub form, then display the info received message and carry on with a blank form
			if(!$original_entry) { // if we're on a multi-form where the display form function was called without an entry, then clear the entries and behave as if we're doing a new add
				unset($entries);
				unset($sub_entries);
				$entries[$fid][0] = "";
				$sub_entries[$sub_fids[0]][0] = "";
			}
			$info_continue = 2;
		}
	}

	$sub_entries_synched = synchSubformBlankDefaults($fid, $entry);
	foreach($sub_entries_synched as $synched_sfid=>$synched_ids) {
		foreach($synched_ids as $synched_id) {
			$sub_entries[$synched_sfid][] = $synched_id;
		}
	}
	if(count($sub_entries_synched)>0) {
		formulize_updateDerivedValues($entry, $fid, $frid);
	}

	// special use of $settings added August 2 2006 -- jwe -- break out of form if $settings so indicates
	// used to allow saving of information when you don't want the form itself to reappear
	if($settings == "{RETURNAFTERSAVE}" AND $_POST['form_submitted']) { return "returning_after_save"; }

      // need to add code here to switch some things around if we're on a subform for the first time (add)
	// note: double nested sub forms will not work currently, since on the way back to the intermediate level, the go_back values will not be set correctly
	// target_sub is only set when adding a sub entry, and adding sub entries is now down by the subform ui
      //if($_POST['target_sub'] OR $_POST['goto_sfid']) {
	if($_POST['goto_sfid']) {
		$info_continue = 0;
		if($_POST['goto_sfid']) {
			$new_fid = $_POST['goto_sfid'];
		} else {
			$new_fid = $_POST['target_sub'];
		}
		$go_back['form'] = $fid;
		$go_back['entry'] = $temp_entries[$fid][0];
		unset($entries);
		unset($fids);
		unset($sub_fids);
		unset($sub_entries);
		$fid = $new_fid;
		$fids[0] = $new_fid;
		if($_POST['target_sub']) { // if we're adding a new entry
			$entries[$new_fid][0] = "";
		} else { // if we're going to an existing entry
			$entries[$new_fid][0] = $_POST['goto_sub'];
		}
		$entry = $entries[$new_fid][0];
		$single_result = getSingle($fid, $uid, $groups, $member_handler, $gperm_handler, $mid);
		$single = $single_result['flag'];
		if($single AND !$entry) {
			$entry = $single_result['entry'];
			unset($entries);
			$entries[$fid][0] = $entry;
		}
		unset($owner);
		$owner = getEntryOwner($entries[$new_fid][0], $new_fid); 
		$editing = is_numeric($entry); 
		unset($owner_groups);
		//$owner_groups =& $member_handler->getGroupsByUser($owner, FALSE);
		$newFidData_handler = new formulizeDataHandler($new_fid);
		$owner_groups = $newFidData_handler->getEntryOwnerGroups($entries[$new_fid][0]);
		$info_received_msg = 0;// never display this message when a subform is displayed the first time.	
		if($entry) { $info_continue = 1; }
		if(!$scheck = security_check($fid, $entries[$fid][0], $uid, $owner, $groups, $mid, $gperm_handler) AND !$viewallforms) {
			print "<p>" . _NO_PERM . "</p>";
			return;
		}
      }

	// set the alldoneoverride if necessary -- August 22 2006
	$config_handler =& xoops_gethandler('config');
	$formulizeConfig = $config_handler->getConfigsByCat(0, $mid);
	// remove the all done button if the config option says 'no', and we're on a single-entry form, or the function was called to look at an existing entry, or we're on an overridden Multi-entry form
	$allDoneOverride = (!$formulizeConfig['all_done_singles'] AND !$profileForm AND (($single OR $overrideMulti OR $original_entry) AND !$_POST['target_sub'] AND !$_POST['goto_sfid'] AND !$_POST['deletesubsflag'] AND !$_POST['parent_form'])) ? true : false;
	
	if($allDoneOverride AND $_POST['form_submitted']) {
		drawGoBackForm($go_back, $currentURL, $settings, $entry);
		print "<script type=\"text/javascript\">window.document.go_parent.submit();</script>\n";
		return;
	} else {
		// only do all this stuff below, the normal form displaying stuff, if we are not leaving this page now due to the all done button being overridden
		
		// we cannot have the back logic above invoked when dealing with a subform, but if the override is supposed to be in place, then we need to invoke it
		if(!$allDoneOverride AND !$formulizeConfig['all_done_singles'] AND !$profileForm AND ($_POST['target_sub'] OR $_POST['goto_sfid'] OR $_POST['deletesubsflag'] OR $_POST['parent_form']) AND ($single OR $original_entry OR $overrideMulti)) {
			$allDoneOverride = true;
		}
	
	
	
		/*if($uid==1) {
		print "Forms: ";
		print_r($fids);
		print "<br>Entries: ";
		print_r($entries);
		print "<br>Subforms: ";
		print_r($sub_fids);
		print "<br>Subentries: ";
		print_r($sub_entries); // debug block - ONLY VISIBLE TO USER 1 RIGHT NOW 
		} */
		
		formulize_benchmark("Ready to start building form.");
		
		$title = "";
		foreach($fids as $this_fid) {
	
			if(!$scheck = security_check($this_fid, $entries[$this_fid][0], $uid, $owner, $groups, $mid, $gperm_handler) AND !$viewallforms) {
				continue;
			}
			
			// if there is more than one form, then force the setting of the linked element after there has been a form submission
			// do this after the security check, hence its placement here
			static $oneToOneLinksMade = false;
			if(!$oneToOneLinksMade AND count($fids)> 1 AND isset($GLOBALS['formulize_newEntryIds'][$this_fid])) {
				$frameworkHandler = icms_getModuleHandler('frameworks', 'formulize');
				$frameworkObject = $frameworkHandler->get($frid);
				foreach($frameworkObject->getVar('links') as $thisLink) {
					if($thisLink->getVar('relationship') == 1) { // 1 signifies one to one relationships
						$form1 = $thisLink->getVar('form1');
						$form2 = $thisLink->getVar('form2');
						$key1 = $thisLink->getVar('key1');
						$key2 = $thisLink->getVar('key2');
						if($thisLink->getVar('common')) {
							if(!isset($_POST["de_".$form1."_new_".$key1]) OR $_POST["de_".$form1."_new_".$key1] === "") {
								// if we don't have a value for this element, then populate it with the value from the other element
								formulize_writeEntry(array($key1=>$_POST["de_".$form2."_new_".$key2]), $GLOBALS['formulize_newEntryIds'][$form1][0]);
							} elseif(!isset($_POST["de_".$form2."_new_".$key2]) OR $_POST["de_".$form2."_new_".$key2] === "") {
								// if we don't have a value for this element, then populate it with the value from the other element
								formulize_writeEntry(array($key2=>$_POST["de_".$form1."_new_".$key1]), $GLOBALS['formulize_newEntryIds'][$form2][0]);
							}
						} else {
							// figure out which one is on which side of the linked selectbox
							$element_handler = icms_getModuleHandler('elements', 'formulize');
							$linkedElement1 = $element_handler->get($key1);
							$linkedElement1EleValue = $linkedElement1->getVar('ele_value');
							if(strstr($linkedElement1EleValue[2], "#*=:*")) {
								// element 1 is the linked selectbox, so get the value of entry id for what we just created in form 2, and put it in element 1 with , , around it
								formulize_writeEntry(array($key1=>",".$GLOBALS['formulize_newEntryIds'][$form2][0].","), $GLOBALS['formulize_newEntryIds'][$form1][0]);
							} else {
								// element 2 is the linked selectbox, so get the value of entry id for what we just created in form 1 and put it in element 2 with , , around it
								formulize_writeEntry(array($key2=>",".$GLOBALS['formulize_newEntryIds'][$form1][0].","), $GLOBALS['formulize_newEntryIds'][$form2][0]);
							}
						}
					} 
				}
				$oneToOneLinksMade = true;
			}
			

				if($single == "group" AND $update_own_entry AND $entry == $single_result['entry']) {
					$groupEntryWithUpdateRights = true;
					$update_other_entries = true;
				}

	
				unset($prevEntry);
				if($entries[$this_fid]) { 	// if there is an entry, then get the data for that entry
					$prevEntry = getEntryValues($entries[$this_fid][0], $formulize_mgr, $groups, $this_fid, $elements_allowed, $mid, $uid, $owner, $groupEntryWithUpdateRights); 
				}

				// display the form

				//get the form title: (do only once)
			$firstform = 0;
			if(!$form) {
	
				$firstform = 1; 	      	
				$title = isset($passedInTitle) ? $passedInTitle : trans(getFormTitle($this_fid));
				unset($form);
				if($formElementsOnly) {
					$form = new formulize_elementsOnlyForm();
				} else {
					$form = new formulize_themeForm($title, 'formulize', "$currentURL", "post", true); // extended class that puts formulize element names into the tr tags for the table, so we can show/hide them as required
				}
				$form->setExtra("enctype='multipart/form-data'"); // imp�ratif!
	
				if(is_array($settings)) { $form = writeHiddenSettings($settings, $form); }
				$form->addElement (new XoopsFormHidden ('ventry', $settings['ventry'])); // necessary to trigger the proper reloading of the form page, until Done is called and that form does not have this flag.
	
				// include who the entry belongs to and the date
				// include acknowledgement that information has been updated if we have just done a submit
				// form_meta includes: last_update, created, last_update_by, created_by
	
				if(!$profileForm AND $titleOverride != "all") {
							// build the break HTML and then add the break to the form
					if(strstr($currentURL, "printview.php")) {
						$breakHTML = "<center>";
					} else {
								$breakHTML = "<center><p><b>";
								if($info_received_msg) { $breakHTML .= _formulize_INFO_SAVED . "&nbsp;"; }
								
								if($info_continue == 1 AND (($owner == $uid AND $update_own_entry) OR $update_other_entries)) {
									$breakHTML .= _formulize_INFO_CONTINUE1 . "</b></p>";
								} elseif($info_continue == 2) {
									$breakHTML .=  _formulize_INFO_CONTINUE2 . "</b></p>";
								} elseif(!$entry AND ($gperm_handler->checkRight("add_own_entry", $fid, $groups, $mid) OR $gperm_handler->checkRight("add_proxy_entries", $fid, $groups, $mid))) {
									$breakHTML .=  _formulize_INFO_MAKENEW . "</b></p>";
								} else {
									$breakHTML .= "</b></p>";
								}

					}
							$breakHTML .= "</center><table cellpadding=5 width=100%><tr><td width=50% style=\"vertical-align: bottom;\">";
	
							$breakHTML .= "<p><b>" . _formulize_FD_ABOUT . "</b><br>";
							
							if($entries[$this_fid][0]) {
								$form_meta = getMetaData($entries[$this_fid][0], $member_handler, $this_fid);
								$breakHTML .= _formulize_FD_CREATED . $form_meta['created_by'] . " " . formulize_formatDateTime($form_meta['created']) . "<br>" . _formulize_FD_MODIFIED . $form_meta['last_update_by'] . " " . formulize_formatDateTime($form_meta['last_update']) . "</p>";
							} else {
								$breakHTML .= _formulize_FD_NEWENTRY . "</p>";
							}
	
							$breakHTML .= "</td><td width=50% style=\"vertical-align: bottom;\">"; //<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p></td><td>";
					if(strstr($currentURL, "printview.php") OR (!$gperm_handler->checkRight("add_own_entry", $fid, $groups, $mid) AND !$gperm_handler->checkRight("add_proxy_entries", $fid, $groups, $mid))) {
						$breakHTML .= "<p>";
					} else {
						// get save and button button options
						$save_button_text = "";
						$done_button_text = "";
						if(is_array($button_text)) {
							$save_button_text = $button_text[1];
							$done_button_text = $button_text[0];						
						} else { 
							$done_button_text = $button_text;						
						}
						if(!$done_button_text AND !$allDoneOverride) {
							$done_button_text = _formulize_INFO_DONE1 . _formulize_DONE . _formulize_INFO_DONE2;
						} elseif($done_button_text != "{NOBUTTON}" AND !$allDoneOverride) {
							$done_button_text = _formulize_INFO_DONE1 . $done_button_text . _formulize_INFO_DONE2;
						// check to see if the user is allowed to modify the existing entry, and if they're not, then we have to draw in the all done button so they have a way of getting back where they're going
						} elseif(($entry AND (($owner == $uid AND $update_own_entry) OR ($owner != $uid AND $update_other_entries))) OR !$entry) {
							$done_button_text = "";
						} else {
							$done_button_text = _formulize_INFO_DONE1 . _formulize_DONE . _formulize_INFO_DONE2;					
						}
	
	
						$nosave = false;
						if(!$save_button_text AND (($entry AND (($owner == $uid AND $update_own_entry) OR ($owner != $uid AND $update_other_entries))) OR (!$entry AND ($add_own_entry OR $add_proxy_entries)))) {
							$save_button_text = _formulize_INFO_SAVEBUTTON;
						} elseif($save_button_text != "{NOBUTTON}" AND (($entry AND (($owner == $uid AND $update_own_entry) OR ($owner != $uid AND $update_other_entries))) OR (!$entry AND ($add_own_entry OR $add_proxy_entries)))) {
							$save_button_text = _formulize_INFO_SAVE1 . $save_button_text . _formulize_INFO_SAVE2;
						} else {
							$save_button_text = _formulize_INFO_NOSAVE;
							$nosave = true;
						}
										$breakHTML .= "<p>" . $save_button_text;
						if($done_button_text) {
											$breakHTML .= "<br>" . $done_button_text;
										}
					}
							$breakHTML .= "</p></td></tr></table>";
							$form->insertBreak($breakHTML, "even");
				} elseif($profileForm) {
					// if we have a profile form, put the profile fields at the top of the form, populated based on the DB values from the _users table
					$form = addProfileFields($form, $profileForm);
				}
	
			}
	
			if($titleOverride=="1" AND !$firstform) { // set onetooneTitle flag to 1 when function invoked to force drawing of the form title over again
				$title = trans(getFormTitle($this_fid));
				$form->insertBreak("<table><th>$title</th></table>","");
			}
	
			// if this form has a parent, then determine the $parentLinks
			if($go_back['form'] AND !$parentLinks[$this_fid]) {
				$parentLinks[$this_fid] = getParentLinks($this_fid, $frid);
			}

			formulize_benchmark("Before Compile Elements.");

					$form = compileElements($this_fid, $form, $formulize_mgr, $prevEntry, $entries[$this_fid][0], $go_back, $parentLinks[$this_fid], $owner_groups, $groups, $overrideValue, $elements_allowed, $profileForm, $frid, $mid, $sub_entries, $sub_fids, $member_handler, $gperm_handler, $title, $screen, $printViewPages, $printViewPageTitles);

			formulize_benchmark("After Compile Elements.");
	
	
		} // end of for each fids
	
		if(!is_object($form)) { exit("Error: the form cannot be displayed.  Does the current group have permission to access the form?"); }
	
				// DRAW IN THE SPECIAL UI FOR A SUBFORM LINK (ONE TO MANY)		
				if(count($sub_fids) > 0) { // if there are subforms, then draw them in...only once we have a bonafide entry in place already
					// draw in special params for this form
			$form->addElement (new XoopsFormHidden ('target_sub', ''));
			$form->addElement (new XoopsFormHidden ('target_sub_instance', ''));
			$form->addElement (new XoopsFormHidden ('numsubents', 1));
			$form->addElement (new XoopsFormHidden ('del_subs', ''));
			$form->addElement (new XoopsFormHidden ('goto_sub', ''));
			$form->addElement (new XoopsFormHidden ('goto_sfid', ''));
			
			foreach($sub_fids as $sfid) {
				// only draw in the subform UI if the subform hasn't been drawn in previously, courtesy of a subform element in the form.
				// Subform elements are recommended since they provide 1. specific placement, 2. custom captions, 3. direct choice of form elements to include
				if(in_array($sfid, $sfidsDrawn) OR $elements_allowed OR (!$scheck = security_check($sfid, "", $uid, $owner, $groups, $mid, $gperm_handler) AND !$viewallforms)) { // no entry passed so this will simply check whether they have permission for the form or not
					continue;
				}
				$subUICols = drawSubLinks($sfid, $sub_entries, $uid, $groups, $member_handler, $frid, $gperm_handler, $mid, $fid, $entry);
				unset($subLinkUI);
				if(isset($subUICols['single'])) {
					$form->insertBreak($subUICols['single'], "even");
				} else {
					$subLinkUI = new XoopsFormLabel($subUICols['c1'], $subUICols['c2']);
					$form->addElement($subLinkUI);
				}
			}
		} 
	
	
		// draw in proxy box if necessary (only if they have permission and only on new entries, not on edits)
		if(!strstr($_SERVER['PHP_SELF'], "formulize/printview.php")) {
			if($gperm_handler->checkRight("add_proxy_entries", $fid, $groups, $mid) AND !$entries[$fid][0]) {
				$form = addOwnershipList($form, $groups, $member_handler, $gperm_handler, $fid, $mid);
			} elseif($entries[$fid][0] AND $gperm_handler->checkRight("update_entry_ownership", $fid, $groups, $mid)) {
				$form = addOwnershipList($form, $groups, $member_handler, $gperm_handler, $fid, $mid, $entries[$fid][0]);	
			}
		}
	
		// draw in the submitbutton if necessary
		if($entry AND !$formElementsOnly) { // existing entry, if it's their own and they can update their own, or someone else's and they can update someone else's
			if(($owner == $uid AND $update_own_entry) OR ($owner != $uid AND $update_other_entries)) {
				$form = addSubmitButton($form, _formulize_SAVE, $go_back, $currentURL, $button_text, $settings, $temp_entries[$this_fid][0], $fids, $formframe, $mainform, $entry, $profileForm, $elements_allowed, $allDoneOverride, $printall, $screen); //nmc 2007.03.24 - added $printall
			} else {
				$form = addSubmitButton($form, '', $go_back, $currentURL, $button_text, $settings, $temp_entries[$this_fid][0], $fids, $formframe, $mainform, $entry, $profileForm, $elements_allowed, false, $printall, $screen); //nmc 2007.03.24 - added $printall
			}
		} elseif(!$formElementsOnly) { // new entry
			if($gperm_handler->checkRight("add_own_entry", $fid, $groups, $mid) OR $gperm_handler->checkRight("add_proxy_entries", $fid, $groups, $mid)) {
				$form = addSubmitButton($form, _formulize_SAVE, $go_back, $currentURL, $button_text, $settings, $temp_entries[$this_fid][0], $fids, $formframe, $mainform, $entry, $profileForm, $elements_allowed, $allDoneOverride, $printall, $screen); //nmc 2007.03.24 - added $printall
			} else {
				$form = addSubmitButton($form, '', $go_back, $currentURL, $button_text, $settings, $temp_entries[$this_fid][0], $fids, $formframe, $mainform, $entry, $profileForm, $elements_allowed, false, $printall, $screen); //nmc 2007.03.24 - added $printall
			}
		}
	
		if(!$formElementsOnly) {
			
			// add flag to indicate that the form has been submitted
			$form->addElement (new XoopsFormHidden ('form_submitted', "1"));
			if($go_back['form']) { // if this is set, then we're doing a subform, so put in a flag to prevent the parent from being drawn again on submission
				$form->addElement (new XoopsFormHidden ('sub_fid', $fid));
				$form->addElement (new XoopsFormHidden ('sub_submitted', $entries[$fid][0]));
				$form->addElement (new XoopsFormHidden ('go_back_form', $go_back['form']));
				$form->addElement (new XoopsFormHidden ('go_back_entry', $go_back['entry']));
			}
			
			// saving message
			print "<div id=savingmessage style=\"display: none; position: absolute; width: 100%; right: 0px; text-align: center; padding-top: 50px;\">\n";
			if ( file_exists(XOOPS_ROOT_PATH."/modules/formulize/images/saving-".$xoopsConfig['language'].".gif") ) {
				print "<img alt='Saving...' src=\"" . XOOPS_URL . "/modules/formulize/images/saving-" . $xoopsConfig['language'] . ".gif\">\n";
			} else {
				print "<img alt='Saving...' src=\"" . XOOPS_URL . "/modules/formulize/images/saving-english.gif\">\n";
			}
			print "</div>\n";

			drawJavascript($nosave);
			if(count($GLOBALS['formulize_renderedElementHasConditions'])>0) {
				drawJavascriptForConditionalElements($GLOBALS['formulize_renderedElementHasConditions'], $entries, $sub_entries);
			}
		}

		print "<div id=formulizeform>".$form->render()."</div>"; // note, security token is included in the form by the xoops themeform render method, that's why there's no explicity references to the token in the compiling/generation of the main form object
		
		// if we're in Drupal, include the main XOOPS js file, so the calendar will work if present...
		// assumption is that the calendar javascript has already been included by the datebox due to no
		// $xoopsTpl being in effect in Drupal -- this assumption will fail if Drupal is displaying a pageworks
		// page that uses the $xoopsTpl, for instance.  (Date select box file itself checks for $xoopsTpl)
		global $user;
		static $includedXoopsJs = false;
		if(is_object($user) AND !$includedXoopsJs) {
			print "<script type=\"text/javascript\" src=\"" . XOOPS_URL . "/include/xoops.js\"></script>\n";
			$includedXoopsJs = true;
		}
	}// end of if we're not going back to the prev page because of an all done button override
}

// THIS FUNCTION ADDS THE SPECIAL PROFILE FIELDS TO THE TOP OF A PROFILE FORM
function addProfileFields($form, $profileForm) {
	// add... 
	// username
	// full name
	// e-mail
	// timezone
	// password

	global $xoopsUser, $xoopsConfig, $xoopsConfigUser;
	$config_handler =& xoops_gethandler('config');
	$xoopsConfigUser =& $config_handler->getConfigsByCat(XOOPS_CONF_USER);
	$user_handler =& xoops_gethandler('user');
	$thisUser = $user_handler->get($profileForm);

	// initialize $thisUser
	if($thisUser) {
		$thisUser_name = $thisUser->getVar('name', 'E');
		$thisUser_uname = $thisUser->getVar('uname');
		$thisUser_timezone_offset = $thisUser->getVar('timezone_offset');
		$thisUser_email = $thisUser->getVar('email');
		$thisUser_uid = $thisUser->getVar('uid');
		$thisUser_viewemail = $thisUser->user_viewemail();
		$thisUser_umode = $thisUser->getVar('umode');
		$thisUser_uorder = $thisUser->getVar('uorder');
		$thisUser_notify_method = $thisUser->getVar('notify_method');
		$thisUser_notify_mode = $thisUser->getVar('notify_mode');
		$thisUser_user_sig = $thisUser->getVar('user_sig', 'E');
		$thisUser_attachsig = $thisUser->getVar('attachsig');
	} else { // anon user
		$thisUser_name = $GLOBALS['name']; //urldecode($_GET['name']);
		$thisUser_uname = $GLOBALS['uname']; //urldecode($_GET['uname']);
		$thisUser_timezone_offset = isset($GLOBALS['timezone_offset']) ? $GLOBALS['timezone_offset'] : $xoopsConfig['default_TZ']; // isset($_GET['timezone_offset']) ? urldecode($_GET['timezone_offset']) : $xoopsConfig['default_TZ'];
		$thisUser_email = $GLOBALS['email']; //urldecode($_GET['email']);
		$thisUser_viewemail = $GLOBALS['user_viewemail']; //urldecode($_GET['viewemail']);
		$thisUser_uid = 0;
		$agree_disc = $GLOBALS['agree_disc'];
	}

		include_once XOOPS_ROOT_PATH . "/language/" . $xoopsConfig['language'] . "/user.php";

	$form->insertBreak(_formulize_ACTDETAILS, "head");
	// Check reg_codes module option to use email address as username
	$module_handler =& xoops_gethandler('module');
	$regcodesModule =& $module_handler->getByDirname("reg_codes");
	$regcodesConfig =& $config_handler->getConfigsByCat(0, $regcodesModule->getVar('mid'));

	// following borrowed from edituser.php
	if($profileForm == "new") {
		// 'new' should ONLY be coming from the modified register.php file that the registration codes module uses
		// ie: we are assuming registration codes is installed
		$form->addElement(new XoopsFormHidden('userprofile_regcode', $GLOBALS['regcode']));
		$uname_size = $xoopsConfigUser['maxuname'] < 255 ? $xoopsConfigUser['maxuname'] : 255;
		$labelhelptext = _formulize_USERNAME_HELP1; // set it to a variable so we can test for its existence; don't want to print this stuff if there's no translation
		$labeltext = $labelhelptext == "" ? _US_NICKNAME : _US_NICKNAME . _formulize_USERNAME_HELP1 . $xoopsConfigUser['minuname'] . _formulize_USERNAME_HELP2 . $uname_size . _formulize_USERNAME_HELP3;
		if ($regcodesConfig['email_as_username'] == 0)	{
			// Allow User names to be created
			$uname_label = new XoopsFormText($labeltext, 'userprofile_uname', $uname_size, $uname_size, $thisUser_uname);
			$uname_reqd = 1;
		}
		else {
			// Usernames are created based on email address
			$uname_label = new XoopsFormHidden('userprofile_uname', $thisUser_uname);
			$uname_reqd = 0;
		}
		$form->addElement($uname_label, $uname_reqd);
	} else {
		$uname_label = new XoopsFormLabel(_US_NICKNAME, $thisUser_uname);
		$form->addElement($uname_label);
	}
	$email_tray = new XoopsFormElementTray(_US_EMAIL, '<br />');
	if ($profileForm == "new" OR (($xoopsConfigUser['allow_chgmail'] == 1) && ($regcodesConfig['email_as_username'] == 0))) {
      	$email_text = new XoopsFormText('', 'userprofile_email', 30, 255, $thisUser_email);
		$email_tray->addElement($email_text, 1);
	}
	else {
        $email_text = new XoopsFormLabel('', $thisUser_email);
		$email_tray->addElement($email_text);
	}
	$email_cbox_value = $thisUser_viewemail ? 1 : 0;
	$email_cbox = new XoopsFormCheckBox('', 'userprofile_user_viewemail', $email_cbox_value);
	$email_cbox->addOption(1, _US_ALLOWVIEWEMAIL);
	$email_tray->addElement($email_cbox);
	$form->addElement($email_tray, 1);
	
		
	$passlabel = $profileForm == "new" ? _formulize_TYPEPASSTWICE_NEW : _formulize_TYPEPASSTWICE_CHANGE;
	$passlabel .= $xoopsConfigUser['minpass'] . _formulize_PASSWORD_HELP1;
	$pwd_tray = new XoopsFormElementTray(_US_PASSWORD.'<br />'.$passlabel);
	$pwd_text = new XoopsFormPassword('', 'userprofile_password', 10, 32);
	$pwd_text2 = new XoopsFormPassword('', 'userprofile_vpass', 10, 32);
	$pass_required = $profileForm == "new" ? 1 : 0;
	$pwd_tray->addElement($pwd_text, $pass_required);
	$pwd_tray->addElement($pwd_text2, $pass_required);
	$form->addElement($pwd_tray, $pass_required);
	$name_text = new XoopsFormText(_US_REALNAME, 'userprofile_name', 30, 60, $thisUser_name);
	$form->addElement($name_text, 1);
	$timezone_select = new XoopsFormSelectTimezone(_US_TIMEZONE, 'userprofile_timezone_offset', $thisUser_timezone_offset);
	$form->addElement($timezone_select);

	if($profileForm != "new") {
      	$umode_select = new XoopsFormSelect(_formulize_CDISPLAYMODE, 'userprofile_umode', $thisUser_umode);
      	$umode_select->addOptionArray(array('nest'=>_NESTED, 'flat'=>_FLAT, 'thread'=>_THREADED));
      	$form->addElement($umode_select);
      	$uorder_select = new XoopsFormSelect(_formulize_CSORTORDER, 'userprofile_uorder', $thisUser_uorder);
      	$uorder_select->addOptionArray(array(XOOPS_COMMENT_OLD1ST => _OLDESTFIRST, XOOPS_COMMENT_NEW1ST => _NEWESTFIRST));
      	$form->addElement($uorder_select);
      	include_once XOOPS_ROOT_PATH . "/language/" . $xoopsConfig['language'] . '/notification.php';
      	include_once XOOPS_ROOT_PATH . '/include/notification_constants.php';
      	$notify_method_select = new XoopsFormSelect(_NOT_NOTIFYMETHOD, 'userprofile_notify_method', $thisUser_notify_method);
      	$notify_method_select->addOptionArray(array(XOOPS_NOTIFICATION_METHOD_DISABLE=>_NOT_METHOD_DISABLE, XOOPS_NOTIFICATION_METHOD_PM=>_NOT_METHOD_PM, XOOPS_NOTIFICATION_METHOD_EMAIL=>_NOT_METHOD_EMAIL));
      	$form->addElement($notify_method_select);
      	$notify_mode_select = new XoopsFormSelect(_NOT_NOTIFYMODE, 'userprofile_notify_mode', $thisUser_notify_mode);
      	$notify_mode_select->addOptionArray(array(XOOPS_NOTIFICATION_MODE_SENDALWAYS=>_NOT_MODE_SENDALWAYS, XOOPS_NOTIFICATION_MODE_SENDONCETHENDELETE=>_NOT_MODE_SENDONCE, XOOPS_NOTIFICATION_MODE_SENDONCETHENWAIT=>_NOT_MODE_SENDONCEPERLOGIN));
      	$form->addElement($notify_mode_select);
      	$sig_tray = new XoopsFormElementTray(_US_SIGNATURE, '<br />');
      	include_once XOOPS_ROOT_PATH . '/include/xoopscodes.php';
      	$sig_tarea = new XoopsFormDhtmlTextArea('', 'userprofile_user_sig', $thisUser_user_sig);
      	$sig_tray->addElement($sig_tarea);
      	$sig_cbox_value = $thisUser_attachsig ? 1 : 0;
      	$sig_cbox = new XoopsFormCheckBox('', 'userprofile_attachsig', $sig_cbox_value);
      	$sig_cbox->addOption(1, _US_SHOWSIG);
      	$sig_tray->addElement($sig_cbox);
      	$form->addElement($sig_tray);
	} else { // display only on new account creation...
		if ($xoopsConfigUser['reg_dispdsclmr'] != 0 && $xoopsConfigUser['reg_disclaimer'] != '') {
			$disc_tray = new XoopsFormElementTray(_US_DISCLAIMER, '<br />');
			$disc_text = new XoopsFormTextarea('', 'disclaimer', trans($xoopsConfigUser['reg_disclaimer']), 8);
			$disc_text->setExtra('readonly="readonly"');
			$disc_tray->addElement($disc_text);
			$agree_chk = new XoopsFormCheckBox('', 'userprofile_agree_disc', $agree_disc);
			$agree_chk->addOption(1, "<span style=\"font-size: 14pt;\">" . _US_IAGREE . "</span>");
			$disc_tray->addElement($agree_chk);
			$form->addElement($disc_tray);
		}
		$form->addElement(new XoopsFormHidden("op", "newuser"));
	}

	$uid_check = new XoopsFormHidden("userprofile_uid", $thisUser_uid);
	$form->addElement($uid_check);
	$form->insertBreak(_formulize_PERSONALDETAILS, "head");

	return $form;

} 


// add the submit button to a form
function addSubmitButton($form, $subButtonText, $go_back="", $currentURL, $button_text, $settings, $entry, $fids, $formframe, $mainform, $cur_entry, $profileForm, $elements_allowed="", $allDoneOverride=false, $printall=0, $screen=null) { //nmc 2007.03.24 - added $printall

	if($printall == 2) { // 2 is special setting in multipage screens that means do not include any printable buttons of any kind
		return $form;
	}

	if(strstr($currentURL, "printview.php")) { // don't do anything if we're on the print view
		return $form;
	} else {

	drawGoBackForm($go_back, $currentURL, $settings, $entry);

	if(!$button_text OR ($button_text == "{NOBUTTON}" AND $go_back['form'] > 0)) { // presence of a goback form (ie: parent form) overrides {NOBUTTON} -- assumption is the save button will not also be overridden at the same time
		$button_text = _formulize_DONE; 
	} elseif(is_array($button_text)) {
		if(!$button_text[0]) { 
			$done_text_temp = _formulize_DONE; 
		} else {
			$done_text_temp = $button_text[0];
		}
		if(!$button_text[1]) { 
			$save_text_temp = _formulize_SAVE; 
		} else {
			$save_text_temp = $button_text[1];
		}
	}

	// override -- the "no-all-done-button" config option turns off the all done button and changes save into a save-and-leave button

	// need to grab the $nosubforms variable created by the multiple page function, so we know to put the printable view button (and nothing else) on the screen for multipage forms
	global $nosubforms;
	if(!$profileForm AND ($save_text_temp != "{NOBUTTON}" OR $nosubforms)) { // do not use printable button for profile forms, or forms where there is no Save button (ie: a non-standard saving process is in use and access to the normal printable option may be prohibited)
		$printbutton = new XoopsFormButton('', 'printbutton', _formulize_PRINTVIEW, 'button');
		if(is_array($elements_allowed)) {
			$ele_allowed = implode(",",$elements_allowed);
		}
		$printbutton->setExtra("onclick='javascript:PrintPop(\"$ele_allowed\");'");
		$rendered_buttons = $printbutton->render(); // nmc 2007.03.24 - added
		if ($printall) {																					// nmc 2007.03.24 - added
			$printallbutton = new XoopsFormButton('', 'printallbutton', _formulize_PRINTALLVIEW, 'button');	// nmc 2007.03.24 - added
			$printallbutton->setExtra("onclick='javascript:PrintAllPop();'");								// nmc 2007.03.24 - added
			$rendered_buttons .= "&nbsp;&nbsp;&nbsp;" . $printallbutton->render();							// nmc 2007.03.24 - added
			}
		$buttontray = new XoopsFormElementTray($rendered_buttons, "&nbsp;"); // nmc 2007.03.24 - amended [nb: FormElementTray 'caption' is actually either 1 or 2 buttons]
	} else {
		$buttontray = new XoopsFormElementTray("", "&nbsp;");
	}
	if($subButtonText == _formulize_SAVE) { // _formulize_SAVE is passed only when the save button is allowed to be drawn
		if($save_text_temp) { $subButtonText = $save_text_temp; }
		if($subButtonText != "{NOBUTTON}") {
			$saveButton = new XoopsFormButton('', 'submitx', trans($subButtonText), 'button'); // doesn't use name submit since that conflicts with the submit javascript function
			$saveButton->setExtra("onclick=javascript:validateAndSubmit();");
			$buttontray->addElement($saveButton);
		}
	}
	
	if((($button_text != "{NOBUTTON}" AND !$done_text_temp) OR (isset($done_text_temp) AND $done_text_temp != "{NOBUTTON}")) AND !$allDoneOverride) { 
		if($done_text_temp) { $button_text = $done_text_temp; }
		$donebutton = new XoopsFormButton('', 'donebutton', trans($button_text), 'button');
		$donebutton->setExtra("onclick=javascript:verifyDone();");
		$buttontray->addElement($donebutton); 
	}

	if(!$profileForm) { // do not use printable button for profile forms
		$newcurrentURL= XOOPS_URL . "/modules/formulize/printview.php";
		print "<form name='printview' action='".$newcurrentURL."' method=post target=_blank>\n";
		
		// add security token
		if(isset($GLOBALS['xoopsSecurity'])) {
			print $GLOBALS['xoopsSecurity']->getTokenHTML();
		}
		
		$currentPage = "";
		$screenid = "";
    if($screen) {
		  $screenid = $screen->getVar('sid');
			// check for a current page setting
			if(isset($settings['formulize_currentPage'])) {
				$currentPage = $settings['formulize_currentPage'];
			}
		}
    
		print "<input type=hidden name=screenid value=".$screenid.">";
		print "<input type=hidden name=currentpage value=".$currentPage.">";

		print "<input type=hidden name=lastentry value=".$cur_entry.">";
		if($go_back['form']) { // we're on a sub, so display this form only
			print "<input type=hidden name=formframe value=".$fids[0].">";	
		} else { // otherwise, display like normal
			print "<input type=hidden name=formframe value='".$formframe."'>";	
			print "<input type=hidden name=mainform value='".$mainform."'>";
		}
		if(is_array($elements_allowed)) {
			$ele_allowed = implode(",",$elements_allowed);
			print "<input type=hidden name=elements_allowed value='".$ele_allowed."'>";
		} else {
			print "<input type=hidden name=elements_allowed value=''>";
		}
		print "</form>";
		//added by Cory Aug 27, 2005 to make forms printable
	}

	$trayElements = $buttontray->getElements();
	if(count($trayElements) > 0 OR $nosubforms) {
		$form->addElement($buttontray);
	}
	return $form;
	}
}

// this function draws in the hidden form that handles the All Done logic that sends user off the form
function drawGoBackForm($go_back, $currentURL, $settings, $entry) {
	if($go_back['url'] == "" AND !isset($go_back['form'])) { // there are no back instructions at all, then make the done button go to the front page of whatever is going on in pageworks
		print "<form name=go_parent action=\"$currentURL\" method=post>"; //onsubmit=\"javascript:verifyDone();\" method=post>";
		if(is_array($settings)) { writeHiddenSettings($settings); }
		print "<input type=hidden name=lastentry value=$entry>";
		print "</form>";
	}
	if($go_back['form']) { // parent form overrides specified back URL
		print "<form name=go_parent action=\"$currentURL\" method=post>"; //onsubmit=\"javascript:verifyDone();\" method=post>";
		print "<input type=hidden name=parent_form value=" . $go_back['form'] . ">";
		print "<input type=hidden name=parent_entry value=" . $go_back['entry'] . ">";
		print "<input type=hidden name=ventry value=" . $settings['ventry'] . ">";
		if(is_array($settings)) { writeHiddenSettings($settings); }
		print "<input type=hidden name=lastentry value=$entry>";
		print "</form>";
	} elseif($go_back['url']) {
		print "<form name=go_parent action=\"" . $go_back['url'] . "\" method=post>"; //onsubmit=\"javascript:verifyDone();\" method=post>";
		if(is_array($settings)) { writeHiddenSettings($settings); }		
		print "<input type=hidden name=lastentry value=$entry>";
		print "</form>";
	} 
}

// this function draws in the UI for sub links
function drawSubLinks($sfid, $sub_entries, $uid, $groups, $member_handler, $frid, $gperm_handler, $mid, $fid, $entry, $customCaption="", $customElements="", $defaultblanks = 0, $showViewButtons = 1, $captionsForHeadings=0, $overrideOwnerOfNewEntries="", $mainFormOwner=0, $hideaddentries, $subformConditions, $subformElementId=0, $rowsOrForms='row') {

	$hideaddentries = $hideaddentries === 'hideaddentries' ? 1 : 0; // only the text value is a valid flag for hiding the entries...because we need an affirmative hide flag, we can't use null, blank, etc, since older subforms will have no value for this flag

	global $xoopsDB, $nosubforms;
	$GLOBALS['framework'] = $frid;
	$form_handler = icms_getModuleHandler('forms', 'formulize');

	// limit the sub_entries array to just the entries that match the conditions, if any
	if(is_array($subformConditions)) {
		list($conditionsFilter, $conditionsFilterOOM, $curlyBracketFormFrom) = buildConditionsFilterSQL($subformConditions, $sfid, $entry, $mainFormOwner, $fid); // pass in mainFormOwner as the comparison ID for evaluating {USER} so that the included entries are consistent when an admin looks at a set of entries made by someone else.
		$subformObject = $form_handler->get($sfid);
		$sql = "SELECT entry_id FROM ".$xoopsDB->prefix("formulize_".$subformObject->getVar('form_handle'))."$curlyBracketFormFrom WHERE entry_id IN (".implode(", ", $sub_entries[$sfid]).") $conditionsFilter $conditionsFilterOOM";
		$sub_entries[$sfid] = array();
		if($res = $xoopsDB->query($sql)) {
			while($array = $xoopsDB->fetchArray($res)) {
				$sub_entries[$sfid][] = $array['entry_id'];
			}
		}
	}
	
	include_once XOOPS_ROOT_PATH . "/modules/formulize/include/extract.php";
	
	$target_sub_to_use = (isset($_POST['target_sub']) AND $_POST['target_sub'] != 0) ? $_POST['target_sub'] : $sfid; 
	$elementq = q("SELECT fl_key1, fl_key2, fl_common_value, fl_form2_id FROM " . $xoopsDB->prefix("formulize_framework_links") . " WHERE fl_frame_id=" . intval($frid) . " AND fl_form2_id=" . intval($fid) . " AND fl_form1_id=" . intval($target_sub_to_use));
	// element_to_write is used below in writing results of "add x entries" clicks, plus it is used for defaultblanks on first drawing blank entries, so we need to get this outside of the saving routine
	if(count($elementq) > 0) {
		$element_to_write = $elementq[0]['fl_key1'];
		$value_source = $elementq[0]['fl_key2'];
		$value_source_form = $elementq[0]['fl_form2_id'];
	} else {
		$elementq = q("SELECT fl_key2, fl_key1, fl_common_value, fl_form1_id FROM " . $xoopsDB->prefix("formulize_framework_links") . " WHERE fl_frame_id=" . intval($frid) . " AND fl_form1_id=" . intval($fid) . " AND fl_form2_id=" . intval($target_sub_to_use));
		$element_to_write = $elementq[0]['fl_key2'];
		$value_source = $elementq[0]['fl_key1'];
		$value_source_form = $elementq[0]['fl_form1_id'];		
	}


	// check for adding of a sub entry, and handle accordingly -- added September 4 2006
	
	if($_POST['target_sub'] AND $_POST['target_sub'] == $sfid AND $_POST['target_sub_instance'] == $subformElementId) { // important we only do this on the run through for that particular sub form (hence target_sub == sfid), and also only for the specific instance of this subform on the page too, since not all entries may apply to all subform instances any longer with conditions in effect now
		// need to handle things differently depending on whether it's a common value or a linked selectbox type of link
		// uid links need to result in a "new" value in the displayElement boxes -- odd things will happen if people start adding linked values to entries that aren't theirs!
		if($element_to_write != 0) {
			if($elementq[0]['fl_common_value']) {
				// grab the value from the parent element -- assume that it is a textbox of some kind!
				if (isset($_POST['de_'.$value_source_form.'_'.$entry.'_'.$value_source])) {
					$value_to_write = $_POST['de_'.$value_source_form.'_'.$entry.'_'.$value_source];
				} else {
					// get this entry and see what the source value is
					$data_handler = new formulizeDataHandler($value_source_form);
					$value_to_write = $data_handler->getElementValueInEntry($entry, $value_source);
				}
			} else {
				$value_to_write = ",".$entry.","; 
			}
			$sub_entry_new = "";
		
			for($i=0;$i<$_POST['numsubents'];$i++) { // actually goahead and create the requested number of new sub entries...start with the key field, and then do all textboxes with defaults too...
				//$subEntWritten = writeElementValue($_POST['target_sub'], $element_to_write, "new", $value_to_write, "", "", true); // Last param is override that allows direct writing to linked selectboxes if we have prepped the value first!
        if($overrideOwnerOfNewEntries) {
          $creation_user_touse = $mainFormOwner;
        } else {
          $creation_user_touse = "";
        }
        $subEntWritten = writeElementValue($_POST['target_sub'], $element_to_write, "new", $value_to_write, $creation_user_touse, "", true); // Last param is override that allows direct writing to linked selectboxes if we have prepped the value first!
	$element_handler = icms_getModuleHandler('elements', 'formulize');
				if(!isset($elementsForDefaults)) {
					$criteria = new CriteriaCompo();
					$criteria->add(new Criteria('ele_type', 'text'), 'OR');
					$criteria->add(new Criteria('ele_type', 'textarea'), 'OR');
					$elementsForDefaults = $element_handler->getObjects2($criteria,$_POST['target_sub']); // get all the text or textarea elements in the form 
				}
				foreach($elementsForDefaults as $thisDefaultEle) {
					// need to write in any default values for any text boxes or text areas that are in the subform.  Perhaps other elements could be included too, but that would take too much work right now. (March 9 2009)
					$defaultTextToWrite = "";
					$ele_value_for_default = $thisDefaultEle->getVar('ele_value');
					switch($thisDefaultEle->getVar('ele_type')) {
						case "text":
							$defaultTextToWrite = getTextboxDefault($ele_value_for_default[2], $_POST['target_sub'], $subEntWritten); // position 2 is default value for text boxes
							break;
						case "textarea":
							$defaultTextToWrite = getTextboxDefault($ele_value_for_default[0], $_POST['target_sub'], $subEntWritten); // position 0 is default value for text boxes
							break;
					}
					if($defaultTextToWrite) {
						writeElementValue($_POST['target_sub'], $thisDefaultEle->getVar('ele_id'), $subEntWritten, $defaultTextToWrite);
					}
				}
				$sub_entry_written[] = $subEntWritten;
			}
		} else {
			$sub_entry_new = "new"; // this happens in uid-link situations?
			$sub_entry_written = "";
		}
	
		// need to also enforce any equals conditions that are on the subform element, if any, and assign those values to the entries that were just added
		if(is_array($subformConditions)) {
			foreach($subformConditions[1] as $i=>$thisOp) {
				if($thisOp == "=" AND $subformConditions[3][$i] != "oom") {
					$conditionElementObject = $element_handler->get($subformConditions[0][$i]);
					$filterValues[$subformConditions[0][$i]] = prepareLiteralTextForDB($conditionElementObject, $subformConditions[2][$i]); 
				}
			}
			foreach($sub_entry_written as $thisSubEntry) {
				formulize_writeEntry($filterValues,$thisSubEntry);	
			}
		}
	
	}
	
	
	

	

	// need to do a number of checks here, including looking for single status on subform, and not drawing in add another if there is an entry for a single

	$sub_single_result = getSingle($sfid, $uid, $groups, $member_handler, $gperm_handler, $mid);
	$sub_single = $sub_single_result['flag'];
	if($sub_single) {
		unset($sub_entries);
		$sub_entries[$sfid][0] = $sub_single_result['entry'];
	}

	if(!is_array($sub_entries[$sfid])) { $sub_entries[$sfid] = array(); }

	if($sub_entry_new AND !$sub_single AND $_POST['target_sub'] == $sfid) {
		for($i=0;$i<$_POST['numsubents'];$i++) {
			array_unshift($sub_entries[$sfid], $sub_entry_new);
		}
	}

	if(is_array($sub_entry_written) AND !$sub_single AND $_POST['target_sub'] == $sfid) {
		foreach($sub_entry_written as $sew) {
			array_unshift($sub_entries[$sfid], $sew);
		}
	}

	if(!$customCaption) {
		// get the title of this subform
		// help text removed for F4.0 RC2, this is an experiment
		$subtitle = q("SELECT desc_form FROM " . $xoopsDB->prefix("formulize_id") . " WHERE id_form = $sfid");
		$col_one = "<p><b>" . trans($subtitle[0]['desc_form']) . "</b></p>"; // <p style=\"font-weight: normal;\">" . _formulize_ADD_HELP;
	} else {
		$col_one = "<p><b>" . trans($customCaption) . "</b></p>"; // <p style=\"font-weight: normal;\">" . _formulize_ADD_HELP;
	}

	/*if(intval($sub_entries[$sfid][0]) != 0 OR $sub_entry_new OR is_array($sub_entry_written)) {
		if(!$nosubforms) { $col_one .= "<br>" . _formulize_ADD_HELP2; }
		$col_one .= "<br>" . _formulize_ADD_HELP3;
	} */

	// list the entries, including links to them and delete checkboxes
	
	// get the headerlist for the subform and convert it into handles
	// note big assumption/restriction that we are only using the first header found (ie: only specify one header for a sub form!)
	// setup the array of elements to draw
	if(is_array($customElements)) {
		$headingDescriptions = array();
		$headerq = q("SELECT ele_caption, ele_colhead, ele_desc, ele_id FROM " . $xoopsDB->prefix("formulize") . " WHERE ele_id IN (" . implode(", ", $customElements). ") ORDER BY ele_order");
		foreach($headerq as $thisHeaderResult) {
			$elementsToDraw[] = $thisHeaderResult['ele_id'];
			$headingDescriptions[]  = $thisHeaderResult['ele_desc'] ? $thisHeaderResult['ele_desc'] : "";
			if($captionsForHeadings) {
				$headersToDraw[] = $thisHeaderResult['ele_caption'];
			} else {
				$headersToDraw[] = $thisHeaderResult['ele_colhead'] ? $thisHeaderResult['ele_colhead'] : $thisHeaderResult['ele_caption'];
			}
		}		
	} else {
		$subHeaderList = getHeaderList($sfid);
		$subHeaderList1 = getHeaderList($sfid, true);
		$headersToDraw[] = trans($subHeaderList[0]);
		$headersToDraw[] = trans($subHeaderList[1]);
		$headersToDraw[] = trans($subHeaderList[2]);
		$elementsToDraw[] = $subHeaderList1[0];
		$elementsToDraw[] = $subHeaderList1[1];
		$elementsToDraw[] = $subHeaderList1[2];
	}
	

	$need_delete = 0;
	$drawnHeadersOnce = false;
	
	if($rowsOrForms=="row" OR $rowsOrForms =='') {
		$col_two = "<table style=\"width: 10%\">";	
	} else {
		$col_two = "<div id=\"subform-$subformElementId\" class=\"subform-accordion-container\" subelementid=\"$subformElementId\" style=\"display: none;\">";
		$col_two .= "<input type='hidden' name='subform_entry_".$subformElementId."_active' id='subform_entry_".$subformElementId."_active' value='' />";
		include_once XOOPS_ROOT_PATH ."/modules/formulize/class/data.php";
		$data_handler = new formulizeDataHandler($sfid);
		
	}

	$deFrid = $frid ? $frid : ""; // need to set this up so we can pass it as part of the displayElement function, necessary to establish the framework in case this is a framework and no subform element is being used, just the default draw-in-the-one-to-many behaviour
	
	// if there's been no form submission, and there's no sub_entries, and there are default blanks to show, then do everything differently -- sept 8 2007
	
	if(!$_POST['form_submitted'] AND count($sub_entries[$sfid]) == 0 AND $defaultblanks > 0 AND ($rowsOrForms == "row"  OR $rowsOrForms =='')) {
	
		for($i=0;$i<$defaultblanks;$i++) {
	
				// nearly same header drawing code as in the 'else' for drawing regular entries
				if(!$drawnHeadersOnce) {
					$col_two .= "<tr><td>\n";
					$col_two .= "<input type=\"hidden\" name=\"formulize_subformValueSource_$sfid\" value=\"$value_source\">\n";
					$col_two .= "<input type=\"hidden\" name=\"formulize_subformValueSourceForm_$sfid\" value=\"$value_source_form\">\n";
					$col_two .= "<input type=\"hidden\" name=\"formulize_subformValueSourceEntry_$sfid\" value=\"$entry\">\n";
					$col_two .= "<input type=\"hidden\" name=\"formulize_subformElementToWrite_$sfid\" value=\"$element_to_write\">\n";
					$col_two .= "<input type=\"hidden\" name=\"formulize_subformSourceType_$sfid\" value=\"".$elementq[0]['fl_common_value']."\">\n";
					$col_two .= "<input type=\"hidden\" name=\"formulize_subformId_$sfid\" value=\"$sfid\">\n"; // this is probably redundant now that we're tracking sfid in the names of the other elements
					$col_two .= "</td>\n";
					foreach($headersToDraw as $x=>$thishead) {
						if($thishead) {
							$headerHelpLinkPart1 = $headingDescriptions[$i] ? "<a href=\"#\" onclick=\"return false;\" alt=\"".$headingDescriptions[$x]."\" title=\"".$headingDescriptions[$x]."\">" : "";
							$headerHelpLinkPart2 = $headerHelpLinkPart1 ? "</a>" : "";
							$col_two .= "<td style=\"width: 10%; text-align: center;\"><p>$headerHelpLinkPart1<b>$thishead</b>$headerHelpLinkPart2</p></td>\n";
						}
					}
					$col_two .= "</tr>\n";
					$drawnHeadersOnce = true;
				}
				$col_two .= "<tr>\n<td>";
				$col_two .= "</td>\n";
				include_once XOOPS_ROOT_PATH . "/modules/formulize/include/elementdisplay.php";
				foreach($elementsToDraw as $thisele) {
					if($thisele) { 
						ob_start();
						// critical that we *don't* ask for displayElement to return the element object, since this way the validation logic is passed back through the global space also (ugh).  Otherwise, no validation logic possible for subforms.
						$renderResult = displayElement($deFrid, $thisele, "subformCreateEntry_".$i."_".$subformElementId); 
						$col_two_temp = ob_get_contents();
						ob_end_clean();
						if($col_two_temp OR $renderResult == "rendered") { // only draw in a cell if there actually is an element rendered (some elements might be rendered as nothing (such as derived values)
							$col_two .= "<td>$col_two_temp</td>\n";
						} else {
							$col_two .= "<td>******</td>";
						}
					}
				}
				$col_two .= "</tr>\n";
				
		}
	
	} elseif(count($sub_entries[$sfid]) > 0) {
		
		// need to figure out the proper order for the sub entries based on the properties set for this form
		// for now, hard code to the word number field to suit the map site only
		// if it's the word subform, then sort the entries differently
		/*if($sfid == 281) {
			$sortClause = " fas_281, block_281, word_number ";
		} 
		elseif ($sfid == 283) {
			$sortClause = " fas_283 ";
		}
		else {*/
			$sortClause = " entry_id ";
		//}
		
		$sformObject = $form_handler->get($sfid);
		$subEntriesOrderSQL = "SELECT entry_id FROM ".$xoopsDB->prefix("formulize_".$sformObject->getVar('form_handle'))." WHERE entry_id IN (".implode(",", $sub_entries[$sfid]).") ORDER BY $sortClause";
		if($subEntriesOrderRes = $xoopsDB->query($subEntriesOrderSQL)) {
			$sub_entries[$sfid] = array();
			while($subEntriesOrderArray = $xoopsDB->fetchArray($subEntriesOrderRes)) {
				$sub_entries[$sfid][] = $subEntriesOrderArray['entry_id'];
			}
		}

		foreach($sub_entries[$sfid] as $sub_ent) {
			if($sub_ent != "") {
				
				if($rowsOrForms=='row' OR $rowsOrForms =='') {
					
					if(!$drawnHeadersOnce) {
						$col_two .= "<tr><td></td>\n";
						foreach($headersToDraw as $i=>$thishead) {
							if($thishead) {
								$headerHelpLinkPart1 = $headingDescriptions[$i] ? "<a href=\"#\" onclick=\"return false;\" alt=\"".$headingDescriptions[$i]."\" title=\"".$headingDescriptions[$i]."\">" : "";
								$headerHelpLinkPart2 = $headerHelpLinkPart1 ? "</a>" : "";
								$col_two .= "<td style=\"width: 10%; text-align: center;\"><p>$headerHelpLinkPart1<b>$thishead</b>$headerHelpLinkPart2</p></td>\n";
							}
						}
						$col_two .= "</tr>\n";
						$drawnHeadersOnce = true;
					}
					$col_two .= "<tr>\n<td style=\"width: 10%;\">";
					// check to see if we draw a delete box or not
					if($sub_ent !== "new") {
						$deleteSelf = $gperm_handler->checkRight("delete_own_entry", $sfid, $groups, $mid);
						$deleteOther = $gperm_handler->checkRight("delete_other_entries", $sfid, $groups, $mid);
						$sub_owner = getEntryOwner($sub_ent, $sfid);
						//print "sub_owner: $sub_owner<br>uid: $uid<br>deleteself: $deleteSelf<br>";
						if(($sub_owner == $uid AND $deleteSelf) OR ($sub_owner != $uid AND $deleteOther)) {
							$need_delete = 1;
							$col_two .= "<input type=checkbox name=delbox$sub_ent value=$sub_ent></input>";
						} 
					}
					$col_two .= "</td>\n";
					include_once XOOPS_ROOT_PATH . "/modules/formulize/include/elementdisplay.php";
					foreach($elementsToDraw as $thisele) {
						if($thisele) { 
							ob_start();
							// critical that we *don't* ask for displayElement to return the element object, since this way the validation logic is passed back through the global space also (ugh).  Otherwise, no validation logic possible for subforms.
							$renderResult = displayElement($deFrid, $thisele, $sub_ent); 
							$col_two_temp = ob_get_contents();
							ob_end_clean();
							if($col_two_temp OR $renderResult == "rendered") { // only draw in a cell if there actually is an element rendered (some elements might be rendered as nothing (such as derived values)
								$col_two .= "<td>$col_two_temp</td>\n";
							} else {
								$col_two .= "<td>******</td>";
							}
						}
					}
					if(!$nosubforms AND $showViewButtons) { $col_two .= "<td><input type=button name=view".$sub_ent." value='"._formulize_SUBFORM_VIEW."' onclick=\"javascript:goSub('$sub_ent', '$sfid');return false;\"></input></td>\n"; }
					$col_two .= "</tr>\n";

				} else { // display the full form
					$headerValues = array();
					foreach($elementsToDraw as $thisele) {
						$headerValues[] = htmlspecialchars(strip_tags($data_handler->getElementValueInEntry($sub_ent, $thisele)));
					}					
					$headerToWrite = implode(" &mdash; ", $headerValues);
					if(str_replace(" &mdash; ", "", $headerToWrite) == "") {
						$headerToWrite = _AM_ELE_SUBFORM_NEWENTRY_LABEL;
					}
					
					// check to see if we draw a delete box or not
					$deleteBox = "";
					if($sub_ent !== "new") {
						$deleteSelf = $gperm_handler->checkRight("delete_own_entry", $sfid, $groups, $mid);
						$deleteOther = $gperm_handler->checkRight("delete_other_entries", $sfid, $groups, $mid);
						$sub_owner = getEntryOwner($sub_ent, $sfid);
						//print "sub_owner: $sub_owner<br>uid: $uid<br>deleteself: $deleteSelf<br>";
						if(($sub_owner == $uid AND $deleteSelf) OR ($sub_owner != $uid AND $deleteOther)) {
							$need_delete = 1;
							$deleteBox = "<input type=checkbox name=delbox$sub_ent value=$sub_ent></input>&nbsp;&nbsp;";
						} 
					}
					
					
					$col_two .= "<div class=\"subform-deletebox\">$deleteBox</div><div class=\"subform-entry-container\" id=\"subform-$sub_ent\">
<p class=\"subform-header\"><a href=\"#\"><span class=\"accordion-name\">".$headerToWrite."</span></a></p>
	<div class=\"accordion-content content\">";
					ob_start();
					$renderResult = displayForm($sfid, $sub_ent, "", "",  "", "", "formElementsOnly"); // SHOULD CHANGE THIS TO USE THE DEFAULT SCREEN FOR THE FORM!!!!!!????
					$col_two_temp = ob_get_contents();
					ob_end_clean();
					$col_two .= $col_two_temp . "</div>\n</div>\n";
				}
			}
		}
		
	} // end of the if condition controlling placement of blank defaults

	

	if($rowsOrForms=='row' OR $rowsOrForms =='') {
		// complete the table if we're drawing rows
		$col_two .= "</table>";	
	} else {
		$col_two .= "</div>\n";
		static $jqueryUILoaded = false;
		if(!$jqueryUILoaded) {
			$col_two .= "<script type=\"text/javascript\" src=\"".XOOPS_URL."/modules/formulize/jquery/jquery-ui-1.8.2.custom.min.js\"></script>\n";
			$col_two .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"".XOOPS_URL."/modules/formulize/jquery/css/start/jquery-ui-1.8.2.custom.css\">\n";
			$jqueryUILoaded = true;
		}
		$col_two .= "\n
<script type=\"text/javascript\">

	jQuery(window).load(function() {
		$(\"#subform-$subformElementId\").accordion({
			autoHeight: false, // no fixed height for sections
			collapsible: true, // sections can be collapsed
			active: ";
			if(is_numeric($_POST['subform_entry_'.$subformElementId.'_active'])) {
				$col_two .= $_POST['subform_entry_'.$subformElementId.'_active'];
			} else {
				$col_two .= 'false';
			}
			$col_two .= ",
			header: \"> div > p.subform-header\"
		});
		$(\"#subform-$subformElementId\").fadeIn();
	});

</script>

<style>

p.subform-header {
	font-size: 10pt !important;
}
.ui-accordion-content {
	background: #80C3DD;
	border-color: white;
	font-size: 10pt; !important;
}
.ui-accordion-header {
	border-color: white;
}
.subform-deletebox {
	float: left;
}\n";

if($need_delete) {
$col_two .= "
.subform-entry-container {
	margin-left: 25px;
}
\n";
}

$col_two .= "
</style>
\n";

	} // end of if we're closing the subform inferface where entries are supposed to be collapsable forms


	if($addSubEntry = $gperm_handler->checkRight("add_own_entry", $sfid, $groups, $mid) AND !$hideaddentries) {
		if(count($sub_entries[$sfid]) == 1 AND $sub_entries[$sfid][0] === "" AND $sub_single) {
			$col_two .= "<p><input type=button name=addsub value='". _formulize_ADD_ONE . "' onclick=\"javascript:add_sub('$sfid', 1, $subformElementId);\"></p>";
		} elseif(!$sub_single) {
			$col_two .=  "<p><input type=button name=addsub value='". _formulize_ADD . "' onclick=\"javascript:add_sub('$sfid', window.document.formulize.addsubentries$sfid$subformElementId.value, $subformElementId);\"><input type=text name=addsubentries$sfid$subformElementId id=addsubentries$sfid$subformElementId value=1 size=2 maxlength=2>" . _formulize_ADD_ENTRIES . "</p>";
		}
	}
	if(((count($sub_entries[$sfid])>0 AND $sub_entries[$sfid][0] != "") OR $sub_entry_new OR is_array($sub_entry_written)) AND $need_delete) {
		// $col_one .= "<br>" . _formulize_ADD_HELP4 . "</p><p><input type=button name=deletesubs value='" . _formulize_DELETE_CHECKED . "' onclick=\"javascript:sub_del('$sfid');\">";
		// re-org of the "columns" means we want this button in the second button in the bottom row now...
		$col_two = str_replace("maxlength=2>" . _formulize_ADD_ENTRIES . "</p>", "maxlength=2>" . _formulize_ADD_ENTRIES . "&nbsp;&nbsp;&nbsp;<input type=button name=deletesubs value='" . _formulize_DELETE_CHECKED . "' onclick=\"javascript:sub_del('$sfid');\"></p>", $col_two);
		static $deletesubsflagIncluded = false;
		if(!$deletesubsflagIncluded) {
			$col_one .= "\n<input type=hidden name=deletesubsflag value=''>\n";
			$deletesubsflagIncluded = true;
		}
	}
	$col_one .= "</p>";
	$to_return['c1'] = $col_one;
	$to_return['c2'] = $col_two;
	//return $to_return; // now returning a single set of HTML, which should be a configurable option
	return array('single'=>$col_one . $col_two);

}


// add the proxy list to a form
function addOwnershipList($form, $groups, $member_handler, $gperm_handler, $fid, $mid, $entry_id="") {

	global $xoopsDB;
			
			$add_groups = $gperm_handler->getGroupIds("add_own_entry", $fid, $mid);
			// May 5, 2006 -- limit to the user's own groups unless the user has global scope
			if(!$globalscope = $gperm_handler->checkRight("view_globalscope", $fid, $groups, $mid)) {
				$add_groups = array_intersect($add_groups, $groups);
			}
			$all_add_users = array();
			foreach($add_groups as $grp) {
				$add_users = $member_handler->getUsersByGroup($grp);
				$all_add_users = array_merge((array)$add_users, $all_add_users);
				unset($add_users);
			}
		
			$unique_users = array_unique($all_add_users);

			foreach($unique_users as $uid) {
				$uqueryforrealnames = "SELECT name, uname FROM " . $xoopsDB->prefix("users") . " WHERE uid=$uid";
				$uresqforrealnames = $xoopsDB->query($uqueryforrealnames);
				$urowqforrealnames = $xoopsDB->fetchRow($uresqforrealnames);
				$punames[] = $urowqforrealnames[0] ? $urowqforrealnames[0] : $urowqforrealnames[1]; // use the uname if there is no full name
				//print "username: $urowqforrealnames[0]<br>"; // debug code
			}

			// alphabetize the proxy list added 11/2/04
			array_multisort($punames, $unique_users);
	
			
			if($entry_id) {
				include_once XOOPS_ROOT_PATH . "/modules/formulize/class/data.php";
				$data_handler = new formulizeDataHandler($fid);
				$entryMeta = $data_handler->getEntryMeta($entry_id);
				$entryOwner = $entryMeta[2];
				$entryOwnerName = $punames[array_search($entryOwner,$unique_users)]; // need to look in one array to find the key to lookup in the other array...a legacy from when corresponding arrays were a common data structure in Formulize...multidimensional arrays were not well understood in the beginning
				$proxylist = new XoopsFormSelect(_AM_SELECT_UPDATE_OWNER, 'updateowner_'.$fid.'_'.$entry_id, 0, 1);
				$proxylist->addOption('nochange', _AM_SELECT_UPDATE_NOCHANGE.$entryOwnerName);
			} else {
				$proxylist = new XoopsFormSelect(_AM_SELECT_PROXY, 'proxyuser', 0, 5, TRUE); // made multi May 3 05
				$proxylist->addOption('noproxy', _formulize_PICKAPROXY);
			}
			
			for($i=0;$i<count($unique_users);$i++)
			{
				$proxylist->addOption($unique_users[$i], $punames[$i]);
			}

			if(!$entry_id) {
				$proxylist->setValue('noproxy');
			} else {
				$proxylist->setValue('nochange');
						
			}
			$form->addElement($proxylist);
			return $form;
}


//this function takes a formid and compiles all the elements for that form
//elements_allowed is NOT based off the display values.  It is based off of the elements that are specifically designated for the current displayForm function (used to display parts of forms at once)
// $title is the title of a grid that is being displayed
function compileElements($fid, $form, $formulize_mgr, $prevEntry, $entry, $go_back, $parentLinks, $owner_groups, $groups, $overrideValue="", $elements_allowed="", $profileForm="", $frid="", $mid, $sub_entries, $sub_fids, $member_handler, $gperm_handler, $title, $screen=null, $printViewPages="", $printViewPageTitles="") {
	
	include_once XOOPS_ROOT_PATH.'/modules/formulize/include/elementdisplay.php';
	
	$entryForDEElements = is_numeric($entry) ? $entry : "new"; // if there is no entry, ie: a new entry, then $entry is "" so when writing the entry value into decue_ and other elements that go out to the HTML form, we need to use the keyword "new"
	
	global $xoopsDB, $xoopsUser;

	// find hidden elements first...
	// elementdisplay.php also has handling for this...they should be amalgamated/rationalized at some point
	$hiddenElements = array();
	//if(!$entry) {
		$notAllowedCriteria = new CriteriaCompo();
		$notAllowedCriteria->add(new Criteria('ele_forcehidden', 1));
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('ele_display', 0), 'OR');
		$criteria2 = new CriteriaCompo();
		foreach($groups as $thisgroup) {
			$criteria2->add(new Criteria('ele_display', '%,'.$thisgroup.',%', 'NOT LIKE'), 'AND');
		}
		$criteria->add($criteria2, 'OR');
		$notAllowedCriteria->add($criteria, 'AND');
		$notAllowedCriteria->setSort('ele_order');
		$notAllowedCriteria->setOrder('ASC');

		$notAllowedElements =& $formulize_mgr->getObjects2($notAllowedCriteria,$fid);
	
		foreach($notAllowedElements as $ni) {
			// display these elements as hidden elements with the default value
			switch($ni->getVar('ele_type')) {

				case "radio":
					if(!$entry) {
						$indexer = 1;
							foreach($ni->getVar('ele_value') as $k=>$v) {
								if($v == 1) {
								$hiddenElements[$ni->getVar('ele_id')] = new xoopsFormHidden('de_'.$fid.'_'.$entryForDEElements.'_'.$ni->getVar('ele_id'), $indexer);
							}
							$indexer++;
						}
					}
					break;
				case "checkbox":
					if(!$entry) {
						$indexer = 1;
							foreach($ni->getVar('ele_value') as $k=>$v) {
								if($v == 1) {
								$hiddenElements[$ni->getVar('ele_id')] = new xoopsFormHidden('de_'.$fid.'_'.$entryForDEElements.'_'.$ni->getVar('ele_id')."[]", $indexer);
							}
							$indexer++;
						}
					} else {
						$data_handler = new formulizeDataHandler($ni->getVar('id_form'));
						$checkBoxOptions = $data_handler->getElementValueInEntry($entry, $ni);
						$indexer = 1;
						foreach($ni->getVar('ele_value') as $k=>$v) {
							if(strstr($checkBoxOptions, $k)) {
								$hiddenElements[$ni->getVar('ele_id')] = new xoopsFormHidden('de_'.$fid.'_'.$entryForDEElements.'_'.$ni->getVar('ele_id')."[]", $indexer);
							} 
							$indexer++;
						}
					}
					break;
				case "yn":
					if(!$entry) {
						$ele_value = $ni->getVar('ele_value');
						$yesNoValue = $ele_value['_YES'] == 1 ? 1 : 2; // check to see if Yes is the value, and if so, set 1, otherwise, set 2.  2 is the value used when No is the selected option in YN radio buttons
						$hiddenElements[$ni->getVar('ele_id')] = new xoopsFormHidden('de_'.$fid.'_'.$entryForDEElements.'_'.$ni->getVar('ele_id'), $yesNoValue);
					}
	        break;				
				case "text":
					if(!$entry) {
						global $myts;
						if(!$myts){ $myts =& icms_core_Textsanitizer::getInstance(); }
						$ele_value = $ni->getVar('ele_value');
						$hiddenElements[$ni->getVar('ele_id')] = new xoopsFormHidden('de_'.$fid.'_'.$entryForDEElements.'_'.$ni->getVar('ele_id'), icms_core_DataFilter::htmlSpecialChars(getTextboxDefault($ele_value[2], $ni->getVar('id_form'), $entry)));
					} else {
						include_once XOOPS_ROOT_PATH . "/modules/class/data.php";
						$data_handler = new formulizeDataHandler($ni->getVar('id_form'));
						$hiddenElements[$ni->getVar('ele_id')] = new xoopsFormHidden('de_'.$fid.'_'.$entryForDEElements.'_'.$ni->getVar('ele_id'), $data_handler->getElementValueInEntry($entry, $ni));
					}
					break;
				case "textarea":
					if(!$entry) {
						global $myts;
						if(!$myts){ $myts =& icms_core_Textsanitizer::getInstance(); }
						$ele_value = $ni->getVar('ele_value');
						$hiddenElements[$ni->getVar('ele_id')] = new xoopsFormHidden('de_'.$fid.'_'.$entryForDEElements.'_'.$ni->getVar('ele_id'), icms_core_DataFilter::htmlSpecialChars(getTextboxDefault($ele_value[0], $ni->getVar('id_form'), $entry)));
					} else {
						include_once XOOPS_ROOT_PATH . "/modules/class/data.php";
						$data_handler = new formulizeDataHandler($ni->getVar('id_form'));
						$hiddenElements[$ni->getVar('ele_id')] = new xoopsFormHidden('de_'.$fid.'_'.$entryForDEElements.'_'.$ni->getVar('ele_id'), $data_handler->getElementValueInEntry($entry, $ni));
					}
					break;
			}
		}
	//} // if(!$entry) is now broken out locally, since for plain textboxes, we are allowing special hidden values when there is an entry.  This feature will likely evolve.
  unset($criteria);
	unset($ele_value);

	// set criteria for matching on display
	// set the basics that everything has to match
	$criteriaBase = new CriteriaCompo();
	$criteriaBase->add(new Criteria('ele_display', 1), 'OR');
	foreach($groups as $thisgroup) {
		$criteriaBase->add(new Criteria('ele_display', '%,'.$thisgroup.',%', 'LIKE'), 'OR');
	}
	if(is_array($elements_allowed)) {
		// if we're limiting the elements, then add a criteria for that (multiple criteria are joined by AND unless you specify OR manually when adding them (as in the base above))
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('ele_id', "(".implode(",",$elements_allowed).")", "IN"));
		$criteria->add($criteriaBase);
	} else {
		$criteria = $criteriaBase; // otherwise, just use the base
	}
	$criteria->setSort('ele_order');
	$criteria->setOrder('ASC');
	$elements =& $formulize_mgr->getObjects2($criteria,$fid,true); // true makes the keys of the returned array be the element ids
	$count = 0;
	$gridCounter = array();
	$inGrid = 0;
	
	formulize_benchmark("Ready to loop elements.");

	// set the array to be used as the structure of the loop, either the passed in elements in order, or the elements as gathered from the DB	
	if(!is_array($elements_allowed)) {
		$element_order_array = $elements;
	} else {
		$element_order_array = $elements_allowed;
	}
	
	// if this is a printview page,  
	
	
	foreach($element_order_array as $thisElement) {
		if(is_numeric($thisElement)) { // if we're doing the order based on passed in element ids...
			if(isset($elements[$thisElement])) {
				$i = $elements[$thisElement]; // set the element object for this iteration of the loop
			} else {
				continue; // do not try to render elements that don't exist in the form!! (they might have been deleted from a multipage definition, or who knows what)
			}
			$this_ele_id = $thisElement; // set the element ID number
		} else { // else...we're just looping through the elements directly from the DB
			$i = $thisElement; // set the element object
			$this_ele_id = $i->getVar('ele_id'); // get the element ID number
		}
	
	/*
	 // old code that is superseded by the new element_order_array stuff above
	 foreach( $elements as $i ){

		$this_ele_id = $i->getVar('ele_id');

		if(is_array($elements_allowed)) {
			if(!in_array($this_ele_id, $elements_allowed)) {
				continue;
			}
		}
  */
	
		// check if we're at the start of a page, when doing a printable view of all pages (only situation when printViewPageTitles and printViewPages will be present), and if we are, then put in a break for the page titles
		if($printViewPages) {
			if(!$currentPrintViewPage) {
				$currentPrintViewPage = 1;
			}
			while(!in_array($this_ele_id, $printViewPages[$currentPrintViewPage]) AND $currentPrintViewPage <= count($printViewPages)) {
				$currentPrintViewPage++;
			}
			if($this_ele_id == $printViewPages[$currentPrintViewPage][0]) {
				$form->insertBreak("<div id=\"formulize-printpreview-pagetitle\">" . $printViewPageTitles[$currentPrintViewPage] . "</div>", "head");
			}
		}
	
		// check if this element is included in a grid, and if so, skip it
		// $inGrid will be a number indicating how many times we have to skip things
		if($inGrid OR isset($gridCounter[$this_ele_id])) {
			if(!$inGrid) {
				$inGrid = $gridCounter[$this_ele_id];
			}
			$inGrid--;
			continue;
		}

		$uid = is_object($xoopsUser) ? $xoopsUser->getVar('uid') : 0;
		$owner = getEntryOwner($entry, $fid);
		$ele_type = $i->getVar('ele_type');
		$ele_value = $i->getVar('ele_value');

		
		if($go_back['form']) { // if there's a parent form...
			// check here to see if we need to initialize the value of a linked selectbox when it is the key field for a subform
			// although this is setup as a loop through all found parentLinks, only the last one will be used, since ele_value[2] is overwritten each time.
			// assumption is there will only be one parent link for this form
			for($z=0;$z<count($parentLinks['source']);$z++) {					
				if($this_ele_id == $parentLinks['self'][$z]) { // this is the element
					// get the caption of the parent's field
					/*$pcq = q("SELECT ele_caption FROM " . $xoopsDB->prefix("formulize") . " WHERE id_form='" . $go_back['form'] . "' AND ele_id='" . $parentLinks['source'][$z] . "'");				
					$parentCap = str_replace ("'", "`", $pcq[0]['ele_caption']);
					$parentCap = str_replace ("&quot;", "`", $parentCap);
					$parentCap = str_replace ("&#039;", "`", $parentCap);
					$pvq = q("SELECT ele_id FROM " . $xoopsDB->prefix("formulize_form") . " WHERE id_form='" . $go_back['form'] . "' AND id_req='" . $go_back['entry'] . "' AND ele_caption='$parentCap'");
					$pid = $pvq[0]['ele_id'];

					// NOTE: assuming that there will only be one value in the match, ie: the link field is not a multiple select box!
					// format of value should be $formid#*=:*$formcaption#*=:*$ele_id
					$ele_value[2] = $go_back['form'] . "#*=:*" . $parentCap . "#*=:*" . $pid; */
					$ele_value[2] = ",".$go_back['entry'].","; // 3.0 datastructure...needs to be tested!!
				}
			}
		} elseif($overrideValue){ // used to force a default setting in a form element, other than the normal default
			if(!is_array($overrideValue)) { //convert a string to an array so that strings don't screw up logic below (which is designed for arrays)
				$temp = $overrideValue;
				unset($overrideValue);
				$overrideValue[0] = $temp;
			}
			// currently only operative for select boxes
			switch($ele_type) {
				case "select":
					foreach($overrideValue as $ov) {
						if(array_key_exists($ov, $ele_value[2])) {
							$ele_value[2][$ov] = 1;
						}	
					}
					break;
				case "date":
                	// debug
                	//var_dump($overrideValue);
					foreach($overrideValue as $ov) {
						//if(ereg ("([0-9]{4})-([0-9]{2})-([0-9]{2})", $ov, $regs)) {
						if(ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})", $ov, $regs)) {
							$ele_value[0] = $ov;
						}
					}
					break;
			}
		}

		if($ele_type != "subform" AND $ele_type != 'grid') { 
			// "" is framework, ie: not applicable
			// $i is element object
			// $entry is entry_id
			// false is "nosave" param...only used to force element to not be picked up by readelements.php after saving
			// $screen is the screen object
			// false means don't print it out to screen, return it here
			$deReturnValue = displayElement("", $i, $entry, false, $screen, $prevEntry, false, $profileForm, $groups);
			if(is_array($deReturnValue)) {
				$form_ele = $deReturnValue[0];
				$isDisabled = $deReturnValue[1];
			} else {
				$form_ele = $deReturnValue;
				$isDisabled = false;
			}
			if(($form_ele == "not_allowed" OR $form_ele == "hidden")) {
				if(isset($GLOBALS['formulize_renderedElementHasConditions']["de_".$fid."_".$entryForDEElements."_".$this_ele_id])) {
					// need to add a tr container for elements that are not allowed, since if it was a condition that caused them to not show up, they might appear later on asynchronously, and we'll need the row to attach them to
					if($ele_type == "ib" AND $form_ele == "not_allowed") {
						$rowHTML = "<tr style='display: none' id='formulize-de_".$fid."_".$entryForDEElements."_".$this_ele_id."'></tr>";
					} elseif($form_ele == "not_allowed") { 
						$rowHTML = "<tr style='display: none' id='formulize-de_".$fid."_".$entryForDEElements."_".$this_ele_id."' valign='top' align='" . _GLOBAL_LEFT . "'></tr>";
					}
					// need to also get the validation code for this element, wrap it in a check for the table row being visible, and assign that to the global array that contains all the validation javascript that we need to add to the form
					// following code follows the pattern set in elementdisplay.php for actually creating rendered element objects
					if($ele_type != "ib") {
						$conditionalValidationRenderer = new formulizeElementRenderer($i);
						if($prevEntry OR $profileForm === "new") {
							$data_handler = new formulizeDataHandler($i->getVar('id_form'));
							$ele_value = loadValue($prevEntry, $i, $ele_value, $data_handler->getEntryOwnerGroups($entry), $groups, $entry, $profileForm); // get the value of this element for this entry as stored in the DB -- and unset any defaults if we are looking at an existing entry
						}
						$conditionalElementForValidiationCode = $conditionalValidationRenderer->constructElement("de_".$fid."_".$entryForDEElements."_".$this_ele_id, $ele_value, $entry, $isDisabled, $screen);
						if($js = $conditionalElementForValidiationCode->renderValidationJS()) {
							$GLOBALS['formulize_renderedElementsValidationJS'][$GLOBALS['formulize_thisRendering']][$conditionalElementForValidiationCode->getName()] = "if(window.document.getElementById('formulize-".$conditionalElementForValidiationCode->getName()."').style.display != 'none') {\n".$js."\n}\n";
						}
						unset($conditionalElementForValidiationCode);
						unset($conditionalValidationRenderer);
					}
					$form->addElement($rowHTML);
				}
				continue;
			}
		}
		
		$req = !$isDisabled ? intval($i->getVar('ele_req')) : 0; 
		
		if($ele_type == "subform") {
			$thissfid = $ele_value[0];		
			if($passed = security_check($thissfid) AND in_array($thissfid, $sub_fids)) {
				$GLOBALS['sfidsDrawn'][] = $thissfid;
				$customCaption = $i->getVar('ele_caption');
				$customElements = $ele_value[1] ? explode(",", $ele_value[1]) : "";
				$subUICols = drawSubLinks($thissfid, $sub_entries, $uid, $groups, $member_handler, $frid, $gperm_handler, $mid, $fid, $entry, $customCaption, $customElements, intval($ele_value[2]), $ele_value[3], $ele_value[4], $ele_value[5], $owner, $ele_value[6], $ele_value[7], $this_ele_id, $ele_value[8]); // 2 is the number of default blanks, 3 is whether to show the view button or not, 4 is whether to use captions as headings or not, 5 is override owner of entry, $owner is mainform entry owner, 6 is hide the add button, 7 is the conditions settings for the subform element, 8 is the setting for showing just a row or the full form
				if(isset($subUICols['single'])) {
					$form->insertBreak($subUICols['single'], "even");
				} else {
					$subLinkUI = new XoopsFormLabel($subUICols['c1'], $subUICols['c2']);
					$form->addElement($subLinkUI);
				}
				unset($subLinkUI);
			}
		} elseif($ele_type == "grid") {

			// we are going to have to store some kind of flag/counter with the id number of the starting element in the table, and the number of times we need to ignore things
			// we need to then listen for this up above and skip those elements as they come up.  This is why grids must come before their elements in the form definition

			include_once XOOPS_ROOT_PATH . "/modules/formulize/include/griddisplay.php";
			list($grid_title, $grid_row_caps, $grid_col_caps, $grid_background, $grid_start, $grid_count) = compileGrid($ele_value, $title, $i);
			$headingAtSide = ($ele_value[5] AND $grid_title) ? true : false; // if there is a value for ele_value[5], then the heading should be at the side, otherwise, grid spans form width as it's own chunk of HTML
			$gridCounter[$grid_start] = $grid_count;
			$gridContents = displayGrid($fid, $entry, $grid_row_caps, $grid_col_caps, $grid_title, $grid_background, $grid_start, "", "", true, $screen, $headingAtSide);
			if($headingAtSide) { // grid contents is the two bits for the xoopsformlabel when heading is at side, otherwise, it's just the contents for the break
				$form->addElement(new XoopsFormLabel($gridContents[0], $gridContents[1]));
			} else {
				$form->insertBreak($gridContents, "head"); // head is the css class of the cell				
			}
		} elseif($ele_type == "ib") {// if it's a break, handle it differently...
			$form->insertBreakFormulize("<div style=\"font-weight: normal;\">" . trans(stripslashes($form_ele[0])) . "</div>", $form_ele[1], 'de_'.$fid.'_'.$entryForDEElements.'_'.$this_ele_id); // final param is used as id name in the table row where this element exists, so we can interact with it for showing and hiding
		} else {
			$form->addElement($form_ele, $req);
		}
		$count++;
		unset($hidden);
		unset($form_ele); // apparently necessary for compatibility with PHP 4.4.0 -- suggested by retspoox, sept 25, 2005
	}

	formulize_benchmark("Done looping elements.");

	foreach($hiddenElements as $element_id=>$thisHiddenElement) {
		$form->addElement(new xoopsFormHidden("decue_".$fid."_".$entryForDEElements."_".$element_id, 1));
		$form->addElement($thisHiddenElement);
		unset($thisHiddenElement); // some odd reference thing going on here...$thisHiddenElement is being added by reference or something like that, so that when $thisHiddenElement changes in the next run through, every previous element that was created by adding it is updated to point to the next element.  So if you unset at the end of the loop, it forces each element to be added as you would expect.
	}

	$form->addElement (new XoopsFormHidden ('counter', $count)); // not used by reading logic?
	if($entry) { 
		$form->addElement (new XoopsFormHidden ('entry'.$fid, $entry));
	}
	if($_POST['parent_form']) { // if we just came back from a parent form, then if they click save, we DO NOT want an override condition, even though we are now technically editing an entry that was previously saved when we went to the subform in the first place.  So the override logic looks for this hidden value as an exception.
		$form->addElement (new XoopsFormHidden ('back_from_sub', 1));
	}
	
	// add a hidden element to carry all the validation javascript that might be associated with elements rendered with elementdisplay.php...only relevant for elements rendered inside subforms or grids...the validation code comes straight from the element, doesn't have a check around it for the conditional table row id, like the custom form classes at the top of the file use, since those elements won't render as hidden and show/hide in the same way
	if(isset($GLOBALS['formulize_renderedElementsValidationJS'][$GLOBALS['formulize_thisRendering']])) {
		$formulizeHiddenValidation = new XoopsFormHidden('validation', '');
		foreach($GLOBALS['formulize_renderedElementsValidationJS'][$GLOBALS['formulize_thisRendering']] as $thisValidation) { // grab all the validation code we stored in the elementdisplay.php file and attach it to this element
			foreach(explode("\n", $thisValidation) as $thisValidationLine) {
				$formulizeHiddenValidation->customValidationCode[] = $thisValidationLine;
			}
		}
		$form->addElement($formulizeHiddenValidation, 1);
	}

	if(get_class($form) == "formulize_elementsOnlyForm") { // forms of this class are ones that we're rendering just the HTML for the elements, and we need to preserve any validation javascript to stick in the final, parent form when it's finished
		$validationJS = $form->renderValidationJS();
		if(trim($validationJS)!="") {
			$GLOBALS['formulize_elementsOnlyForm_validationCode'][] = $validationJS."\n\n";
		}
	} elseif(count($GLOBALS['formulize_elementsOnlyForm_validationCode']) > 0) {
		$elementsonlyvalidation = new XoopsFormHidden('elementsonlyforms', '');
		$elementsonlyvalidation->customValidationCode = $GLOBALS['formulize_elementsOnlyForm_validationCode'];
		$form->addElement($elementsonlyvalidation, 1);
	}
	
	return $form;

}

// $groups is deprecated and not used in this function any longer
// $owner_groups is used when dealing with a usernames or fullnames selectbox
function loadValue($prevEntry, $i, $ele_value, $owner_groups, $groups, $entry, $profileForm="") {
//global $xoopsUser;
//if($xoopsUser->getVar('uid') == 1) {
//print_r($prevEntry);

//}

	global $myts;
	/*
	 * Hack by F�lix <INBOX Solutions> for sedonde
	 * myts == NULL
	 */
	if(!$myts){
		$myts =& icms_core_Textsanitizer::getInstance();
	}
	/*
	 * Hack by F�lix <INBOX Solutions> for sedonde
	 * myts == NULL
	 */
			$type = $i->getVar('ele_type');
			// going direct from the DB since if multi-language is active, getVar will translate the caption
			//$caption = $i->getVar('ele_caption');
			$ele_id = $i->getVar('ele_id');

			// if we're handling a new profile form, check to see if the user has filled in the form already and use that value if necessary
			// This logic could be of general use in handling posted requests, except for it's inability to handle 'other' boxes.  An update may pay off in terms of speed of reloading the page.
			$value = "";
			if($profileForm === "new") {
				$dataFromUser = "";
				foreach($_POST as $k=>$v) {
					if( preg_match('/de_/', $k)){
						$n = explode("_", $k);
						if($n[3] == $ele_id) { // found the element in $_POST;
							$dataFromUser = prepDataForWrite($i, $v);
							break;
						}
					}
				}
				if($dataFromUser) {
					$value = $dataFromUser;
				}
			}

			if(!$value) {
	     			global $xoopsDB;
     				$ecq = q("SELECT ele_handle FROM " . $xoopsDB->prefix("formulize") . " WHERE ele_id = '$ele_id'");
     				$handle = $ecq[0]['ele_handle'];
						$key = "";
	     			$keysFound = array_keys($prevEntry['handles'], $handle);
						foreach($keysFound as $thisKeyFound) {
							if("xyz".$prevEntry['handles'][$thisKeyFound] == "xyz".$handle) { // do a comparison with a prefixed string to avoid problems comparing numbers to numbers plus text, ie: "1669" and "1669_copy" since the loose typing in PHP will not interpret those as intended
								$key = $thisKeyFound;
								break;
							}
						}
     				// if the caption was not found in the existing values for this entry, then return the ele_value, unless we're looking at an existing entry, and then we need to clear defaults first
     				if(!is_numeric($key) AND $key=="") { 
     					if($entry) {
     						switch($type) {
     							case "text":
     								$ele_value[2] = "";
     								break;
	     						case "textarea":
     								$ele_value[0] = "";
     								break;
     						}
     					} 
	     				return $ele_value; 
     				}
						if($key !== "") {
							$value = $prevEntry['values'][$key];
						}
			}

			/*print_r($ele_value);
			print "<br>After: "; //debug block
			*/
			switch ($type)
			{
				case "derived":
					$ele_value[5] = $value;	// there is not a number 5 position in ele_value for derived values...we add the value to print in this position so we don't mess up any other information that might need to be carried around
					break;
				case "text":
					$ele_value[2] = $value;				
					$ele_value[2] = eregi_replace("'", "&#039;", $ele_value[2]);				
					break;
				case "textarea":
				/*
				 * Hack by F�lix<INBOX International>
				 * Adding colorpicker form element
				 */
				case "colorpick":
				/*
				 * End of Hack by F�lix<INBOX International>
				 * Adding colorpicker form element
				 */
					$ele_value[0] = $value;								
					break;
				case "select":
				case "radio":
				case "checkbox":

					// NEED TO ADD IN INITIALIZATION OF LINKED SELECT BOXES FOR SUBFORMS

					// NOTE:  unique delimiter used to identify LINKED select boxes, so they can be handled differently.
					if(strstr($ele_value[2], "#*=:*")) // if we've got a linked select box, then do everything differently
					{
						$ele_value[2] .= "#*=:*".$value; // append the selected entry ids to the form and handle info in the element definition
					}
					else
					{

						// put the array into another array (clearing all default values)
						// then we modify our place holder array and then reassign
	
						if ($type != "select")
						{
							$temparray = $ele_value;
						}
						else
						{
							$temparray = $ele_value[2];
						}					
						$temparraykeys = array_keys($temparray);
	
						if($temparraykeys[0] === "{FULLNAMES}" OR $temparraykeys[0] === "{USERNAMES}") { // ADDED June 18 2005 to handle pulling in usernames for the user's group(s)
							$ele_value[2]['{SELECTEDNAMES}'] = explode("*=+*:", $value);
							if(count($ele_value[2]['{SELECTEDNAMES}']) > 1) { array_shift($ele_value[2]['{SELECTEDNAMES}']); }
							$ele_value[2]['{OWNERGROUPS}'] = $owner_groups;
							break;
						}
	
						// need to turn the prevEntry got from the DB into something the same as what is in the form specification so defaults show up right
						// important: this is safe because $value itself is not being sent to the browser!
						// we're comparing the output of these two lines against what is stored in the form specification, which does not have HTML escaped characters, and has extra slashes.  Assumption is that lack of HTML filtering is okay since only admins and trusted users have access to form creation.  Not good, but acceptable for now.
						$value = icms_core_DataFilter::undoHtmlSpecialChars($value);
						if(get_magic_quotes_gpc()) { $value = addslashes($value); } 
	
						$selvalarray = explode("*=+*:", $value);
						$numberOfSelectedValues = strstr($value, "*=+*:") ? count($selvalarray)-1 : 1; // if this is a multiple selection value, then count the array values, minus 1 since there will be one leading separator on the string.  Otherwise, it's a single value element so the number of selections is 1.
						
						$assignedSelectedValues = array();
						foreach($temparraykeys as $k)
						{
							if((string)$k === (string)html_entity_decode($value, ENT_QUOTES)) // if there's a straight match (not a multiple selection)
							{
								$temparray[$k] = 1;
								$assignedSelectedValues[$k] = true;
							}
							elseif( is_array($selvalarray) AND in_array((string)htmlspecialchars($k, ENT_QUOTES), $selvalarray, TRUE) ) // or if there's a match within a multiple selection array) -- TRUE is like ===, matches type and value
							{
								$temparray[$k] = 1;
								$assignedSelectedValues[$k] = true;
							}
							else // otherwise set to zero.
							{
								$temparray[$k] = 0;
							}
						}
						if((!empty($value) OR $value === 0 OR $value === "0") AND count($assignedSelectedValues) < $numberOfSelectedValues) { // if we have not assigned the selected value from the db to one of the options for this element, then lets add it to the array of options, and flag it as out of range.  This is to preserve out of range values in the db that are there from earlier times when the options were different, and also to preserve values that were imported without validation on purpose
							foreach($selvalarray as $selvalue) {
								if(!isset($assignedSelectedValues[$selvalue]) AND (!empty($selvalue) OR $selvalue === 0 OR $selvalue === "0")) {
									$temparray[_formulize_OUTOFRANGE_DATA.$selvalue] = 1;
								}
							}
						}							
						if ($type == "radio" AND $entry != "new" AND ($value === "" OR is_null($value)) AND array_search(1, $ele_value)) { // for radio buttons, if we're looking at an entry, and we've got no value to load, but there is a default value for the radio buttons, then use that default value (it's normally impossible to unset the default value of a radio button, so we want to ensure it is used when rendering the element in these conditions)
							$ele_value = $ele_value;
						} elseif ($type != "select")
						{
							$ele_value = $temparray;
						}
						else
						{
							$ele_value[2] = $temparray;
						}
					} // end of IF we have a linked select box
					break;
				case "yn":
					if($value == 1)
					{
						$ele_value = array("_YES"=>1, "_NO"=>0);
					}
					elseif($value == 2)
					{
						$ele_value = array("_YES"=>0, "_NO"=>1);
					}
					else
					{
						$ele_value = array("_YES"=>0, "_NO"=>0);
					}
					break;
				case "date":

					$ele_value[0] = $value;

					break;
				default:
					if(file_exists(XOOPS_ROOT_PATH."/modules/formulize/class/".$type."Element.php")) {
						$customTypeHandler = icms_getModuleHandler($type."Element", 'formulize');
						return $customTypeHandler->loadValue($value, $ele_value, $i);
					} 
			} // end switch

			/*print_r($ele_value);
			print "<br>"; //debug block
			*/

			return $ele_value;
}

// THIS FUNCTION TAKES THE ELE_VALUE SETTINGS FOR A GRID AND RETURNS ALL THE NECESSARY PARAMS READY FOR PASSING TO THE DISPLAYGRID FUNCTION
// ALSO WORKS OUT THE NUMBER OF ELEMENTS THAT CAN BE ENTERED INTO THIS GRID
function compileGrid($ele_value, $title, $element) {

	// 1 is heading
	// 2 is row captions
	// 3 is col captions
	// 4 is shading
	// 5 is first element

	switch($ele_value[0]) {
		case "caption":
			global $myts;
			if(!$myts){
				$myts =& icms_core_Textsanitizer::getInstance();
			}
			// call the text sanitizer, first try to convert HTML chars, and if there were no conversions, then do a textarea conversion to automatically make links clickable
			$ele_caption = trans($element->getVar('ele_caption'));
			$htmlCaption = icms_core_DataFilter::undoHtmlSpecialChars($ele_caption);
			if($htmlCaption == $ele_caption) {
				$ele_caption = $myts->displayTarea($ele_caption);
			} else {
				$ele_caption = $htmlCaption;
			}
			$toreturn[] = $ele_caption;
			break;
		case "form":
			$toreturn[] = $title;
			break;
		case "none":
			$toreturn[] = "";
			break;
	}

	$toreturn[] = explode(",", $ele_value[1]);
	$toreturn[] = explode(",", $ele_value[2]);

	$toreturn[] = $ele_value[3];

	$toreturn[] = $ele_value[4];

	// number of cells in this grid
	$toreturn[] = count($toreturn[1]) * count($toreturn[2]);

	return $toreturn;

}

// THIS FUNCTION FORMATS THE DATETIME INFO FOR DISPLAY CLEANLY AT THE TOP OF THE FORM
function formulize_formatDateTime($dt) {
	// assumption is that the server timezone has been set correctly!
	// needs to figure out daylight savings time correctly...ie: is the user's timezone one that has daylight savings, and if so, if they are currently in a different dst condition than they were when the entry was created, add or subtract an hour from the seconds offset, so that the time information is displayed correctly.
	global $xoopsConfig, $xoopsUser;
	$serverTimeZone = $xoopsConfig['server_TZ'];
	$userTimeZone = $xoopsUser ? $xoopsUser->getVar('timezone_offset') : $serverTimeZone;
	$tzDiff = $userTimeZone - $serverTimeZone;
	$tzDiffSeconds = $tzDiff*3600;
	
	if(substr($dt, -8) == "00:00:00") { // assume anything at midnight, to the second, is actually an historical entry which did not have the time recorded when it was made/saved
		return _formulize_TEMP_ON . " " . date("F jS, Y", strtotime($dt)+$tzDiffSeconds); // on October 23rd, 2008
	} else {
		return _formulize_TEMP_AT . " " . date("g:i a, F jS, Y", strtotime($dt)+$tzDiffSeconds); // at 2:33pm, May 3rd, 2008
	}
}


// write the settings passed to this page from the view entries page, so the view can be restored when they go back
function writeHiddenSettings($settings, $form) {
	//unpack settings
	$sort = $settings['sort'];
	$order = $settings['order'];
	$oldcols = $settings['oldcols'];
	$currentview = $settings['currentview'];
	foreach($settings as $k=>$v) {
		if(substr($k, 0, 7) == "search_" AND $v != "") {
			$thiscol = substr($k, 7);
			$searches[$thiscol] = $v;
		}
	}
	//calculations:
	$calc_cols = $settings['calc_cols'];
	$calc_calcs = $settings['calc_calcs'];
	$calc_blanks = $settings['calc_blanks'];
	$calc_grouping = $settings['calc_grouping'];

	$hlist = $settings['hlist'];
	$hcalc = $settings['hcalc'];
	$lockcontrols = $settings['lockcontrols'];
	$asearch = $settings['asearch'];
	$lastloaded = $settings['lastloaded'];	

	// used for calendars...
	$calview = $settings['calview'];
	$calfrid = $settings['calfrid'];
	$calfid = $settings['calfid'];
	// plus there's the calhidden key that is handled below
	// plus there's the page number on the LOE screen that is handled below...
	// plus there's the multipage prev and current page

	// write hidden fields
	if($form) { // write as form objects and return form
		$form->addElement (new XoopsFormHidden ('sort', $sort));
		$form->addElement (new XoopsFormHidden ('order', $order));
		$form->addElement (new XoopsFormHidden ('currentview', $currentview));
		$form->addElement (new XoopsFormHidden ('oldcols', $oldcols));
		foreach($searches as $key=>$search) {
			$search_key = "search_" . $key;
			$search = str_replace("'", "&#39;", $search);
			$form->addElement (new XoopsFormHidden ($search_key, stripslashes($search)));
		}
		$form->addElement (new XoopsFormHidden ('calc_cols', $calc_cols));
		$form->addElement (new XoopsFormHidden ('calc_calcs', $calc_calcs));
		$form->addElement (new XoopsFormHidden ('calc_blanks', $calc_blanks));
		$form->addElement (new XoopsFormHidden ('calc_grouping', $calc_grouping));
		$form->addElement (new XoopsFormHidden ('hlist', $hlist));
		$form->addElement (new XoopsFormHidden ('hcalc', $hcalc));
		$form->addElement (new XoopsFormHidden ('lockcontrols', $lockcontrols));
		$form->addElement (new XoopsFormHidden ('lastloaded', $lastloaded));
		$asearch = str_replace("'", "&#39;", $asearch);
		$form->addElement (new XoopsFormHidden ('asearch', stripslashes($asearch)));
		$form->addElement (new XoopsFormHidden ('calview', $calview));
		$form->addElement (new XoopsFormHidden ('calfrid', $calfrid));
		$form->addElement (new XoopsFormHidden ('calfid', $calfid));
		foreach($settings['calhidden'] as $chname=>$chvalue) {
			$form->addElement (new XoopsFormHidden ($chname, $chvalue));
		}
		$form->addElement (new XoopsFormHidden ('formulize_LOEPageStart', $_POST['formulize_LOEPageStart']));
		if(isset($settings['formulize_currentPage'])) { // drawing a multipage form...
			$form->addElement( new XoopsFormHidden ('formulize_currentPage', $settings['formulize_currentPage']));
			$form->addElement( new XoopsFormHidden ('formulize_prevPage', $settings['formulize_prevPage']));
			$form->addElement( new XoopsFormHidden ('formulize_doneDest', $settings['formulize_doneDest']));
			$form->addElement( new XoopsFormHidden ('formulize_buttonText', $settings['formulize_buttonText']));
		}
		if($_POST['overridescreen']) {
			$form->addElement( new XoopsFormHidden ('overridescreen', intval($_POST['overridescreen'])));
		}
		if(strlen($_POST['formulize_lockedColumns'])>0) {
			$form->addElement( new XoopsFormHidden ('formulize_lockedColumns', $_POST['formulize_lockedColumns']));
		}
		return $form;
	} else { // write as HTML
		print "<input type=hidden name=sort value='" . $sort . "'>";
		print "<input type=hidden name=order value='" . $order . "'>";
		print "<input type=hidden name=currentview value='" . $currentview . "'>";
		print "<input type=hidden name=oldcols value='" . $oldcols . "'>";
		foreach($searches as $key=>$search) {
			$search_key = "search_" . $key;
			$search = str_replace("\"", "&quot;", $search);
			print "<input type=hidden name=$search_key value=\"" . stripslashes($search) . "\">";
		}
		print "<input type=hidden name=calc_cols value='" . $calc_cols . "'>";
		print "<input type=hidden name=calc_calcs value='" . $calc_calcs . "'>";
		print "<input type=hidden name=calc_blanks value='" . $calc_blanks . "'>";
		print "<input type=hidden name=calc_grouping value='" . $calc_grouping . "'>";
		print "<input type=hidden name=hlist value='" . $hlist . "'>";
		print "<input type=hidden name=hcalc value='" . $hcalc . "'>";
		print "<input type=hidden name=lockcontrols value='" . $lockcontrols . "'>";
		print "<input type=hidden name=lastloaded value='" . $lastloaded . "'>";
		$asearch = str_replace("\"", "&quot;", $asearch);
		print "<input type=hidden name=asearch value=\"" . stripslashes($asearch) . "\">";
		print "<input type=hidden name=calview value='" . $calview . "'>";
		print "<input type=hidden name=calfrid value='" . $calfrid . "'>";
		print "<input type=hidden name=calfid value='" . $calfid . "'>";
		foreach($settings['calhidden'] as $chname=>$chvalue) {
			print "<input type=hidden name=$chname value='" . $chvalue . "'>";
		}
		print "<input type=hidden name=formulize_LOEPageStart value='" . $_POST['formulize_LOEPageStart'] . "'>";
		if(isset($settings['formulize_currentPage'])) { // drawing a multipage form...
			print "<input type=hidden name=formulize_currentPage value='".$settings['formulize_currentPage']."'>";
			print "<input type=hidden name=formulize_prevPage value='".$settings['formulize_prevPage']."'>";
			print "<input type=hidden name=formulize_doneDest value='".$settings['formulize_doneDest']."'>";
			print "<input type=hidden name=formulize_buttonText value='".$settings['formulize_buttonText']."'>";
		}
		if($_POST['overridescreen']) {
			print "<input type=hidden name=overridescreen value='".intval($_POST['overridescreen'])."'>";
		}
		if(strlen($_POST['formulize_lockedColumns'])>0) {
			print "<input type=hidden name=formulize_lockedColumns value='".$_POST['formulize_lockedColumns']."'>";
		}
	}
}


// draw in javascript for this form that is relevant to subforms
// $nosave indicates that the user cannot save this entry, so we shouldn't check for formulizechanged
function drawJavascript($nosave) {
static $drawnJavascript = false;
if($drawnJavascript) {
	return;
}
global $xoopsUser;
$uid = $xoopsUser ? $xoopsUser->getVar('uid') : 0;
// Left in for possible future use by the rankOrderList element type or other elements that might use jQuery
//print "<script type=\"text/javascript\" src=\"".XOOPS_URL."/modules/formulize/jquery/jquery-1.3.2.min.js\"></script><script type=\"text/javascript\" src=\"".XOOPS_URL."/modules/formulize/jquery/jquery-ui-1.7.2.custom.min.js\"></script>";
//$GLOBALS['formulize_jQuery_included'] = true;
print "\n<script type='text/javascript'>\n";

print " initialize_formulize_xhr();\n";
print " var formulizechanged=0;\n";
print " var formulize_xhr_returned_check_for_unique_value = 'notreturned';\n";
?>

if (typeof jQuery == 'undefined') { 
	var head = document.getElementsByTagName('head')[0];
	script = document.createElement('script');
	script.id = 'jQuery';
	script.type = 'text/javascript';
	script.src = '<?php print XOOPS_URL; ?>/modules/formulize/jquery/jquery-1.4.2.min.js';
	head.appendChild(script);
}

function showPop(url) {

	if (window.formulize_popup == null) {
		formulize_popup = window.open(url,'formulize_popup','toolbar=no,scrollbars=yes,resizable=yes,width=800,height=550,screenX=0,screenY=0,top=0,left=0');
      } else {
		if (window.formulize_popup.closed) {
			formulize_popup = window.open(url,'formulize_popup','toolbar=no,scrollbars=yes,resizable=yes,width=800,height=550,screenX=0,screenY=0,top=0,left=0');
            } else {
			window.formulize_popup.location = url;              
		}
	}
	window.formulize_popup.focus();

}

function validateAndSubmit() {
<?php
if(!$nosave) { // need to check for add or update permissions on the current user and this entry before we include this javascript, otherwise they should not be able to save the form
?>
	var validate = xoopsFormValidate_formulize();
	if(validate) {
		jQuery(".subform-accordion-container").map(function() {
			subelementid = jQuery(this).attr('subelementid');			
			window.document.getElementById('subform_entry_'+subelementid+'_active').value = jQuery(this).accordion( "option", "active" );
		});
		if(window.document.formulize.submitx) {
			window.document.formulize.submitx.disabled=true;
		}
		if(window.document.pagebuttons) {
			window.document.pagebuttons.prev.disabled = true;
			window.document.pagebuttons.next.disabled = true;
		}
		window.document.getElementById('formulizeform').style.opacity = 0.5;
		window.document.getElementById('savingmessage').style.display = 'block';
		
		window.scrollTo(0,0);
		window.document.formulize.submit(); 
	}
<?php
} // end of if not $nosave
?>
}

<?php

print "	function verifyDone() {\n";
//print "		alert(formulizechanged);\n";
if(!$nosave) {
	print "	if(formulizechanged==0) {\n";
}
print "			window.document.go_parent.submit();\n";
if(!$nosave) {
	print "	} else {\n";
	print "		var answer = confirm (\"" . _formulize_CONFIRMNOSAVE . "\");\n";
	print "		if (answer) {\n";
	print "			window.document.go_parent.submit();\n";
	print "		} else {\n";
	print "			return false;\n";
	print "		}\n";
	print "	}\n";
}
print "	}\n";
	
print "	function add_sub(sfid, numents, ele_id) {\n";
print "		document.formulize.target_sub.value=sfid;\n";
print "		document.formulize.numsubents.value=numents;\n";
print "		document.formulize.target_sub_instance.value=ele_id;\n";
print "		validateAndSubmit();\n";
print "	}\n";

print "	function sub_del(sfid) {\n";
print "		var answer = confirm ('" . _formulize_DEL_ENTRIES . "')\n";
print "		if (answer) {\n";
print "			document.formulize.deletesubsflag.value=sfid;\n";
print "			validateAndSubmit();\n";
print "		} else {\n";
print "			return false;\n";
print "		}\n";
print "	}\n";

print "	function goSub(ent, fid) {\n";
print "		document.formulize.goto_sub.value = ent;\n";
print "		document.formulize.goto_sfid.value = fid;\n";
//print "		document.formulize.submit();\n";
print "		validateAndSubmit();\n";
print "	}\n";
			
//added by Cory Aug 27, 2005 to make forms printable


print "function PrintPop(ele_allowed) {\n";
print "		window.document.printview.elements_allowed.value=ele_allowed;\n"; // nmc 2007.03.24 - added 
print "		window.document.printview.submit();\n";
print "}\n";

//added by Cory Aug 27, 2005 to make forms printable

print "function PrintAllPop() {\n";									// nmc 2007.03.24 - added 
print "		window.document.printview.elements_allowed.value='';\n"; // nmc 2007.03.24 - added 
print "		window.document.printview.submit();\n";					// nmc 2007.03.24 - added 
print "}\n";														// nmc 2007.03.24 - added 

// try and catch changes in a datebox element
print "jQuery(window).load(function() {
  jQuery(\"img[title='"._CALENDAR."']\").click(function() {
	formulizechanged=1;		
  }); 
});
\n";

drawXhrJavascript();


print "</script>\n";
$drawnJavascript = true;
}


function drawJavascriptForConditionalElements($conditionalElements, $entries, $sub_entries) {

global $xoopsUser;
$uid = $xoopsUser ? $xoopsUser->getVar('uid') : 0;

// need to setup governing elements array...which is inverse of the conditional elements
$element_handler = icms_getModuleHandler('elements','formulize');
$governingElements = array();
foreach($conditionalElements as $handle=>$theseGoverningElements) {
	foreach($theseGoverningElements as $thisGoverningElement) {
		$elementObject = $element_handler->get($thisGoverningElement);
		$governingElements = compileGoverningElements($entries, $governingElements, $elementObject, $handle);
		$governingElements = compileGoverningElements($sub_entries, $governingElements, $elementObject, $handle);
		$governingElements = compileGoverningLinkedSelectBoxSourceConditionElements($governingElements, $handle);
		
		// must wrap required validation javascript in some check for the pressence of the element??  
	}
}

print "
<script type='text/javascript'>

var conditionalHTML = new Array(); // needs to be global!

jQuery(window).load(function() {

	// preload the current state of the HTML for any conditional elements that are currently displayed, so we can compare against what we get back when their conditions change
	var conditionalElements = new Array('".implode("', '",array_keys($conditionalElements))."');
	var governedElements = new Array();
	var relevantElements = new Array();
	";
	$topKey = 0;
	$relevantElementArray = array();
	foreach($governingElements as $thisGoverningElement=>$theseGovernedElements) {
		print "governedElements['".$thisGoverningElement."'] = new Array();\n";
		foreach($theseGovernedElements as $innerKey=>$thisGovernedElement) {
			if(!isset($relevantElementArray[$thisGovernedElement])) {
				print "relevantElements['".$thisGovernedElement."'] = new Array();\n";
				$relevantElementArray[$thisGovernedElement] = true;
			}
			print "relevantElements['".$thisGovernedElement."'][$topKey] = '".$thisGoverningElement."';\n";
			print "governedElements['".$thisGoverningElement."'][$innerKey] = '".$thisGovernedElement."';\n";
		}
		$topKey++;
	}
	print "
	for(key in conditionalElements) {
		var handle = conditionalElements[key];
		getConditionalHTML(handle); // isolate ajax call in a function to control the scope of handle so it's the same no matter the time difference when the return value gets here
	}

	jQuery(\"[name='".implode("'], [name='", array_keys($governingElements))."']\").live('change', function() { // live is necessary because it will bind events to the right DOM elements even after they've been replaced by ajax events
		for(key in governedElements[jQuery(this).attr('name')]) {
			var handle = governedElements[jQuery(this).attr('name')][key];
			elementValuesForURL = getRelevantElementValues(relevantElements[handle]);
			checkCondition(handle, conditionalHTML[handle], elementValuesForURL);	
		}
	});
});

function getConditionalHTML(handle) {
	partsArray = handle.split('_');
	jQuery.get(\"".XOOPS_URL."/modules/formulize/formulize_xhr_responder.php?uid=".$uid."&op=get_element_row_html&elementId=\"+partsArray[3]+\"&entryId=\"+partsArray[2]+\"&fid=\"+partsArray[1], function(data) {
		assignConditionalHTML(handle, data);
	});
}

function assignConditionalHTML(handle, html) {
	conditionalHTML[handle] = html; 
}

function checkCondition(handle, currentHTML, elementValuesForURL) {
	partsArray = handle.split('_');
	jQuery.get(\"".XOOPS_URL."/modules/formulize/formulize_xhr_responder.php?uid=".$uid."&op=get_element_row_html&elementId=\"+partsArray[3]+\"&entryId=\"+partsArray[2]+\"&fid=\"+partsArray[1]+\"\"+elementValuesForURL, function(data) {
		if(data) {
			// should only empty if there is a change from the current state
			if(window.document.getElementById('formulize-'+handle).style.display == 'none' || currentHTML != data) {
				jQuery('#formulize-'+handle).empty();
				jQuery('#formulize-'+handle).append(data);
				window.document.getElementById('formulize-'+handle).style.display = 'table-row';
				ShowHideTableRow('#formulize-'+handle,false,0,function() {}); // because the newly appended row will have full opacity so immediately make it transparent
				ShowHideTableRow('#formulize-'+handle,true,1500,function() {});
				assignConditionalHTML(handle, data);
			}
		} else {
			if( window.document.getElementById('formulize-'+handle).style.display != 'none') {
				ShowHideTableRow('#formulize-'+handle,false,700,function() {
					jQuery('#formulize-'+handle).empty();
					window.document.getElementById('formulize-'+handle).style.display = 'none';
					assignConditionalHTML(handle, data);
				});
			}
		}
		
	});
}

function getRelevantElementValues(elements) {
	var ret = '';
	for(key in elements) {
		var handle = elements[key];
		if(handle.indexOf('[]')!=-1) { // grab multiple value elements from a different tag
			nameToUse = '[jquerytag='+handle.substring(0, handle.length-2)+']';
		} else {
			nameToUse = '[name='+handle+']';
		}
		elementType = jQuery(nameToUse).attr('type');
		if(elementType == 'radio') {
			selected = jQuery(nameToUse+':checked').val();
		} else if(elementType == 'checkbox') {
			selected = new Array();
			jQuery(nameToUse).map(function() { // need to check each one individually, because val isn't working right?!
			  if(jQuery(this).attr('checked')) {
				foundval = jQuery(this).attr('value');
				selected.push(foundval);
			  }
			});
		} else {
			selected = jQuery(nameToUse).val();
		}
		if(jQuery.isArray(selected)) {
			for(key in selected) {
				ret = ret + '&'+handle+'='+encodeURIComponent(selected[key]);					
			}
		} else {
			ret = ret + '&'+handle+'='+encodeURIComponent(selected);				
		}

	}
	return ret;
}


function ShowHideTableRow(rowSelector, show, speed, callback)
{
    var childCellsSelector = jQuery(rowSelector).children('td');
    var ubound = childCellsSelector.length - 1;
    var lastCallback = null;

    childCellsSelector.each(function(i)
    {
        // Only execute the callback on the last element.
        if (ubound == i)
            lastCallback = callback

        if (show)
        {
            jQuery(this).fadeIn(speed, lastCallback)
        }
        else
        {
            jQuery(this).fadeOut(speed, lastCallback)
        }
    });
}




</script>";
	
}

function compileGoverningElements($entries, $governingElements, $elementObject, $handle) {
	$type = $elementObject->getVar('ele_type');
	$ele_value = $elementObject->getVar('ele_value');
	if($type == "checkbox" OR ($type == "select" AND $ele_value[1])) {
		$additionalNameParts = "[]"; // set things up with the right [] for multiple value elements
	} else {
		$additionalNameParts = "";
	}
	if(isset($entries[$elementObject->getVar('id_form')])) {
		foreach($entries[$elementObject->getVar('id_form')] as $thisEntry) {
			if($thisEntry == "") {
				$thisEntry = "new";
			}
			$governingElements['de_'.$elementObject->getVar('id_form').'_'.$thisEntry.'_'.$elementObject->getVar('ele_id').$additionalNameParts][] = $handle;
		}
	}
	return $governingElements;
}

function compileGoverningLinkedSelectBoxSourceConditionElements($governingElements, $handle) {
	// figure out if the $handle is for a lsb
	// if so, check if there are conditions on the lsb
	// check if the terms include any { } elements and grab those
	$handleParts = explode("_",$handle); // de, fid, entry, elementId
	$element_handler = icms_getModuleHandler('elements','formulize');
	$elementObject = $element_handler->get($handleParts[3]);
	if($elementObject->isLinked) {
		$ele_value = $elementObject->getVar('ele_value');
		$elementConditions = $ele_value[5];
		foreach($elementConditions[2] as $thisTerm) {
			if(substr($thisTerm,0,1)=="{" AND substr($thisTerm, -1) == "}") {
				// figure out the element, which is presumably in the same form, and assume the same entry
				$curlyBracketElement = $element_handler->get(trim($thisTerm,"{}"));
				$governingElements['de_'.$curlyBracketElement->getVar('id_form').'_'.$handleParts[2].'_'.$curlyBracketElement->getVar('ele_id')][] = $handle;
			}
		}
	} 
	return $governingElements;
}
