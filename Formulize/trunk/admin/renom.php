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

include("admin_header.php");
include_once '../../../include/cp_header.php';
if ( file_exists("../language/".$xoopsConfig['language']."/main.php") ) {
	include "../language/".$xoopsConfig['language']."/main.php";
} else {
	include "../language/english/main.php";
}


include_once XOOPS_ROOT_PATH."/class/xoopstree.php";
include_once XOOPS_ROOT_PATH."/class/xoopslists.php";
include_once XOOPS_ROOT_PATH."/include/xoopscodes.php";
include_once XOOPS_ROOT_PATH."/class/module.errorhandler.php";
$myts =& MyTextSanitizer::getInstance();

if(!isset($_POST['title'])){
	$title = isset ($_GET['title']) ? $_GET['title'] : '0';
}else {
	$title = $_POST['title'];
}
if(!isset($_POST['op'])){
	$op = isset ($_GET['op']) ? $_GET['op'] : '0';
}else {
	$op = $_POST['op'];
}

$form_handler = xoops_getmodulehandler('forms', 'formulize');
$formObject = $form_handler->get(intval($title));
if($formObject->getVar('lockedform')) {
	redirect_header("formindex.php",3,_NO_PERM);
}

	$sql="SELECT desc_form FROM ".$xoopsDB->prefix("formulize_id")." WHERE id_form = ".$title;
	$res = icms::$xoopsDB->queryF( $sql );

if ( $res ) {
  while ( $row = icms::$xoopsDB->fetchRow( $res ) ) {
    $desc_form = $row[0];
  }
}

xoops_cp_header();
$op = isset ($_GET['op']) ? $_GET['op'] : '0';

if ($op != 1) {
	echo '	<form action="renom.php?title='.$title.'&op=1" method="post">
	<table class="outer" width="100%">

	<th colspan="2"><center><font size=4>'.$desc_form.'</font></center></th>';

	echo '<tr><td class="head" ALIGN=center>'._FORM_NOM.'</td>
	      <td class="odd" align="center">  
	      <input maxlength="255" size="50" id="title2" name="title2" type="text" value="'.$desc_form.'"></a></td></tr>';

	$submit = new XoopsFormButton('', 'submit', _SUBMIT, 'submit');
	echo '  <tr>
		<td class="foot" colspan="7">'.$submit->render().'
		</tr>
		</table>';
	//$renom = new XoopsFormHidden($title2, $title2);
	//$renom->render();
		
	echo '</form>';
}

else
{
	global $xoopsDB, $_POST, $myts, $eh, $desc_form, $title2;
	$title2 = $myts->makeTboxData4Save($_POST["title2"]);
	//$title3 = $myts->makeTboxData4Save($_POST["desc_form3"]);
	if (empty($title)) {
		redirect_header("formindex.php", 2, _MD_ERRORTITLE);
	}
	$title2 = get_magic_quotes_gpc() ? stripslashes($title2) : $title2;
	$title2 = icms::$xoopsDB->escape($title2);
		
	$sql = sprintf("UPDATE %s SET desc_form='%s' WHERE id_form='%s'", $xoopsDB->prefix("formulize_id"),$title2, intval($title));
	$xoopsDB->queryF($sql) or $eh->show("error insertion 1 dans renform");
	
	$sql2 = sprintf("UPDATE %s SET itemname='%s',itemurl='%s' WHERE itemname='%s'", $xoopsDB->prefix("formulize_menu"), $title2, XOOPS_URL.'/modules/formulize/index.php?title='.$title2, icms::$xoopsDB->escape($desc_form));
	$xoopsDB->query($sql2) or $eh->show("error insertion 2 dans renform");
	redirect_header("formindex.php",1,_formulize_FORMMOD);
}