<?php

/**
 * @package CSST Nav
 */

/*
Plugin Name: CSST Nav
Plugin URI: https://css-tricks.com
Description: A widget for outputting a custom menu via a custom walker class.
Version: 0.1
Author: Scott Fennell
Author URI: http://scottfennell.org
License: GPLv2 or later
Text Domain: csst-nav
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

// Make sure we don't expose any info if called directly
if ( ! function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

define( 'CSST_NAV_VERSION', '0.1' );
define( 'CSST_NAV_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'CSST_NAV_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

require_once( CSST_NAV_PLUGIN_DIR . 'class.csst-nav-walker.php' );
require_once( CSST_NAV_PLUGIN_DIR . 'class.csst-nav-shortcode.php' );