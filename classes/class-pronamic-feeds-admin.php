<?php

class Pronamic_Feeds_Admin {
	public function __construct() {
		// Loads the required javascript
		add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_styles' ) );

		add_action( 'admin_init', array( $this, 'register_settings' ) );

		// Metaboxes
		add_action( 'add_meta_boxes', array( $this, 'metaboxes' ) );

		// Intercept save post
		add_action( 'save_post', array( $this, 'save_details_metabox' ) );

		// Addition of a custom sub menu page
		add_action( 'admin_menu', array( $this, 'submenus' ) );

		// Ajax methods
		add_action( 'wp_ajax_add_message', array( $this, 'add_post_from_message' ) );
	}

	public function register_admin_scripts( $hook ) {
		if ( 'pronamic_feed_page_pronamic_feeds_messages' == $hook )
			wp_enqueue_script( 'pronamic_feeds_admin', plugins_url( PRONAMIC_FEEDS_BASE . '/assets/admin/pronamic_feeds_admin.js' ), 'jquery' );
	}

	public function register_admin_styles( $hook ) {
		if ( 'pronamic_feed_page_pronamic_feeds_messages' == $hook )
			wp_enqueue_style( 'pronamic_feeds_admin_style', plugins_url( PRONAMIC_FEEDS_BASE . '/assets/admin/pronamic_feeds_admin_styles.css' ) );
	}

	public function metaboxes() {
		add_meta_box(
			'pronamic_feeds_metabox',
			__( 'Feed Details', 'pronamic_feeds' ),
			array( $this, 'display_details_metabox' ),
			'pronamic_feed',
			'normal',
			'high'
		);

		add_meta_box(
			'pronamic_feeds_data',
			__('Feed Info', 'pronamic_feeds'),
			array($this, 'display_feed_data_metabox'),
			'post',
			'side',
			'high'
		);
	}

	public function display_details_metabox() {
		global $post;

		// Generate the nonce field
		wp_nonce_field( basename( __FILE__ ), 'pronamic_feeds_metabox' );

		// Load the view
		Pronamic_Loader::view( 'views/admin/pronamic_feeds_metabox_view', array(
				'saved_feed_url' => get_post_meta( $post->ID, 'pronamic_feed_url', true )
			) );
	}

	public function save_details_metabox( $post_id ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return;

		if ( !isset( $_POST['pronamic_feeds_metabox'] ) || !wp_verify_nonce( $_POST['pronamic_feeds_metabox'], basename( __FILE__ ) ) )
			return;

		if ( !current_user_can( 'edit_post' ) )
			return;

		$pronamic_feed_url = filter_input( INPUT_POST, 'pronamic_feed_url', FILTER_VALIDATE_URL );

		if ( !empty( $pronamic_feed_url ) )
			update_post_meta( $post_id, 'pronamic_feed_url' , $pronamic_feed_url );
	}

	public function submenus() {
		add_submenu_page(
			'edit.php?post_type=pronamic_feed',
			__( 'Messages', 'pronamic_feeds' ),
			__( 'Feeds Messages', 'pronamic_feeds' ),
			'edit_posts',
			'pronamic_feeds_messages',
			array( $this, 'display_feeds_messages' )
		);

		add_submenu_page(
			'edit.php?post_type=pronamic_feed',
			__( 'Options', 'pronamic_feeds' ),
			__( 'Options', 'pronamic_feeds' ),
			'edit_posts',
			'pronamic_feeds_options',
			array( $this, 'display_options' )
		);
	}

	public function display_feeds_messages() {
		$feeds = new Wp_Query( array(
				'post_type' => 'pronamic_feed'
			) );

		// Get all posts with a set feed id
		$posts = new Wp_Query( array(
				'post_type'     => 'post',
				'meta_query'    => array(
					array(
						'key' => '_pronamic_feed_id',
					)
				)
			) );

		// Array of all existing feed ids
		$existing_ids = array();

		if ( $posts->have_posts() ) {
			foreach ( $posts->posts as $post ) {
				$existing_ids[] = get_post_meta( $post->ID, '_pronamic_feed_id', true );
			}
		}

		Pronamic_Loader::view( 'views/admin/display_feeds_messages', array(
				'feeds'             => $feeds,
				'existing_ids'      => $existing_ids
			) );
	}

	public function add_post_from_message() {
		// Get POST information
		$message_id     = filter_input( INPUT_POST, 'message_id', FILTER_VALIDATE_INT );
		$hashed_id      = filter_input( INPUT_POST, 'hashed_id', FILTER_SANITIZE_STRING );
		$feed_url       = filter_input( INPUT_POST, 'feed_url', FILTER_VALIDATE_URL );

		// Get the feed from the
		$rss = fetch_feed( $feed_url );

		if ( is_wp_error( $rss ) )
			exit;

		// Gets quantity with a limit of 20, incase they try to add a message that is no longer
		// available from the latest 10
		$total = $rss->get_item_quantity( 20 );

		// Gets an array of messages
		$messages = $rss->get_items( 0, $total );

		// CHecks there are messages and a message with the passed array id
		if ( empty( $messages ) || empty( $messages[$message_id] ) )
			$this->_ajax_response( 'error', __( 'Error', 'pronamic_feeds' ), __( 'No message exists! Try refreshing!', 'pronamic_feeds' ) );

		// Gets the chosen message and cleans up memory
		$chosen_message = $messages[$message_id];
		unset( $messages );

		$post = new Wp_Query( array(
				'post_type'     => 'post',
				'meta_query'    => array(
					array(
						'key'       => '_pronamic_feed_id',
						'value'     => $chosen_message->get_id( true )
					)
				)
			) );

		// Determine if they already exist
		if ( $post->have_posts() )
			$this->_ajax_response( 'error', __( 'Error', 'pronamic_feeds' ), __( 'That message already exists', 'pronamic_feeds' ) );

		// Generate post array of information
		$post = array(
			'comment_status'    => 'closed', // add setting on options page
			'ping_status'       => 'closed', // add setting
			'post_author'       => get_current_user_id(),
			'post_content'      => $chosen_message->get_content(),
			'post_excerpt'      => '', // base off setting wether to get excerpt or not
			'post_name'         => sanitize_title_with_dashes( $chosen_message->get_title() ),
			'post_parent'       => null,
			'post_password'     => null,
			'post_status'       => 'publish', // base off setting wether to require moderation
			'post_title'        => $chosen_message->get_title(),
			'post_type'         => 'post' // base off input text setting
		);

		// Adds the new post
		$post_id = wp_insert_post( $post );

		// Update meta information for this new post
		update_post_meta( $post_id, '_pronamic_feed_id', $chosen_message->get_id( true ) );
		update_post_meta( $post_id, '_pronamic_feed_url', $feed_url );
		update_post_meta( $post_id, '_pronamic_feed_post_url', $chosen_message->get_permalink() );

		// Respond back
		$this->_ajax_response( 'success', __( 'Success', 'pronamic_feeds' ), __( 'Successfully added the message to your posts!', 'pronamic_feeds' ) );

	}

	public function display_feed_data_metabox()
	{
		global $post;

		Pronamic_Loader::view( 'views/admin/display_feed_data_metabox', array(
			'post_url' => get_post_meta($post->ID, '_pronamic_feed_post_url', true)
		) );
	}

	public function display_options() {
		Pronamic_Loader::view( 'views/admin/display_options' );
	}

	public function register_settings() {
		// Class to handle view of inputs callbacks
		$input = new Pronamic_Settings;

		// Settings sections
		add_settings_section( 'pronamic_feeds_options', __( 'Options', 'pronamic_feeds' ), array( $this, 'settings_section' ), 'pronamic_feeds_options' );

		// Settings fields for the options section
		add_settings_field( 'pronamic_feeds_posts_per_feed', __( 'Posts per feed', 'pronamic_feeds' ), array( $input, 'text' ), 'pronamic_feeds_options', 'pronamic_feeds_options', array( 'name' => 'pronamic_feeds_posts_per_feed' ) );

		// Registered settings
		register_setting( 'pronamic_feeds_options', 'pronamic_feeds_posts_per_feed' );

	}

	public function settings_section() {}

	/**
	 * A response for the ajax request to add the message to the posts
	 *
	 * @param type    | String | The Type of response, Supports error|success
	 * @param title   | String | The title to show in the flash message
	 * @param message | String | The actual message to respond back with
	 */
	private function _ajax_response( $type, $title, $message ) {
		echo json_encode( array(
				'type'      => $type,
				'title'     => $title,
				'message'   => $message
			) );

		exit;
	}

}
