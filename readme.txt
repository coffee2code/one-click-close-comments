=== One Click Close Comments ===
Contributors: coffee2code
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=6ARCFJ9TX3522
Tags: comments, close comments, open comments, admin, comment, discussion, commenting status, coffee2code
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Requires at least: 4.7
Tested up to: 5.3
Stable tag: 2.6

Conveniently close or open comments for a post or page with one click from the admin listing of posts.


== Description ==

From the admin listing of posts ('Edit Posts') and pages ('Edit Pages'), a user can close or open comments to any posts to which they have sufficient privileges to make such changes (essentially admins and post authors for their own posts). This is done via an AJAX-powered color-coded indicator. The color-coding gives instant feedback on the current status of the post for comments: green means the post/page is open to comments, red means the post/page is closed to comments. Being AJAX-powered means that the change is submitted in the background after being clicked without requiring a page reload.

This plugin will only function for administrative users in the admin who have JavaScript enabled.

Links: [Plugin Homepage](http://coffee2code.com/wp-plugins/one-click-close-comments/) | [Plugin Directory Page](https://wordpress.org/plugins/one-click-close-comments/) | [GitHub](https://github.com/coffee2code/one-click-close-comments/) | [Author Homepage](http://coffee2code.com)


== Installation ==

1. Install via the built-in WordPress plugin installer. Or download and unzip `one-click-close-comments.zip` inside the plugins directory for your site (typically `wp-content/plugins/`)
2. Activate the plugin through the 'Plugins' admin menu in WordPress
3. When on the 'Edit Posts' or 'Edit Pages' admin pages, click the indicators to toggle the comment status for a post, as necessary.


== Frequently Asked Questions ==

= I can see the colored comment bubble indicating current commenting status, but why aren't they clickable? =

The commenting status link/button is only clickable is you have JavaScript enabled.

= What does the color-coding of the comment bubble mean? =

Green means the post is currently open for comments; red means the post is not currently open for comments.

= How can I customize the color-coding used for the comment bubble? =

You can customize the colors via CSS. `.comment-state-1` indicates comments are open. `.comment-state-0` indicates comments are closed.

= How can I customize the character used to represent commenting status? =

By default, commenting status is represented using the comment bubble dashicon, `dashicons-admin-comments`. You can change this by filtering `c2c_one_click_close_comments_click_char`. Here's an example -- added to a theme's functions.php file -- to change it to the original bullet
(solid circle) character:

`add_filter( 'c2c_one_click_close_comments_click_char', function ( $a ) { return "&bull;"; } );`


== Screenshots ==

1. A screenshot of the 'Posts' admin page with the plugin activated. The tooltip (from hovering over a green indicator) reads: "Comments are open. Click to close." Were the mouse to hover over a red indicator, the tooltip would read: "Comments are closed. Click to open."


== Hooks ==

The plugin exposes one filter for hooking. Such code should ideally be put into a mu-plugin or site-specific plugin (which is beyond the scope of this readme to explain).

**c2c_one_click_close_comments_click_char (filter)**

The 'c2c_one_click_close_comments_click_char' hook allows you to use an alternative character, string, or markup as the plugin's indicator in the posts listing tables. It is the character that gets color-coded to indicate if comments are open or close, and the thing to click to toggle the comment open status. You can make use of [Dashicons](https://developer.wordpress.org/resource/dashicons/) by specifying the desired dashicon's name (with the "dashicons-" prefix). By default this is the comments dashicon, `dashicons-admin-comments`.

Arguments:

* $char (array): The character, string, or markup to be used for display (by default this is `dashicons-admin-comments`).

Example:

`
/**
 * Changes the character used as the one-click link to a bullet (solid circle).
 *
 * @param string $char The default character.
 * @return string
 */
function custom_one_click_char( $char ) {
	return '&bull;';
}
add_filter( 'c2c_one_click_close_comments_click_char', 'custom_one_click_char' );
`


== Changelog ==

= 2.6 (2019-03-22) =
* New: Add support for using dashicons for the click character
* Change: Replace the bullet character (solid circle) with comment bubble dashicon as column icon for one-click link
* New: Add CHANGELOG.md file and move all but most recent changelog entries into it
* New: Add inline documentation for hook
* Change: Initialize plugin on 'plugins_loaded' action instead of on load
* Change: Use `apply_filters_deprecated()` when using the deprecated filter
* Change: Use `wp_doing_ajax()` for official detection of use of AJAX
* Change: Tweak plugin description
* Change: Split paragraph in README.md's "Support" section into two
* Change: Note compatibility through WP 5.1+
* Change: Remove support for versions of WordPress older than 4.7
* Change: Update copyright date (2019)
* Change: Update License URI to be HTTPS
* Change: Update screenshot, icon, and banner for Plugin Directory

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

_Full changelog is available in [CHANGELOG.md](https://github.com/coffee2code/one-click-close-comments/blob/master/CHANGELOG.md)._


== Upgrade Notice ==

= 2.6 =
Recommended update: change commenting status indicator to comment bubble icon, support dashicons as alternative status indicators, tweaked plugin initialization, dropped compatibility with WP older than 4.7, noted compatibility through WP 5.1+, updated copyright date (2019), more.

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
