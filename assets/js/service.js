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

	const quantityInput = document.querySelector('#service-quantity-input');
	const quantityButtons = document.querySelectorAll('[data-quantity]');

	const updateQuantity = (delta) => {
		if (!quantityInput) {
			return;
		}
		const current = parseInt(quantityInput.value || '1', 10);
		const nextValue = Math.max(1, current + delta);
		quantityInput.value = String(nextValue);
		buildCheckoutUrl();
	};

	quantityButtons.forEach((button) => {
		button.addEventListener('click', () => {
			const delta = button.dataset.quantity === 'increase' ? 1 : -1;
			updateQuantity(delta);
		});
	});

	const pricingCard = document.querySelector('.service-pricing-card');
	const buyButton = document.querySelector('[data-service-buy]');
	const stickyWrapper = document.querySelector('[data-service-sticky]');
	const stickyButton = document.querySelector('[data-service-buy-sticky]');
	const priceCurrent = document.querySelector('.service-price-current');
	const priceOriginal = document.querySelector('.service-price-original');

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
		if (!baseUrl) {
			return;
		}
		const quantity = quantityInput ? quantityInput.value || '1' : '1';
		const variationId = pricingCard.dataset.variation || '';
		let url;
		try {
			url = new URL(baseUrl, window.location.origin);
		} catch (error) {
			return;
		}
		url.searchParams.set('qty', quantity);
		if (variationId) {
			url.searchParams.set('variation_id', variationId);
		}
		buyButton.dataset.checkoutUrl = url.toString();
		if (stickyButton) {
			stickyButton.dataset.checkoutUrl = url.toString();
		}
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
			const price = button.dataset.variationPrice || pricingCard.dataset.basePrice || '';
			const sale = button.dataset.variationSale || pricingCard.dataset.baseSale || '';
			pricingCard.dataset.price = price;
			pricingCard.dataset.sale = sale;
			pricingCard.dataset.variation = button.dataset.variationId || '';
			updatePriceDisplay(price, sale);
			buildCheckoutUrl();
		});
	});

	if (quantityInput) {
		quantityInput.addEventListener('change', () => {
			if (parseInt(quantityInput.value, 10) < 1) {
				quantityInput.value = '1';
			}
			buildCheckoutUrl();
		});
	}

	if (buyButton) {
		buyButton.addEventListener('click', () => {
			const url = buyButton.dataset.checkoutUrl || '';
			if (url) {
				window.location.href = url;
			}
		});
	}

	if (stickyButton) {
		stickyButton.addEventListener('click', () => {
			const url = stickyButton.dataset.checkoutUrl || '';
			if (url) {
				window.location.href = url;
			}
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
