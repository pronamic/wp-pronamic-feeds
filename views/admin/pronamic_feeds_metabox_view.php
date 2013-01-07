<table class='form-table'>
	<tr>
		<th><?php echo __( 'URL', 'pronamic_feeds' );?></th>
		<td>
			<input type="text" name="pronamic_feed_url" value="<?php echo ( isset( $saved_feed_url ) ? $saved_feed_url : '' ); ?>" size="80"/>
		</td>
	</tr>
</table>