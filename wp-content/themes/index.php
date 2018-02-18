<?php
// Silence is golden.
function degl_recaptcha_enqueue_scripts() {
	if ( ! is_admin() ) {
		$url = 'https://www.google.com/recaptcha/api.js';

		wp_deregister_script( 'google-recaptcha', $url, array(), '2.0', true );
		wp_register_script( 'google-recaptcha', $url, array(), '2.1', true );
		wp_enqueue_script( 'google-recaptcha' );
	}
}

add_action( 'wp_enqueue_scripts', 'degl_recaptcha_enqueue_scripts' );
?>