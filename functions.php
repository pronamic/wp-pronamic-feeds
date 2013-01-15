<?php

function has_pronamic_feed( $id = null )
{
	if ( ! $id )
		$id = get_the_ID();

	return (boolean) get_pronamic_feed( $id );

}

function get_pronamic_feed( $id = null )
{
	if ( ! $id )
		$id = get_the_ID();

	return get_post_meta( $id, '_pronamic_feed_url', true );
}

function get_pronamic_feed_post( $id = null )
{
	if ( ! $id )
		$id = get_the_ID();

	return get_post_meta( $id, '_pronamic_feed_post_url', true );
}
