# TODO

The following list comprises ideas, suggestions, and known issues, all of which are in consideration for possible implementation in future releases.

***This is not a roadmap or a task list.*** Just because something is listed does not necessarily mean it will ever actually get implemented. Some might be bad ideas. Some might be impractical. Some might either not benefit enough users to justify the effort or might negatively impact too many existing users. Or I may not have the time to devote to the task.

* Add template tag (or inject via filter) an AJAX link for admins (and post authors) to close link from the front-end
* Support non-JS usage
* Consider making comment status indicator accurately indicate if comments are truly enabled/disabled for the post (taking into account filtering by other plugins). If the current state differs from the value of the Allow Comments setting, then somehow denote the difference, likely the superscript circle like for pending comments, but without a number.
* Add line breaks to output
* Add admin bar node for front end access when viewing individual post
* Add unit test for localized strings passed to JS
* Remove jQuery dependency

Feel free to make your own suggestions or champion for something already on the list (via the [plugin's support forum on WordPress.org](https://wordpress.org/support/plugin/one-click-close-comments/) or on [GitHub](https://github.com/coffee2code/one-click-close-comments/) as an issue or PR).