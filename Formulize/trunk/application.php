<?php
###############################################################################
##     Formulize - ad hoc form creation and reporting module for XOOPS       ##
##                    Copyright (c) 2005 Freeform Solutions                  ##
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

include 'header.php';

$xoopsOption['template_main'] = 'formulize_application.html';

require(XOOPS_ROOT_PATH."/header.php");

global $xoopsDB;

$form_handler = icms_getModuleHandler('forms', 'formulize');
$application_handler = icms_getModuleHandler('applications', 'formulize');
include_once XOOPS_ROOT_PATH . "/modules/formulize/include/functions.php";
$allowedForms = allowedForms();

if(isset($_GET['id']) AND $_GET['id'] === "all") {
	$applicationsToDraw = $application_handler->getAllApplications();
	$applicationsToDraw[] = 0; // add in forms with no app at the end of the list
} else {
	$aid = (isset($_GET['id'])) ? intval($_GET['id']) : 0 ;
	$applicationsToDraw = array($aid);
}

$allAppData = array();
foreach($applicationsToDraw as $aid) {
	if(is_object($aid)) {
		$aid = $aid->getVar('appid'); // when 'all' is requested, the array will be of objects, not ids
	}

	$forms = $form_handler->getFormsByApplication($aid, true);
	$formsToShow = array_intersect($forms, $allowedForms);
	$formsToSend = getNavDataForForms($formsToShow, $form_handler);
	if($aid) {
		$application = $application_handler->get($aid);
		$app_name = $application->getVar('name');
	} else {
		$app_name = _AM_CATGENERAL;
	}
	if(count($formsToSend)==0) {
		$noforms =  _AM_NOFORMS_AVAIL;
	} else {
		$noforms = 0;
	}
	$allAppData[] = array('app_name'=>$app_name, 'noforms'=>$noforms, 'formData'=>$formsToSend);

}

$xoopsTpl->assign("allAppData", $allAppData);

require(XOOPS_ROOT_PATH."/footer.php");

// $forms must be an array of form ids
function getNavDataForForms($forms, $form_handler) {
	$formsToSend = array();
	$i=0;
	foreach($forms as $thisForm) {
		$thisFormObject = $form_handler->get($thisForm);
		if($thisFormObject->getVar('menutext')) {
			$title = html_entity_decode($thisFormObject->getVar('menutext'), ENT_QUOTES) == "Use the form's title" ? $thisFormObject->getVar('title') : $thisFormObject->getVar('menutext');
			if($title) {
				$formsToSend[$i]['fid'] = $thisFormObject->getVar('id_form');
				$formsToSend[$i]['title'] = $title;
				$i++;
			}
		}
	}
	return $formsToSend;
}

