(function ($) {
	$(function () {
		$('.mbf-product-faqs').on('click', '.mbf-faq-toggle', function (event) {
			event.preventDefault();

			var $button = $(this);
			var isExpanded = $button.attr('aria-expanded') === 'true';
			var targetId = $button.attr('aria-controls');
			var $answer = $('#' + targetId);

			$button.attr('aria-expanded', !isExpanded);
			$button.closest('.mbf-product-faq').toggleClass('is-open', !isExpanded);

			if ($answer.length) {
				if (isExpanded) {
					$answer.attr('hidden', true);
				} else {
					$answer.removeAttr('hidden');
				}
			}
		});
	});
})(jQuery);
