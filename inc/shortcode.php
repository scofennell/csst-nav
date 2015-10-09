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
function csst_nav_cb( $atts = array() ) {

	$csst_nav = new CSST_Nav( $atts );

	$out = $csst_nav -> get();

	echo $out;

}

/**
 * Pass our function to the WP shortcode API.
 */
add_shortcode( 'csst_nav_cb', CSST_NAV );
