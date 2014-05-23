<?php



global $is_mobile_version;

if ($is_mobile_version){
    
    print "<div data-role=\"page\">";
    print "THIS IS THE GOOD VERSION!!!";
}

// if it's not the mobile version, don't output buttons in a table format
if (!$is_mobile_version) {

        print "<table cellpadding=10><tr><td id='titleTable' style=\"vertical-align: top;\" width=100%>";
        
        // print out title in content div if we're using the mobile version
        print "<h1>" . $settings['translated_title'] . "</h1>";

}

//print "<h1>" . $settings['translated_title'] . "</h1>";


// don't display configure options if we're using the mobile version
if (!$is_mobile_version) {

        if($thisButtonCode = $buttonCodeArray['modifyScreenLink']) { print "$thisButtonCode<br />"; }
}

    if($settings['loadview'] AND $settings['lockcontrols']) {
        print "<h3>" . $settings['loadviewname'] . "</h3></td><td>";
    print "<input type=hidden name=currentview id=currentview value=\"${settings['currentview']}\"></input>\n<input type=hidden name=loadviewname id=loadviewname value=\"${settings['loadviewname']}\"></input>$submitButton";
    }   else {
        
        if (!$is_mobile_version) {
            print "</td>";
        }
            if(!$settings['lockcontrols']) {
                
                // if it's mobile, don't output buttons in a table
                if (!$is_mobile_version) {
    
                    print "<td id='buttonsTable' class='outerTable' rowspan=3 style=\"vertical-align: bottom;\">";        
    
                    print "<table><tr><td id='leftButtonColumn' class='innerTable' style=\"vertical-align: bottom;\">";
                }
    
                if ($is_mobile_version) {
                    print "<div data-role=\"panel\" id=\"buttonpanel\">";
                }
                
                    print "<p>$submitButton<br>";
                if (!$is_mobile_version) {
                    if($settings['atLeastOneActionButton']) {
                            print "<b>" . _formulize_DE_ACTIONS . "</b>";
                    }
                    print "\n";
                }
                            
                    if( $thisButtonCode = $buttonCodeArray['changeColsButton']) { print "<br>$thisButtonCode"; }
                    if( $thisButtonCode = $buttonCodeArray['resetViewButton']) { print "<br>$thisButtonCode"; }
                    // there is a create reports permission, but we are currently allowing everyone to save their own views regardless of that permission.  The publishing permissions do kick in on the save popup.
                    if( $thisButtonCode = $buttonCodeArray['saveViewButton']) { print "<br>$thisButtonCode"; }
                    // you can always create and delete your own reports right now (delete_own_reports perm has no effect).  If can delete other reports, then set $pubstart to 10000 -- which is done above -- (ie: can delete published as well as your own, because the javascript will consider everything beyond the start of 'your saved views' to be saved instead of published (published be thought to never begin))
                    if( $thisButtonCode = $buttonCodeArray['deleteViewButton']) { print "<br>$thisButtonCode"; }
    
                
                //if it's mobile, don't output buttons in a table
                if (!$is_mobile_version) {
                    print "</p></td><td id='middleButtonColumn' class='innerTable' style=\"vertical-align: bottom;\"><p style=\"text-align: center;\">";
                }
                if ($is_mobile_version) {
                    print "<br />";
                }
    
    if (($settings['add_own_entry'] AND $singleMulti[0]['singleentry'] == "") OR ($settings['user_can_delete'] and !$settings['lockcontrols'])) {
                            if( $thisButtonCode = $buttonCodeArray['selectAllButton']) { print "$thisButtonCode"; }
                            if( $thisButtonCode = $buttonCodeArray['clearSelectButton']) { print "<br>$thisButtonCode<br>"; }
                    }
                    if($settings['add_own_entry'] AND $singleMulti[0]['singleentry'] == "") {
                            if( $thisButtonCode = $buttonCodeArray['cloneButton']) { print "$thisButtonCode<br>"; }
                    }
    if ($settings['user_can_delete'] and !$settings['lockcontrols']) {
                            if( $thisButtonCode = $buttonCodeArray['deleteButton']) { print "$thisButtonCode<br>"; }
                    }
    
                // don't output buttons in table for mobile version
                if (!$is_mobile_version) {
                
                    print "</p></td><td id='rightButtonColumn' class='innerTable' style=\"vertical-align: bottom;\"><p style=\"text-align: center;\">";
                }
    
                    if( $thisButtonCode = $buttonCodeArray['calcButton']) { print "<br>$thisButtonCode"; }
                    if( $thisButtonCode = $buttonCodeArray['advCalcButton']) { print "<br>$thisButtonCode"; }
                    if( $thisButtonCode = $buttonCodeArray['advSearchButton']) { print "<br>$thisButtonCode"; }
                    if( $thisButtonCode = $buttonCodeArray['exportButton']) { print "<br>$thisButtonCode"; }
                    if($settings['import_data'] AND !$frid AND $thisButtonCode = $buttonCodeArray['importButton']) { // cannot import into a framework currently
                            print "<br>$thisButtonCode";
                    }
                
                // formatting for mobile version
                if ($is_mobile_version) {
                    print "<br />";
                }
                
                    if( $thisButtonCode = $buttonCodeArray['notifButton']) { print "$thisButtonCode"; } 
                    
                if (!$is_mobile_version) {
                    print "</p>";
                }
                
                
                if (!$is_mobile_version) {
                    print "</td></tr></table></td></tr>\n";
                }
                
            } else { // if lockcontrols set, then write in explanation...
                    print "<td></td></tr></table>";
                    print "<table><tr><td style=\"vertical-align: bottom;\">";
                    print "<input type=hidden name=curviewid id=curviewid value=$curviewid></input>";
                    print "<p>$submitButton<br>" . _formulize_DE_WARNLOCK . "</p>";
                    print "</td></tr>";
            } // end of if controls are locked
    
        // don't make add entry buttons a cell for mobile version
        if (!$is_mobile_version) {
            // cell for add entry buttons
            print "<tr><td id='outerAddEntryPanel' style=\"vertical-align: top;\">\n";
        }
        
    
            if(!$settings['lockcontrols']) {
                    // added October 18 2006 -- moved add entry buttons to left side to emphasize them more
                if (!$is_mobile_version) {
                    print "<table><tr><td id='innerAddEntryPanel' style=\"vertical-align: bottom;\"><p>\n";
                }
    
                    $addButton = $buttonCodeArray['addButton'];
                    $addMultiButton = $buttonCodeArray['addMultiButton'];
                    $addProxyButton = $buttonCodeArray['addProxyButton'];
            
                    if($settings['add_own_entry'] AND $singleMulti[0]['singleentry'] == "" AND ($addButton OR $addMultiButton)) {
                        
                        if (!$is_mobile_version) { print "<b>" . _formulize_DE_FILLINFORM . "</b>\n"; }
                            
                            if( $addButton) { print "<br>$addButton"; } // this will include proxy box if necessary
                            if( $addMultiButton) { print "<br>$addMultiButton"; }
                    } elseif($settings['add_own_entry'] AND $settings['proxy'] AND ($addButton OR $addProxyButton)) { // this is a single entry form, so add in the update and proxy buttons if they have proxy, otherwise, just add in update button
                            print "<b>" . _formulize_DE_FILLINFORM . "</b>\n";
                            if( $addButton) { print "<br>$addButton"; }
                            if( $addProxyButton) { print "<br>$addProxyButton"; }
                    } elseif($settings['add_own_entry'] AND $addButton) {
                            print "<b>" . _formulize_DE_FILLINFORM . "</b>\n";
                            if( $addButton) { print "<br>$addButton"; }
                    } elseif($settings['proxy'] AND $addProxyButton) {
                            print "<b>" . _formulize_DE_FILLINFORM . "</b>\n";
                            if( $addProxyButton) { print "<br>$addProxyButton"; }
                    }
                
                // not a table if it's the mobile version
                if (!$is_mobile_version) {
                    print "<br><br></p></td></tr></table>\n";
                }
            }
            
            if ($is_mobile_version) {
                // closing div for buttonpanel
                print "</div>";
                
                // content div
                print "<div data-role=\"content\">";
                
                // print table title
                print "<h1>" . $settings['translated_title'] . "</h1>";

                // link to buttonpanel
                print "<a href=\"#buttonpanel\" class=\"ui-btn ui-shadow ui-corner-all ui-btn-inline ui-btn-icon-left ui-icon-bars\">Actions</a>";
            }
        
            else {
                print "</td></tr><tr><td id=currentViewSelectTable style=\"vertical-align: bottom;\">";
            }
    
            if ($currentViewList = $buttonCodeArray['currentViewList']) { print $currentViewList; }
        
            // close content div
            //if ($is_mobile_version) {
            // print "</div>";
            //}
    
    } // end of if there's a loadview or not

    
    // regardless of if a view is loaded and/or controls are locked, always print the page navigation controls
if ($pageNavControls = $buttonCodeArray['pageNavControls']) { print $pageNavControls; }


    if (!$is_mobile_version) {
        print "</td></tr></table>";
    }

// close "data-role=page" div
//if ($is_mobile_version) {
//  print "</div>";
//}