<?php

class Pronamic_Feeds_Admin
{
	public function __construct()
	{
		add_action( 'add_meta_boxes', array( $this, 'metaboxes' ) );

		add_action( 'save_post', array( $this, 'save_details_metabox' ) );

		add_action( 'admin_menu', array( $this, 'feeds_messages' ) );

		add_action( 'wp_ajax_add_post', array( $this, 'add_post_from_message' ) );
	}

	public function metaboxes()
	{
		add_meta_box( 
		 	'pronamic_feeds_metabox', 
		 	__( 'Feed Details', 'pronamic_feeds' ), 
		 	array( $this, 'display_details_metabox' ),
		 	'pronamic_feed', 
		 	'normal',
		 	'high' 
		);
	}

	public function display_details_metabox()
	{
		global $post;

		// Generate the nonce field
		wp_nonce_field( basename( __FILE__ ), 'pronamic_feeds_metabox' );

		// Load the view
		Pronamic_Loader::view( 'views/admin/pronamic_feeds_metabox_view', array(
			'saved_feed_url' => get_post_meta( $post->ID, 'pronamic_feed_url', true )
		) );
	}

	public function save_details_metabox( $post_id )
	{
		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return;

		if( !isset( $_POST['pronamic_feeds_metabox'] ) || !wp_verify_nonce( $_POST['pronamic_feeds_metabox'], basename( __FILE__ ) ) )
			return;

		if( !current_user_can( 'edit_post' ) )
			return;

		$pronamic_feed_url = filter_input( INPUT_POST, 'pronamic_feed_url', FILTER_VALIDATE_URL );

		if( !empty( $pronamic_feed_url ) )
			update_post_meta( $post_id, 'pronamic_feed_url' , $pronamic_feed_url );
	}

	public function feeds_messages()
	{
		add_submenu_page( 
			'edit.php?post_type=pronamic_feed', 
			__('Messages', 'pronamic_feeds'), 
			__('Feeds Messages', 'pronamic_feeds'), 
			'edit_posts', 
			'pronamic_feeds_messages', 
			array( $this, 'display_feeds_messages' ) );
	}

	public function display_feeds_messages()
	{
		$feeds = new Wp_Query(array(
			'post_type' => 'pronamic_feed'
		));

		Pronamic_Loader::view( 'views/admin/display_feeds_messages', array(
			'feeds' => $feeds,
		) );
	}

	public function add_post_from_message()
	{
		// Get POST information
		$message_id = filter_var( INPUT_POST, 'message_id', FILTER_SANITIZE_STRING );
		$feed_url = filter_var( INPUT_POST, 'message_full_url', FILTER_VALIDATE_URL );		

		// Get the feed from the 
		$rss = fetch_feed( $feed_url );

		if( is_wp_error( $rss ) )
			exit;

		// Gets quantity with a limit of 20, incase they try to add a message that is no longer
		// available from the latest 10
		$total = $rss->get_item_quantity( 20 );

		// Gets an array of messages
		$messages = $rss->get_items( 0, $total );

		// CHecks there are messages and a message with the passed array id
		if(empty($messages) || empty($messages[$message_id]))
			exit;

		// Gets the chosen message and cleans up memory
		$chosen_message = $messages[$message_id];
		unset($messages);

		// Change to be determined from a setting, either the post date
		// or the date of the website.

		// Sets the current time
		$post_date = new DateTime();

		// Sets the GMT Time
		$post_date_gmt = new DateTime();
		$post_date_gmt->setTimezone(new DateTimeZone( 'Europe/London' ) );

		// Generate post array of information
		$post = array(
			'comment_status' => 'closed', // add setting on options page
			'ping_status' => 'closed', // add setting
			'post_author' => get_current_user_id(),
			'post_content' => $chosen_message->get_description(),
			'post_excerpt' => '', // base off setting wether to get excerpt or not
			'post_name' => sanitize_title_with_dashes( $chosen_message->get_title() ),
			'post_parent' => null,
			'post_password' => null,
			'post_status' => 'publish', // base off setting wether to require moderation
			'post_title' => $chosen_message->get_title(),
			'post_type' => 'post' // base off input text setting
		);

		// Adds the new post
		$post_id = wp_insert_post( $post );

		// Update meta information for this new post
		update_post_meta( $post_id, '_pronamic_feed_id', $chosen_message->get_id( true ) );
		update_post_meta( $post_id, '_pronamic_feed_url', $chosen_message->get_permalink() );
		update_post_meta( $post_id, '_pronamic_feed_post_url', $feed_url );
		
	}

}