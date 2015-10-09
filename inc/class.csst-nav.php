<?php

/**
 * Our shortcode class for calling any custom menu,
 * using our custom walker class.
 *
 * @package WordPress
 * @subpackage CSST_Nav
 * @since CSST Nav 1.0
 */

class CSST_Nav  {

	/**
	 * A flag to detect if we have already run our constructor.
	 * 
	 * @var boolean
	 */
	public static $already_constructed = FALSE;

	/**
	 * Will hold the name, slug, or ID of the menu we're grabbing.
	 * 
	 * @var mixed
	 */
	public $which_menu = '';

	public function __construct( $atts ) {

		// If we have not yet run this script on this page load...
		if ( ! self::$already_constructed ) {

			/**
			 * Pass some php stuff to our JS. This has to happen before we print
			 * our inline scripts or call our external scripts.
			 */
			add_action( 'wp_footer', array( $this, 'localize' ), 1 );

			/**
			 * Grab our plugin scripts and styles. Normally we could do this at
			 * wp_enqueue_scripts, but we want to wait and see if the shortcode
			 * is being used.  If this code is being run, then the shortcode is in
			 * use, so enqueue them now.
			 */
			$this -> enqueue();

			/**
			 * Apply our jQuery plugin to our menu. This has to happen after we
			 * localize our php.
			 */
			add_action( 'wp_footer', array( $this, 'inline_js' ), 900 );

			/**
			 * Take note!  We have already run this block and
			 * don't need to do it again.
			 */ 
			self::$already_constructed = TRUE;

		}

		// Set our $which_menu member to the value passed to the shortcode.
		$this -> which_menu = $atts['which_menu'];

	}

	/**
	 * Grab our plugin scripts and styles.
	 */
	public function enqueue() {

		// We rely on jQuery in order to load our scripts.
		wp_enqueue_script(
			
			// Our script nickname.
			__CLASS__ . '-script',
			
			// Our script url.
			CSST_NAV_PLUGIN_URL . 'js/jquery.csstNav.js',
			
			// We rely on jQuery.
			array( 'jquery' ),
			
			// Add a cache-buster to the filename.
			CSST_NAV_VERSION,
			
			// Sure, we can wait until the footer.
			TRUE
	
		);

		// Our plugin CSS file, with a cache-buster version number.
		wp_enqueue_style(

			// Our stylesheet nickname.
			__CLASS__ . '-style',

			// Stylesheet url.
			CSST_NAV_PLUGIN_URL . 'css/style.css',
			
			// We don't rely on any dependancies in order to load it.
			FALSE,

			// Add a cache-buster to the filename.
			CSST_NAV_VERSION

		);
	
	}

	/**
	 * Upon dom ready, echo some JS to apply our jQuery plugin to elements with
	 * our CSS class.
	 */
	public function inline_js() {

		$out = <<<EOT

			<script>

				jQuery( document ).ready( function( $ ) {

					/**
					 * Grab our plugin namespace, which we passed ot our JS via
					 * localize().
					 *
					 * All of our menus carry this CSS class. We could change
					 * this to fit some other project.
					 */
					var el = $( '.' + csst_nav.namespace );

					// We could pass some options if we had any.  We don't.
					options = {};

					// Apply our jquery plugin to our selection.
					$( el ).csstNav( options );

				});
		
			</script>

EOT;

		echo $out;

	}

	/**
	 * Pass some php stuff, such as our plugin slug, to our JS.
	 */
	public function localize() {

		wp_localize_script(

			// When our plugin JS is loaded...
			__CLASS__ . '-script',

			// Print a JS object named after our plugin...
			CSST_NAV,

			// And have the object contain these values:
			array(

				// Tell our JS what our PHP plugin slug is.
				'namespace' => CSST_NAV,
			
			)
		
		);

	}

	/**
	 * The main template tag for this class.  Get a custom menu via our walker.
	 * 
	 * @return string A WordPress custom menu, passed through our walker class.
	 */
	public function get() {
		
		// The CSS class for our shortcode.
		$class = strtolower( __CLASS__ );

		// Get a menu from the db.
		$which_menu = $this -> which_menu;

		/**
		 * Args for a call to wp_nav_menu().
		 * 
		 * Some of these args don't get used by wp_nav_menu() per se,
		 * but we're able to pass them through to our walker class, which does use them.
		 */ 
		$menu_args = array(

			// Instead of wrapping each menu item as list item, let's do a span.
			'after' => '</span>',

			// The closing markup after a submenu.
			'after_submenu' => '</span>',

			// Instead of wrapping each menu item as list item, let's do a span.
			'before' => '<span class="%s">',

			// The opening markup before a submenu.
			'before_submenu' => '<span class="%s">',

			// Nope, we don't need extra markup wrapping our menu.
			'container' => FALSE,

			// Nope, let's return instead of echo.
			'echo' => FALSE,

			// Let's use a <nav> instead of a nested list.
			'items_wrap' => '<nav role="navigation" class="%2$s">%3$s</nav>',

			// Which menu to grab?  Takes ID, name, or slug.
			'menu' => $which_menu,

			// CSS classes for our menu.
			'menu_class' => "$class $class-$which_menu",

			// Our custom walker.
			'walker' => new CSST_Nav_Walker(),

		);

		// The main content of the shortcode is in fact a call to wp_nav_menu().
		$out = wp_nav_menu( $menu_args );

		// Build and sanitize the output.
		//$out = wp_kses_post( $out );

		return $out;

	}
	
}