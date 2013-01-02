<?php

class Pronamic_Feeds
{
	public function __construct()
	{
		add_action( 'init', array( $this, 'initialize' ) );
		add_action( 'init', array( $this, 'post_types' ) );
	}

	public function initialize()
	{
		load_plugin_textdomain( 'pronamic_feeds', false, plugins_url( __FILE__ ) );

	}

	public function post_types()
	{
		$pronamic_labels = array(
            'name'                  => _x( 'Feeds', 'custom post type feeds', 'pronamic_feeds' ),
            'singular_name'         => _x( 'Feed', 'custom post type singular', 'pronamic_feeds' ),
            'add_new'               => _x( 'Add New', 'add new feed', 'pronamic_feeds' ),
            'add_new_item'          => __( 'Add New Feed', 'pronamic_feeds' ),
            'edit_item'             => __( 'Edit Feed', 'pronamic_feeds' ),
            'new_item'              => __( 'New Feed', 'pronamic_feeds' ),
            'all_items'             => __( 'All feeds', 'pronamic_feeds' ),
            'view_item'             => __( 'View feed', 'pronamic_feeds' ),
            'search_items'          => __( 'Search feeds', 'pronamic_feeds' ),
            'not_found'             => __( 'No feeds found', 'pronamic_feeds' ),
            'not_found_in_trash'    => __( 'No feeds found in trash', 'pronamic_feeds' ),
            'parent_item_colon'     => __( 'Feeds:', 'pronamic_feeds' ),
            'menu_name'             => __( 'Feeds', 'pronamic_feeds' )
		);

		register_post_type( 'pronamic_feed', array(
            'labels'                    => $pronamic_labels,
            'public'                    => false,
            'publicly_queryable'        => false,
            'show_ui'                   => true,
            'show_in_menu'              => true,
            'query_var'                 => false,
            'rewrite'                   => array( 'slug' => 'feeds' ),
            'capability_type'           => 'post',
            'has_archive'               => false,
            'hierachical'               => false,
            'menu_position'             => 10,
            'supports'                  => array( 'title' )
		) );
	}

}