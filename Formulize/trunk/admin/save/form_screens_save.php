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

// this file handles saving of submissions from the form_screens page of the new admin UI

// if we aren't coming from what appears to be save.php, then return nothing
if(!isset($processedValues)) {
  return;
}

$fid = intval($_POST['formulize_admin_key']);
$aid = intval($_POST['formulize_admin_aid']);

// CHECK IF THE FORM IS LOCKED DOWN AND SCOOT IF SO
$form_handler = icms_getModuleHandler('forms', 'formulize');
$formObject = $form_handler->get($fid);
if($formObject->getVar('lockedform')) {
  return;
}

// check if the user has permission to edit the form
if(!$gperm_handler->checkRight("edit_form", $fid, $groups, $mid)) {
  return;
}

// do saving of defaults here
$screens = $processedValues['screens'];
$form = $form_handler->get($fid);
$form->setVar('defaultform',intval($screens['defaultform']));
$form->setVar('defaultlist',intval($screens['defaultlist']));

if(!$fid = $form_handler->insert($form)) {
  print "Error: could not save the form properly: ".icms::$xoopsDB->error();
}


// do deletion here
if($_POST['deletescreen']) {
  $screen_handler = icms_getModuleHandler('screen', 'formulize');
  $screen = $screen_handler->get(intval($_POST['deletescreen']));
  if(!$screen_handler->delete($screen->getVar('sid'), $screen->getVar('type'))) {
    print "Error: could not delete screen ".intval($_POST['deletescreen']);
  } else {
    print "/* eval */ reloadWithScrollPosition()";
  }
}

// if the form name was changed, then force a reload of the page...reload will be the application id
if($_POST['gotoscreen']) {
  print "/* eval */ window.location = '". XOOPS_URL ."/modules/formulize/admin/ui.php?page=screen&aid=$aid&fid=$fid&sid=".intval($_POST['gotoscreen'])."'";
}
