<script type="text/javascript">
	jQuery(Pronamic_Feeds_Admin.rss.ready);
</script>
<style type="text/css">
.alert {
  padding: 8px 35px 8px 14px;
  margin-bottom: 20px;
  text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5);
  background-color: #fcf8e3;
  border: 1px solid #fbeed5;
  -webkit-border-radius: 4px;
     -moz-border-radius: 4px;
          border-radius: 4px;
}

.alert,
.alert h4 {
  color: #c09853;
}

.alert h4 {
  margin: 0;
}

.alert .close {
  position: relative;
  top: -2px;
  right: -21px;
  line-height: 20px;
}

.alert-success {
  color: #468847;
  background-color: #dff0d8;
  border-color: #d6e9c6;
}

.alert-success h4 {
  color: #468847;
}

.alert-danger,
.alert-error {
  color: #b94a48;
  background-color: #f2dede;
  border-color: #eed3d7;
}

.alert-danger h4,
.alert-error h4 {
  color: #b94a48;
}

.alert-info {
  color: #3a87ad;
  background-color: #d9edf7;
  border-color: #bce8f1;
}

.alert-info h4 {
  color: #3a87ad;
}

.alert-block {
  padding-top: 14px;
  padding-bottom: 14px;
}

.alert-block > p,
.alert-block > ul {
  margin-bottom: 0;
}

.alert-block p + p {
  margin-top: 5px;
}
</style>
<h2><?php echo __( 'Feeds Messages', 'pronamic_feeds' );?></h2>
<div class="message_holder">
</div>
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
						<?php $total = (get_option( 'pronamic_feeds_posts_per_feed' ) ?: 0 ) ;?>
                        <?php $total_messages   = $rss->get_item_quantity( $total ); ?>
                        <?php $messages         = $rss->get_items( 0, $total_messages); ?>
						<?php if( !empty( $messages ) ): ?>
							<table style="width:100%">								
								<?php foreach( $messages as $key => $message): ?>
								<tr>
									<td style="width:90%"><?php echo $message->get_title();?></td>
									<?php if( !in_array( $message->get_id( true ), $existing_ids ) ): ?>
										<td><a href="#" class='jAddMessage' data-id="<?php echo $key;?>" data-url="<?php echo $feed_url; ?>" data-hashedid="<?php echo $message->get_id( true );?>">Add</a>
									<?php endif;?>
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