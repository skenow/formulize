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
* @version		$Id: search.php 9343 2009-09-06 14:47:26Z Phoenyx $
*/

$xoopsOption['pagetype'] = "search";

include 'mainfile.php';

if ($icmsConfigSearch['enable_search'] == false) {
    header('Location: '.ICMS_URL.'/index.php');
    exit();
}

$search_limiter = (($icmsConfigSearch['enable_deep_search'] == true) ? $icmsConfigSearch['num_shallow_search'] : false);
$xoopsOption['template_main'] = 'system_search.html';
include ICMS_ROOT_PATH.'/header.php';

$action = 'search';
if(!empty($_GET['action'])) {$action = trim(StopXSS($_GET['action']));}
elseif(!empty($_POST['action'])) {$action = trim(StopXSS($_POST['action']));}
$query = '';
if(!empty($_GET['query'])) {$query = StopXSS($_GET['query']);}
elseif(!empty($_POST['query'])) {$query = StopXSS($_POST['query']);}
$andor = 'AND';
if(!empty($_GET['andor'])) {$andor = StopXSS($_GET['andor']);}
elseif(!empty($_POST['andor'])) {$andor = StopXSS($_POST['andor']);}
$mid = $uid = $start = 0;
if(!empty($_GET['mid'])) {$mid = intval($_GET['mid']);}
elseif(!empty($_POST['mid'])) {$mid = intval($_POST['mid']);}
if(!empty($_GET['uid'])) {$uid = intval($_GET['uid']);}
elseif(!empty($_POST['uid'])) {$uid = intval($_POST['uid']);}
if(!empty($_GET['start'])) {$start = intval($_GET['start']);}
elseif(!empty($_POST['start'])) {$start = intval($_POST['start']);}

$xoopsTpl->assign("start", $start + 1);

$queries = array();

if ($action == "results") {
	if ($query == "") {
		redirect_header("search.php",1,_SR_PLZENTER);
		exit();
	}
} elseif ($action == "showall") {
	if ($query == "" || empty($mid)) {
		redirect_header("search.php",1,_SR_PLZENTER);
		exit();
	}
} elseif ($action == "showallbyuser") {
	if (empty($mid) || empty($uid)) {
		redirect_header("search.php",1,_SR_PLZENTER);
		exit();
	}
}

global $icmsUser;
$groups = is_object($icmsUser) ? $icmsUser -> getGroups() : XOOPS_GROUP_ANONYMOUS;
$gperm_handler = & xoops_gethandler( 'groupperm' );
$available_modules = $gperm_handler->getItemIds('module_read', $groups);

$xoopsTpl->assign('basic_search', false);
if ($action == 'search') {
	// This area seems to handle the 'just display the advanced search page' part.
	$search_form = include 'include/searchform.php';
	$xoopsTpl->assign('search_form', $search_form);
	$xoopsTpl->assign('basic_search', true);
	$xoopsTpl->assign('xoops_pagetitle', _SEARCH);
	include ICMS_ROOT_PATH.'/footer.php';
	exit();
}

if ( $andor != "OR" && $andor != "exact" && $andor != "AND" ) {
	$andor = "AND";
}
if ($andor == 'OR') $label_andor = _SR_ANY;
if ($andor == 'AND') $label_andor = _SR_ALL;
if ($andor == 'exact') $label_andor = _SR_EXACT;
$xoopsTpl->assign("label_search_type", _SR_TYPE . ':');
$xoopsTpl->assign("search_type", $label_andor);

$myts =& MyTextSanitizer::getInstance();
if ($action != 'showallbyuser') {
	if ( $andor != "exact" ) {
		$ignored_queries = array(); // holds kewords that are shorter than allowed minmum length  
		
		preg_match_all('/(?:").*?(?:")|(?:\').*?(?:\')/', $query,$compostas);
		$res = $simpl = array();
		foreach ($compostas[0] as $comp){
			$res[] = substr($comp,1,strlen($comp)-3);
		}
		$compostas = $res;
	
		$simples = preg_replace('/(?:").*?(?:")|(?:\').*?(?:\')/', '', $query);
		$simples = preg_split('/[\s,]+/', $simples);
	
		if (count($simples) > 0){
			foreach ($simples as $k=>$v){
				if ($v != "\\"){
					$simpl[] = $v;
				}
			}
			$simples = $simpl;
		}
	
		if (count($compostas) > 0 && count($simples) > 0){
			$temp_queries = array_merge($simples,$compostas);
		}elseif (count($compostas) <= 0 && count($simples) > 0){
			$temp_queries = $simples;
		}elseif (count($compostas) > 0 && count($simples) <= 0){
			$temp_queries = $compostas;
		}else{
			$temp_queries = array();
		}
	
		foreach ($temp_queries as $q) {
			$q = trim($q);
			if (strlen($q) >= $icmsConfigSearch['keyword_min']) {
				$queries[] = $myts->addSlashes($q);
			} else {
				$ignored_queries[] = $myts->addSlashes($q);
			}
		}
	
		if (count($queries) == 0) {
			redirect_header('search.php', 2, sprintf(_SR_KEYTOOSHORT, icms_conv_nr2local($icmsConfigSearch['keyword_min'])));
			exit();
		}
	} else {
		$query = trim($query);
		if (strlen($query) < $icmsConfigSearch['keyword_min']) {
			redirect_header('search.php', 2, sprintf(_SR_KEYTOOSHORT, icms_conv_nr2local($icmsConfigSearch['keyword_min'])));
			exit();
		}
		$queries = array($myts->addSlashes($query));
	}
}
$xoopsTpl->assign("label_search_results", _SR_SEARCHRESULTS);

// Keywords section.
$xoopsTpl->assign("label_keywords", _SR_KEYWORDS . ':');
$keywords = array();
$ignored_keywords = array();
foreach ($queries as $q) {
	$keywords[] = htmlspecialchars(stripslashes($q));
}

if (!empty($ignored_queries)) {
	$xoopsTpl->assign("label_ignored_keywords", sprintf(_SR_IGNOREDWORDS, $icmsConfigSearch['keyword_min']));
	foreach ($ignored_queries as $q) {
		$ignored_keywords[] = htmlspecialchars(stripslashes($q));
	}
	$xoopsTpl->assign("ignored_keywords", $ignored_keywords);
}
$xoopsTpl->assign("searched_keywords", $keywords);

$all_results = array();
$all_results_counts = array();
switch ($action) {
	case "results":
		$max_results_per_page = intval($icmsConfigSearch['num_shallow_search']);
		$module_handler =& xoops_gethandler('module');
		$criteria = new CriteriaCompo(new Criteria('hassearch', 1));
		$criteria->add(new Criteria('isactive', 1));
		$criteria->add(new Criteria('mid', "(".implode(',', $available_modules).")", 'IN'));
		$modules =& $module_handler->getObjects($criteria, true);
		$mids = isset($_REQUEST['mids']) ? $_REQUEST['mids'] : array();
		if (empty($mids) || !is_array($mids)) {
			unset($mids);
			$mids = array_keys($modules);
		}

		foreach ($mids as $mid) {
			$mid = intval($mid);
			if ( in_array($mid, $available_modules) ) {
		    $module =& $modules[$mid];
		    $results =& $module->search($queries, $andor, $search_limiter, 0);
		    if (!$results) continue;
		    $xoopsTpl->assign("showing", sprintf(_SR_SHOWING, 1, $max_results_per_page));
		    $count = count($results);
		   	$all_results_counts[$module->getVar('name')] = $count;
	
		   	if (!is_array($results) || $count == 0) {
		   		if( $icmsConfigSearch['search_no_res_mod']){$all_results[$module->getVar('name')] = array(); }
		    } else {
					(($count - $start) > $max_results_per_page)? $num_show_this_page = $max_results_per_page: $num_show_this_page = $count - $start;
					for ($i = 0; $i < $num_show_this_page; $i++) {
					  $results[$i]['processed_image_alt_text'] = $myts->displayTarea($module->getVar('name')) . ": ";
	
				    if (isset($results[$i]['image']) && $results[$i]['image'] != "") {
							$results[$i]['processed_image_url'] = "modules/" . $module->getVar('dirname') . "/" . $results[$i]['image'];
				    } else {
							$results[$i]['processed_image_url'] = "images/icons/posticon2.gif";
				    }
	
				    if (isset ($results[$i]['link']) && $results[$i]['link'] != '') {
							if (!preg_match("/^http[s]*:\/\//i", $results[$i]['link'])) {
								$results[$i]['link'] = "modules/".$module->getVar('dirname')."/".$results[$i]['link'];
							}
					    $results[$i]['processed_title'] = $myts->displayTarea($results[$i]['title']);
						}
						/*UnderDog Mark*/
				    if( $icmsConfigSearch['search_user_date']){
							$results[$i]['uid'] = @intval($results[$i]['uid']);
					    if ( !empty($results[$i]['uid']) ) {
								$uname = XoopsUser::getUnameFromId($results[$i]['uid']);
								$results[$i]['processed_user_name'] = $uname;
								$results[$i]['processed_user_url'] = ICMS_URL."/userinfo.php?uid=".$results[$i]['uid'];
					    }
					    $results[$i]['processed_time'] = !empty($results[$i]['time']) ? " (". formatTimestamp(intval($results[$i]['time'])).")" : "";
						}
					}

					if ($icmsConfigSearch['enable_deep_search'] == true) {
						if ( $count > $max_results_per_page) {
							$search_url = ICMS_URL.'/search.php?query='.urlencode(stripslashes(implode(' ', $queries)));
							$search_url .= "&mid=$mid&action=showall&andor=$andor";
						} else {
							$search_url = "";
						}
					} else {
						if ( $count >= $max_results_per_page ) {
							$search_url = ICMS_URL.'/search.php?query='.urlencode(stripslashes(implode(' ', $queries)));
							$search_url .= "&mid=$mid&action=showall&andor=$andor";
						} else {
							$search_url = "";
						}
					}

					$all_results[$module->getVar('name')] = array("search_more_title" => _SR_SHOWALLR, 
					"search_more_url" => htmlspecialchars($search_url), 
					"results" => array_slice($results, 0, $num_show_this_page));
				}
			}
			unset($results);
			unset($module);
		}
	break;

	case "showall":
	case 'showallbyuser':
		$max_results_per_page = intval($icmsConfigSearch['search_per_page']);
		$module_handler =& xoops_gethandler('module');
		$module =& $module_handler->get($mid);
		$results =& $module->search($queries, $andor, 0, $start, $uid);
		$xoopsTpl->assign('xoops_pagetitle', _SR_SEARCHRESULTS);
		//$xoopsTpl->assign("showing", sprintf(_SR_SHOWING, $start + 1, $start + 20));
		$count = count($results);
		$all_results_counts[$module->getVar('name')] = $count;
		if (is_array($results) && $count > 0) {
			(($count - $start) > $max_results_per_page)? $num_show_this_page = $max_results_per_page: $num_show_this_page = $count - $start;
			for ($i = 0; $i < $num_show_this_page; $i++) {
				$results[$i]['processed_image_alt_text'] = $myts->displayTarea($module->getVar('name')) . ": ";
				if (isset($results[$i]['image']) && $results[$i]['image'] != "") {
					$results[$i]['processed_image_url'] = "modules/" . $module->getVar('dirname') . "/" . $results[$i]['image'];
				} else {
					$results[$i]['processed_image_url'] = "images/icons/posticon2.gif";
				}
				if (!preg_match("/^http[s]*:\/\//i", $results[$i]['link'])) {
					$results[$i]['link'] = "modules/".$module->getVar('dirname')."/".$results[$i]['link'];
				}
				$results[$i]['processed_title'] = $myts->displayTarea($results[$i]['title']);
				if( $icmsConfigSearch['search_user_date']){
					$results[$i]['uid'] = @intval($results[$i]['uid']);
					if ( !empty($results[$i]['uid']) ) {
						$uname = XoopsUser::getUnameFromId($results[$i]['uid']);
						$results[$i]['processed_user_name'] = $uname;
						$results[$i]['processed_user_url'] = ICMS_URL."/userinfo.php?uid=".$results[$i]['uid'];
					}
					$results[$i]['processed_time'] = !empty($results[$i]['time']) ? " (". formatTimestamp(intval($results[$i]['time'])).")" : "";
				}
			}

			$search_url_prev = "";
			$search_url_next = "";

			$search_url_base = ICMS_URL.'/search.php?';
			$search_url_get_params = 'query=' . urlencode(stripslashes(implode(' ', $queries)));
			$search_url_get_params .= "&mid=$mid&action=$action&andor=$andor";
			if ($action=='showallbyuser') {
				$search_url_get_params .= "&uid=$uid";
			}
			$search_url = $search_url_base . $search_url_get_params;

			include_once ICMS_ROOT_PATH.'/class/pagenav.php';
			$pagenav = new XoopsPageNav($count, $max_results_per_page, $start, "start", $search_url_get_params);
			$all_results[$module->getVar('name')] = array("results" =>array_slice($results, 0, $num_show_this_page),
			"page_nav" => $pagenav->renderNav());
		} else {
			echo '<p>'._SR_NOMATCH.'</p>';
		}
	break;
}
arsort($all_results_counts);
$xoopsTpl->assign("module_sort_order", $all_results_counts);
$xoopsTpl->assign("search_results", $all_results);

$search_form = include 'include/searchform.php';
$xoopsTpl->assign('search_form', $search_form);

include ICMS_ROOT_PATH."/footer.php";
?>