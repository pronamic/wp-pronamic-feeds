<script type="text/javascript">
  Pronamic_Feeds_Admin.config.spinner = "<?php echo includes_url( 'images/wpspin.gif' ); ?>";
  jQuery(Pronamic_Feeds_Admin.rss.ready);
</script>
<h2><?php _e( 'Feeds Messages', 'pronamic_feeds' );?></h2>
<div class="message_holder">
</div>
<table class="widefat">
  <thead>
    <tr>
      <th><?php _e( 'Feed', 'pronamic_feeds' );?></th>
      <th><?php _e( 'Messages', 'pronamic_feeds' );?></th>
    </tr>
  </thead>
  <tbody>
    <?php if ( ! empty( $feeds ) && $feeds->have_posts() ) : ?>
      <?php foreach ( $feeds->posts as $feed ): ?>
            <?php $feed_url     = get_post_meta( $feed->ID, 'pronamic_feed_url', true ); ?>
            <?php $rss          = fetch_feed( $feed_url );?>
      <tr>
        <td><a href="<?php echo get_edit_post_link( $feed->ID );?>" title="<?php echo $feed_url; ?>"><?php echo $feed->post_title;?></td>
        <td>
          <?php if ( ! is_wp_error( $rss ) ): ?>
            <?php $total = ( get_option( 'pronamic_feeds_posts_per_feed' ) ?: 0 ) ;?>
            <?php $total_messages   = $rss->get_item_quantity( $total ); ?>
            <?php $messages         = $rss->get_items( 0, $total_messages ); ?>
            <?php if ( ! empty( $messages ) ): ?>
              <table style="width:100%">
                <?php foreach ( $messages as $key => $message ): ?>
                  <tr>
                    <td style="width:90%"><?php echo $message->get_title(); ?></td>
                    <?php if ( ! in_array( $message->get_id( true ), $existing_ids ) ): ?>
                      <td><a href="#" class='jAddMessage' data-id="<?php echo $key;?>" data-url="<?php echo $feed_url; ?>" data-hashedid="<?php echo $message->get_id( true ); ?>"><?php echo __( 'Add', 'pronamic_feeds' ); ?></a>
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