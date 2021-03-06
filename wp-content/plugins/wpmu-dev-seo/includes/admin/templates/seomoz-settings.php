<?php
/**
 * Moz settings template
 *
 * @package wpmu-dev-seo
 */

?>
<div class='wrap'>

	<?php $this->_render( 'settings-message-top' ); ?>

	<h1><?php esc_html_e( 'SmartCrawl Wizard: Moz' , 'wds' ); ?></h1>

	<?php
	$smartcrawl_options = Smartcrawl_Settings::get_options();
	if ( ! smartcrawl_is_allowed_tab( $_view['slug'] ) ) {
		printf( __( "Your network admin prevented access to '%s', please move onto next step.", 'wds' ), __( 'Moz' , 'wds' ) );
	} elseif ( 'settings' === $_view['name'] || ( ! empty( $smartcrawl_options[ $_view['name'] ] ) ) ) {

		echo $additional;

	} else {
		printf( __( "You've chosen not to set up '%s', please move onto next step.", 'wds' ), __( 'Moz' , 'wds' ) );
	}

	?>
</div>