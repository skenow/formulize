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

// this file handles saving of submissions from the screen_list_view page of the new admin UI

// if we aren't coming from what appears to be save.php, then return nothing
if(!isset($processedValues)) {
  return;
}


$aid = intval($_POST['aid']);
$sid = $_POST['formulize_admin_key'];

$screens = $processedValues['screens'];


$screen_handler = icms_getModuleHandler('listOfEntriesScreen', 'formulize');
$screen = $screen_handler->get($sid);

// check if the user has permission to edit the form
if(!$gperm_handler->checkRight("edit_form", $screen->getVar('fid'), $groups, $mid)) {
  return;
}


$screen->setVar('defaultview',$screens['defaultview']);
$screen->setVar('usecurrentviewlist',$screens['usecurrentviewlist']);
$screen->setVar('limitviews',$screens['limitviews']);
$screen->setVar('useworkingmsg',(array_key_exists('useworkingmsg',$screens))?$screens['useworkingmsg']:0);
$screen->setVar('usescrollbox',(array_key_exists('usescrollbox',$screens))?$screens['usescrollbox']:0);
$screen->setVar('entriesperpage',$screens['entriesperpage']);
$screen->setVar('viewentryscreen',$screens['viewentryscreen']);


if(!$screen_handler->insert($screen)) {
  print "Error: could not save the screen properly: ".icms::$xoopsDB->error();
}
?>
