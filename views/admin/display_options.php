<div class="wrap">
	<?php screen_icon(); ?>
	<h2><?php echo __( 'Pronamic Feeds Options', 'pronamic_feeds' ); ?></h2>
	<form action="options.php" method="post">
		<?php settings_fields( 'pronamic_feeds_options' ); ?>
		
		<?php do_settings_sections( 'pronamic_feeds_options' ); ?>
		<?php submit_button(); ?>
	</form>
</div>