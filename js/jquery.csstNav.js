/**
 * Our plugin script.
 *
 * It's bundled up as a jQuery plugin, which might be overkill but
 * is also a nice way of wrapping things, making it easy to pass
 * options in, if desired. 
 *
 * @see https://learn.jquery.com/plugins/basic-plugin-creation/
 * @package WordPress
 * @subpackage CSST_Nav
 * @since CSST Nav 1.0
 */

/**
 * Protect the '$' alias from other JS.
 *
 * @see   https://learn.jquery.com/plugins/basic-plugin-creation/#protecting-the-alias-and-adding-scope
 * @param {object} $ The jQuery object.
 */
( function ( $ ) {

	/**
	 * Finally, our plugin.
	 * 
	 * @param  {object} options We could pass options when invoking.
	 * @return {object} The dom element that carries our special css class, with our plugin applied to it.
	 */
	$.fn.csstNav = function( options ) {

		// Grab the selector that was used to invoke the plugin.
		var selector = this.selector;

		// We'll use this to build css classes.
		var prefix = 'csst_nav';

		// Log our plugin.
		console.log( 'csstNav() applied to `' + selector + '`. Options:' );
		console.log( options );

		/**
		 * This gets called when the user clicks a submenu toggle link.
		 * 
		 * @param  {object} link The toggle link that was just clicked.
		 */
		function runToggle( link ) {
			
			// Find the submenu that this link applies to.
			var submenu = $( link ).siblings( '.' + prefix + '_walker-submenu' );

			// Toggle classes on that submenu for show/hide.
			$( submenu ).toggleClass( prefix + '_walker-hide' ); 
			$( submenu ).toggleClass( prefix + '_walker-show' );

			// Toggle classes on the menu item itself.
			$( link ).toggleClass( prefix + '_walker-item-toggle_link-open' );
			$( link ).toggleClass( prefix + '_walker-item-toggle_link-closed' );

		}
		
		/**
		 * For each menu that carries our special css class...
		 */
		return this.each( function() {
	
			// Store "this" for later.
			var that = this;

			// The toggle link for show/hiding a submenu.
			var toggleHandle = $( that ).find( '.csst_nav_walker-item-toggle_link' );
			
			// The user has clicked the toggler!
			$( toggleHandle ).on( 'click', function( event ) {
				
				// Don't navigate to a new page.
				event.preventDefault();
			
				// Do the stuff that one does upon show/hiding a submenu.
				runToggle( this );

			});

		});

	}

} ( jQuery ) );