(function () {
	const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	const setHeaderOffset = () => {
		const header = document.querySelector('.mbf-header');
		const offset = header && header.offsetHeight ? header.offsetHeight : 0;
		const target = document.querySelector('.lead-gen-page') || document.documentElement;
		target.style.setProperty('--lead-gen-header-offset', `${offset}px`);
	};

	const loadVideos = () => {
		document.querySelectorAll('[data-lead-gen-video]').forEach((video) => {
			const source = video.querySelector('source');
			if (!source) {
				return;
			}
			const dataSrc = source.getAttribute('data-src');
			if (!dataSrc || source.getAttribute('src')) {
				return;
			}

			if (source.getAttribute('src')) {
				return;
			}
			source.setAttribute('src', dataSrc);
			video.load();
			const playPromise = video.play();
			if (playPromise && typeof playPromise.catch === 'function') {
				playPromise.catch(() => {});
			}
		});
	};

	window.addEventListener(
		'load',
		() => {
			setHeaderOffset();
			if (prefersReducedMotion) {
				return;
			}
			const idleLoader = () => loadVideos();
			if ('requestIdleCallback' in window) {
				window.requestIdleCallback(idleLoader, { timeout: 2000 });
			} else {
				window.setTimeout(idleLoader, 200);
			}
		},
		{ once: true }
	);

	window.addEventListener('resize', setHeaderOffset);

	const initLeadGenCustomForms = () => {
		const wrappers = document.querySelectorAll('[data-lead-gen-form-wrapper]');
		if (!wrappers.length) {
			return;
		}

		wrappers.forEach((wrapper) => {
			const customForm = wrapper.querySelector('[data-lead-gen-custom-form]');
			const fluentWrapper = wrapper.querySelector('[data-lead-gen-fluent-form]');
			const fluentForm = fluentWrapper ? fluentWrapper.querySelector('form') : null;
			const errorMessage = wrapper.querySelector('[data-lead-gen-error]');
			if (!customForm || !fluentForm) {
				return;
			}

			const findFluentEmailField = () =>
				fluentForm.querySelector('input[type="email"], input[name*="email"]');

			const setErrorMessage = (message) => {
				if (!errorMessage) {
					return;
				}
				if (message) {
					errorMessage.textContent = message;
					errorMessage.classList.add('is-visible');
				} else {
					errorMessage.textContent = '';
					errorMessage.classList.remove('is-visible');
				}
			};

			const pullFluentErrors = () => {
				const errorSelectors = [
					'.ff-form-errors',
					'.ff-message-error',
					'.ff-el-error',
					'.ff-el-input--error .error',
				];
				let message = '';
				errorSelectors.some((selector) => {
					const errorEl = fluentForm.querySelector(selector);
					if (errorEl && errorEl.textContent.trim()) {
						message = errorEl.textContent.trim();
						return true;
					}
					return false;
				});

				if (!message) {
					const invalidInput = fluentForm.querySelector('[aria-invalid="true"]');
					if (invalidInput) {
						message = invalidInput.getAttribute('data-error-text') || '';
					}
				}

				setErrorMessage(message);
			};

			const observer = new MutationObserver(pullFluentErrors);
			observer.observe(fluentForm, {
				childList: true,
				subtree: true,
				attributes: true,
				attributeFilter: ['class', 'aria-invalid'],
			});

			customForm.addEventListener('submit', (event) => {
				event.preventDefault();
				const customInput = customForm.querySelector('input[type="email"]');
				const fluentInput = findFluentEmailField();
				if (customInput && fluentInput) {
					fluentInput.value = customInput.value;
					fluentInput.dispatchEvent(new Event('input', { bubbles: true }));
					fluentInput.dispatchEvent(new Event('change', { bubbles: true }));
				}

				setErrorMessage('');
				if (typeof fluentForm.requestSubmit === 'function') {
					const submitButton = fluentForm.querySelector(
						'button[type="submit"], input[type="submit"]'
					);
					fluentForm.requestSubmit(submitButton || undefined);
				} else {
					fluentForm.submit();
				}
			});

			customForm.addEventListener('input', () => setErrorMessage(''));
			pullFluentErrors();
		});
	};

	document.addEventListener('DOMContentLoaded', initLeadGenCustomForms);
})();
