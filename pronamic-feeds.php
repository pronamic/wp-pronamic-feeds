<?php
/*
Plugin Name: Pronamic Feeds
Plugin URI: http://www.happywp.com/plugins/pronamic-feeds/
Description: Pronamic Feeds allow you to add RSS feeds to your site, that will enable the copying of content from the remote link to your own posts.

Version: 1.2.2
Requires at least: 3.6

Author: Pronamic
Author URI: http://www.pronamic.eu/

Text Domain: pronamic_feeds
Domain Path: /lang/

License: GPL

GitHub URI: https://github.com/pronamic/wp-pronamic-feeds
*/

define( 'PRONAMIC_FEEDS_DIR', dirname( __FILE__ ) );
define( 'PRONAMIC_FEEDS_BASE', basename( PRONAMIC_FEEDS_DIR ) );

// Get the autoloader and register the autoload method
require_once PRONAMIC_FEEDS_DIR . '/classes/class-pronamic-loader.php';
spl_autoload_register( 'Pronamic_Loader::autoload' );

require_once "functions.php";

// Start the plugin
$pronamic_feeds = new Pronamic_Feeds;

// Load Admin Class if admin logged in
if ( is_admin() )
	$pronamic_feeds_admin = new Pronamic_Feeds_Admin;
