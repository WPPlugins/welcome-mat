<?php
namespace MaxInbound;
defined('ABSPATH') or die('No direct access permitted');
/*
Plugin Name: Welcome Mat
Plugin URI: http://welcomemat.io
Description: Welcome Mat email acquisition plugin is one of the most effective ways to build your site's audience  
Version: 1.3
Author: Max Foundry
Author URI: http://maxfoundry.com

Copyright 2016 Max Foundry, LLC (http://maxfoundry.com)
*/

// Classes
const WM_VERSION_NUM = '1.3'; 
const WM_ROOT_FILE = __FILE__; 

if (! function_exists('maxinbound_php52_nono'))
{
	function maxinbound_php52_nono()
	{
		$message = sprintf( __("Welcome Mat requires at least PHP version 5.3. You are running version: %s ","maxinbound"), PHP_VERSION);
		echo"<div class='error'> <h4>$message</h4></div>"; 
		return; 
	}
}
if ( version_compare(PHP_VERSION, '5.3', '<' ) ) {
 
	add_action( 'admin_notices', 'maxinbound_php52_nono' ); 
	return;
}


require_once('classes/maxinbound-class.php'); 
require_once('classes/class.database.php'); 
require_once('classes/class.install.php'); // activation / deactivation hooks

require_once('classes/class.cssparser.php');
require_once('classes/templates.php'); 
require_once('classes/template.php');
require_once('classes/editors.php');
require_once('classes/simple_template.php'); 

require_once('classes/whistle.php'); 
require_once('classes/editors/class.editor.php');
require_once('classes/fields/class.field.php');
require_once('classes/modules.php'); 
require_once('classes/module.php');
require_once('classes/class.data.php');
require_once('classes/class.plugin.php');

require_once('classes/utils.php');
//require_once('classes/notices.php');
require_once('classes/class.maxerror.php');



/* Loading third party libraries, checking first.  */

	if (! extension_loaded('simplexml') )
	{
		add_action('admin_notices', array('maxInbound\miInstall', 'simplexml_notloaded') );
	}

	/*if (! class_exists('simple_html_dom_node'))
		require_once('assets/libraries/simplehtmldom/simple_html_dom.php');
	*/

	if (! class_exists('csstidy'))
	{
	//	require_once('assets/libraries/csstidy/class.csstidy.php'); 
	}
	
	// should be loaded outside admin as well. 
	if (! class_exists('pQuery')) 
	{
		require_once('assets/libraries/pquery/load_pquery.php'); 
	}
	
	if ( ! class_exists('Mobile_Detect')) 
	{
		require_once('assets/libraries/mobile_detect/Mobile_Detect.php'); 
	
	}

function MI() 
{
	return maxInbound::getInstance(); 
	
}

$maxinbound = new maxInbound(); // runtime 

