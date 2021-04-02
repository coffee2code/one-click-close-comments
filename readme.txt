=== One Click Close Comments ===
Contributors: coffee2code
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=6ARCFJ9TX3522
Tags: comments, close comments, open comments, admin, comment, discussion, commenting status, coffee2code
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Requires at least: 4.7
Tested up to: 5.7
Stable tag: 2.7.1

Conveniently close or open comments for a post or page with one click from the admin listing of posts.


== Description ==

From the admin listing of posts ('Edit Posts') and pages ('Edit Pages'), a user can close or open comments to any posts to which they have sufficient privileges to make such changes (essentially admins and post authors for their own posts). This is done via an AJAX-powered color-coded indicator. The color-coding gives instant feedback on the current status of the post for comments: green means the post/page is open to comments, red means the post/page is closed to comments. Being AJAX-powered means that the change is submitted in the background after being clicked without requiring a page reload.

This plugin will only function for administrative users in the admin who have JavaScript enabled.

Links: [Plugin Homepage](https://coffee2code.com/wp-plugins/one-click-close-comments/) | [Plugin Directory Page](https://wordpress.org/plugins/one-click-close-comments/) | [GitHub](https://github.com/coffee2code/one-click-close-comments/) | [Author Homepage](https://coffee2code.com)


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

By default, commenting status is represented using the comment bubble dashicon, `dashicons-admin-comments`. You can change this by filtering `c2c_one_click_close_comments_click_char`. Here's an example -- added to a theme's functions.php file -- to change it to the original bullet (solid circle) character:

`add_filter( 'c2c_one_click_close_comments_click_char', function ( $a ) { return "&bull;"; } );`

= How can I open or close comments when editing a specific post? =

WordPress already includes the interface for you to edit the comment status for a specific post. When editing a post, you'll find the setting in the "Discussion" section; the checkbox is labeled "Allow Comments".

= Why does the comment status indicator indicate that comments are open, when in reality comments are disabled (or vice vera)? =

The comment status indicator only reflects the value of the "Allow Comments" setting for the post. In most cases, the indicator will accurately reflect the ability for the user to see the comment form and/or submit a comment.

However, other plugins, the theme, or custom code may affect the visitor's ability to see the comment form when viewing a post and/or may permit or disable commenting at the time the post is shown to them despite the value of the setting. For instance, you could have another plugin installed which disables comments for a post after one year. After a year, that plugin would disable comments in its own way, but the comment status indicator for the post could reflect that comments are open since technically the post's "Allow Comments" setting would still be enabled.


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

= 2.7.1 (2021-04-01) =
* New: Add a unit test
* Change: Note compatibility through WP 5.7+
* Change: Update copyright date (2021)

= 2.7 (2020-08-02) =
Highlights:

* This recommended release updates its JavaScript, streamlines markup output, adds unit testing, adds a TODO.md file, updates a few URLs to be HTTPS, notes compatibility through 5.4+, and other minor behind-the-scenes improvements.

Details:

* New: Extract code for determining click character into new `get_click_char()`
* New: Add unit tests
* New: Add `reset()` for resetting memoized variables
* New: Add TODO.md and move existing TODO list from top of main plugin file into it (and add items to it)
* Change: Improve output of markup
    * Remove encompassing `span` only shown for users authorized to toggle comment status
    * Add 'title' attribute to primary span to indicate current state
    * Change text to not indicate that comment staus can be toggled when user does not have that capability
* Change: Update JavaScript
    * Change: Migrate use of deprecated `.live()` to `.on()`
    * Change: Handle removal of a previously encapsulating `span`
    * Change: Remove unused code
    * Change: Update code syntax
* Change: Allow class to be defined even when loaded outside the admin
* Change: Return '-1' to Ajax requests that don't result in the comment status being toggled
* Change: Add `$and_exit` argument to `toggle_comment_status()` to prevent exiting in order to facilitate unit testing
* Change: Refactor `add_post_column()` to be more concise
* Change: Add inline docs for deprecated filter `one-click-close-comments-click-char`
* Change: Switch to use of strict equality operator instead of simple equality operator
* Change: Note compatibility through WP 5.4+
* Change: Update links to coffee2code.com to be HTTPS

= 2.6.1 (2019-11-24) =
* New: Add additional FAQ items
* Change: Note compatibility through WP 5.3+
* Change: Update copyright date (2020)

_Full changelog is available in [CHANGELOG.md](https://github.com/coffee2code/one-click-close-comments/blob/master/CHANGELOG.md)._


== Upgrade Notice ==

= 2.7.1 =
Trivial update: noted compatibility through WP 5.7+ and updated copyright date (2021)

= 2.7 =
Recommended update: updated JavaScript, streamlined markup output, added unit testing, added a TODO.md file, updated a few URLs to be HTTPS, noted compatibility through 5.4+, and other minor behind-the-scenes improvements.

= 2.6.1 =
Trivial update: added a couple more FAQs, noted compatibility through WP 5.3+, and updated copyright date (2020)

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
