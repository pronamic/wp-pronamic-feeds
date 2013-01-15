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

		$html = "<select name='{$args['label_for']}'>";

		foreach ( $args['options'] as $option ) {
			if ( $chosen == $option['value'] ) {
				$html .= "<option value='{$option['value']}' selected='selected'>{$option['name']}</option>";
			}
			else {
				$html .= "<option value='{$option['value']}'>{$option['name']}</option>";
			}
		}

		$html .= '</select>';

		echo $html;
	}
}