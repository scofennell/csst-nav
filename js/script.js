jQuery( document ).ready( function( $ ) {

	var el = $( '.csst_nav' );

	$( el ).csstNav();

});

( function ( $ ) {

	$.fn.csstNav = function( options ) {

		function runToggle( link ) {

			$( link ).toggleClass( 'csst_nav_walker-item-toggle_link-closed' );
			$( link ).toggleClass( 'csst_nav_walker-item-toggle_link-open' );

			$( link ).closest( '.csst_nav_walker-item' ).find( ' > .csst_nav_walker-submenu' ).toggleClass( 'csst_nav_walker-hide csst_nav_walker-show' );


			$( link ).find( ' > .csst_nav_walker-get_icon' ).toggleClass( 'csst_nav_walker-get_icon-down csst_nav_walker-get_icon-up' );

		}
		
		return this.each( function() {
	
			var that = this;

			var toggleHandle = $( that ).find( '.csst_nav_walker-item-toggle_link' );
			
			$( toggleHandle ).on( 'click', function( event ) {
				
				event.preventDefault();
			
				runToggle( this );

			});

		});

	}

} ( jQuery ) );