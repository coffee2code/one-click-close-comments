# Developer Documentation

This plugin provides a [hook](#hooks) for developer usage.

## Hooks

The plugin exposes a filter for hooking. Code using this filter should ideally be put into a mu-plugin or site-specific plugin (which is beyond the scope of this readme to explain).

### `c2c_one_click_close_comments_click_char` _(filter)_

The `c2c_one_click_close_comments_click_char` hook allows customization of the character, string, or markup used as the plugin's indicator in the posts listing tables. It is the character that gets color-coded to indicate if comments are open or close, and the thing to click to toggle the comment open status. You can make use of [Dashicons](https://developer.wordpress.org/resource/dashicons/) by specifying the desired dashicon's name (with the "dashicons-" prefix). By default this is the comments dashicon, 'dashicons-admin-comments'.

#### Arguments

* `$char` _(array)_:
The character, string, or markup to be used for display (by default this is 'dashicons-admin-comments').

Example:

```php
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
```
