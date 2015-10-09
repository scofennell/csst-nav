<?php

/**
 * A class for grabbing an SVG icon.
 *
 * @package WordPress
 * @subpackage CSST_Nav
 * @since CSST Nav 1.0
 */

class CSST_Nav_SVG {

	// Have we defined the icon yet? We only want to do so once.
	static $got_svg = FALSE;

	// We'll store the slug for our icon here.
	public $slug = '';

	public function __construct() {

		// Define the slug/id/class/href for our icon.
		$this -> slug = strtolower( __CLASS__ . '-icon' );

		// When this class is called, define the icon in the footer.
		add_action( 'wp_footer', array( $this, 'the_icon_definition' ) );
	
	}

	/**
	 * Define an icon to indicate our dropdown menu.
	 * 
	 * @return string An SVG down-pointing triangle.
	 */
	public function get_icon_definition() {

		$slug = $this -> slug;

		return "
			<svg xmlns='http://www.w3.org/2000/svg' id='$slug-definition' width='0' height='0' display='none'>
				<defs>
					<polygon id='$slug' points='0,0 10,0 5,10' />
				</defs>
			</svg>
		";

	}

	/**
	 * Output our icon.
	 * 
	 * @return mixed False if we have already defined the icon, else void.
	 */
	public function the_icon_definition() {

		// Have we already defined our icon?  If so, bail.
		if( self::$got_svg ) { return FALSE; }

		// Set the flag to true so we don't output this twice.
		self::$got_svg = TRUE;

		// Grab the icon definition.
		$out = $this -> get_icon_definition();

		// Output the icon definition.
		echo $out;

	}

	/**
	 * Grab our icon for inlining.
	 * 
	 * @return string An instance of our triangle icon.
	 */ 
	public function get() {

		$slug = $this -> slug;

		return "
			<svg class='$slug'>
				<use xlink:href='#$slug'></use>
			</svg>
		";

	}

}