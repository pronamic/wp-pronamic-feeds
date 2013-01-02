<?php

class Pronamic_Feeds_Admin
{
	public function __construct()
	{
		add_action( 'add_meta_boxes', array( $this, 'metabox' ) );

		add_action( 'save_post', array( $this, 'save_details_metabox' ) );
	}

	public function metabox()
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
}