<?php

/**
 * Here's how we're getting a highly customized menu output.
 * 
 * @see https://core.trac.wordpress.org/browser/tags/4.2.2/src//wp-includes/nav-menu-template.php#L0 
 */
class CSST_Nav_Walker extends Walker_Nav_Menu {

	/**
	 * Provide the opening markup for a new menu within our menu (AKA a submenu).
	 * 
	 * I'm just pasting these params from trac, with no understanding of them.
	 * 
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args   An array of arguments. @see wp_nav_menu()
	 */
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		
		// The CSS class for our menu.
		$class = strtolower( __CLASS__ );

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

		/**
		 * Deal with the class names for the menu item.
		 * 
		 * What an un-standardized, un-SMACCS-able mess they are.  Not even going to try to standardize them.
		 */
		$classes  = $item -> classes;
		$class = strtolower( __CLASS__ ) . '-item';
		$classes[]= $class;

		// Does this menu item have children?
		if ( $args -> has_children ) {
			$classes[]= "$class-has_children";
		}

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

		// Deal with sub menus.
		if ( $args -> has_children ) {

			$icon = $this -> get_icon( 'down' );
			
			$dropdown_label = esc_html__( 'Sub-Menu', 'csst-nav' );
			$dropdown       = "$icon<span class='screen-reader-text'>$dropdown_label</span>";
			$link          .= "<a href='#' class='$class-toggle_link $class-link $class-toggle_link-closed'>$dropdown</a>";
		
		}

		// Since it's passed by reference, we don't need to return anything.
		$output .= $link;

	}

	public function get_icon( $direction ) {

		$class = strtolower( __CLASS__ . '-' . __FUNCTION__ );

		return "<span class='$class $class-$direction'></span>";

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
	 * A function I stole from Twitter Bootstrap.  All it does is add a property to the menu item object to denote if it has children, and output the menu item.
	 * 
	 * I'm just pasting these params from github.  Who knows that they really mean.
	 * 
	 * @param object $element Data object
	 * @param array $children_elements List of elements to continue traversing.
	 * @param int $max_depth Max depth to traverse.
	 * @param int $depth Depth of current element.
	 * @param array $args
	 * @param string $output Passed by reference. Used to append additional content.
	 * @return null Null on failure with no changes to parameters.
	 */
	public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
	   
		// I don't know what these are but they look important.
		if ( ! $element ) { return; }
		if( ! isset( $this -> db_fields['id'] ) ) { return; }

		// You're guess is as good as mine.
		$id_field = $this -> db_fields['id'];
		
		// Add a property to the menu item to determine if it has a sub menu.
		if ( is_object( $args[0] ) ) {
			$has_children = FALSE;
			if( ! empty( $children_elements[ $element -> $id_field ] ) ) {
				$has_children = TRUE;
			}
			$args[0] -> has_children = $has_children;
		}

		// Output the menu item.
		parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
	
	}

} // Walker_Nav_Menu