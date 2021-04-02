<?php
/**
 * Plugin Name: One Click Close Comments
 * Version:     2.7.1
 * Plugin URI:  https://coffee2code.com/wp-plugins/one-click-close-comments/
 * Author:      Scott Reilly
 * Author URI:  https://coffee2code.com/
 * Text Domain: one-click-close-comments
 * License:     GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Description: Conveniently close or open comments for a post or page with one click from the admin listing of posts.
 *
 * Compatible with WordPress 4.7 through 5.7+.
 *
 * =>> Read the accompanying readme.txt file for instructions and documentation.
 * =>> Also, visit the plugin's homepage for additional information and updates.
 * =>> Or visit: https://wordpress.org/plugins/one-click-close-comments/
 *
 * @package One_Click_Close_Comments
 * @author  Scott Reilly
 * @version 2.7.1
 */

/*
	Copyright (c) 2009-2021 by Scott Reilly (aka coffee2code)

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

if ( ! class_exists( 'c2c_OneClickCloseComments' ) ) :

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
		return '2.7.1';
	}

	/**
	 * Resets memoized variables.
	 *
	 * @since 2.7
	 */
	public static function reset() {
		self::$click_char = '';
	}

	/**
	 * Handles installation tasks, such as ensuring plugin options are instantiated and saved to options table.
	 */
	public static function init() {
		if ( ! is_admin() ) {
			return;
		}

		add_action( 'load-edit.php',         array( __CLASS__, 'do_init' ) );
		add_action( 'load-edit.php',         array( __CLASS__, 'enqueue_scripts_and_styles' ) );
		add_action( 'wp_ajax_'.self::$field, array( __CLASS__, 'toggle_comment_status' ) );

		if ( wp_doing_ajax() && isset( $_REQUEST['action'] ) && 'inline-save' === $_REQUEST['action'] ) {
			add_action( 'admin_init',        array( __CLASS__, 'do_init' ) );
		}
	}

	/**
	 * Handles actions to be hooked to 'init' action, such as loading text domain and loading plugin config data array.
	 */
	public static function do_init() {
		// Load textdomain.
		load_plugin_textdomain( 'one-click-close-comments' );

		// Set translatable and filterable strings.
		self::$help_text = array(
			0 => __( 'Comments are closed. Click to open.', 'one-click-close-comments' ),
			1 => __( 'Comments are open. Click to close.', 'one-click-close-comments' ),
			2 => __( 'Comments are closed.', 'one-click-close-comments' ),
			3 => __( 'Comments are open.', 'one-click-close-comments' ),
		);
		self::$field_title = '';

		// Register hooks.
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
		// Enqueues JS for admin page.
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_admin_js' ) );

		// Register and enqueue styles for admin page.
		self::register_styles();
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_admin_css' ) );
	}

	/**
	 * AJAX responder to toggle the comment status for a post (if user if
	 * authorized to do so).
	 *
	 * Possible echo values:
	 * - '1' if comments for post are now open
	 * - '0' if comments for post are now closed
	 * - '-1' if nonce check fails, post does not exist, user is not authorized
	 *
	 * @param bool $and_exit Exit after echoing result? Default true.
	 */
	public static function toggle_comment_status( $and_exit = true ) {
		$post_id = isset( $_POST['post_id'] ) ? (int) $_POST['post_id'] : null;
		check_ajax_referer( self::$field, false, $and_exit );

		$echo = '-1';

		if ( $post_id && current_user_can( 'edit_post', $post_id ) ) {
			$post = get_post( $post_id );
			if ( $post ) {
				global $wpdb;
				$new_status = ( 'open' === $post->comment_status ? 'closed' : 'open' );
				$wpdb->query( $wpdb->prepare( "UPDATE $wpdb->posts SET comment_status = %s WHERE ID = %d", $new_status, $post_id ) );
				$echo = ( 'open' === $new_status ? '1' : '0' );
			}
		}

		echo $echo;

		if ( false !== $and_exit ) {
			exit;
		}
	}

	/**
	 * Returns the click character or markup.
	 *
	 * @since 2.7
	 *
	 * @return string
	 */
	public static function get_click_char() {
		if ( self::$click_char ) {
			return self::$click_char;
		}

		/**
		 * Filters the character or markup used for the comment status toggle.
		 *
		 * @since 2.0
		 * @deprecated 2.1 Use 'c2c_one_click_close_comments_click_char'
		 *
		 * @param string $markup The character or markup for the comment status toggle.
		 *                       A dashicon can be used when specified with 'dashicons-'
		 *                       prefix. Default 'dashicons-admin-comments'.
		 */
		$click_char = apply_filters_deprecated(
			'one-click-close-comments-click-char',
			array( 'dashicons-admin-comments' ),
			'2.1.0',
			'c2c_one_click_close_comments_click_char'
		);

		/**
		 * Filters the character or markup used for the comment status toggle.
		 *
		 * Note: This is the renamed successor to the original filter
		 * 'one-click-close-comments-click-char'.
		 *
		 * @since 2.1
		 *
		 * @param string $markup The character or markup for the comment status toggle.
		 *                       A dashicon can be used when specified with 'dashicons-'
		 *                       prefix. Default 'dashicons-admin-comments'.
		 */
		$click_char = apply_filters( 'c2c_one_click_close_comments_click_char', $click_char );

		if ( 0 === strpos( $click_char, 'dashicons-' ) ) {
			$click_char = sprintf( '<span class="dashicons %s"></span>', esc_attr( $click_char ) );
		}

		return self::$click_char = $click_char;
	}

	/**
	 * Adds a column for the one-click close button to the table of posts in the admin.
	 *
	 * @param array  $posts_columns Array of post column titles.
	 * @return array The $posts_columns array with the one-click close comment column's title added.
	 */
	public static function add_post_column( $posts_columns ) {
		// Insert column just before the comments count column.  If that column isn't visible to user, put at end.
		if ( $i = array_search( 'comments', array_keys( $posts_columns ), true ) ) {
			$posts_columns = array_slice( $posts_columns, 0, $i, true )
				+ array( self::$field => self::$field_title )
				+ array_slice( $posts_columns, $i, null, true );
		} else {
			$posts_columns[ self::$field ] = self::$field_title;
		}

		return $posts_columns;
	}

	/**
	 * Outputs the one-click close button for each post listed in the post listing table in the admin.
	 *
	 * @param string $column_name The name of the column.
	 * @param int    $post_id     The id of the post being displayed.
	 */
	public static function handle_column_data( $column_name, $post_id ) {
		$post = get_post( $post_id );

		if ( self::$field === $column_name ) {
			$auth = current_user_can( 'edit_post', $post_id );
			$state = ( 'open' === $post->comment_status ? 1 : 0 );
			$help_text_index = $auth ? $state : $state + 2;

			printf(
				'<span id="%s" class="%s-%s" title="%s" aria-hidden="true">%s</span>',
				esc_attr( wp_create_nonce( self::$field ) ),
				esc_attr( self::$css_class ),
				esc_attr( $state ),
				esc_attr( self::$help_text[ $help_text_index ] ),
				self::get_click_char()
			);

			echo '<span class="screen-reader-text">' . self::$help_text[ $help_text_index ] . '</span>';

			return;
		}
	}

	/**
	 * Registers styles.
	 *
	 * @since 2.2
	 */
	public static function register_styles() {
		wp_register_style( __CLASS__, plugins_url( 'assets/admin.css', __FILE__ ), array(), self::version() );
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
		wp_enqueue_script( __CLASS__, plugins_url( 'assets/admin.js', __FILE__ ), array( 'jquery' ), self::version(), true );

		$text = array(
			'comments_closed_text' => self::$help_text[0],
			'comments_opened_text' => self::$help_text[1]
		);
		wp_localize_script( __CLASS__, __CLASS__, $text );
	}

} // end c2c_OneClickCloseComments

add_action( 'plugins_loaded', array( 'c2c_OneClickCloseComments', 'init' ) );

endif; // end if !class_exists()
