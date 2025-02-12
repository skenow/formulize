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

// this file handles saving of submissions from the application_settings page of the new admin UI

// if we aren't coming from what appears to be save.php, then return nothing
if(!isset($processedValues)) {
  return;
}

$application_handler = icms_getModuleHandler('applications', 'formulize');
$appObject = $application_handler->get($_POST['formulize_admin_key']);
foreach($processedValues['applications'] as $property=>$value) {
  $appObject->setVar($property, $value);
}
$application_handler->insert($appObject);

// if the form name was changed, then force a reload of the page...reload will be the application id
if(isset($_POST['reload_settings']) AND $_POST['reload_settings'] == 1) {
  print "/* eval */ window.location = window.location;";
}