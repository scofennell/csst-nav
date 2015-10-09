<?php

/**
 * Register our shortcode, [csst_nav].
 *
 * @package WordPress
 * @subpackage CSST_Nav
 * @since CSST Nav 1.0
 */

/**
 * The main output function for our plugin.
 * 
 * @param  array  $atts An array of shortcode attributes.
 * @return string A WordPress custom menu, via our custom walker.
 */
function csst_nav( $atts = array() ) {

	// Invoke our menu class, passing our atts to it.
	$csst_nav = new CSST_Nav( $atts );

	// Get the custom menu.
	$out = $csst_nav -> get();

	// Shortcodes must return, not echo.
	return $out;

}

/**
 * Pass our function to the WP shortcode API.
 */
add_shortcode( 'csst_nav', 'csst_nav' );
