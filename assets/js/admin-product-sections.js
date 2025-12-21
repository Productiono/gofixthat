(function ($) {
	function addRow($group) {
		var templateId = $group.data('template');
		var $template = $('#' + templateId);

		if (!$template.length) {
			return;
		}

		var html = $template.html();

		if ($group.hasClass('mbf-repeatable-faq')) {
			var nextIndex = parseInt($group.data('next-index'), 10);

			if (isNaN(nextIndex)) {
				nextIndex = $group.find('.mbf-repeatable-row').length;
			}

			html = html.replace(/__index__/g, nextIndex);
			$group.data('next-index', nextIndex + 1);
		}

		$group.append(html);
	}

	$(function () {
		$('.mbf-repeatable-group .mbf-add-row').on('click', function (event) {
			event.preventDefault();

			var $button = $(this);
			var templateId = $button.data('template');
			var $group = $button.closest('.mbf-repeatable-group').find('.mbf-repeatable').first();

			if ($group.length && templateId) {
				addRow($group);
			}
		});

		$(document).on('click', '.mbf-repeatable .mbf-remove-row', function (event) {
			event.preventDefault();
			$(this).closest('.mbf-repeatable-row').remove();
		});
	});
})(jQuery);
