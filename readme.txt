=== One Click Close Comments ===
Contributors: coffee2code
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=6ARCFJ9TX3522
Tags: comments, close comments, open comments, admin, comment, discussion, commenting status, coffee2code
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Requires at least: 4.6
Tested up to: 4.9
Stable tag: 2.5

Conveniently close or open comments for a post or page with one click.


== Description ==

From the admin listing of posts ('Edit Posts') and pages ('Edit Pages'), a user can close or open comments to any posts to which they have sufficient privileges to make such changes (essentially admins and post authors for their own posts). This is done via an AJAX-powered color-coded indicator. The color-coding gives instant feedback on the current status of the post for comments: green means the post/page is open to comments, red means the post/page is closed to comments. Being AJAX-powered means that the change is submitted in the background without requiring a page reload.

This plugin will only function for administrative users in the admin who have JavaScript enabled.

Links: [Plugin Homepage](http://coffee2code.com/wp-plugins/one-click-close-comments/) | [Plugin Directory Page](https://wordpress.org/plugins/one-click-close-comments/) | [GitHub](https://github.com/coffee2code/one-click-close-comments/) | [Author Homepage](http://coffee2code.com)


== Installation ==

1. Install via the built-in WordPress plugin installer. Or download and unzip `one-click-close-comments.zip` inside the plugins directory for your site (typically `wp-content/plugins/`)
2. Activate the plugin through the 'Plugins' admin menu in WordPress
3. When on the 'Edit Posts' or 'Edit Pages' admin pages, click the indicators to toggle the comment status for a post, as necessary.


== Frequently Asked Questions ==

= I can see the colored dots indicating current commenting status, but why aren't they clickable? =

The commenting status link/button is only clickable is you have JavaScript enabled.

= What does the color-coding of the dot mean? =

Green means the post is currently open for comments; red means the post is not currently open for comments.

= How can I customize the color-coding used for the dot? =

You can customize the colors via CSS. `.comment-state-1` indicates comments are open. `.comment-state-0` indicates comments are closed.

= How can I customize the dot used to represent commenting status? =

By default, commenting status is represented using the `&bull;` character. You can change this by filtering `c2c_one_click_close_comments_click_char`. Here's an example -- added to a theme's functions.php file -- to change it to a solid diamond:

`add_filter( 'c2c_one_click_close_comments_click_char', create_function('$a', 'return "&diams";') );`


== Screenshots ==

1. A screenshot of the 'Posts' admin page with the plugin activated. The tooltip reads: "Comments are open. Click to close." Were the mouse to hover over the red indicator, the tooltip would read: "Comments are closed. Click to open."


== Hooks ==

The plugin exposes one action for hooking. Such code should ideally be put into a mu-plugin or site-specific plugin (which is beyond the scope of this readme to explain).

**c2c_one_click_close_comments_click_char (action)**

The 'c2c_one_click_close_comments_click_char' hook allows you to use an alternative character or string as the plugin's indicator in the posts listing tables. It is the character that get color-coded to indicate if comments are open or close, and the thing to click to toggle the comment open status. By default this is a bullet, `&bull;` (a solid circle).

Arguments:

* $char (array): The character to be used for display (by default this is `&bull;`).

Example:

`
/**
 * Changes the character used as the one-click link to a diamond.
 *
 * @param string $char The default character (a bullet)
 * @return string
 */
function custom_one_click_char( $char ) {
	return '&diams;';
}
add_filter( 'c2c_one_click_close_comments_click_char', 'custom_one_click_char' );
`


== Changelog ==

= () =
* Change: Update copyright date (2019)
* Change: Update License URI to be HTTPS


= 2.5 (2018-08-03) =
* Change: Improve display of control toggle (and label) on smaller viewports
* Change: Include plugin version number when registering styles
* New: Add README.md
* Change: Add explicit curly braces to JS 'if' statement
* Change: Add GitHub link to readme
* Change: Note compatibility through WP 4.9+
* Change: Update copyright date (2018)
* Change: Rename readme.txt section from 'Filters' to 'Hooks'
* Change: Modify formatting of hook name in readme to prevent being uppercased when shown in the Plugin Directory

= 2.4 (2017-02-04) =
* Change: Improve accessbility (a11y)
    * Add descriptive text for close/open link to display instead of the indicator character for screen readers
    * Change colors to be WCAG AA compliant
* Change: Use `printf()` to format output markup rather than concatenating strings, variables, and function calls.
* Change: Escape variables used as markup attributes (hardening; none of the instances are user input).
* Change: Note compatibility through WP 4.7+.
* Change: Remove support for WordPress older than 4.6 (should still work for earlier versions back to WP 3.1)
* Change: Minor code reformatting (add spacing between sections of code).
* Change: Minor readme.txt improvements.
* Change: Update copyright date (2017).
* Change: Update screenshot.

= 2.3.5 (2016-03-16) =
* Change: Add support for language packs:
    * Don't load textdomain from file.
    * Remove .pot file and /lang subdirectory.
    * Remove 'Domain Path' from plugin header.
* New: Add LICENSE file.
* New: Add empty index.php to prevent files from being listed if web server has enabled directory listings.
* Change: Note compatibility through WP 4.4+.
* Change: Update copyright date (2016).

= 2.3.4 (2015-09-15) =
* Bugfix: Really revert back to using `dirname(__FILE__)`; __DIR__ is only PHP 5.3+
* Change: Note compatibility through WP 4.3+.

= 2.3.3 (2015-03-12) =
* Revert back to using `dirname(__FILE__)`; __DIR__ is only PHP 5.3+

= 2.3.2 (2015-02-18) =
* Reformat plugin header
* Use __DIR__ instead of `dirname(__FILE__)`
* Minor code reformatting (spacing, bracing)
* Minor documentation spacing changes throughout
* Change documentation links to wp.org to be https
* Note compatibility through WP 4.1+
* Update copyright date (2015)
* Add plugin icon
* Rengenerate .pot

= 2.3.1 =
* Minor code tweaks (spacing)
* Note compatibility through WP 3.8+
* Update copyright date (2014)
* Change donate link
* Update banner image to reflect WP 3.8 admin refresh
* Update screenshot to reflect WP 3.8 admin refresh

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

= 2.5 =
Minor update: improved display of control toggle (and label) on smaller viewports; verified compatibility through WP 4.9+; updated copyright date (2018); other minor tweaks

= 2.4 =
Minor update: improved accessibility, compatibility is now WP 4.6-4.7+, updated copyright date (2017), and more

= 2.3.5 =
Minor update: added support for language packs; verified compatibility through WP 4.4; updated copyright date (2016)

= 2.3.4 =
Minor bugfix release for users running PHP 5.2.x: revert use of a constant only defined in PHP 5.3+. You really should upgrade your PHP or your host if this affects you. Also noted compatibility with WP 4.3+.

= 2.3.3 =
Minor bugfix release for users running PHP 5.2.x: revert use of a constant only defined in PHP 5.3+. You really should upgrade your PHP or your host if this affects you.

= 2.3.2 =
Trivial update: noted compatibility through WP 4.1+; added plugin icon

= 2.3.1 =
Trivial update: updated banner and screenshot images; noted compatibility through WP 3.8+

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
