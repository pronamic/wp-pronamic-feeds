<?php

function has_pronamic_feed( $id = null )
{
	if ( ! $id )
		$id = get_the_ID();

	return ( ! empty( get_pronamic_feed( $id ) ) );

}

function get_pronamic_feed( $id = null )
{
	if ( ! $id )
		$id = get_the_ID();

	return get_post_meta( $id, '_pronamic_feed_url', true );
}