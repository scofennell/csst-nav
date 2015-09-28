<?php

/**
 * Register CSST_Nav widget.
 */
function CSST_nav_widget() {
	register_widget( 'CSST_Nav' );
}
add_action( 'widgets_init', 'CSST_nav_widget' );

class CSST_Nav extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'csst_nav',
			__( 'CSST Nav', 'csst-nav' ),
			array(
				'description' => __( 'A Cool Nav Menu Widget', 'csst-nav' ),
			)
		);

		add_action( 'wp_head', array( $this, 'inline_styles' ) );
		add_action( 'wp_footer', array( $this, 'inline_script' ) );

	}

	public function inline_styles() {

		if( is_admin() ) { return FALSE; }

		$out = <<<EOT

			<style>
				.csst_nav_walker-item {
					display: block;
					position: relative;

				}

				.csst_nav_walker-submenu {
					margin-left: 2em;
				}

				.csst_nav_walker-hide,
				.csst_nav_walker-show {
					display: block;
					overflow: hidden;
				}

				.csst_nav_walker-hide {
					max-height: 0;
					opacity: 0;
				}

				.csst_nav_walker-show {
					transition: opacity .5s ease-in-out;
					max-height: auto;
					opacity: 1;
				}

				.csst_nav_walker-get_icon {
					width: 0; 
					height: 0; 
					border-style: solid;
					border-width: .5em;
					border-color: transparent;
					display: inline-block;
					transform: scale( .75 );
					vertical-align: middle;
					margin: 1px 3px 0;
				}

				.csst_nav_walker-get_icon-down {
					border-bottom: none;
					border-top: calc( 1em * .866 ) solid currentColor;
				}

				.csst_nav_walker-get_icon-up {
					border-top: none;
					border-bottom: calc( 1em * .866 ) solid currentColor;
				}

			</style>

EOT;

		echo $out;

	}

	public function inline_script() {

		if( is_admin() ) { return FALSE; }

		$out = <<<EOT

			<script>
				
				jQuery( document ).ready( function() {


					// Grab all the inputs, by type, that are worthy of clear-on-focus behavior.
					var el = jQuery( '.csst_nav' );

					jQuery( el ).csstNav();

				});

				( function ( $ ) {

					$.fn.csstNav = function( options ) {

						// For each input...
						return this.each( function() {
					
							var that = this;

							var toggleHandle = jQuery( that ).find( '.csst_nav_walker-item-toggle_link' );
							var toggleHandleClosed = jQuery( that ).find( '.csst_nav_walker-item-toggle_link-closed' );
							var toggleHandleOpen = jQuery( that ).find( '.csst_nav_walker-item-toggle_link-open' );

							jQuery( document ).on( 'click', '.csst_nav_walker-item-toggle_link-closed', function( event ){
								event.preventDefault();
						
								jQuery( this ).closest( '.csst_nav_walker-item' ).find( ' > .csst_nav_walker-hide' ).removeClass( 'csst_nav_walker-hide' ).addClass( 'csst_nav_walker-show' );

								jQuery( this ).toggleClass( 'csst_nav_walker-item-toggle_link-closed' );
								jQuery( this ).toggleClass( 'csst_nav_walker-item-toggle_link-open' );

								jQuery( this ).find( ' > .csst_nav_walker-get_icon-down' ).toggleClass( 'csst_nav_walker-get_icon-down csst_nav_walker-get_icon-up' );
						
							});

							jQuery( document ).on( 'click', '.csst_nav_walker-item-toggle_link-open', function( event ){
								event.preventDefault();

								jQuery( this ).closest( '.csst_nav_walker-item' ).find( ' > .csst_nav_walker-show' ).removeClass( 'csst_nav_walker-show' ).addClass( 'csst_nav_walker-hide' );

								jQuery( this ).toggleClass( 'csst_nav_walker-item-toggle_link-closed' );
								jQuery( this ).toggleClass( 'csst_nav_walker-item-toggle_link-open' );



								jQuery( this ).find( ' > .csst_nav_walker-get_icon-up' ).toggleClass( 'csst_nav_walker-get_icon-down csst_nav_walker-get_icon-up' );

							});

						});

					}

				}( jQuery ) );

			</script>

EOT;

		echo $out;

	}	

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		
		// The CSS class for our widget.
		$class = strtolower( __CLASS__ );

		// Get a menu from the db.
		$which_menu = FALSE;
		if( isset( $instance['which_menu'] ) ) {
			$which_menu = sanitize_html_class( $instance['which_menu'] );
		}

		// Args for a call to wp_nav_menu().
		$menu_args = array(
			'menu'            => $which_menu,
			'container'       => FALSE,
			'container_class' => FALSE,
			'container_id'    => FALSE,
			'menu_class'      => "$class $class-$which_menu",
			'menu_id'         => FALSE,
			'echo'            => FALSE,
			'fallback_cb'     => FALSE,
			'before'          => FALSE,
			'after'           => FALSE,
			'link_before'     => FALSE,
			'link_after'      => FALSE,
			'items_wrap'      => '<nav role="navigation" class="%2$s">%3$s</nav>',
			'walker'          => new CSST_Nav_Walker(),
		);

		// The main content of the widget is in fact a call to wp_nav_menu().
		$content = wp_nav_menu( $menu_args );
	
		// Build the widget title, wrapping it as defined in theme.
		$before_title = $args['before_title'];
		$title_text   = esc_html__( 'CSS Tricks Demo Title', 'csst-nav' );
		$after_title  = $args['after_title'];
		$title        = $before_title . $title_text . $after_title;

		// Grab the widget wrappers as per theme-defined values.
		$before_widget = $args['before_widget'];
		$after_widget = $args['after_widget'];

		// Build and sanitize the output.
		$out = $before_widget . $title . $content . $after_widget;
		$out = wp_kses_post( $out );

		echo $out;

	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		
		$instance = array();
		
		// Save the value for which menu to grab.
		$instance['which_menu']= sanitize_text_field( $new_instance['which_menu'] );
		
		return $instance;
	
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		
		// Which menu to grab?
		$which_menu = FALSE;
		if ( isset( $instance['which_menu'] ) ) {
			$which_menu = $instance['which_menu'];
		}

		// Attrs for our select input for choosing which nav menu.
		$id     = esc_attr( $this -> get_field_id( 'which_menu' ) );
		$name   = esc_attr( $this -> get_field_name( 'which_menu' ) );
		$label  = esc_html__( 'Which Menu?', 'csst-nav' );
		$options =  $this -> get_menus_as_options( $which_menu );
		
		// The widget control in wp-admin.
		$out = "
			<p>
				<label for='$id'>$label</label>
				<select name='$name' id='$id'>
					$options
				</select>
			</p>
		";

		echo $out;

	}

	/**
	 * Get all custom menus a options for an HTML select menu.
	 * 
	 * The menu ID will be the option name, with the menu slug as the label.
	 * 
	 * @param  $which_menu Which nav menu to pre-populate the sticky input.
	 * @return array All custom menus as an associative array.
	 */
	public function get_menus_as_options( $which_menu ) {

		// Start with an empty option, prompting the user to select a menu.
		$please = esc_html__( 'Please choose a menu.', 'csst-nav' );
		$out    = "<option value='0'>$please</option>";

		// Get all nav menus.
		$menus = get_terms(
			'nav_menu',
			array(
				'hide_empty' => FALSE,
				'fields'     => 'id=>name',
			)
		);

		// For each menu as id => label...
		foreach( $menus as $k => $v ) {

			// Is this the currently selected item?
			$selected = selected( $which_menu, $k, FALSE );
			
			// The option value is the menu ID.
			$id = esc_attr( $k );
			
			// The option label is the menu name.
			$label = esc_html( $v );

			$out .= "<option value='$id' $selected>$label</option>";

		}

		return $out;

	}
	
}