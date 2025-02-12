<?php
/**
*
* @copyright	http://www.xoops.org/ The XOOPS Project
* @copyright	XOOPS_copyrights.txt
* @copyright	http://www.impresscms.org/ The ImpressCMS Project
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package		core
* @since		XOOPS
* @author		http://www.xoops.org The XOOPS Project
* @author	   Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
* @version		$Id: readpmsg.php 8768 2009-05-16 22:48:26Z pesianstranger $
*/

$xoopsOption['pagetype'] = "pmsg";
include_once "mainfile.php";
include_once ICMS_ROOT_PATH.'/class/module.textsanitizer.php';
$myts =& MyTextSanitizer::getInstance();

if ( !is_object($icmsUser) ) {
    redirect_header("user.php",0);
} else {
    $pm_handler =& xoops_gethandler('privmessage');
    if ( !empty($_POST['delete']) ) {
        if (!$GLOBALS['xoopsSecurity']->check()) {
            echo implode('<br />', $GLOBALS['xoopsSecurity']->getErrors());
            exit();
        }
        $pm =& $pm_handler->get(intval($_POST['msg_id']));
        if (!is_object($pm) || $pm->getVar('to_userid') != $icmsUser->getVar('uid') || !$pm_handler->delete($pm)) {
            exit();
        } else {
            redirect_header("viewpmsg.php",1,_PM_DELETED);
            exit();
        }
    }
    $start = !empty($_GET['start']) ? intval($_GET['start']) : 0;
    $total_messages = !empty($_GET['total_messages']) ? intval($_GET['total_messages']) : 0;
    include ICMS_ROOT_PATH.'/header.php';
    $criteria = new Criteria('to_userid', intval($icmsUser->getVar('uid')));
    $criteria->setLimit(1);
    $criteria->setStart($start);
    $criteria->setSort('msg_time');
    $pm_arr =& $pm_handler->getObjects($criteria);
    echo "<div><h4>". _PM_PRIVATEMESSAGE."</h4></div><br /><a href='userinfo.php?uid=". intval($icmsUser->getVar("uid")) ."'>". _PM_PROFILE ."</a>&nbsp;<span style='font-weight:bold;'>&raquo;&raquo;</span>&nbsp;<a href='viewpmsg.php'>". _PM_INBOX ."</a>&nbsp;<span style='font-weight:bold;'>&raquo;&raquo;</span>&nbsp;\n";
    if (empty($pm_arr)) {
        echo '<br /><br />'._PM_YOUDONTHAVE;
    } else {
        if (!$pm_handler->setRead($pm_arr[0])) {
            //echo "failed";
        }
        echo $pm_arr[0]->getVar("subject")."<br /><form action='readpmsg.php' method='post' name='delete".$pm_arr[0]->getVar("msg_id")."'><table border='0' cellpadding='4' cellspacing='1' class='outer' width='100%'><tr><th colspan='2'>". _PM_FROM ."</th></tr><tr class='even'>\n";
        $poster = new XoopsUser(intval($pm_arr[0]->getVar("from_userid")));
        if ( !$poster->isActive() ) {
            $poster = false;
        }
        echo "<td valign='top'>";
        if ( $poster != false ) { // we need to do this for deleted users
                echo "<a href='userinfo.php?uid=".intval($poster->getVar("uid"))."'>".$poster->getVar("uname")."</a><br />\n";
            if ( $poster->getVar("user_avatar") != "" ) {
                echo "<img src='uploads/".$poster->getVar("user_avatar")."' alt='' /><br />\n";
            }
            if ( $poster->getVar("user_from") != "" ) {
                echo _PM_FROMC."".$poster->getVar("user_from")."<br /><br />\n";
            }
            if ( $poster->isOnline() ) {
            echo "<span style='color:#ee0000;font-weight:bold;'>"._PM_ONLINE."</span><br /><br />\n";
            }
        } else {
            echo $icmsConfig['anonymous']; // we need to do this for deleted users
        }
        echo "</td><td><img src='images/subject/".$pm_arr[0]->getVar("msg_image", "E")."' alt='' />&nbsp;"._PM_SENTC."".formatTimestamp($pm_arr[0]->getVar("msg_time"));
        echo "<hr /><b>".$pm_arr[0]->getVar("subject")."</b><br /><br />\n";
        $var = $pm_arr[0]->getVar('msg_text', 'N');
        echo $myts->displayTarea( $var, 1, 1, 1 ) . "<br /><br /></td></tr><tr class='foot'><td width='20%' colspan='2' align='"._GLOBAL_LEFT."'>";
        // we dont want to reply to a deleted user!
        if ( $poster != false ) {
            echo "<a href='#' onclick='javascript:openWithSelfMain(\"".ICMS_URL."/pmlite.php?reply=1&amp;msg_id=".$pm_arr[0]->getVar("msg_id")."\",\"pmlite\",800,680);'><img src='".ICMS_URL."/images/icons/".$GLOBALS["xoopsConfig"]["language"]."/reply.gif' alt='"._PM_REPLY."' /></a>\n";
        }
        echo "<input type='hidden' name='delete' value='1' />";
        echo $GLOBALS['xoopsSecurity']->getTokenHTML();
        echo "<input type='hidden' name='msg_id' value='".$pm_arr[0]->getVar("msg_id")."' />";
        echo "<a href='#".$pm_arr[0]->getVar("msg_id")."' onclick='javascript:document.delete".$pm_arr[0]->getVar("msg_id").".submit();'><img src='".ICMS_URL."/images/icons/".$GLOBALS["xoopsConfig"]["language"]."/delete.gif' alt='"._PM_DELETE."' /></a>";
        echo "</td></tr><tr><td colspan='2' align='"._GLOBAL_RIGHT."'>";
        $previous = $start - 1;
            $next = $start + 1;
            if ( $previous >= 0 ) {
            echo "<a href='readpmsg.php?start=".intval($previous)."&amp;total_messages=".intval($total_messages)."'>"._PM_PREVIOUS."</a> | ";
        } else {
            echo _PM_PREVIOUS." | ";
        }
        if ( $next < $total_messages ) {
            echo "<a href='readpmsg.php?start=".intval($next)."&amp;total_messages=".intval($total_messages)."'>"._PM_NEXT."</a>";
        } else {
            echo _PM_NEXT;
        }
        echo "</td></tr></table></form>\n";
    }
    include "footer.php";
}
?>