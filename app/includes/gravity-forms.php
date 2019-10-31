<?php

/**
 * Remove Gravity form fields and field groups from the edit form page.
 */
add_filter( 'gform_add_field_buttons', function( $groups ) {
	unset(
		$groups[3], // Pricing fields
		$groups[2], // Post fields
		$groups[1]['fields'][8], // Captcha field
		$groups[1]['fields'][4], // Address field
		$groups[0]['fields'][10] // Page field
	);
	return $groups;
});
/**
 * Add CSS classes to fields
 */
add_filter( 'gform_pre_render', function( $form ) {
	foreach ( $form['fields'] as $field ) {
		$field->cssClass .= ' gfield-type-' . $field->type;
		if ( ! empty( $field->placeholder ) ) {
			$field->cssClass .= ' gfield-has-placeholder';
		}
	}
	return $form;
});
/**
 * Change submit button tag
 */
add_filter( 'gform_submit_button', function( $button, $form ) {
	ob_start();
	?>
	<button class="btn gform_button" id="gform_submit_button_<?php echo esc_attr( $form['id'] ); ?>">
		<?php echo esc_html( $form['button']['text'] ); ?>
	</button>
	<?php
	return ob_get_clean();
}, 10, 2 );
/**
 * Remove anchor after submission
 */
add_filter( 'gform_confirmation_anchor', '__return_false' );
/**
 * Move inline scripts to footer
 */
add_filter( 'gform_init_scripts_footer', '__return_true' );
/**
 * Wrap inline scripts to make sure they run after the dom has loaded.
 * But not when using an ajax form because that breaks redirect confirmations.
 */
add_filter( 'gform_cdata_open', function( $content = '' ) {
	if ( isset( $_REQUEST['gform_ajax'] ) ) {
		return $content;
	}
	$content = "\r\n/* <![CDATA[ */\r\n";
	$content .= 'document.addEventListener( "DOMContentLoaded", function() { ';
	return $content;
});
add_filter( 'gform_cdata_close', function( $content = '' ) {
	if ( isset( $_REQUEST['gform_ajax'] ) ) {
		return $content;
	}
	$content = ' }, false );';
	$content .= "\r\n/* ]]> */\r\n";
	return $content;
});
