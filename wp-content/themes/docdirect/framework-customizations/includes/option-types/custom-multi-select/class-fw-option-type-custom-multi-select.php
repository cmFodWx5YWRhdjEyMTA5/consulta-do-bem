<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

if ( ! class_exists( 'FW_Option_Type_Custom_Checkboxes' ) ):

class FW_Option_Type_Custom_Checkboxes extends FW_Option_Type {
	public function get_type() {
		return 'custom-checkboxes';
	}

	/**
	 * @internal
	 * {@inheritdoc}
	 */
	protected function _enqueue_static( $id, $option, $data ) {
	}

	/**
	 * @param string $id
	 * @param array $option
	 * @param array $data
	 *
	 * @return string
	 *
	 * @internal
	 */
	protected function _render( $id, $option, $data ) {
		$option['value'] = is_array( $data['value'] ) ? $data['value'] : array();

		$div_attr = $option['attr'];
		unset( $div_attr['name'] );
		unset( $div_attr['value'] );

		if ( $option['inline'] ) {
			$div_attr['class'] .= ' fw-option-type-checkboxes-inline fw-clearfix';
		}

		$html = '<div ' . fw_attr_to_html( $div_attr ) . '>';

		$html .= '<input type="checkbox" name="' . esc_attr( $option['attr']['name'] ) . '[]" value="" checked="checked" style="display: none">' .
		         '<!-- used for "' . esc_attr( $id ) . '" to be present in _POST -->';
		
		foreach ( $option['choices'] as $value => $choice ) {
			if (is_string($choice)) {
				$choice = array(
					'text' => $choice,
					'attr' => array(),
				);
			}
			
			$choice['attr'] = array_merge(
				isset($choice['attr']) ? $choice['attr'] : array(),
				array(
					'type' => 'checkbox',
					'name' => $option['attr']['name'] . '[' . $value . ']',
					'value' => $value,
					'id' => $option['attr']['id'] . '-' . $value,
				),
				isset( $option['value'][ $value ] ) && $option['value'][ $value ]
					? array('checked' => 'checked') : array()
			);

			$html .=
				'<div>' .
					'<label for="' . esc_attr( $choice['attr']['id'] ) . '">' .
						'<input  ' . fw_attr_to_html($choice['attr']) . '>' .
						' ' . htmlspecialchars( $choice['text'], ENT_COMPAT, 'UTF-8' ) .
					'</label>' .
				'</div>';
		}

		$html .= '</div>';

		return $html;
	}

	/**
	 * @param array $option
	 * @param array|null|string $input_value
	 *
	 * @return array
	 *
	 * @internal
	 */
	protected function _get_value_from_input( $option, $input_value ) {
		if ( is_array( $input_value ) ) {
			$value = array();

			foreach ( $input_value as $choice => $val ) {
				if ( $val === '' ) {
					continue;
				}

				if ( ! isset( $option['choices'][ $choice ] ) ) {
					continue;
				}

				$value[ $choice ] = true;
			}
		} else {
			$value = $option['value'];
		}

		return $value;
	}

	/**
	 * @internal
	 */
	public function _get_backend_width_type() {
		return 'auto';
	}

	/**
	 * @internal
	 */
	protected function _get_defaults() {
		return array(
			'inline'  => false, // Set this parameter to true in case you want all checkbox inputs to be rendered inline
			'value'   => array(
				// 'choice_id' => bool
			),
			/**
			 * Avoid bool or int keys http://bit.ly/1cQgVzk
			 */
			'choices' => array(
				// 'choice_id' => 'Choice Label'
			),
		);
	}
}

FW_Option_Type::register( 'FW_Option_Type_Custom_Checkboxes' );

endif;
