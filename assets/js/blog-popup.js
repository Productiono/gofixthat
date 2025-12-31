(function ($) {
	$(function () {
		var $popup = $('.blog-exit-popup');

		if (!$popup.length) {
			return;
		}

		var sessionKey = 'mbfBlogPopupDismissed';
		var hasSeen = false;
		var $body = $('body');
		var storageAllowed = true;

		try {
			if (!window.sessionStorage) {
				storageAllowed = false;
			}
		} catch (error) {
			storageAllowed = false;
		}

		function hasDismissed() {
			try {
				if (storageAllowed && window.sessionStorage.getItem(sessionKey)) {
					return true;
				}
			} catch (error) {
				// Ignore storage errors.
			}

			return document.cookie.indexOf(sessionKey + '=1') !== -1;
		}

		if (hasDismissed()) {
			return;
		}

		function rememberDismissal() {
			try {
				if (storageAllowed) {
					window.sessionStorage.setItem(sessionKey, '1');
				}
			} catch (error) {
				// Ignore storage errors.
			}

			document.cookie = sessionKey + '=1;path=/';
		}

		function showPopup() {
			if (hasSeen) {
				return;
			}

			hasSeen = true;
			$popup.addClass('is-active').attr('aria-hidden', 'false');
			$body.addClass('blog-exit-popup-open');
		}

		function hidePopup() {
			hasSeen = true;
			$popup.removeClass('is-active').attr('aria-hidden', 'true');
			$body.removeClass('blog-exit-popup-open');

			rememberDismissal();
		}

		$popup.find('.blog-exit-popup__close').on('click', function (event) {
			event.preventDefault();
			hidePopup();
		});

		$popup.on('click', function (event) {
			if ($(event.target).is('.blog-exit-popup')) {
				hidePopup();
			}
		});

		function handleExitIntent(event) {
			if (hasSeen) {
				return;
			}

			if (event.clientY <= 0 || event.clientY <= 10 && !event.relatedTarget) {
				showPopup();
				document.removeEventListener('mouseout', handleExitIntent);
			}
		}

		function handleScrollTrigger() {
			if (hasSeen) {
				return;
			}

			var scrollTop = window.scrollY || document.documentElement.scrollTop;
			var docHeight = document.documentElement.scrollHeight - window.innerHeight;

			if (docHeight <= 0) {
				showPopup();
				window.removeEventListener('scroll', handleScrollTrigger);
				return;
			}

			var progress = scrollTop / docHeight;

			if (progress >= 0.5) {
				showPopup();
				window.removeEventListener('scroll', handleScrollTrigger);
			}
		}

		var isCoarsePointer = window.matchMedia && window.matchMedia('(pointer: coarse)').matches;

		if (isCoarsePointer) {
			window.addEventListener('scroll', handleScrollTrigger, { passive: true });
		} else {
			document.addEventListener('mouseout', handleExitIntent);
		}
	});
})(jQuery);
