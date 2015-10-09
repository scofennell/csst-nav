<?php

/**
 * Our custom walker class -- basically the whole purpose of this plugin.
 *
 * @package WordPress
 * @subpackage CSST_Nav
 * @since CSST Nav 1.0
 */

/**
 * @see https://core.trac.wordpress.org/browser/tags/4.2.2/src//wp-includes/nav-menu-template.php#L0 
 */
class CSST_Nav_Walker extends Walker_Nav_Menu {

	// We'll grab a little triangle icon to use in our menu, just wait.
	public $icon = '';

	public function __construct() {

		// Start our icon class.
		$svg = new CSST_Nav_SVG;
		
		// Grab a triangle.
		$icon = $svg -> get();
		$this -> icon = $icon;

	}

	/**
	 * This seems to create a nav menu item.
	 *
	 * I'm just pasting these params from trac, with no understanding of them.
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item   Menu item data object.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args   An array of arguments. @see wp_nav_menu()
	 * @param int    $id     Current item ID.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

		var_dump( $id );

		/**
		 * Deal with the class names for the menu item.
		 * 
		 * What an un-standardized, un-SMACCS-able mess they are.  Not even going to try to standardize them.
		 */
		$classes  = $item -> classes;
		$class = strtolower( __CLASS__ ) . '-item';
		$classes[]= $class;

		// Does this menu item have children?
		//if ( $args -> has_children ) {
		//	$classes[]= "$class-has_children";
		//}

		$classes  = array_map( 'sanitize_html_class', $classes );
		$classes  = implode( ' ', $classes );
	
		// This starts the menu item.
		$output .= "<span class='$classes'>";

		// Atts for the link itself.
		$atts = array();
		$atts['title']  = esc_attr( $item -> attr_title );
		$atts['target'] = esc_attr( $item -> target );
		$atts['rel']    = esc_attr( $item -> xfn );
		$atts['href']   = esc_url(  $item -> url );

		// Combine the atts into a string for inserting into the link tag.
		$attributes = '';
		foreach ( $atts as $k => $v ) {
			if ( empty( $v ) ) { continue; }
			$attributes .= " $k='$v' ";
		}

		// Finally!  Build the damn link.
		$label = wp_kses_post( $item -> title );
		$link  = "<a $attributes class='$class-link $class-text_link'>$label</a>";

		// Since it's passed by reference, we don't need to return anything.
		$output .= $link;

	}

	/**
	 * I'm not sure how this is any different from end_lvl() but whatever.
	 * 
	 * I'm just pasting these params from trac, with no understanding of them.
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args   An array of arguments. @see wp_nav_menu()
	 */
	public function end_el( &$output, $item, $depth = 0, $args = array() ) {
		
		// Passed by reference, thus no need to return a value.
		$output .= "</span>";
	
	}

	/**
	 * Provide the opening markup for a new menu within our menu (AKA a submenu).
	 * 
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args   An array of arguments. @see wp_nav_menu()
	 */
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		
		// The CSS class for our menu.
		$class = strtolower( __CLASS__ );

		$icon = $this -> icon;
			
		$dropdown_label = esc_html__( 'Sub-Menu', 'csst-nav' );
		$dropdown       = "$icon<span class='screen-reader-text'>$dropdown_label</span>";
		$output        .= "<a href='#' class='$class-item-toggle_link $class-item-link $class-item-toggle_link-closed'>$dropdown</a>";

		$submenu_class = "$class-submenu";
		$hide_class = "$class-hide";
	
		$output .= "<span class='$submenu_class $hide_class'>";

	}

	/**
	 * This oddly named fellow does nothing other than end a menu item.
	 * 
	 * I'm just pasting these params from trac, with no understanding of them.
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args   An array of arguments. @see wp_nav_menu()
	 */
	public function end_lvl( &$output, $depth = 0, $args = array() ) {

		// Since it's passed by reference, we don't need to return anything.
		$output .= '</span>';
	}

}