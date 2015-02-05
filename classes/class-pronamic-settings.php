<?php

class Pronamic_Settings {
	public function text( $args ) {
		printf(
			'<input name="%s" id="%s" type="text" value="%s" class="%s" />',
			esc_attr( $args['label_for'] ),
			esc_attr( $args['label_for'] ),
			esc_attr( get_option( $args['label_for'] ) ),
			'regular-text code'
		);
	}

	public function select( $args ) {
		$chosen = get_option( $args['label_for'] );

		printf( '<select name="%s">', $args['label_for'] );

		foreach ( $args['options'] as $option ) {
			if ( $chosen == $option['value'] ) {
				printf( '<option value="%s" selected="selected">%s</option>', esc_attr( $option['value'] ), esc_html( $option['name'] ) );
			} else {
				printf( '<option value="%s">%s</option>', esc_attr( $option['value'] ), esc_html( $option['name'] ) );
			}
		}

		printf( '</select>' );
	}
}