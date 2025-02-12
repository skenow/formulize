<?php
/**
* Generates pagination
*
* @copyright	http://www.xoops.org/ The XOOPS Project
* @copyright	XOOPS_copyrights.txt
* @copyright	http://www.impresscms.org/ The ImpressCMS Project
* @license	LICENSE.txt
* @package	core
* @since	XOOPS
* @author	http://www.xoops.org The XOOPS Project
* @author	modified by UnderDog <underdog@impresscms.org>
* @version	$Id: pagenav.php 9328 2009-09-05 10:26:36Z pesianstranger $
*/

/**
 * Class to facilitate navigation in a multi page document/list
 *
 * @package		kernel
 * @subpackage	util
 *
 * @author		Kazumi Ono 	<onokazu@xoops.org>
 * @copyright	(c) 2000-2003 The Xoops Project - www.xoops.org
 */
class XoopsPageNav
{
	/**#@+
	 * @access	private
	 */

  /**
   * @public int $total  Total of pages to show
   */
	public $total;

  /**
   * @public int $perpage  Total of items to show per page
   */
	public $perpage;

  /**
   * @public int $current  What is the current page
   */
	public $current;

  /**
   * @public string $url   What is the current url
   */
	public $url;
	/**#@-*/

	/**
	 * Constructor
	 *
	 * @param   int	 $total_items	Total number of items
	 * @param   int	 $items_perpage  Number of items per page
	 * @param   int	 $current_start  First item on the current page
	 * @param   string  $start_name	 Name for "start" or "offset"
	 * @param   string  $extra_arg	  Additional arguments to pass in the URL
	 **/
	function XoopsPageNav($total_items, $items_perpage, $current_start, $start_name="start", $extra_arg="")
	{
		$this->total = intval($total_items);
		$this->perpage = intval($items_perpage);
		$this->current = intval($current_start);
		if ( $extra_arg != '' && ( substr($extra_arg, -5) != '&amp;' || substr($extra_arg, -1) != '&' ) ) {
			$extra_arg .= '&amp;';
		}
		$this->url = $_SERVER['PHP_SELF'].'?'.$extra_arg.trim($start_name).'=';
	}

	/**
	 * Create text navigation
	 *
	 * @param   integer $offset
	 * @return  string
	 **/
	function renderNav($offset = 4)
	{	
		global $icmsConfigPersona, $xoTheme;
		
		$style = (isset($icmsConfigPersona['pagstyle']) && file_exists(ICMS_LIBRARIES_PATH . '/paginationstyles/paginationstyles.php'))?$icmsConfigPersona['pagstyle']:'default';
		$ret = '';
		if(isset($xoTheme)){
		$xoTheme->addStylesheet(ICMS_LIBRARIES_URL . '/paginationstyles/css/'.$icmsConfigPersona['pagstyle'].'.css', array("media" => "all"));
		}else{
			echo'<link rel="stylesheet" type="text/css" href="'.ICMS_LIBRARIES_URL . '/paginationstyles/css/'.$icmsConfigPersona['pagstyle'].'.css" />';
		} 
		if ( $this->total <= $this->perpage ) {
			return $ret;
		}
		$total_pages = ceil($this->total / $this->perpage);
		if ( $total_pages > 1 ) {
			$prev = $this->current - $this->perpage;
			if ( $prev >= 0 ) {
				$ret .= '<a href="'.$this->url.$prev.'">'.(( defined('_ADM_USE_RTL') && _ADM_USE_RTL )?"&#9658; ":"&#9668; ").''._PREV.'</a> ';
			}else{
				$ret .= '<span class="disabled"><b>'.(( defined('_ADM_USE_RTL') && _ADM_USE_RTL )?"&#9658; ":"&#9668; ").''._PREV.'</b></span> ';
			}
			$counter = 1;
			$current_page = intval(floor(($this->current + $this->perpage) / $this->perpage));
			while ( $counter <= $total_pages ) {
				if ( $counter == $current_page ) {
					$ret .= '<span class="current"><b>'.(($style == 'default')?'(':'').icms_conv_nr2local($counter).(($style == 'default')?')':'').'</b></span> ';
				} elseif ( ($counter > $current_page-$offset && $counter < $current_page + $offset ) || $counter == 1 || $counter == $total_pages ) {
					if ( $counter == $total_pages && $current_page < $total_pages - $offset ) {
						$ret .= '... ';
					}
					$ret .= '<a href="'.$this->url.(($counter - 1) * $this->perpage).'">'.icms_conv_nr2local($counter).'</a> ';
					if ( $counter == 1 && $current_page > 1 + $offset ) {
						$ret .= '... ';
					}
				}
				$counter++;
			}
			$next = $this->current + $this->perpage;
			if ( $this->total > $next ) {
				$ret .= '<a href="'.$this->url.$next.'">'._NEXT.''.(( defined('_ADM_USE_RTL') && _ADM_USE_RTL )?" &#9668;":" &#9658;").'</a> ';
			}else{
				$ret .= '<span class="disabled"><b>'._NEXT.''.(( defined('_ADM_USE_RTL') && _ADM_USE_RTL )?" &#9668;":" &#9658;").'</b></span> ';
			}
		}
		return '<div class="pagination '.$style.'">'.$ret.'</div>';
	}

	/**
	 * Create a navigational dropdown list
	 *
	 * @param   boolean	 $showbutton Show the "Go" button?
	 * @return  string
	 **/
	function renderSelect($showbutton = false)
	{
		if ( $this->total < $this->perpage ) {
			return;
		}
		$total_pages = ceil($this->total / $this->perpage);
		$ret = '';
		if ( $total_pages > 1 ) {
   			$ret = '<form name="pagenavform">';
			$ret .= '<select name="pagenavselect" onchange="location=this.options[this.options.selectedIndex].value;">';
			$counter = 1;
			$current_page = intval(floor(($this->current + $this->perpage) / $this->perpage));
			while ( $counter <= $total_pages ) {
				if ( $counter == $current_page ) {
					$ret .= '<option value="'.$this->url.(($counter - 1) * $this->perpage).'" selected="selected">'.icms_conv_nr2local($counter).'</option>';
				} else {
					$ret .= '<option value="'.$this->url.(($counter - 1) * $this->perpage).'">'.icms_conv_nr2local($counter).'</option>';
				}
				$counter++;
			}
			$ret .= '</select>';
			if ($showbutton) {
				$ret .= '&nbsp;<input type="submit" value="'._GO.'" />';
			}
			$ret .= '</form>';
		}
		return $ret;
	}

	/**
	 * Create navigation with images
	 *
	 * @param   integer	 $offset
	 * @return  string
	 **/
	function renderImageNav($offset = 4)
	{
		if ( $this->total < $this->perpage ) {
			return;
		}
		$total_pages = ceil($this->total / $this->perpage);
		$ret = '';
		if ( $total_pages > 1 ) {
   			$ret = '<table><tr>';
			$prev = $this->current - $this->perpage;
			if ( $prev >= 0 ) {
				$ret .= '<td class="pagneutral"><a href="'.$this->url.$prev.'">&lt;</a></td><td><img src="'.ICMS_URL.'/images/blank.gif" width="6" alt="" /></td>';
			} else {
				$ret .= '<td class="pagno"></a></td><td><img src="'.ICMS_URL.'/images/blank.gif" width="6" alt="" /></td>';
			}
			$counter = 1;
			$current_page = intval(floor(($this->current + $this->perpage) / $this->perpage));
			while ( $counter <= $total_pages ) {
				if ( $counter == $current_page ) {
					$ret .= '<td class="pagact"><b>'.$counter.'</b></td>';
				} elseif ( ($counter > $current_page-$offset && $counter < $current_page + $offset ) || $counter == 1 || $counter == $total_pages ) {
					if ( $counter == $total_pages && $current_page < $total_pages - $offset ) {
						$ret .= '<td class="paginact">...</td>';
					}
					$ret .= '<td class="paginact"><a href="'.$this->url.(($counter - 1) * $this->perpage).'">'.$counter.'</a></td>';
					if ( $counter == 1 && $current_page > 1 + $offset ) {
						$ret .= '<td class="paginact">...</td>';
					}
				}
				$counter++;
			}
			$next = $this->current + $this->perpage;
			if ( $this->total > $next ) {
				$ret .= '<td><img src="'.ICMS_URL.'/images/blank.gif" width="6" alt="" /></td><td class="pagneutral"><a href="'.$this->url.$next.'">&gt;</a></td>';
			} else {
				$ret .= '<td><img src="'.ICMS_URL.'/images/blank.gif" width="6" alt="" /></td><td class="pagno"></td>';
			}
			$ret .= '</tr></table>';
		}
		return $ret;
	}
}

?>