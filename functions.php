<?php

function has_pronamic_feed( $id = null ) {
	if ( ! $id ) {
		$id = get_the_ID();
	}

	return (boolean) get_pronamic_feed( $id );

}

function get_pronamic_feed( $id = null ) {
	if ( ! $id ) {
		$id = get_the_ID();
	}

	return get_post_meta( $id, '_pronamic_feed_url', true );
}

function get_pronamic_feed_post( $id = null ) {
	if ( ! $id ) {
		$id = get_the_ID();
	}

	return get_post_meta( $id, '_pronamic_feed_post_url', true );
}

/**
 * Feeds cache transient lifetime
 *
 * @see https://github.com/WordPress/WordPress/blob/4.1/wp-includes/feed.php#L633-L634
 */
function pronamic_feeds_cache_transient_lifetime( $lifetime ) {
	$lifetime = HOUR_IN_SECONDS;

	return $lifetime;
}

/**
 * Fetch feed
 */
function pronamic_feeds_fetch_feed( $url ) {
	add_filter( 'wp_feed_cache_transient_lifetime', 'pronamic_feeds_cache_transient_lifetime' );

	$feed = fetch_feed( $url );

	remove_filter( 'wp_feed_cache_transient_lifetime', 'pronamic_feeds_cache_transient_lifetime' );

	return $feed;
}
