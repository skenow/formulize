<?php
/**
* Installer No PHP 5 information page
*
* See the enclosed file license.txt for licensing information.
* If you did not receive this file, get it at http://www.fsf.org/copyleft/gpl.html
*
* @copyright    The XOOPS project http://www.xoops.org/
* @license      http://www.fsf.org/copyleft/gpl.html GNU General Public License (GPL)
* @package		installer
* @since        Xoops 2.3.0
* @author		marcan <marcan@impresscms.org>
* @version		$Id: page_no_php5.php 8568 2009-04-11 13:15:53Z icmsunderdog $
*/
/**
 *
 */
require_once 'common.inc.php';
if ( !defined( 'XOOPS_INSTALL' ) )	exit();

	$wizard->setPage( 'no_php5' );
	$pageHasForm = false;

	ob_start();
	echo NO_PHP5_CONTENT;
	$content = ob_get_contents();
	ob_end_clean();

    include 'install_tpl.php';

?>