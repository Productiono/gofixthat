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
})();
