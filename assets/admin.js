jQuery(document).ready(function($) {
	$(".close_comments span").live('mouseenter', function() {
		$(this).addClass('click-span');
	});

	$(".close_comments span").live('click', function() {
		var span = $(this).find('span');
		var current_class = span.attr('class');
		if ( current_class == undefined )
			return;
		var cclass = current_class.split('-');
		var new_class = cclass[0] + '-';
		var post_tr = $(this).parents('tr');
		var post_id = post_tr.attr('id').substr(5);
		var help_text = [c2c_OneClickCloseComments.comments_closed_text, c2c_OneClickCloseComments.comments_opened_text];
		$.post(ajaxurl, {
				action: "close_comments",
				_ajax_nonce: span.attr('id'),
				post_id: post_id
			}, function(data) {
				if (data >= 0 && data <= 1) {
					span.removeClass(current_class);
					span.addClass(new_class + data);
					span.parent().attr('title', help_text[data]);
					// Update hidden field used to configure Quick Edit
					$('#inline_'+post_id+' div.comment_status').html( (data == '1' ? 'open' : 'closed') );
				}
			}, "text"
		);
		return false;
	});
});
