=== One Click Close Comments ===
Contributors: coffee2code
Donate link: http://coffee2code.com/donate
Tags: comments, close comments, open comments, admin, comment, discussion, commenting status, coffee2code
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Requires at least: 2.8
Tested up to: 3.5
Stable tag: 2.3
Version: 2.3

Conveniently close or open comments for a post or page with one click.


== Description ==

Conveniently close or open comments for a post or page with one click.

From the admin listing of posts ('Edit Posts') and pages ('Edit Pages'), a user can close or open comments to any posts to which they have sufficient privileges to make such changes (essentially admins and post authors for their own posts).  This is done via an AJAX-powered color-coded indicator.  The color-coding gives instant feedback on the current status of the post for comments: green means the post/page is open to comments, red means the post/page is closed to comments.  Being AJAX-powered means that the change is submitted in the background without requiring a page reload.

This plugin will only function for administrative users in the admin who have JavaScript enabled.

Links: [Plugin Homepage](http://coffee2code.com/wp-plugins/one-click-close-comments/) | [Plugin Directory Page](http://wordpress.org/extend/plugins/one-click-close-comments/) | [Author Homepage](http://coffee2code.com)


== Installation ==

1. Unzip `one-click-close-comments.zip` inside the `/wp-content/plugins/` directory for your site (or install via the built-in WordPress plugin installer)
1. Activate the plugin through the 'Plugins' admin menu in WordPress
1. When on the 'Edit Posts' or 'Edit Pages' admin pages, click the indicators to toggle the comment status for a post, as necessary.


== Frequently Asked Questions ==

= I can see the colored dots indicating current commenting status, but why aren't they clickable? =

The commenting status link/button is only clickable is you have JavaScript enabled.

= What does the color-coding of the dot mean? =

Green means the post is currently open for comments; red means the post is not currently open for comments.

= How can I customize the color-coding used for the dot? =

You can customize the colors via CSS.  `.comment-state-1` indicates comments are open.  `.comment-state-0` indicates comments are closed.

= How can I customize the dot used to represent commenting status? =

By default, commenting status is represented using the `&bull;` character.  You can change this by filtering `c2c_one_click_close_comments_click_char`.  Here's an example -- added to a theme's functions.php file -- to change it to a solid diamond:

`add_filter( 'c2c_one_click_close_comments_click_char', create_function('$a', 'return "&diams";') );`


== Screenshots ==

1. A screenshot of the 'Edit Posts' admin page with the plugin activated. (The full tool-tip reads: "Comments are closed. Click to open.")


== Filters ==

The plugin exposes one action for hooking.

= c2c_one_click_close_comments_click_char (action) =

The 'c2c_one_click_close_comments_click_char' hook allows you to use an alternative character or string as the plugin's indicator in the posts listing tables.  It is the character that get color-coded to indicate if comments are open or close, and the thing to click to toggle the comment open status.  By default this is a bullet, `&bull;` (a solid circle).

Arguments:

* $char (array): The character to be used for display (by default this is `&bull;`).

Example:

`
add_filter( 'c2c_one_click_close_comments_click_char', 'custom_one_click_char' );
function custom_one_click_char( $char ) {
	return '&diams;';  // Use a diamond character instead of the bullet
}
`


== Changelog ==

= 2.3 =
* Use string instead of variable to specify translation textdomain
* Remove load_config() and merge its contents into do_init()
* Add check to prevent execution of code if file is directly accessed
* Note compatibility through WP 3.5+
* Update copyright date (2013)
* Move screenshot into repo's assets directory

= 2.2.1 =
* Re-license as GPLv2 or later (from X11)
* Add 'License' and 'License URI' header tags to readme.txt and plugin file
* Add banner image for plugin page
* Remove ending PHP close tag
* Note compatibility through WP 3.4+

= 2.2 =
* Increase font size for click character to make it a larger click target
* Fix for one-click character not being clickable for quick-edited post rows
* Enqueue CSS and JavaScript rather than defining in, and outputting via, PHP
* Create 'assets' subdirectory and add admin.js and admin.css to it
* Add enqueue_scripts_and_styles(), register_styles(), enqueue_admin_css(), enqueue_admin_js()
* Remove add_css(), add_js()
* Hook 'load-edit.php' action to initialize plugin rather than using pagenow
* Add version() to return plugin version
* Create 'lang' subdirectory and move .pot file into it
* Regenerate .pot
* Note compatibility through WP 3.3+
* Add 'Domain Path' directive to top of main plugin file
* Add link to plugin directory page to readme.txt
* Update screenshot for WP 3.3
* Update copyright date (2012)

= 2.1.1 =
* Note compatibility through WP 3.2+
* Minor code formatting changes (spacing)
* Fix plugin homepage and author links in description in readme.txt

= 2.1 =
* Switch from object instantiation to direct class function invocation
* Rename the class from 'OneClickCloseComments' to 'c2c_OneClickCloseComments'
* Declare all class methods public static and class variables private static
* Output JS via 'admin_print_footer_scripts' action instead of 'admin_footer' action
* Rename filter from 'one-click-close-comments-click-char' to 'c2c_one_click_close_comments_click_char'
* Add Filters section to readme.txt
* Note compatibility through WP 3.1+
* Update copyright date (2011)

= 2.0.1 =
* Don't even define class unless in the admin section of site
* Store plugin instance in global variable, $c2c_one_click_close_comments, to allow for external manipulation
* Move registering actions and filters into init()
* Remove docs from top of plugin file (all that and more are in readme.txt)
* Note compatibility with WP 3.0+
* Minor tweaks to code formatting (spacing)
* Add Upgrade Notice section to readme.txt
* Remove trailing whitespace

= 2.0 =
* Display commenting status even if JS is disabled
* Render commenting status as a 'span' instead of an 'a' and use unobtrusive JS to make it clickable
* Insert column into desired position using PHP instead of JS
* Fix issue related to disappearance of button for a post after using Quick Edit
* Fix issue of 'Allow Comments' checkbox in 'Quick Edit' getting out of sync with actual comment status
* Allow filtering of character used as click link, via 'one-click-close-comments-click-char'
* Move initialization of config array out of constructor and into new function load_config()
* Create init() to handle calling load_textdomain() and load_config() (textdomain must be loaded before initializing config)
* Add support for localization
* Add PHPDoc documentation
* Add .pot file
* Note compatibility with WP 2.9+
* Drop compatibility with versions of WP older than 2.8
* Update documentation (descriptions, FAQs, etc) to reflect behavior changes
* Update copyright date

= 1.1 =
* Bail out early if not on pertinent admin pages
* Make use of admin_url() for path to admin section
* Note WP 2.8 compatibility

= 1.0 =
* Initial release


== Upgrade Notice ==

= 2.3 =
Minor update: a few internals changes; noted compatibility through WP 3.5+

= 2.2.1 =
Trivial update: noted compatibility through WP 3.4+; explicitly stated license

= 2.2 =
Recommended update. Increased size of button for closing comments; noted WP 3.3 compatibility; and more.

= 2.1 =
Minor update: renamed class, added Filters section to readme.txt, noted compatibility with WP 3.1+, and updated copyright date.

= 2.0.1 =
Minor update. Highlights: renamed class; minor non-functionality tweaks; verified WP 3.0 compatibility.