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

// code snippet that handles saving of data...called in the normal save operation in admin/elements.php, but also invoked in certain cases when the page reloads but the user should not have left the editing screen yet

if(!$id_form) { return; } // this is set in admin/elements.php which is the only place elements_save.php should be called from
$form_handler = xoops_getmodulehandler('forms', 'formulize');
$formObject = $form_handler->get(intval($id_form));
if($formObject->getVar('lockedform')) {
	redirect_header("formindex.php",3,_NO_PERM);
}

$databaseElement = ($ele_type == "areamodif" OR $ele_type == "ib" OR $ele_type == "sep" OR $ele_type == "subform" OR $ele_type == "grid") ? false : true;

if( !empty($ele_id) AND $clone == 0){
      
      $element = $formulize_mgr->get($ele_id);

			$ocq = "SELECT ele_caption, ele_handle FROM " . $xoopsDB->prefix("formulize") . " WHERE ele_id='$ele_id'";
			$res_ocq = $xoopsDB->query($ocq);
			$array_ocq = $xoopsDB->fetchArray($res_ocq);
			//$original_caption = $array_ocq['ele_caption'];
      $newFieldNeeded = false;
			$original_handle = $array_ocq['ele_handle'];
			
		}else{
			unset($ele_id); // just in case we're cloning something
			$element =& $formulize_mgr->create();
      $newFieldNeeded = $databaseElement ? true : false; // some fields don't exist in the database
		}

    $ele_caption = get_magic_quotes_gpc() ? stripslashes($ele_caption) : $ele_caption;
		//$ele_caption = formulize_verifyUniqueCaption($ele_caption, $ele_id, $id_form);
		
		$element->setVar('ele_caption', $ele_caption);
		$ele_delim = $ele_delim=='custom' ? $ele_delim_custom : $ele_delim;
		$element->setVar('ele_delim', $ele_delim); // only set for radio and checkbox, but cannot be put into ele_value because ele_value is not a multidimensional array for those elements, so must be treated as a separate db field for now
		$element->setVar('ele_desc', $ele_desc);
		$element->setVar('ele_colhead', $ele_colhead);
		// check that handle is unique
    $ele_handle = str_replace(" ", "_", $ele_handle);
    $ele_handle = str_replace("'", "", $ele_handle);
    $ele_handle = str_replace("\"", "", $ele_handle);
		if($ele_handle) {
			$form_handler =& xoops_getmodulehandler('forms');
			$firstUniqueCheck = true;
			while(!$uniqueCheck = $form_handler->isHandleUnique($ele_handle, $ele_id)) {
						if($firstUniqueCheck) {
									$ele_handle = $ele_handle . "_".$id_form;
									$firstUniqueCheck = false;
						} else {
									$ele_handle = $ele_handle . "_copy";
						}
			}			
		}
		$element->setVar('ele_handle', $ele_handle);
		$req = !empty($ele_req) ? 1 : 0;
		$element->setVar('ele_req', $req);
		$order = empty($ele_order) ? 0 : intval($ele_order);
		$order = $ele_order_changed ? $order : formulize_getElementHighOrder($id_form); // if order was not modified from it's original state, then make sure we're writing the current highest order to the DB
		$element->setVar('ele_order', $order);
		$uitext = "";

		// grab the forcehidden setting -- added Nov 7 2005
		if(!$fhide) { $fhide_checked = 0; } else { $fhide_checked = $fhide; }
		$element->setVar('ele_forcehidden', $fhide_checked);

		// grab the private setting -- added July 15 2006
		if(!$private) { $private_checked = 0; } else { $private_checked = $private; }
		$element->setVar('ele_private', $private_checked);
    
    // grab the encrypt setting -- added July 15 2009 -- can probably pass in the intval of $encrypt, but we'll follow the convention set out three years ago for consistency/readability
		if(!$encrypt) { $encrypt_checked = 0; } else { $encrypt_checked = $encrypt; }
    if($ele_id AND $element->getVar('ele_encrypt') != $encrypt_checked) {
      // if the encryption setting changed, then we need to encrypt/decrypt all the existing data
      include_once XOOPS_ROOT_PATH . "/modules/formulize/class/data.php";
			$data_handler = new formulizeDataHandler($id_form);
      if(!$data_handler->toggleEncryption($original_handle, $element->getVar('ele_encrypt'))) {
        print "Warning:  unable to toggle the encryption of the '$original_handle' field on/off!";
      }
    }
		$element->setVar('ele_encrypt', $encrypt_checked);
    
		/* ALTERED - 20100315 - freeform - jeff/julian - start */
	  // handle any conditions
	  $elementFilterSettings = array();
	  if($_POST['elementfilter']!="all") {
	   if($_POST["new_elementfilter_term"] != "") {
		$_POST["elementfilter_elements"][] = $_POST["new_elementfilter_element"];
		$_POST["elementfilter_ops"][] = $_POST["new_elementfilter_op"];
		$_POST["elementfilter_terms"][] = $_POST["new_elementfilter_term"];
		$_POST["elementfilter_types"][] = "all";
	   }
	   if($_POST["new_elementfilter_oom_term"] != "") {
		$_POST["elementfilter_elements"][] = $_POST["new_elementfilter_oom_element"];
		$_POST["elementfilter_ops"][] = $_POST["new_elementfilter_oom_op"];
		$_POST["elementfilter_terms"][] = $_POST["new_elementfilter_oom_term"];
		$_POST["elementfilter_types"][] = "oom";
	   } 
	   $elementFilterSettings[0] = $_POST["elementfilter_elements"];
	   $elementFilterSettings[1] = $_POST["elementfilter_ops"];
	   $elementFilterSettings[2] = $_POST["elementfilter_terms"];
	   $elementFilterSettings[3] = $_POST["elementfilter_types"];
	  }
		/* ALTERED - 20100315 - freeform - jeff/julian - stop */
		$element->setVar('ele_filtersettings', $elementFilterSettings);
    
    

		// replaced - start - August 18 2005 - jpc
		//$display = !empty($ele_display) ? 1 : 0;
		if($ele_display[0] == "all")
        {
			$display = 1;        
        }
        else if($ele_display[0] == "none" || $ele_display[1] == "none")
        {
			$display = 0;        
        }
        else
        {
			$display = "," . implode(",", $ele_display) . ",";
        }
		//var_dump($ele_display); echo $display; die();
		// replaced - end - August 18 2005 - jpc


		$element->setVar('ele_display', $display);
                
                if($ele_disabled[0] == "all"){
			$disabled = 1;        
                } else if($ele_disabled[0] == "none" || $ele_disabled[1] == "none"){
			$disabled = 0;        
                } else {
			$disabled = "," . implode(",", $ele_disabled) . ",";
                }
                $element->setVar('ele_disabled', $disabled);
                
		$element->setVar('ele_type', $ele_type);
		//$element->setVar('poids',$poids);
		switch($ele_type){
			case 'text':
				$value = array();
				$value[] = !empty($ele_value[0]) ? intval($ele_value[0]) : $xoopsModuleConfig['t_width'];
				$value[] = !empty($ele_value[1]) ? intval($ele_value[1]) : $xoopsModuleConfig['t_max'];
				$value[] = $ele_value[2];
				$value[] = $ele_value[3];
				
				// formlink option added to textboxes June 20 2006 -- jwe
				if($_POST['formlink'] != "none") {
					$value[] = $_POST['formlink'];
				} else {
					$value[] = "";
				}

			  $value[] = $ele_value[5];
				$value[] = $ele_value[6];
				$value[] = $ele_value[7];
				$value[] = $ele_value[8];
				
				$value[] = $ele_value[9]; // require unique

			break;
			case 'textarea':
				$value = array();
				$value[] = $ele_value[0];
				if( intval($ele_value[1]) != 0 ){
					$value[] = intval($ele_value[1]);
				}else{
					$value[] = $xoopsModuleConfig['ta_rows'];
				}
				if( intval($ele_value[2]) != 0 ){
					$value[] = intval($ele_value[2]);
				}else{
					$value[] = $xoopsModuleConfig['ta_cols'];
				}
				// formlink option added to textboxes June 20 2006 -- jwe
				if($_POST['formlink'] != "none") {
					$value[] = $_POST['formlink'];
				} else {
					$value[] = "";
				}
				
			break;
			case 'areamodif': // leftright display
				$value = array();
				$value[] = $ele_value[0];
			break;
			case 'ib': // added June 20 2005 -- span both column display
				$value[] = $ele_value[0];
				$value[] = $ele_value[1];
			break;
			case 'checkbox':
				$checked = $_POST['checked']; // note that $_POST is blindly parsed into a new set of variables at the top of this file, so this line is strictly speaking unnecessary but it aids readability
				$value = array();
				list($ele_value, $uitext) = formulize_extractUIText($ele_value);
				while( $v = each($ele_value) ){
					//if( !empty($v['value']) ){
					if( $v['value'] !== "" ){
						if( $checked[$v['key']] == 1 ){
							$check = 1;
						}else{
							$check = 0;
						}
						$value[$v['value']] = $check;
					}
				}
			break;
			case 'radio':
				// added this next line to actually set checked so radio button defaults are set correctly. -- jwe 7/28/04
				$checked = $_POST['checked'];
				$value = array();
				list($ele_value, $uitext) = formulize_extractUIText($ele_value);
				while( $v = each($ele_value) ){
					/*print "<br>debug:<br>v = ";
					print_r ($v);
					print "<br>v[value] =";
					print_r($v['value']);
					print "<br>v[key] =";
					print_r($v['key']);
					print "<br>checked = $checked";*/

					//if( !empty($v['value']) OR $v['value'] === 0 OR $v['value'] === "0"){
			    if( $v['value'] !== "" ){
						// added if loop below to account for the similarity of checked =0 (first element) or checked is not set (no defaults)
						if(isset($checked))
						{
							if( $checked == $v['key'] ){
								$value[$v['value']] = 1;
							}else{
								$value[$v['value']] = 0;
							}
						}
						else
						{
							$value[$v['value']] = 0;
						}
					}
				}
				/*print "<br><br>Final output: ";
				print_r ($value);*/
			break;
			// this case altered to properly accept cases with no default value.
			case 'yn':
				$value = array();
				if( $ele_value == '_NO' ){
					$value = array('_YES'=>0,'_NO'=>1);
				}elseif ( $ele_value == '_YES' ){
					$value = array('_YES'=>1,'_NO'=>0);
				}else {
					$value = array('_YES'=>0,'_NO'=>0);
				}
			break;
			case 'date':
				$value = array();
				if($ele_value != "YYYY-mm-dd" AND $ele_value != "") { 
					$ele_value = date("Y-m-d", strtotime($ele_value)); 
				} else {
					$ele_value = "";
				}
				$value[] = $ele_value;
			break;
			case 'sep':
				$value = array();
				$value[] = $ele_value[0];
				if( intval($ele_value[1]) != 0 ){
					$value[] = intval($ele_value[1]);
				}else{
					$value[] = $xoopsModuleConfig['ta_rows'];
				}
				if( intval($ele_value[2]) != 0 ){
					$value[] = intval($ele_value[2]);
				}else{
					$value[] = $xoopsModuleConfig['ta_cols'];
				}
				if ($option[0]) {$value[0] = '<center>'.$value[0].'</center>';}
				if ($option[1]) {$value[0] = '<u>'.$value[0].'</u>';}
				if ($option[2]) {$value[0] = '<I>'.$value[0].'</I>';}
				// $value[0] = '<h5>'.$value[0].'</h5>'; // jwe 01/05/05 -- deemed a bug
				
				$value[0] = '<font color='.$couleur.'>'.$value[0].'</font>';
				
			break;
			case 'select':
				$value = array();
				$value[0] = $ele_value[0]>1 ? intval($ele_value[0]) : 1;
				$value[1] = !empty($ele_value[1]) ? 1 : 0;
				$value[3] = implode(",", $_POST['formlink_scope']); // added august 30 2006
				$value[4] = $_POST['linkscopelimit'];
        $value[6] = $_POST['linkscopeanyall'];
        
        // check for conditions...added jwe feb 6 2008
        if((isset($_POST['formlink']) AND $_POST['formlink'] != "none") OR $ele_value[2][0] === "{FULLNAMES}" OR $ele_value[2][0] === "{USERNAMES}") {
          if($_POST['setfor'] == "con") {
            
            if($_POST['new_term'] !== "") { // add the last specified condition if there was one
              $_POST['elements'][] = $_POST['new_element'];
              $_POST['ops'][] = $_POST['new_op'];
              $_POST['terms'][] = $_POST['new_term'];
            }

            $value[5] = array(0=>$_POST['elements'], 1=>$_POST['ops'], 2=>$_POST['terms']); 
          } else {
            $value[5] = "";
          }
        } else {
          $value[5] = "";
        }
				// check to see if a link to another form was made and if so put in a marker that will be picked up at render time and handled accordingly...  -- jwe 7/29/04
				if(isset($_POST['formlink']) AND $_POST['formlink'] != "none")
				{
					// $value[2] = stripslashes($_POST['formlink']);
					// now receiving an ele_id due to the effects of xlanguage, so get the real caption out of the DB
					$sql_link = "SELECT ele_caption, id_form, ele_handle FROM " . $xoopsDB->prefix("formulize") . " WHERE ele_id = " . intval($_POST['formlink']);
					$res_link = $xoopsDB->query($sql_link);
					$array_link = $xoopsDB->fetchArray($res_link);
					$value[2] = $array_link['id_form'] . "#*=:*" . $array_link['ele_handle'];
         
				} 
				else
				{
				$v2 = array();
				$multi_flag = 1;
				list($ele_value[2], $uitext) = formulize_extractUIText($ele_value[2]);
				while( $v = each($ele_value[2]) ){
					if( $v['value'] !== "" ){
						if( $value[1] == 1 || $multi_flag ){
							if( $checked[$v['key']] == 1 ){
								$check = 1;
								$multi_flag = 0;
							}else{
								$check = 0;
							}
						}else{
							$check = 0;
						}
						$v2[$v['value']] = $check;
					}
				}
				$value[2] = $v2;
				} // end of formlink check -- jwe
			break;
			// subform added September 4 2006
			case 'subform':
				$value[0] = $_POST['subform'];
				$value[1] = $_POST['subformelements'] ? implode(",",$_POST['subformelements']) : "";
        $value[2] = intval($_POST['subformblanks']);
				$value[3] = intval($_POST['showviewbuttons']); 
			break;
			// grid added January 19 2007
			case 'grid':
				foreach($ele_value as $key=>$val) {
					$value[$key] = get_magic_quotes_gpc() ? stripslashes($val) : $val;
				}
			break;
			// derived added March 27 2007
			case 'derived':
				$ele_value[0] = get_magic_quotes_gpc() ? stripslashes($ele_value[0]) : $ele_value[0];
				$value[0] = $ele_value[0];
				// Added for number formatting values 2008-10-31 kw
				$value[1] = $ele_value[1];
				$value[2] = $ele_value[2];
				$value[3] = $ele_value[3];
				$value[4] = $ele_value[4];	
			break;
			case 'upload': 
				$value = array();
				$v2 = array();
				$value[] = $ele_value[0];
				$value[] = $ele_value[1];
				while( $v = each($ele_value[2]) ){
					if( !empty($v) ) $v2[] = $v;
				}
				$value[] = $v2;
			break;
			default:
			if(file_exists(XOOPS_ROOT_PATH."/modules/formulize/class/".$ele_type.".php")) {
				$elementTypeHandler = xoops_getmodulehandler($ele_type);
				list($value, $uitext) = $elementTypeHandler->adminSave($ele_value);
			}
			break;
		}
		
		// check to see if we should be reassigning user submitted values, and if so, trap the old ele_value settings, and the new ones, and then pass off the job to the handling function that does that change
		if(isset($_POST['changeuservalues']) AND $_POST['changeuservalues']==1) {
			if(!isset($data_handler)) {
				include_once XOOPS_ROOT_PATH . "/modules/formulize/class/data.php";
				$data_handler = new formulizeDataHandler($id_form);
			}
			switch($ele_type) {
				case "radio":
				case "check":
					$newValues = $value;
					break;
				case "select":
					$newValues = $value[2];
					break;
			}
			if(!$changeResult = $data_handler->changeUserSubmittedValues($ele_id, $newValues)) {
				print "Error updating user submitted values for the options in element $ele_id";
			}
		}
		
		
		$element->setVar('ele_uitext', $uitext);
		$element->setVar('ele_value', $value);
		$element->setVar('id_form', $id_form);
       
		if( !$formulize_mgr->insert($element) ){
			xoops_cp_header();
			echo $element->getHtmlErrors();
		}else{
			
      $ele_id = $element->getVar('ele_id'); // ele_id set inside the insert method after writing to database
			// don't let ele_handle be blank
			if($element->getVar('ele_handle') == "") {
            $ele_handle = $ele_id;
            $form_handler =& xoops_getmodulehandler('forms');
            while(!$uniqueCheck = $form_handler->isHandleUnique($ele_handle, $ele_id)) {
                  $ele_handle = $ele_handle . "_copy";
            }	    
						$element->setVar('ele_handle', $ele_handle); 
						if( !$formulize_mgr->insert($element) ){
									xoops_cp_header();
									echo $element->getHtmlErrors();
						}
			}
      
      if($original_handle) { // rewrite references in other elements to this handle (linked selectboxes)
				$ele_handle_len = strlen($ele_handle) + 5 + strlen($id_form);
				$orig_handle_len = strlen($original_handle) + 5 + strlen($id_form);
				$lsbHandleFormDefSQL = "UPDATE " . $xoopsDB->prefix("formulize") . " SET ele_value = REPLACE(ele_value, 's:$orig_handle_len:\"$id_form#*=:*$original_handle', 's:$ele_handle_len:\"$id_form#*=:*$ele_handle') WHERE ele_value LIKE '%$id_form#*=:*$original_handle%'"; // must include the cap lengths or else the unserialization of this info won't work right later, since ele_value is a serialized array!
				if($ele_handle != $original_handle) {
					if(!$res = $xoopsDB->query($lsbHandleFormDefSQL)) {
						print "Error:  update of linked selectbox element definitions failed.";
					}
				}
			}

			if($newFieldNeeded) {
        global $xoopsDB;
			      // figure out what the data type should be.
			      // the rules:
            // if it's encrypted, it's a BLOB, otherwise...
			      // date fields get 'date'
            // colorpicker gets 'text'
			      // for text element types...
			      // if 'text' is the specified type, and it's numbers only with a decimal, then use decimal with that number of spaces
			      // if 'text' is the specified type, and it's numbers only with 0 decimals, then use int
			      // all other cases, use the specified type
            if($encrypt) {
              $dataType = 'blob';
            } else {
              switch($ele_type) {
                    case 'date':
                          $dataType = 'date';
                          break;
                    case 'colorpick':
                          $dataType = 'text';
                          break;
									  case 'yn':
												  $dataType = 'int'; // they are stored as 1 and 2
													break;
                    case 'text':
                          if($ele_value[3] == 1 AND $_POST['element_datatype'] == 'text') { // numbers only...and Formulize was asked to figure out the right datatype.....
                                if($datadecimals = intval($ele_value[5])) {
                                      if($datadecimals > 20) { // mysql only allows a certain number of digits in a decimal datatype, so we're making some arbitrary size limitations
                                            $datadecimals = 20;
                                      }
                                      $datadigits = $datadecimals < 10 ? 11 : $datadecimals + 1; // digits must be larger than the decimal value, but a minimum of 11
                                      $dataType = "decimal($datadigits,$datadecimals)"; 
                                } else {
                                      $dataType = 'int(10)'; // value in () is just the visible number of digits to use in a mysql console display
                                }
                          } else {
                                $dataType = getRequestedDataType();
                          }
                          break;
                    default:
                          $dataType = getRequestedDataType();
              }
            }
			   
				$form_handler =& xoops_getmodulehandler('forms', 'formulize');
        if(!$insertResult = $form_handler->insertElementField($element, $dataType)) {
          exit("Error: could not add the new element to the data table in the database.");
        }
      }	elseif(($original_handle != $ele_handle OR (isset($_POST['element_default_datatype']) AND $_POST['element_datatype'] != $_POST['element_default_datatype'])) AND $databaseElement) {
						// figure out if the datatype needs changing...
						if(isset($_POST['element_default_datatype']) AND $_POST['element_datatype'] != $_POST['element_default_datatype']) {
									$dataType = getRequestedDataType();
						} else {
									$dataType = false;
						}
						// need to update the name of the field in the data table
						$form_handler =& xoops_getmodulehandler('forms', 'formulize');
						if(!$updateResult = $form_handler->updateField($element, $original_handle, $dataType)) {
									print "Error: could not update the data table field name to match the new data handle";
						}
			}
			
			// need to serialize the ele_value and uitext now, since it was put into the element object as an array, but the writing operation will handle the serialization so it's ok in the DB, but meanwhile it's still an array in the object.
      // we need to serialize it so that it will be retrieved properly later.
      $element->setVar('ele_value', serialize($value));
			$element->setVar('ele_uitext', serialize($uitext));
			
    }


// this function returns the datatype requested for this element
function getRequestedDataType() {
			switch($_POST['element_datatype']) {
						case 'decimal':
												if($datadecimals = intval($_POST['element_datatype_decimalsize'])) {
															if($datadecimals > 20) {
																		$datadecimals = 20;
															}
												} else {
															$datadecimals = 2;
												}
												$datadigits = $datadecimals < 10 ? 11 : $datadecimals + 1; // digits must be larger than the decimal value, but a minimum of 11
												$dataType = "decimal($datadigits,$datadecimals)";
												break;
									case 'int':
												$dataType = 'int(10)';
												break;
									case 'varchar':
												if(!$varcharsize = intval($_POST['element_datatype_varcharsize'])) {
														$varcharsize = 255;  
												}
												$varcharsize = $varcharsize > 255 ? 255 : $varcharsize;
												$dataType = "varchar($varcharsize)";
												break;
									case 'char':
												if(!$charsize = intval($_POST['element_datatype_charsize'])) {
														$charsize = 255;  
												}
												$charsize = $charsize > 255 ? 255 : $charsize;
												$dataType = "char($charsize)";
												break;
									case 'text':
												$dataType = 'text';
												break;
									default:
												exit("ERROR: unrecognized datatype has been specified: ".strip_tags(htmlspecialchars($_POST['element_datatype'])));
			}
			return $dataType;
}


      ?>
