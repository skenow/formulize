<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {resized_image} function plugin for impressCMS
 *
 * Type:     function
 * Name:     resized_image
 * Date:     Feb 24, 2003
 * Purpose:  resizes an image to the specified size and returns a formatted HTML tag for the image
 * Input:
 *         - file = file (and path) of image (required)
 *         - height = image height (optional, default actual height)
 *         - width = image width (optional, default actual width)
 *         - basedir = base directory for absolute paths, default
 *                     is environment variable DOCUMENT_ROOT
 *         - path_prefix = prefix for path output (optional, default empty)
 *		   - fit = The way the image is resized. Possible values:
			� inside (the image will fit inside the new dimensions while maintaining the aspect ratio)
			� fill (the image will completely fit inside the new dimensions)
			� outside (the image will be resized to completely fill the specified rectangle, while still maintaining aspect ratio)
 *
 * Examples: {resized_image file="/images/image.jpg" height=70 width=120 fit="inside"}
 * You can add extra parameters to the tag, such as class, alt or id.
 * {resized_image file="/images/image.jpg" height=70 width=120 fit="inside" class="resized"}
 * @author Ignacio Segura <nacho at pensamientosdivergentes.net>
 * Based on work by Monte Ohrt <monte at ohrt dot com> and Duda <duda@big.hu>
 * @version  1.0
 * @param array
 * @param Smarty
 * @return string
 * @uses smarty_function_escape_special_chars()
 * @uses WideImage library
 */
function smarty_function_resized_image($params, &$smarty)
{
    require_once $smarty->_get_plugin_filepath('shared','escape_special_chars');
    
	// Preparing arrays that will store original and resized image data
	$original = array ();
	$resized = array ();
	
	// Get parameters from the tag
    $alt = '';
    $file = '';
    $height = '';
    $width = '';
    $extra = '';
    $prefix = '';
    $suffix = '';
	$fit = 'inside';
    $path_prefix = '';
    $server_vars = ($smarty->request_use_auto_globals) ? $_SERVER : $GLOBALS['HTTP_SERVER_VARS'];
    $basedir = isset($server_vars['DOCUMENT_ROOT']) ? $server_vars['DOCUMENT_ROOT'] : '';
    foreach($params as $_key => $_val) {
        switch($_key) {
            case 'file':
            case 'height':
            case 'width':
			case 'fit':
            case 'path_prefix':
            case 'basedir':
                $$_key = $_val;
                break;

            case 'alt':
                if(!is_array($_val)) {
                    $$_key = smarty_function_escape_special_chars($_val);
                } else {
                    $smarty->trigger_error("resized_image: extra attribute '$_key' cannot be an array", E_USER_NOTICE);
                }
                break;

            case 'link':
            case 'href':
                $prefix = '<a href="' . $_val . '">';
                $suffix = '</a>';
                break;

            default:
                if(!is_array($_val)) {
                    $extra .= ' '.$_key.'="'.smarty_function_escape_special_chars($_val).'"';
                } else {
                    $smarty->trigger_error("resized_image: extra attribute '$_key' cannot be an array", E_USER_NOTICE);
                }
                break;
        }
    }
	// Checking the existence of required parameters
    if (empty($file)) {
        $smarty->trigger_error("resized_image: missing 'file' parameter", E_USER_ERROR);
        return;
    }
	if (!isset($params['width']) && !isset($params['height'])) {
		$smarty->trigger_error("resized_image: New size was not specified", E_USER_ERROR);
		return;
	}
	// If image resized is 'fit', both height and width are required
	if ($fit == 'fill' && (empty($width) || empty($height) ) ) {
		$smarty->trigger_error("resized_image:  When you choose 'fill' fit, you have to specify both width and height", E_USER_ERROR);
	}
	// Preparing paths
    if (substr($file,0,1) == '/') {
        $original['path'] = $basedir . $file;
		$resized['path'] = ICMS_ROOT_PATH.'/cache'.$file;
		$resized['url'] = ICMS_URL.'/cache'.$file;
    } else {
        $original['path'] = $file;
		$resized['path'] = ICMS_ROOT_PATH.'/cache/'.$file;
		$resized['url'] = ICMS_URL.'/cache/'.$file;
    }
    // Check if original image exists
	if(!$_image_data = @getimagesize($original['path'])) {
		if(!file_exists($original['path'])) {
			$smarty->trigger_error("resized_image: unable to find '".$original['path']."'", E_USER_NOTICE);
			return;
		} else if(!is_readable($original['path'])) {
			$smarty->trigger_error("resized_image: unable to read '".$original['path']."'", E_USER_NOTICE);
			return;
		} else {
			$smarty->trigger_error("resized_image: '".$original['path']."' is not a valid image file", E_USER_NOTICE);
			return;
		}
	}
	// Smarty Security check (comes from Smarty html_image tag, being honest, I don't understand what it does).
	if ($smarty->security &&
		($_params = array('resource_type' => 'file', 'resource_name' => $original['path'])) &&
		(require_once(SMARTY_CORE_DIR . 'core.is_secure.php')) &&
		(!smarty_core_is_secure($_params, $smarty)) ) {
		$smarty->trigger_error("resized_image: (secure) '".$original['path']."' not in secure directory", E_USER_NOTICE);
	}
	
	// Original and resized dimensions
	if(!isset($params['width'])) {
		$original['width'] = $_image_data[0];
	}
	if(!isset($params['height'])) {
		$original['height'] = $_image_data[1];
	}
	
	$resized['width'] = $width;
	$resized['height'] = $height;

	// build resized file name
	$resized['dir'] = substr($resized['path'], 0, strrpos($resized['path'], "/")); // extract path
	$resized['path'] = substr($resized['path'], 0, strrpos($resized['path'], ".") )
		. "-" . $resized['width']. "x" . $resized['height']  
		. substr($resized['path'], strrpos($resized['path'], ".") ); // build path + file name
	$resized['url'] = substr($resized['url'], 0, strrpos($resized['url'], ".") )
		. "-" . $resized['width']. "x" . $resized['height'] 
		. substr($resized['url'], strrpos($resized['url'], ".") ); // build file URL
	
	// If file does not exist
	// or it's outdated, create:
	if (!file_exists($resized['path']) or ( filemtime ($original['path']) !== filemtime ($resized['path']) ) ) { 
		if (!is_dir($resized['dir'])) { // If dir does not exist, create
			mkdir ($resized['dir'], 0755, true);
		}
		// Resize image using WideImage library
		include_once ICMS_LIBRARIES_PATH.'/wideimage/lib/WideImage.php';
		$resized_img = WideImage::load($original['path'], 'jpg');
		$resized_img->resize($resized['width'], $resized['height'], $fit)->saveToFile($resized['path']);
	}

    return $prefix . '<img src="'.$resized['url'].'" alt="'.$alt.'" '.$extra.' />' . $suffix;
}

?>