<?php
/**
* Really old functions within ImpressCMS
*
* @copyright	http://www.xoops.org/ The XOOPS Project
* @copyright	XOOPS_copyrights.txt
* @copyright	http://www.impresscms.org/ The ImpressCMS Project
* @license	LICENSE.txt
* @package	core
* @since	XOOPS
* @author	http://www.xoops.org The XOOPS Project
* @author	modified by UnderDog <underdog@impresscms.org>
* @version	$Id: old_functions.php 9520 2009-11-11 14:32:52Z pesianstranger $
*/


// #################### Block functions from here ##################

/*
 * Purpose : Builds the blocks on both sides
 * Input   : $side = On wich side should the block are displayed?
 *			 0, l, left : On the left side
 *			 1, r, right: On the right side
 *			 other:   Only on one side (
 *						  Call from theme.php makes all blocks on the left side
 *						  and from theme.php for the right site)
 * @param	string	$side	Which side will the sidebar be on?
 */
function make_sidebar($side)
{
	global $icmsUser;
	$xoopsblock = new XoopsBlock();
	if ($side == "left") {
		$side = XOOPS_SIDEBLOCK_LEFT;
	} elseif ($side == "right") {
		$side = XOOPS_SIDEBLOCK_RIGHT;
	} else {
		$side = XOOPS_SIDEBLOCK_BOTH;
	}
	if (is_object($icmsUser)) {
		$block_arr =& $xoopsblock->getAllBlocksByGroup($icmsUser->getGroups(), true, $side, XOOPS_BLOCK_VISIBLE);
	} else {
		$block_arr =& $xoopsblock->getAllBlocksByGroup(ICMS_GROUP_ANONYMOUS, true, $side, XOOPS_BLOCK_VISIBLE);
	}

	$block_count = count($block_arr);
	if (!isset($GLOBALS['xoopsTpl']) || !is_object($GLOBALS['xoopsTpl'])) {
		include_once ICMS_ROOT_PATH.'/class/template.php';
		$xoopsTpl = new XoopsTpl();
	} else {
		$xoopsTpl =& $GLOBALS['xoopsTpl'];
	}
	$xoopsLogger =& XoopsLogger::instance();
	for ($i = 0; $i < $block_count; $i++) {
		$bcachetime = intval($block_arr[$i]->getVar('bcachetime'));
		if (empty($bcachetime)) {
			$xoopsTpl->xoops_setCaching(0);
		} else {
			$xoopsTpl->xoops_setCaching(2);
			$xoopsTpl->xoops_setCacheTime($bcachetime);
		}
		$btpl = $block_arr[$i]->getVar('template');
		if ($btpl != '') {
			if (empty($bcachetime) || !$xoopsTpl->is_cached('db:'.$btpl)) {
				$xoopsLogger->addBlock($block_arr[$i]->getVar('name'));
				$bresult =& $block_arr[$i]->buildBlock();
				if (!$bresult) {
					continue;
				}
				$xoopsTpl->assign_by_ref('block', $bresult);
				$bcontent =& $xoopsTpl->fetch('db:'.$btpl);
				$xoopsTpl->clear_assign('block');
			} else {
				$xoopsLogger->addBlock($block_arr[$i]->getVar('name'), true, $bcachetime);
				$bcontent =& $xoopsTpl->fetch('db:'.$btpl);
			}
		} else {
			$bid = $block_arr[$i]->getVar('bid');
			if (empty($bcachetime) || !$xoopsTpl->is_cached('db:system_dummy.html', 'blk_'.$bid)) {
				$xoopsLogger->addBlock($block_arr[$i]->getVar('name'));
				$bresult =& $block_arr[$i]->buildBlock();
				if (!$bresult) {
					continue;
				}
				$xoopsTpl->assign_by_ref('dummy_content', $bresult['content']);
				$bcontent =& $xoopsTpl->fetch('db:system_dummy.html', 'blk_'.$bid);
				$xoopsTpl->clear_assign('block');
			} else {
				$xoopsLogger->addBlock($block_arr[$i]->getVar('name'), true, $bcachetime);
				$bcontent =& $xoopsTpl->fetch('db:system_dummy.html', 'blk_'.$bid);
			}
		}
		switch ($block_arr[$i]->getVar('side')) {
		case XOOPS_SIDEBLOCK_LEFT:
			themesidebox($block_arr[$i]->getVar('title'), $bcontent);
			break;
		case XOOPS_SIDEBLOCK_RIGHT:
			if (function_exists("themesidebox_right")) {
				themesidebox_right($block_arr[$i]->getVar('title'), $bcontent);
			} else {
				themesidebox($block_arr[$i]->getVar('title'), $bcontent);
			}
			break;
		}
		unset($bcontent);
	}
}

/*
* Function to display center block
* Renders the block to echoes it (no return string)
*/
function make_cblock()
{
	global $icmsUser, $xoopsOption;
	$xoopsblock = new XoopsBlock();
	$cc_block = $cl_block = $cr_block = "";
	$arr = array();
	if ($xoopsOption['theme_use_smarty'] == 0) {
		if (!isset($GLOBALS['xoopsTpl']) || !is_object($GLOBALS['xoopsTpl'])) {
			include_once ICMS_ROOT_PATH.'/class/template.php';
			$xoopsTpl = new XoopsTpl();
		} else {
			$xoopsTpl =& $GLOBALS['xoopsTpl'];
		}
		if (is_object($icmsUser)) {
			$block_arr =& $xoopsblock->getAllBlocksByGroup($icmsUser->getGroups(), true, XOOPS_CENTERBLOCK_ALL, XOOPS_BLOCK_VISIBLE);
		} else {
			$block_arr =& $xoopsblock->getAllBlocksByGroup(ICMS_GROUP_ANONYMOUS, true, XOOPS_CENTERBLOCK_ALL, XOOPS_BLOCK_VISIBLE);
		}
		$block_count = count($block_arr);
		$xoopsLogger =& XoopsLogger::instance();
		for ($i = 0; $i < $block_count; $i++) {
			$bcachetime = intval($block_arr[$i]->getVar('bcachetime'));
			if (empty($bcachetime)) {
				$xoopsTpl->xoops_setCaching(0);
			} else {
				$xoopsTpl->xoops_setCaching(2);
				$xoopsTpl->xoops_setCacheTime($bcachetime);
			}
			$btpl = $block_arr[$i]->getVar('template');
			if ($btpl != '') {
				if (empty($bcachetime) || !$xoopsTpl->is_cached('db:'.$btpl)) {
					$xoopsLogger->addBlock($block_arr[$i]->getVar('name'));
					$bresult =& $block_arr[$i]->buildBlock();
					if (!$bresult) {
						continue;
					}
					$xoopsTpl->assign_by_ref('block', $bresult);
					$bcontent =& $xoopsTpl->fetch('db:'.$btpl);
					$xoopsTpl->clear_assign('block');
				} else {
					$xoopsLogger->addBlock($block_arr[$i]->getVar('name'), true, $bcachetime);
					$bcontent =& $xoopsTpl->fetch('db:'.$btpl);
				}
			} else {
				$bid = $block_arr[$i]->getVar('bid');
				if (empty($bcachetime) || !$xoopsTpl->is_cached('db:system_dummy.html', 'blk_'.$bid)) {
					$xoopsLogger->addBlock($block_arr[$i]->getVar('name'));
					$bresult =& $block_arr[$i]->buildBlock();
					if (!$bresult) {
						continue;
					}
					$xoopsTpl->assign_by_ref('dummy_content', $bresult['content']);
					$bcontent =& $xoopsTpl->fetch('db:system_dummy.html', 'blk_'.$bid);
					$xoopsTpl->clear_assign('block');
				} else {
					$xoopsLogger->addBlock($block_arr[$i]->getVar('name'), true, $bcachetime);
					$bcontent =& $xoopsTpl->fetch('db:system_dummy.html', 'blk_'.$bid);
				}
			}
			$title = $block_arr[$i]->getVar('title');
			switch ($block_arr[$i]->getVar('side')) {
			case XOOPS_CENTERBLOCK_CENTER:
				if ($title != "") {
					$cc_block .= '<tr valign="top"><td colspan="2"><b>'.$title.'</b><hr />'.$bcontent.'<br /><br /></td></tr>'."\n";
				} else {
					$cc_block .= '<tr><td colspan="2">'.$bcontent.'<br /><br /></td></tr>'."\n";
				}
				break;
			case XOOPS_CENTERBLOCK_LEFT:
				if ($title != "") {
					$cl_block .= '<p><b>'.$title.'</b><hr />'.$bcontent.'</p>'."\n";
				} else {
					$cl_block .= '<p>'.$bcontent.'</p>'."\n";
				}
				break;
			case XOOPS_CENTERBLOCK_RIGHT:
				if ($title != "") {
					$cr_block .= '<p><b>'.$title.'</b><hr />'.$bcontent.'</p>'."\n";
				} else {
					$cr_block .= '<p>'.$bcontent.'</p>'."\n";
				}
				break;
			default:
				break;
			}
			unset($bcontent, $title);
		}
		echo '<table width="100%">'.$cc_block.'<tr valign="top"><td width="50%">'.$cl_block.'</td><td width="50%">'.$cr_block.'</td></tr></table>'."\n";
	}
}

/**
* Opens table with forum thread
*
* @param string $width Width of the table
*/
function openThread($width="100%")
{
	echo "<table border='0' cellpadding='0' cellspacing='0' align='center' width='$width'><tr><td class='bg2'><table border='0' cellpadding='4' cellspacing='1' width='100%'><tr class='bg3' align='"._GLOBAL_LEFT."'><td class='bg3' width='20%'>". _CM_POSTER ."</td><td class='bg3'>". _CM_THREAD ."</td></tr>";
}

/**
* Shows thread
*
*/
function showThread($color_number, $subject_image, $subject, $text, $post_date, $ip_image, $reply_image, $edit_image, $delete_image, $username="", $rank_title="", $rank_image="", $avatar_image="", $reg_date="", $posts="", $user_from="", $online_image="", $profile_image="", $pm_image="", $email_image="", $www_image="", $icq_image="", $aim_image="", $yim_image="", $msnm_image="")
{
	if ( $color_number == 1 ) {
		$bg = 'bg1';
	} else {
		$bg = 'bg3';
	}
	echo "<tr align='"._GLOBAL_LEFT."'><td valign='top' class='$bg' nowrap='nowrap'><b>$username</b><br />$rank_title<br />$rank_image<br />$avatar_image<br /><br />$reg_date<br />$posts<br />$user_from<br /><br />$online_image</td>";
	echo "<td valign='top' class='$bg'><table width='100%' border='0'><tr><td valign='top'>$subject_image&nbsp;<b>$subject</b></td><td align='"._GLOBAL_RIGHT."'>".$ip_image."".$reply_image."".$edit_image."".$delete_image."</td></tr>";
	echo "<tr><td colspan='2'><p>$text</p></td></tr></table></td></tr>";
	echo "<tr align='"._GLOBAL_LEFT."'><td class='$bg' valign='middle'>$post_date</td><td class='$bg' valign='middle'>".$profile_image."".$pm_image."".$email_image."".$www_image."".$icq_image."".$aim_image."".$yim_image."".$msnm_image."</td></tr>";
}

/**
* Closes table with forum thread
*
*/
function closeThread()
{
	echo '</table></td></tr></table>';
}
?>