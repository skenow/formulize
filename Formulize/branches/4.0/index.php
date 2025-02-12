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

// SET $formulize_screen_id IN A PHP BLOCK, AND THEN INCLUDE
// XOOPS_ROOT_PATH . "/modules/formulize/index.php" TO CALL UP
// A SCREEN IN A BLOCK WITHOUT THE ENTIRE XOOPS TEMPLATE COMING IN

// uncomment these two lines to enable benchmarking of performance...depends also on the user id specified in formulize_benchmark in include/extract.php
//include_once XOOPS_ROOT_PATH . "/modules/formulize/include/extract.php";
//$GLOBALS['startPageTime'] = microtime_float();

if(isset($formulize_screen_id)) {
    if(is_numeric($formulize_screen_id)) {
        global $xoTheme;
        if($xoTheme) {
            $xoTheme->addStylesheet("/modules/formulize/templates/css/style.css");
        }
        include 'initialize.php';        
    }
} else {
    require_once "../../mainfile.php";
    include XOOPS_ROOT_PATH.'/header.php';
    global $xoTheme;
    if($xoTheme) {
        $xoTheme->addStylesheet("/modules/formulize/templates/css/style.css");
    }
    include 'initialize.php';

    include XOOPS_ROOT_PATH.'/footer.php';
}


