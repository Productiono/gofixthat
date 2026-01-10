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

			const paymentLink = wrapper.dataset.leadGenPaymentLink || '';
			const formId = wrapper.dataset.leadGenFormId || '';
			let hasRedirected = false;

			const shouldRedirect = (submittedFormId) => {
				if (hasRedirected || !paymentLink) {
					return false;
				}
				if (!formId || !submittedFormId) {
					return true;
				}
				return String(submittedFormId) === String(formId);
			};

			const handleSuccess = (submittedFormId) => {
				if (!shouldRedirect(submittedFormId)) {
					return;
				}
				hasRedirected = true;
				window.location.assign(paymentLink);
			};

			fluentForm.addEventListener('fluentform_submission_success', (event) => {
				const detail = event.detail || {};
				handleSuccess(detail.formId || detail.form_id || detail?.form?.id);
			});

			if (window.jQuery && typeof window.jQuery === 'function') {
				window.jQuery(fluentForm).on('fluentform_submission_success', (event, response) => {
					const submittedFormId =
						(response && (response.form_id || response.formId)) ||
						(event && event.detail && (event.detail.formId || event.detail.form_id));
					handleSuccess(submittedFormId);
				});
			}

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

	const initLeadGenFaq = () => {
		document.querySelectorAll('[data-lead-gen-faq] .lead-gen-faq__item').forEach((item) => {
			const trigger = item.querySelector('.lead-gen-faq__trigger');
			const content = item.querySelector('.lead-gen-faq__content');
			if (!trigger || !content) {
				return;
			}

			const setExpanded = (expanded) => {
				item.classList.toggle('is-open', expanded);
				trigger.setAttribute('aria-expanded', expanded ? 'true' : 'false');
				if (expanded) {
					content.style.maxHeight = `${content.scrollHeight}px`;
				} else {
					content.style.maxHeight = '0px';
				}
			};

			trigger.addEventListener('click', () => {
				setExpanded(!item.classList.contains('is-open'));
			});

			setExpanded(false);
		});
	};

	const initLeadGenStickyForm = () => {
		const hero = document.querySelector('.lead-gen-hero');
		const card = document.querySelector('.lead-gen-hero .lead-gen-form-card--dark');
		const cta = document.querySelector('.lead-gen-cta');
		if (!hero || !card || !('IntersectionObserver' in window)) {
			return;
		}
		let observer = null;
		let isStickyVisible = false;
		let isHeroVisible = true;
		const mobileQuery = window.matchMedia('(max-width: 640px)');
		const originalParent = card.parentElement;
		const originalNextSibling = card.nextElementSibling;

		const restoreCardPosition = () => {
			if (!originalParent || card.parentElement === originalParent) {
				return;
			}
			if (originalNextSibling && originalNextSibling.parentElement === originalParent) {
				originalParent.insertBefore(card, originalNextSibling);
			} else {
				originalParent.appendChild(card);
			}
		};

		const setStickyVisible = (isVisible) => {
			isStickyVisible = isVisible;
			card.classList.toggle('is-sticky-visible', isVisible);
			if (isVisible && mobileQuery.matches) {
				if (card.parentElement !== document.body) {
					document.body.appendChild(card);
				}
			} else {
				restoreCardPosition();
			}
		};

		const isCtaBelowViewport = () => {
			if (!cta) {
				return true;
			}
			const rect = cta.getBoundingClientRect();
			return rect.top > window.innerHeight;
		};

		const updateStickyVisibility = () => {
			setStickyVisible(!isHeroVisible && isCtaBelowViewport());
		};

		const handleIntersection = (entries) => {
			entries.forEach((entry) => {
				isHeroVisible = entry.isIntersecting;
				updateStickyVisibility();
			});
		};

		const handleViewportChange = () => {
			if (!isStickyVisible) {
				restoreCardPosition();
				return;
			}
			if (mobileQuery.matches && card.parentElement !== document.body) {
				document.body.appendChild(card);
			} else if (!mobileQuery.matches) {
				restoreCardPosition();
			}
		};

		const setupObserver = () => {
			if (observer) {
				observer.disconnect();
			}
			observer = new IntersectionObserver(handleIntersection, {
				threshold: 0.1,
			});
			observer.observe(hero);
		};
		setupObserver();
		let scrollTicking = false;
		const handleScroll = () => {
			if (scrollTicking) {
				return;
			}
			scrollTicking = true;
			window.requestAnimationFrame(() => {
				updateStickyVisibility();
				scrollTicking = false;
			});
		};
		window.addEventListener('scroll', handleScroll, { passive: true });
		window.addEventListener('resize', updateStickyVisibility);
		updateStickyVisibility();
		if (typeof mobileQuery.addEventListener === 'function') {
			mobileQuery.addEventListener('change', handleViewportChange);
		} else if (typeof mobileQuery.addListener === 'function') {
			mobileQuery.addListener(handleViewportChange);
		}
	};

	document.addEventListener('DOMContentLoaded', initLeadGenCustomForms);
	document.addEventListener('DOMContentLoaded', initLeadGenFaq);
	document.addEventListener('DOMContentLoaded', initLeadGenStickyForm);
})();
