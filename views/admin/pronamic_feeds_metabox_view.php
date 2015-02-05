<?php

if ( ! isset( $saved_feed_url ) ) {
	$saved_feed_url = '';
}

?>
<table class="form-table">
	<tr>
		<th><?php _e( 'URL', 'pronamic_feeds' );?></th>
		<td>
			<input type="text" name="pronamic_feed_url" value="<?php echo esc_attr( $saved_feed_url ); ?>" size="80" />
		</td>
	</tr>
</table>
