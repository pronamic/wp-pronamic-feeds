<?php

/**
 * Plugin Name: Pronamic Feeds
 * Plugin URI: http://www.pronamic.nl
 * Author: Pronamic
 * Author URI: http://www.pronamic.nl
 * Description: RSS Feeds to Posts plugin
 */

define('PRONAMIC_FEEDS_BASE', dirname( __FILE__ ) );

// Get the autoloader and register the autoload method
require_once(PRONAMIC_FEEDS_BASE . '/classes/class-pronamic-loader.php' );
spl_autoload_register('Pronamic_Loader::autoload');

// Start the plugin
$pronamic_feeds = new Pronamic_Feeds;

// Load Admin Class if admin logged in
if( is_admin() )
	$pronamic_feeds_admin = new Pronamic_Feeds_Admin;