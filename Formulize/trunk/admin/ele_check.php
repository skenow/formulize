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
if( !preg_match("/elements.php/", $_SERVER['PHP_SELF']) ){
	exit("Access Denied");
}
$options = array();
$opt_count = 0;
if( !empty($ele_id) ){
	$keys = array_keys($value);
	for( $i=0; $i<count($keys); $i++ ){
		$v = $myts->makeTboxData4PreviewInForm($keys[$i]);
		$options[] = addOption('ele_value['.$opt_count.']', 'checked['.$opt_count.']', $v, 'check', $value[$keys[$i]]);
		$opt_count++;
	}

	
}

	// added check below to add in blank rows that are unaccounted for above.
	// above code adds in all the options the user has typed in.  If there are blank rows on the form, this code will add in the appropriate amount, based on the 'rowcount' hidden element.
	// This code added by jwe 01/05/05
	if($opt_count < $_POST['rowcount']) {
		for($i=$opt_count;$i<$_POST['rowcount'];$i++) {
			$options[] = addOption('ele_value['.$i.']', 'checked['.$i.']');
		}
		$opt_count = $_POST['rowcount']; // make the opt_count equal to the number of rows, since we've now brought the number back up to where it should be.
	}

	if(empty($addopt) AND empty($ele_id)) {
		$addopt = 2;
	} 
	for( $i=0; $i<$addopt; $i++ ){
		$options[] = addOption('ele_value['.$opt_count.']', 'checked['.$opt_count.']');
		$opt_count++;
	}
	// these two lines part of the jwe added code
	$rowcount = new XoopsFormHidden("rowcount", $opt_count);
	$form->addElement($rowcount);


$add_opt = addOptionsTray();
$options[] = $add_opt;
$opt_tray = new XoopsFormElementTray(_AM_ELE_OPT, '<br />');
$opt_tray->setDescription(_AM_ELE_OPT_DESC_CHECKBOXES.'<br /><br />'._AM_ELE_OTHER.'<br /><br />'._AM_ELE_OPT_UITEXT);
for( $i=0; $i<count($options); $i++ ){
	$opt_tray->addElement($options[$i]);
}

// add setting to flag whether to change corresponding values in people's entries, when webmaster changes options for questions
$changeUserValues = new xoopsFormCheckbox('', 'changeuservalues');
$changeUserValues->addOption(1, _AM_ELE_OPT_CHANGEUSERVALUES);
$opt_tray->addElement($changeUserValues);

$form->addElement($opt_tray);

// delimiter option added June 7 2006 -- jwe
$default_custom_text = ($element->getVar('ele_delim')!="br" AND $element->getVar('ele_delim')!="space" AND $element->getVar('ele_delim')!="") ? $element->getVar('ele_delim') : "";
if(!$default_custom_text) {
	$default_to_set = $element->getVar('ele_delim')=="" ? $default_delim = $xoopsModuleConfig['delimeter'] : $element->getVar('ele_delim');
} else {
	$default_to_set = 'custom';
}
$delim_tray = new XoopsFormElementTray(_AM_ELE_DELIM_CHOICE, '<br />');
$delim_choice_br = new xoopsFormRadio('', 'ele_delim', $default_to_set);
$delim_choice_br->addOption('br', _MI_formulize_DELIMETER_BR);
$delim_tray->addElement($delim_choice_br);
$delim_choice_space = new xoopsFormRadio('', 'ele_delim', $default_to_set);
$delim_choice_space->addOption('space', _MI_formulize_DELIMETER_SPACE);
$delim_tray->addElement($delim_choice_space);
$delim_choice_custom_box = new xoopsFormText('', 'ele_delim_custom', 25, 255, $default_custom_text);
$delim_choice_custom_box->setExtra("onfocus=\"javascript:this.form.ele_delim[2].checked = true;\"");
$delim_choice_custom = new xoopsFormRadio('', 'ele_delim', $default_to_set);
$delim_choice_custom->addOption('custom', _MI_formulize_DELIMETER_CUSTOM. ": " . $delim_choice_custom_box->render());
$delim_tray->addElement($delim_choice_custom);

$form->addElement($delim_tray);

?>