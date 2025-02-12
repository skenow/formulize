<?php
/**
* Admin header file
*
* This file is included in all pages of the admin side and being so, it proceeds to a few
* common things.
*
* @copyright	The ImpressCMS Project <http://www.impresscms.org>
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.3
* @author		Gustavo Pilla (aka nekro) <nekro@impresscms.org>
* @package		profile
* @version		$Id$
*/

include_once '../../../include/cp_header.php';

include_once ICMS_ROOT_PATH.'/modules/' . basename(dirname(dirname(__FILE__))) .'/include/common.php';
if( !defined("PROFILE_ADMIN_URL") ) define('PROFILE_ADMIN_URL', PROFILE_URL . "admin/");
?>
