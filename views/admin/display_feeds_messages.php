<h2><?php echo __( 'Feeds Messages', 'pronamic_feeds' );?></h2>
<table class="widefat">
	<thead>
		<tr>
			<th><?php echo __( 'Feed', 'pronamic_feeds' );?></th>
			<th><?php echo __( 'Messages', 'pronamic_feeds' );?></th>
		</tr>
	</thead>
	<tbody>
		<?php if( !empty($feeds) && $feeds->have_posts()) : ?>
			<?php foreach($feeds->posts as $feed): ?>
			<?php $feed_url = get_post_meta( $feed->ID, 'pronamic_feed_url', true ); ?>
			<?php $rss = fetch_feed( $feed_url );?>
			<tr>
				<td><a href="<?php echo admin_url( "post.php?post={$feed->ID}&action=edit" );?>" title="<?php echo $feed_url; ?>"><?php echo $feed->post_title;?></td>
				<td>
					<?php if( !is_wp_error( $rss ) ): ?>
                        <?php $total_messages   = $rss->get_item_quantity( 10 ); ?>
                        <?php $messages         = $rss->get_items( 0, $total_messages); ?>
						<?php if( !empty( $messages ) ): ?>
							<table style="width:100%">								
								<?php foreach( $messages as $key => $message): ?>
								<tr>
									<td style="width:90%"><?php echo $message->get_title();?></td>
									<td><a href="#" class='jAddMessage' data-id="<?php echo $key;?>" data-url="<?php echo $feed_url; ?>">Add</a>
								<tr>
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