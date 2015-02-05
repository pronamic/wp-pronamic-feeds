<script type="text/javascript">
	Pronamic_Feeds_Admin.config.spinner = "<?php echo esc_js( includes_url( 'images/wpspin.gif' ) ); ?>";
	jQuery(Pronamic_Feeds_Admin.rss.ready);
</script>

<h2><?php esc_html_e( 'Feeds Messages', 'pronamic_feeds' );?></h2>

<div class="message_holder"></div>

<table class="widefat">
	<thead>
		<tr>
			<th><?php _e( 'Feed', 'pronamic_feeds' );?></th>
			<th><?php _e( 'Messages', 'pronamic_feeds' );?></th>
		</tr>
	</thead>

	<tbody>
		<?php if ( ! empty( $feeds ) && $feeds->have_posts() ) : ?>

			<?php foreach ( $feeds->posts as $feed ) : ?>

				<?php $feed_url = get_post_meta( $feed->ID, 'pronamic_feed_url', true ); ?>
				<?php $rss      = fetch_feed( $feed_url );?>
				
				<tr>
					<td>
						<a href="<?php echo esc_attr( get_edit_post_link( $feed->ID ) ); ?>" title="<?php echo esc_attr( $feed_url ); ?>">
							<?php echo esc_html( $feed->post_title ); ?>
						</a>
					</td>
					<td>
						<?php if ( ! is_wp_error( $rss ) ) : ?>

							<?php $total = ( get_option( 'pronamic_feeds_posts_per_feed' ) ?: 0 ) ;?>
							<?php $total_messages = $rss->get_item_quantity( $total ); ?>
							<?php $messages       = $rss->get_items( 0, $total_messages ); ?>

							<?php if ( ! empty( $messages ) ) : ?>

								<table style="width:100%">

									<?php foreach ( $messages as $key => $message ) : ?>

										<tr>
										
											<?php if ( ! in_array( $message->get_id( true ), $existing_ids ) ) : ?>

												<td style="width:90%"><a class="pronamic_feeds_get_post" href="<?php echo esc_attr( $message->get_permalink() ); ?>" target="_blank"><?php echo esc_html( $message->get_title() ); ?></a></td>                      
												<td><a href="#" class='jAddMessage' data-id="<?php echo esc_attr( $key ); ?>" data-feedID="<?php echo esc_attr( $feed->ID ); ?>" data-url="<?php echo esc_attr( $feed_url ); ?>" data-hashedid="<?php echo esc_attr( $message->get_id( true ) ); ?>"><?php esc_html_e( 'Add', 'pronamic_feeds' ); ?></a>

											<?php else : ?>

												<td style="width:90%"><a class="pronamic_feeds_have_post" href="<?php echo esc_attr( get_edit_post_link( $post_ids[ $message->get_id( true ) ] ) ); ?>" target="_blank"><?php esc_html_e( $message->get_title() ); ?></a></td>
												<td><a href="<?php echo esc_attr( get_delete_post_link( $post_ids[ $message->get_id( true ) ] ) ); ?>"><?php _e( 'Delete' ); ?></a></td>

											<?php endif;?>

										</tr>

									<?php endforeach;?>

								</table>

							<?php endif;?>

						<?php endif;?>
					</td>
				</tr>

			<?php endforeach;?>

		<?php endif;?>

	</tbody>
</table>
