<?php 
/*
Plugin Name: WP Block Pack
Plugin URI: https://wpblockpack.com
Description: A development plugin that serving additional feature for the new WordPress block editor - Gutenberg.
Version: 0.7.0
Author: Falcon Team
Author URI: https://falcontheme.com/team
License: GPLv2 or later
Text Domain: wp-block-pack

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

Copyright 2018  WP Block Pack Team (email : support@wpblockpack.com)
*/

define( 'WPBLOCKPACK_VER', '0.7.0' );
define( 'WPBLOCKPACK_FILE_', __FILE__ );
define( 'WPBLOCKPACK_PLUGIN_BASE', plugin_basename( WPBLOCKPACK_FILE_ ) );
define( 'WPBLOCKPACK_PATH', plugin_dir_path( WPBLOCKPACK_FILE_ ) );

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit; 

// Stop working if Gutenberg not activated
if ( ! function_exists('the_gutenberg_project') ) {
	function wpblockpack_error_notice() {
?>
	<div class="error notice">
	    <p><?php _e( '<strong>WP Block Pack</strong> plugin can\'t get to work. Because it doesn\'t detect Gutenberg plugin activated. Please activate it first. Thank you!', 'wp-block-pack' ); ?></p>
	</div>
<?php
	}
	add_action( 'admin_notices', 'wpblockpack_error_notice' );
	return;
}

// Load The Back-End Area 
require( WPBLOCKPACK_PATH . 'back/back.php' );

// Load The Front-End Area
require( WPBLOCKPACK_PATH . 'front/front.php' );