<?php

class Pronamic_Settings
{
	public function text($args)
	{
		printf(
			'<input name="%s" id="%s" type="text" value="%s" class="%s" />', 
			esc_attr( $args['name'] ),
			esc_attr( $args['name'] ),
			esc_attr( get_option( $args['name'] ) ),
			'regular-text code'
		);
	}
}