<?php
/**
 * @package One_Click_Close_Comments
 * @author Scott Reilly
 * @version 2.3
 */
/*
Plugin Name: One Click Close Comments
Version: 2.3
Plugin URI: http://coffee2code.com/wp-plugins/one-click-close-comments/
Author: Scott Reilly
Author URI: http://coffee2code.com/
Text Domain: one-click-close-comments
Domain Path: /lang/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Description: Conveniently close or open comments for a post or page with one click.

Compatible with WordPress 2.8 through 3.5+.

=>> Read the accompanying readme.txt file for instructions and documentation.
=>> Also, visit the plugin's homepage for additional information and updates.
=>> Or visit: http://wordpress.org/extend/plugins/one-click-close-comments/

TODO:
	* Add template tag (or inject via filter) an AJAX link for admins (and post authors) to close link from the front-end
*/

/*
	Copyright (c) 2009-2013 by Scott Reilly (aka coffee2code)

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

defined( 'ABSPATH' ) or die();

if ( is_admin() && ! class_exists( 'c2c_OneClickCloseComments' ) ) :

class c2c_OneClickCloseComments {
	private static $css_class   = 'comment_state'; /* Changing this requires changing .css and .js files */
	private static $field       = 'close_comments'; /* Changing this requires changing .css and .js files */
	private static $nonce_field = 'update-close_comments';
	private static $field_title = '';
	private static $click_char  = '';
	private static $help_text   = array();

	/**
	 * Returns version of the plugin.
	 *
	 * @since 2.2
	 *
	 * @return string Version number as string
	 */
	public static function version() {
		return '2.3';
	}

	/**
	 * Handles installation tasks, such as ensuring plugin options are instantiated and saved to options table.
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'load-edit.php',         array( __CLASS__, 'do_init' ) );
		add_action( 'load-edit.php',         array( __CLASS__, 'enqueue_scripts_and_styles' ) );
		add_action( 'load-edit-pages.php',   array( __CLASS__, 'do_init' ) ); /* backcompat for pre-WP3.1ish */
		add_action( 'load-edit-pages.php',   array( __CLASS__, 'enqueue_scripts_and_styles' ) ); /* backcompat for pre-WP3.1ish */
		add_action( 'wp_ajax_'.self::$field, array( __CLASS__, 'toggle_comment_status' ) );

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX && isset( $_REQUEST['action'] ) && 'inline-save' == $_REQUEST['action'] )
			add_action( 'admin_init',        array( __CLASS__, 'do_init' ) );
	}

	/**
	 * Handles actions to be hooked to 'init' action, such as loading text domain and loading plugin config data array.
	 *
	 * @return void
	 */
	public static function do_init() {

		// Load textdomain
		load_plugin_textdomain( 'one-click-close-comments', false, basename( dirname( __FILE__ ) ) );

		// Set translatable and filterable strings
		self::$help_text = array(
			0 => __( 'Comments are closed. Click to open.', 'one-click-close-comments' ),
			1 => __( 'Comments are open. Click to close.', 'one-click-close-comments' )
		);
		self::$field_title = '';
		self::$click_char = apply_filters( 'one-click-close-comments-click-char', '&bull;' ); /* Deprecated! */
		self::$click_char = apply_filters( 'c2c_one_click_close_comments_click_char', self::$click_char );

		// Register hooks
		add_filter( 'manage_posts_columns',       array( __CLASS__, 'add_post_column' ) );
		add_action( 'manage_posts_custom_column', array( __CLASS__, 'handle_column_data' ), 10, 2 );
		add_filter( 'manage_pages_columns',       array( __CLASS__, 'add_post_column' ) );
		add_action( 'manage_pages_custom_column', array( __CLASS__, 'handle_column_data' ), 10, 2 );
	}

	/**
	 * Enqueues styles and scripts.
	 *
	 * @since 2.2
	 */
	public static function enqueue_scripts_and_styles() {
		// Enqueues JS for admin page
		add_action( 'admin_enqueue_scripts',      array( __CLASS__, 'enqueue_admin_js' ) );
		// Register and enqueue styles for admin page
		self::register_styles();
		add_action( 'admin_enqueue_scripts',      array( __CLASS__, 'enqueue_admin_css' ) );
	}

	/**
	 * AJAX responder to toggle the comment status for a post (if user if authorized to do so).
	 *
	 * @return void
	 */
	public static function toggle_comment_status() {
		$post_id = isset( $_POST['post_id'] ) ? $_POST['post_id'] : null;
		check_ajax_referer( self::$field );
		if ( $post_id && current_user_can( 'edit_post', $post_id ) ) {
			$post = get_post( $post_id );
			if ( $post ) {
				global $wpdb;
				$new_status = ( 'open' == $post->comment_status ? 'closed' : 'open' );
				$wpdb->query( $wpdb->prepare( "UPDATE $wpdb->posts SET comment_status = %s WHERE ID = %d", $new_status, $post_id ) );
				echo ( 'open' == $new_status ? '1' : '0' );
			}
		}
		die();
	}

	/**
	 * Adds a column for the one-click close button to the table of posts in the admin.
	 *
	 * @param array $posts_columns Array of post column titles.
	 * @return array The $posts_columns array with the one-click close comment column's title added.
	 */
	public static function add_post_column( $posts_columns ) {
		// Insert column just before the comments count column.  If that column isn't visible to user, put at end.
		if ( array_key_exists( 'comments', $posts_columns ) ) {
			// Damn PHP for not facilitating this.
			$new_cols = array();
			foreach ( $posts_columns as $k => $v ) {
				if ( $k == 'comments' )
					$new_cols[self::$field] = self::$field_title;
				$new_cols[$k] = $v;
			}
			$posts_columns = $new_cols;
		} else {
			$posts_columns[self::$field] = self::$field_title;
		}
		return $posts_columns;
	}

	/**
	 * Outputs the one-click close button for each post listed in the post listing table in the admin.
	 *
	 * @param string $column_name The name of the column.
	 * @param int $post_id The id of the post being displayed.
	 * @return void
	 */
	public static function handle_column_data( $column_name, $post_id ) {
		$post = get_post( $post_id );
		if ( self::$field == $column_name ) {
			$auth = current_user_can( 'edit_post', $post_id );
			$state = ( 'open' == $post->comment_status ? 1 : 0 );

			if ( $auth )
				echo "<span title='" . esc_attr( self::$help_text[$state] ) . "'>";
			echo "<span id='" . wp_create_nonce( self::$field ) . "' class='" . self::$css_class . "-{$state}'>" . self::$click_char . '</span>';
			if ( $auth )
				echo '</span>';
			return;
		}
	}

	/**
	 * Registers styles.
	 *
	 * @since 2.2
	 */
	public static function register_styles() {
		wp_register_style( __CLASS__, plugins_url( 'assets/admin.css', __FILE__ ) );
	}

	/**
	 * Enqueues stylesheets.
	 *
	 * @since 2.2
	 */
	public static function enqueue_admin_css() {
		wp_enqueue_style( __CLASS__ );
	}

	/**
	 * Enqueues scripts.
	 *
	 * @since 2.2
	 */
	public static function enqueue_admin_js() {
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( __CLASS__, plugins_url( 'assets/admin.js', __FILE__ ), array( 'jquery' ), self::version(), true );
		$text = array(
			'comments_closed_text' => self::$help_text[0],
			'comments_opened_text' => self::$help_text[1]
		);
		wp_localize_script( __CLASS__, __CLASS__, $text );
	}

} // end c2c_OneClickCloseComments

c2c_OneClickCloseComments::init();

endif; // end if !class_exists()
