<?php
/*
Plugin Name: One Click Close Comments
Version: 1.1
Plugin URI: http://coffee2code.com/wp-plugins/one-click-close-comments
Author: Scott Reilly
Author URI: http://coffee2code.com
Description: Conveniently close or open comments for a post or page with one click.

From the admin listing of posts ('Edit Posts') and pages ('Edit Pages'), a user can close or open comments to any
posts to which they have sufficient privileges to make such changes (essentially admins and post authors for their
own posts).  This is done via an AJAX-powered color-coded indicator.  The color-coding gives instant feedback on the
current status of the post for comments: green means the post/page is open to comments, red means the post/page is
closed to comments.  Being AJAX-powered means that the change is submitted in the background without requiring a
page reload.

This plugin will only function for administrative users in the admin who have JavaScript enabled.

Compatible with WordPress 2.6+, 2.7+, 2.8+.

=>> Read the accompanying readme.txt file for more information.  Also, visit the plugin's homepage
=>> for more information and the latest updates

Installation:

1. Download the file http://www.coffee2code.com/wp-plugins/one-click-close-comments.zip and unzip it into your 
/wp-content/plugins/ directory.
2. Activate the plugin through the 'Plugins' admin menu in WordPress
3. When on the 'Edit Posts' or 'Edit Pages' admin pages, click the indicator to toggle the comment status for a post, as necessary

*/

/*
Copyright (c) 2009 by Scott Reilly (aka coffee2code)

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation 
files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, 
modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the 
Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR
IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

if ( !class_exists('OneClickCloseComments') ) :

class OneClickCloseComments {
	var $css_class = 'comment_state';
	var $field = 'close_comments';
	var $field_title = 'Close Comments';
	var $nonce_field = 'update-close_comments';
	var $help_text = array();

	function OneClickCloseComments() {
		global $pagenow;
		if ( !is_admin() || !in_array($pagenow, array('admin-ajax.php', 'edit.php', 'edit-pages.php')) )
			return;

		$this->help_text = array(
			0 => __('Comments are closed. Click to open.'),
			1 => __('Comments are open. Click to close.')
		);
		$this->field_title = __($this->field_title);

		add_action('admin_head', array(&$this, 'add_css'));
		add_action('admin_footer', array(&$this, 'add_js'));
		add_filter('manage_posts_columns', array(&$this, 'add_post_column'));
		add_action('manage_posts_custom_column', array(&$this, 'handle_column_data'), 10, 2);
		add_filter('manage_pages_columns', array(&$this, 'add_post_column'));
		add_action('manage_pages_custom_column', array(&$this, 'handle_column_data'), 10, 2);
		add_action('wp_ajax_'.$this->field, array(&$this, 'toggle_comment_status'));
	}

	function toggle_comment_status() {
		$post_id = isset($_POST['post_id']) ? $_POST['post_id'] : null;
		check_ajax_referer( $this->field );
		if ( $post_id && current_user_can('edit_post', $post_id) ) {
			$post = get_post($post_id);
			if ( $post ) {
				global $wpdb;
				$new_status = ( 'open' == $post->comment_status ? 'closed' : 'open' );
				$wpdb->query( $wpdb->prepare("UPDATE $wpdb->posts SET comment_status = %s WHERE ID = %d", $new_status, $post_id) );
				echo ( 'open' == $new_status ? '1' : '0' );
			}
		}
		die();
	}

	function add_post_column( $posts_columns ) {
		$posts_columns[$this->field] = $this->field_title;
		return $posts_columns;
	}

	function handle_column_data( $column_name, $post_id ) {
		$post = get_post($post_id);
		$ajax_url = admin_url() . 'admin-ajax.php';
		if ( $this->field == $column_name ) {
			$auth = current_user_can('edit_post', $post_id);
			$state = ('open' == $post->comment_status ? 1 : 0);
			if ( $auth ) echo "<a href='$ajax_url' title='" . $this->help_text[$state] . "'>";
			echo "<span id='" . wp_create_nonce($this->field) . "' class='{$this->css_class}-{$state}'>&bull;</span>";
			if ( $auth ) echo '</a>';
			return;
		}
	}

	function add_css() {
		echo <<<CSS
		<style type="text/css">
		.column-{$this->field} { width:1em; display:none; }
		td.column-{$this->field} { padding:0; vertical-align:middle; text-align:center;}
		.{$this->css_class}-1 { font-size:18px; color:#00ff00; }
		.{$this->css_class}-0 { font-size:18px; color:#ff0000; }
		</style>

CSS;
	}

	function add_js() {
		echo <<<JS
		<script type="text/javascript">
		jQuery(document).ready(function($) {
			$(".column-comments").each(function() {
				$(this).parents('tr').find('.column-{$this->field}').insertBefore($(this)).show();
			});

			$("th.column-{$this->field}").text('').show();
 
			$(".{$this->field} a").click(function() {
				var span = $(this).find('span');
				var current_class = span.attr('class');
				var cclass = current_class.split('-');
				var new_class = cclass[0] + '-';
				var post_tr = $(this).parents('tr')
				var help_text = ["{$this->help_text[0]}", "{$this->help_text[1]}"];
				$.post($(this).attr('href'), {
						action: "{$this->field}",
						_ajax_nonce: span.attr('id'),
						post_id: post_tr.attr('id').substr(5)
					}, function(data) {
						if (data >= 0 && data <= 1) {
							span.removeClass(current_class);
							span.addClass(new_class + data);
							span.parent().attr('title', help_text[data]);
						}
					}, "text"
				);
				return false;
			});
		});
		</script>

JS;
	}
} // end OneClickCloseComments

endif; // end if !class_exists()

if ( class_exists('OneClickCloseComments') )
	new OneClickCloseComments();

?>