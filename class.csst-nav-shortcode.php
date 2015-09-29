<?php

/**
 * Register CSST_Nav shortcode.
 */
add_shortcode( 'csst_nav', 'csst_nav' );
function csst_nav( $atts = array() ) {

	$csst_nav = new CSST_Nav( $atts );

	$out = $csst_nav -> out();

	echo $out;

}

class CSST_Nav  {

	public $which_menu = '';

	public function __construct( $atts ) {

		$this -> enqueue();
		$this -> which_menu = $atts['which_menu'];

	}

	public function enqueue() {

		wp_enqueue_script( __CLASS__ . '-script', CSST_NAV_PLUGIN_URL . '/js/script.js', array( 'jquery' ) );
		wp_enqueue_style( __CLASS__ . '-style', CSST_NAV_PLUGIN_URL . '/css/style.css' );
	
	}

	public function out() {
		
		// The CSS class for our shortcode.
		$class = strtolower( __CLASS__ );

		// Get a menu from the db.
		$which_menu = $this -> which_menu;

		// Args for a call to wp_nav_menu().
		$menu_args = array(
			'menu'            => $which_menu,
			'container'       => FALSE,
			'menu_class'      => "$class $class-$which_menu",
			'echo'            => FALSE,
			'items_wrap'      => '<nav role="navigation" class="%2$s">%3$s</nav>',
			'walker'          => new CSST_Nav_Walker(),
		);

		// The main content of the shortcode is in fact a call to wp_nav_menu().
		$out = wp_nav_menu( $menu_args );

		// Build and sanitize the output.
		$out = wp_kses_post( $out );

		echo $out;

	}
	
}