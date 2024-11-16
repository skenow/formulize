<?php
###############################################################################
##     Formulize - ad hoc form creation and reporting module for XOOPS       ##
##                    Copyright (c) 2010 Freeform Solutions                  ##
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
##  Author of this file: Freeform Solutions                                  ##
##  URL: http://www.freeformsolutions.ca/formulize                           ##
##  Project: Formulize                                                       ##
###############################################################################

// this file gets all the data about applications, so we can display the Settings/forms/relationships tabs for applications

// need to listen for $_GET['aid'] later so we can limit this to just the application that is requested
$aid = intval($_GET['aid']);
$framework_handler = icms_getModuleHandler('frameworks', 'formulize');
$form_handler = icms_getModuleHandler('forms', 'formulize');
$application_handler = icms_getModuleHandler('applications','formulize');
if($aid == 0) {
	$appName = "Forms with no app";
	$appDesc = "";
	$appForms = array();
	$formObjects = $form_handler->getFormsByApplication(0); // returns array of objects
} else {
	$appObject = $application_handler->get($aid);
	$appName = $appObject->getVar('name');
	$appDesc = $appObject->getVar('description');
	$formObjects = $form_handler->getFormsByApplication($aid);
	// get list of all the forms
	$allFormObjects = $form_handler->getAllForms();
	foreach($allFormObjects as $thisFormObject) {
		$allForms[$thisFormObject->getVar('id_form')]['name'] = $thisFormObject->getVar('title');
		$allForms[$thisFormObject->getVar('id_form')]['id'] = $thisFormObject->getVar('id_form'); // settings tab uses id
	}
}
$screen_handler = icms_getModuleHandler('screen', 'formulize');
$gperm_handler = xoops_gethandler('groupperm');
global $xoopsUser;
foreach($formObjects as $thisFormObject) {
	if(!$gperm_handler->checkRight("edit_form", $thisFormObject->getVar('id_form'), $xoopsUser->getGroups(), getFormulizeModId())) {
		continue;
	}
	$formsInApp[$thisFormObject->getVar('id_form')]['name'] = $thisFormObject->getVar('title');
	$formsInApp[$thisFormObject->getVar('id_form')]['fid'] = $thisFormObject->getVar('id_form'); // forms tab uses fid
	$hasDelete = $gperm_handler->checkRight("delete_form", $thisFormObject->getVar('id_form'), $xoopsUser->getGroups(), getFormulizeModId());
	$formsInApp[$thisFormObject->getVar('id_form')]['hasdelete'] = $hasDelete;
	// get the default screens for each form too
	$defaultFormScreen = $thisFormObject->getVar('defaultform');
	$defaultListScreen = $thisFormObject->getVar('defaultlist');
	$defaultFormObject = $screen_handler->get($defaultFormScreen);
	if(is_object($defaultFormObject)) {
		$defaultFormName = $defaultFormObject->getVar('title');
	}
	$defaultListObject = $screen_handler->get($defaultListScreen);
	if(is_object($defaultListObject)) {
		$defaultListName = $defaultListObject->getVar('title');
	}
	$formsInApp[$thisFormObject->getVar('id_form')]['defaultformscreenid'] = $defaultFormScreen;
	$formsInApp[$thisFormObject->getVar('id_form')]['defaultlistscreenid'] = $defaultListScreen;
	$formsInApp[$thisFormObject->getVar('id_form')]['defaultformscreenname'] = $defaultFormName;
	$formsInApp[$thisFormObject->getVar('id_form')]['defaultlistscreenname'] = $defaultListName;
	$formsInApp[$thisFormObject->getVar('id_form')]['lockedform'] = $thisFormObject->getVar('lockedform');
	$formsInApp[$thisFormObject->getVar('id_form')]['istableform'] = $thisFormObject->getVar('tableform');
}


$allRelationships = array();
foreach($formObjects as $thisForm) {
	$allRelationships = array_merge($allRelationships, $framework_handler->getFrameworksByForm($thisForm->getVar('id_form'))); // returns array of objects
	if($aid) {
		// package up the info we need for drawing the list of forms in the app
		$allForms[$thisForm->getVar('id_form')]['selected'] = " selected";
	}
}
$relationships = array();
$relationshipIndex = array();
$i = 1;
foreach($allRelationships as $thisRelationship) {
	$frid = $thisRelationship->getVar('frid');
	if(isset($relationshipIndex[$frid])) { continue; }
	$relationships[$i]['name'] = $thisRelationship->getVar('name');
	$relationships[$i]['content']['frid'] = $frid;

  $framework_handler = icms_getModuleHandler('frameworks', 'formulize');
  $relationshipObject = $framework_handler->get($frid);
  $relationshipLinks = $relationshipObject->getVar('links');
  $li = 1;
  foreach($relationshipLinks as $relationshipLink) {
    // get names of forms in the link
    $links[$li]['form1'] = printSmart(getFormTitle($relationshipLink->getVar('form1')));
    $links[$li]['form2'] = printSmart(getFormTitle($relationshipLink->getVar('form2')));
    // get the name of the relationship
    switch($relationshipLink->getVar('relationship')) {
      case 1:
        $relationship = _AM_FRAME_ONETOONE;
        break;
      case 2:
        $relationship = _AM_FRAME_ONETOMANY;
        break;
      case 3:
        $relationship = _AM_FRAME_MANYTOONE;
        break;
    }
    $links[$li]['relationship'] = printSmart($relationship);
    $li++;
  }
	$relationships[$i]['content']['links'] = $links;

	$relationshipIndex[$frid] = true;
	$i++;
}

$common['aid'] = $aid;
$common['name'] = $appName;

// adminPage tabs sections must contain a name, template and content key
// content is the data the is available in the tab as $content.foo
// any declared sub key of $content, such as 'forms' will be assigned to accordions
// accordion content is available as $sectionContent.foo

$i=0;
if($aid > 0) {
	$i++;
	$adminPage['tabs'][$i]['name'] = _AM_APP_SETTINGS;
	$adminPage['tabs'][$i]['template'] = "db:admin/application_settings.html";
	$adminPage['tabs'][$i]['content'] = $common;
	$adminPage['tabs'][$i]['content']['description'] = $appDesc;
	$adminPage['tabs'][$i]['content']['forms'] = $allForms;
}

$i++;
$adminPage['tabs'][$i]['name'] = "Forms";
$adminPage['tabs'][$i]['template'] = "db:admin/application_forms.html";
$adminPage['tabs'][$i]['content'] = $common;
$adminPage['tabs'][$i]['content']['forms'] = $formsInApp; 

$i++;
$adminPage['tabs'][$i]['name'] = _AM_APP_RELATIONSHIPS;
$adminPage['tabs'][$i]['template'] = "db:admin/application_relationships.html";
$adminPage['tabs'][$i]['content'] = $common;
$adminPage['tabs'][$i]['content']['relationships'] = $relationships; 

$adminPage['pagetitle'] = _AM_APP_APPLICATION.$appName;
$adminPage['needsave'] = true;

$breadcrumbtrail[1]['url'] = "page=home";
$breadcrumbtrail[1]['text'] = "Home";
$breadcrumbtrail[2]['text'] = $appName;

