<?php

/*

Plugin Name: Snippet
Plugin URI: http://theme.co/x/
Description: Add Schema.Org for SEO improvement
Version: 2.0.2
Author: Themeco
Author URI: http://theme.co/
Text Domain: __tco__
X Plugin: snippet

*/

// =============================================================================
// TABLE OF CONTENTS
// -----------------------------------------------------------------------------
//   01. Define Constants and Global Variables
//   03. Setup Menu
//   04. Initialize
// =============================================================================

// Define Constants and Global Variables
// =============================================================================

//
// Constants.
//

if (!defined('SNIPPET_VERSION')) define( 'SNIPPET_VERSION', '2.0.2' );
if (!defined('SNIPPET_IS_LOADED')) define( 'SNIPPET_IS_LOADED', true );
if (!defined('SNIPPET_URL')) define( 'SNIPPET_URL', plugins_url( '', __FILE__ ) );
if (!defined('SNIPPET_PATH')) define( 'SNIPPET_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );


//
// Global variables.
//

GLOBAL $snippet;



// Setup Menu
// =============================================================================

if ( ! function_exists( 'tco_snippet_admin_menu' ) ) {

  function tco_snippet_admin_menu() {
    GLOBAL $snippet;
    add_options_page( __( 'Snippet', '__tco__' ), __( 'Snippet', '__tco__' ), 'manage_options', 'tco-extensions-snippet', array( $snippet, 'admin_controller' ) );
  }

}

if ( ! function_exists( 'x_snippet_admin_menu' ) ) {

  function x_snippet_admin_menu() {
    GLOBAL $snippet;
    add_submenu_page( 'x-addons-home', __( 'Snippet', '__tco__' ), __( 'Snippet', '__tco__' ), 'manage_options', 'tco-extensions-snippet', array( $snippet, 'admin_controller' ) );
  }

}

$theme = wp_get_theme(); // gets the current theme
$is_pro_theme = ( 'Pro' == $theme->name || 'Pro' == $theme->parent_theme );
$is_x_theme = function_exists( 'CS' );
add_action( 'admin_menu', ( $is_pro_theme || $is_x_theme ) ? 'x_snippet_admin_menu' : 'tco_snippet_admin_menu', 100 );



// Initialize
// =============================================================================

require( SNIPPET_PATH . '/functions/framework/init.php' );
require( SNIPPET_PATH . '/functions/plugin.php' );

$snippet = new Tco_Snippet( __FILE__, 'snippet' );
