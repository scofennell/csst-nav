/**
 * Our plugin script.
 *
 * It's bundled up as a jQuery plugin, which might be overkill but
 * is also a nice way of wrapping things, making it easy to pass
 * options in, if desired. 
 *
 * @see https://learn.jquery.com/plugins/basic-plugin-creation/
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

/**
 * Upon dom ready, apply our jQuery plugin to elements with our CSS class.
 */
jQuery( document ).ready( function( $ ) {

	// All of our menus carry this CSS class.
	var el = $( '.csst_nav' );

	// Apply our jquery plugin to our menus.
	$( el ).csstNav();

});

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

		/**
		 * This gets called when the user clicks a submenu toggle link.
		 * 
		 * @param  {object} link The toggle link that was just clicked.
		 */
		function runToggle( link ) {

			// Find the submenu that this link applies to.
			var submenu = $( link ).closest( '.csst_nav_walker-item' ).find( ' > .csst_nav_walker-submenu' );

			// Find the icon that this link applies to.
			var icon = $( link ).find( ' > .csst_nav_walker-get_icon' );

			// Toggle classes on that submenu for show/hide.
			$( submenu ).toggleClass( 'csst_nav_walker-hide csst_nav_walker-show' );

			// Toggle classes on that icon for up/down.
			$( icon ).toggleClass( 'csst_nav_walker-get_icon-down csst_nav_walker-get_icon-up' );

			// Toggle classes on the menu item itself.
			$( link ).toggleClass( 'csst_nav_walker-item-toggle_link-open csst_nav_walker-item-toggle_link-closed' );

		}
		
		/**
		 * For each menu that carries our special css class...
		 */
		return this.each( function() {
	
			// Store "this" for later.
			var that = this;

			// The toggle link for show/hiding a submenu.
			var toggleHandle = $( that ).find( '.csst_nav_walker-item-toggle_link' );
			
			// The user has clicked th toggler!
			$( toggleHandle ).on( 'click', function( event ) {
				
				// Don't navigate to a new page.
				event.preventDefault();
			
				// Do the stuff that one does upon show/hiding a submenu.
				runToggle( this );

			});

		});

	}

} ( jQuery ) );