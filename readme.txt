=== One Click Close Comments ===
Contributors: coffee2code
Donate link: http://coffee2code.com/donate
Tags: comments, close comments, open comments, admin, comment, discussion, commenting status, coffee2code
Requires at least: 2.8
Tested up to: 2.9.1
Stable tag: 2.0
Version: 2.0

Conveniently close or open comments for a post or page with one click.

== Description ==

Conveniently close or open comments for a post or page with one click.

From the admin listing of posts ('Edit Posts') and pages ('Edit Pages'), a user can close or open comments to any posts to which they have sufficient privileges to make such changes (essentially admins and post authors for their own posts).  This is done via an AJAX-powered color-coded indicator.  The color-coding gives instant feedback on the current status of the post for comments: green means the post/page is open to comments, red means the post/page is closed to comments.  Being AJAX-powered means that the change is submitted in the background without requiring a page reload.

This plugin will only function for administrative users in the admin who have JavaScript enabled.

KNOWN ISSUE: After using the 'Quick Edit' for a post, the one click close button for that post no longer works until a page refresh (the button still appears and properly reflect the current status, though).


== Installation ==

1. Unzip `one-click-close-comments.zip` inside the `/wp-content/plugins/` directory for your site (or install via the built-in WordPress plugin installer)
1. Activate the plugin through the 'Plugins' admin menu in WordPress
1. When on the 'Edit Posts' or 'Edit Pages' admin pages, click the indicators to toggle the comment status for a post, as necessary.


== Frequently Asked Questions ==

= I can see the colored dots indicating current commenting status, but why aren't they clickable? =

The commenting status link/button is only clickable is you have JavaScript enabled.

= What does the color-coding of the dot mean?

Green means the post is currently open for comments; red means the post is not currently open for comments.

= How can I customize the color-coding used for the dot? =

You can customize the colors via CSS.  `.comment-state-1` indicates comments are open.  `.comment-state-0` indicates comments are closed.

= How can I customize the dot used to represent commenting status? =

By default, commenting status is represented using the `&bull;` character.  You can change this by filtering `one-click-close-comments-click-char`.  Here's an example -- added to a theme's functions.php file -- to change it to a solid diamond:

`add_filter( 'one-click-close-comments-click-char', create_function('$a', 'return "&diams";') );`


== Screenshots ==

1. A screenshot of the 'Edit Posts' admin page with the plugin activated.


== Changelog ==

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
