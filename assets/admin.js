jQuery(document).ready(function($) {
	$('.close_comments').on('click', '> span', function() {
		const span = $(this);
		const current_class = span.attr('class');
		if ( current_class === undefined ) {
			return;
		}
		const cclass = current_class.split('-');
		const new_class = cclass[0] + '-';
		const post_tr = $(this).parents('tr');
		const post_id = post_tr.attr('id').substr(5);
		const help_text = [c2c_OneClickCloseComments.comments_closed_text, c2c_OneClickCloseComments.comments_opened_text];
		$.post(ajaxurl, {
				action: "close_comments",
				_ajax_nonce: span.attr('id'),
				post_id: post_id
			}, function(data) {
				if (data >= 0 && data <= 1) {
					span.removeClass(current_class);
					span.addClass(new_class + data);
					span.parent().attr('title', help_text[data]);
					span.parent().find('.screen-reader-text').text(help_text[data]);
					// Update hidden field used to configure Quick Edit
					$('#inline_'+post_id+' div.comment_status').html( (data == '1' ? 'open' : 'closed') );
				}
			}, "text"
		);
		return false;
	});
});
