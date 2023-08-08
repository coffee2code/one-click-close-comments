function handleCommentStateChange( event ) {
	// Is event related to the spacebar or enter key?
	const is_keypress = event.keyCode === 32 || event.keyCode === 13;

	// Only trigger on clicks and applicable key presses.
	if ( ! ( event.type === 'click' || is_keypress ) ) {
		return;
	}
	event.preventDefault();

	const target = is_keypress ? event.target : event.target.parentNode;

	if ( ! target.classList.contains('comment_state') || target.classList.contains('comment_state-disabled')) {
		return;
	}

	const is_close = target.classList.contains('comment_state-0');
	const is_open = target.classList.contains('comment_state-1');

	// If target is neither open or close, something is wrong or this isn't a desired target.
	if ( ! is_close && ! is_open ) {
		return;
	}

	const post_id = target.closest('tr').id.substr(5);
	const help_text = [c2c_OneClickCloseComments.comments_closed_text, c2c_OneClickCloseComments.comments_opened_text];
	const ajax_nonce = target.dataset.nonce;

	fetch(ajaxurl, {
		method: 'POST',
		headers: {'Content-Type':'application/x-www-form-urlencoded'},
		body: `action=close_comments&_ajax_nonce=${encodeURIComponent(ajax_nonce)}&post_id=${encodeURIComponent(post_id)}`
	})
	.then(res => res.text())
	.then(data => {
		if (data >= 0 && data <= 1) {
			// Toggle classes.
			target.classList.toggle('comment_state-0');
			target.classList.toggle('comment_state-1');
			// Update title attribute for toggle.
			target.setAttribute('title', help_text[data]);
			// Update screen reader text.
			target.parentNode.querySelector('.screen-reader-text').textContent = help_text[data];
			// Update hidden field used to configure Quick Edit.
			document.querySelector('#inline_'+post_id+' div.comment_status').textContent = (data == '1' ? 'open' : 'closed');
		}
	});
}

document.addEventListener('DOMContentLoaded', function () {
	document.querySelectorAll('.comment_state:not(.comment_state-disabled').forEach( (btn) => {
		btn.addEventListener('click', handleCommentStateChange);
		btn.addEventListener('keyup', handleCommentStateChange);
	});
}, false);
