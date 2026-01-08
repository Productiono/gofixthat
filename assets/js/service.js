(function () {
	const tabButtons = document.querySelectorAll('.service-tab');
	const tabPanels = document.querySelectorAll('.service-tab-panel');

	const activateTab = (target) => {
		tabButtons.forEach((button) => {
			const isActive = button.dataset.tab === target;
			button.classList.toggle('is-active', isActive);
			button.setAttribute('aria-selected', isActive ? 'true' : 'false');
		});
		tabPanels.forEach((panel) => {
			panel.classList.toggle('is-active', panel.dataset.tabPanel === target);
		});
	};

	tabButtons.forEach((button) => {
		button.addEventListener('click', () => {
			activateTab(button.dataset.tab);
		});
	});

	const pricingCard = document.querySelector('.service-pricing-card');
	const buyButton = document.querySelector('[data-service-buy]');
	const stickyWrapper = document.querySelector('[data-service-sticky]');
	const stickyButton = document.querySelector('[data-service-buy-sticky]');
	const priceCurrent = pricingCard ? pricingCard.querySelector('.service-price-current') : null;
	const priceOriginal = pricingCard ? pricingCard.querySelector('.service-price-original') : null;
	const variationSelect = document.querySelector('[data-service-variation]');

	const formatPrice = (value) => {
		const number = parseFloat(value);
		if (Number.isNaN(number)) {
			return '';
		}
		return `$${number.toFixed(2)}`;
	};

	const updatePriceDisplay = (price, sale) => {
		if (!priceCurrent) {
			return;
		}
		const hasSale = sale !== '' && sale !== null && parseFloat(sale) < parseFloat(price);
		if (hasSale) {
			if (priceOriginal) {
				priceOriginal.textContent = formatPrice(price);
				priceOriginal.classList.remove('is-hidden');
			}
			priceCurrent.textContent = formatPrice(sale);
		} else {
			if (priceOriginal) {
				priceOriginal.textContent = '';
				priceOriginal.classList.add('is-hidden');
			}
			priceCurrent.textContent = formatPrice(price);
		}
	};

	const buildCheckoutUrl = () => {
		if (!pricingCard || !buyButton) {
			return;
		}
		const baseUrl = pricingCard.dataset.checkout || '';
		const priceId = pricingCard.dataset.priceId || '';
		const endpoint = pricingCard.dataset.checkoutEndpoint || '';
		if (!baseUrl && (!priceId || !endpoint)) {
			buyButton.dataset.checkoutUrl = '';
			buyButton.setAttribute('disabled', 'disabled');
			if (stickyButton) {
				stickyButton.dataset.checkoutUrl = '';
				stickyButton.setAttribute('disabled', 'disabled');
			}
			return;
		}
		buyButton.dataset.checkoutUrl = baseUrl;
		buyButton.removeAttribute('disabled');
		if (stickyButton) {
			stickyButton.dataset.checkoutUrl = baseUrl;
			stickyButton.removeAttribute('disabled');
		}
	};

	const applyVariationSelection = (data) => {
		if (!pricingCard || !data) {
			return;
		}
		const price = data.price || pricingCard.dataset.basePrice || '';
		const sale = data.sale || pricingCard.dataset.baseSale || '';
		const checkout = data.checkout || pricingCard.dataset.baseCheckout || '';
		const priceId = data.priceId || pricingCard.dataset.basePriceId || '';
		pricingCard.dataset.price = price;
		pricingCard.dataset.sale = sale;
		pricingCard.dataset.variation = data.id || '';
		pricingCard.dataset.checkout = checkout;
		pricingCard.dataset.priceId = priceId;
		updatePriceDisplay(price, sale);
		buildCheckoutUrl();
	};

	if (pricingCard) {
		updatePriceDisplay(pricingCard.dataset.price || '', pricingCard.dataset.sale || '');
		buildCheckoutUrl();
	}

	const variationButtons = document.querySelectorAll('[data-variation-select]');
	variationButtons.forEach((button) => {
		button.addEventListener('click', () => {
			variationButtons.forEach((item) => item.classList.remove('is-selected'));
			button.classList.add('is-selected');
			applyVariationSelection({
				price: button.dataset.variationPrice || '',
				sale: button.dataset.variationSale || '',
				id: button.dataset.variationId || '',
				checkout: button.dataset.variationCheckout || '',
				priceId: button.dataset.variationPriceId || '',
			});
			if (variationSelect && button.dataset.variationId) {
				variationSelect.value = button.dataset.variationId;
			}
		});
	});

	if (variationSelect) {
		const selectedOption = variationSelect.selectedOptions[0];
		if (selectedOption) {
			applyVariationSelection({
				price: selectedOption.dataset.variationPrice || '',
				sale: selectedOption.dataset.variationSale || '',
				id: selectedOption.value || '',
				checkout: selectedOption.dataset.variationCheckout || '',
				priceId: selectedOption.dataset.variationPriceId || '',
			});
		}
		variationSelect.addEventListener('change', () => {
			const option = variationSelect.selectedOptions[0];
			if (!option) {
				return;
			}
			applyVariationSelection({
				price: option.dataset.variationPrice || '',
				sale: option.dataset.variationSale || '',
				id: option.value || '',
				checkout: option.dataset.variationCheckout || '',
				priceId: option.dataset.variationPriceId || '',
			});
			if (option.value) {
				variationButtons.forEach((button) => {
					button.classList.toggle('is-selected', button.dataset.variationId === option.value);
				});
			}
		});
	}

	const requestCheckoutSession = async () => {
		if (!pricingCard) {
			return;
		}
		const endpoint = pricingCard.dataset.checkoutEndpoint || '';
		const priceId = pricingCard.dataset.priceId || '';
		if (!endpoint || !priceId) {
			return;
		}

		const payload = {
			price_id: priceId,
			service_id: pricingCard.dataset.serviceId || '',
			variation_id: pricingCard.dataset.variation || '',
			quantity: 1,
		};

		const response = await fetch(endpoint, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
			},
			body: JSON.stringify(payload),
		});

		if (!response.ok) {
			throw new Error('Unable to start checkout.');
		}

		const data = await response.json();
		if (data.url) {
			window.location.href = data.url;
		}
	};

	const handleCheckoutClick = async (button) => {
		const url = button.dataset.checkoutUrl || '';
		if (url) {
			window.location.href = url;
			return;
		}

		button.setAttribute('disabled', 'disabled');
		button.setAttribute('aria-busy', 'true');
		try {
			await requestCheckoutSession();
		} catch (error) {
			button.removeAttribute('aria-busy');
			buildCheckoutUrl();
		}
	};

	if (buyButton) {
		buyButton.addEventListener('click', () => {
			handleCheckoutClick(buyButton);
		});
	}

	if (stickyButton) {
		stickyButton.addEventListener('click', () => {
			handleCheckoutClick(stickyButton);
		});
	}

	const toggleStickyVisibility = (isVisible) => {
		if (!stickyWrapper) {
			return;
		}
		stickyWrapper.classList.toggle('is-visible', !isVisible);
		stickyWrapper.setAttribute('aria-hidden', isVisible ? 'true' : 'false');
	};

	const setupStickyVisibility = () => {
		if (!stickyWrapper) {
			return null;
		}
		if (stickyWrapper.parentElement !== document.body) {
			document.body.appendChild(stickyWrapper);
		}
		const servicePage = document.querySelector('.service-page');
		const mediaQuery = window.matchMedia('(max-width: 600px)');
		const updateStickyLayout = () => {
			const isMobile = mediaQuery.matches;
			toggleStickyVisibility(!isMobile);
			if (!servicePage) {
				return;
			}
			if (isMobile) {
				window.requestAnimationFrame(() => {
					const height = stickyWrapper.offsetHeight || 0;
					if (height) {
						servicePage.style.setProperty('--service-sticky-offset', `${height}px`);
					}
				});
			} else {
				servicePage.style.removeProperty('--service-sticky-offset');
			}
		};
		updateStickyLayout();
		mediaQuery.addEventListener('change', updateStickyLayout);
		window.addEventListener('resize', updateStickyLayout);
		return () => {
			mediaQuery.removeEventListener('change', updateStickyLayout);
			window.removeEventListener('resize', updateStickyLayout);
		};
	};

	setupStickyVisibility();

	const gallery = document.querySelector('[data-service-gallery]');
	const galleryTrigger = document.querySelector('[data-service-screenshots]');
	const galleryImage = document.querySelector('.service-gallery-image');
	const galleryCloseButtons = document.querySelectorAll('[data-gallery-close]');
	const galleryPrev = document.querySelector('[data-gallery-prev]');
	const galleryNext = document.querySelector('[data-gallery-next]');
	let galleryItems = [];
	let galleryIndex = 0;

	const updateGalleryImage = () => {
		if (!galleryItems.length || !galleryImage) {
			return;
		}
		const current = galleryItems[galleryIndex];
		galleryImage.src = current.url;
		galleryImage.alt = current.alt || '';
	};

	const openGallery = () => {
		if (!gallery || !galleryItems.length) {
			return;
		}
		gallery.classList.add('is-active');
		gallery.setAttribute('aria-hidden', 'false');
		updateGalleryImage();
	};

	const closeGallery = () => {
		if (!gallery) {
			return;
		}
		gallery.classList.remove('is-active');
		gallery.setAttribute('aria-hidden', 'true');
	};

	if (gallery) {
		try {
			galleryItems = JSON.parse(gallery.dataset.images || '[]');
		} catch (error) {
			galleryItems = [];
		}
	}

	if (galleryTrigger) {
		galleryTrigger.addEventListener('click', () => {
			galleryIndex = 0;
			openGallery();
		});
	}

	galleryCloseButtons.forEach((button) => {
		button.addEventListener('click', closeGallery);
	});

	if (galleryPrev) {
		galleryPrev.addEventListener('click', () => {
			if (!galleryItems.length) {
				return;
			}
			galleryIndex = (galleryIndex - 1 + galleryItems.length) % galleryItems.length;
			updateGalleryImage();
		});
	}

	if (galleryNext) {
		galleryNext.addEventListener('click', () => {
			if (!galleryItems.length) {
				return;
			}
			galleryIndex = (galleryIndex + 1) % galleryItems.length;
			updateGalleryImage();
		});
	}

	document.addEventListener('keydown', (event) => {
		if (!gallery || !gallery.classList.contains('is-active')) {
			return;
		}
		if (event.key === 'Escape') {
			closeGallery();
		}
		if (event.key === 'ArrowRight') {
			if (!galleryItems.length) {
				return;
			}
			galleryIndex = (galleryIndex + 1) % galleryItems.length;
			updateGalleryImage();
		}
		if (event.key === 'ArrowLeft') {
			if (!galleryItems.length) {
				return;
			}
			galleryIndex = (galleryIndex - 1 + galleryItems.length) % galleryItems.length;
			updateGalleryImage();
		}
	});
})();
