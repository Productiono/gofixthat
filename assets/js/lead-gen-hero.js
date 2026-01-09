(function () {
	const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	if (prefersReducedMotion) {
		return;
	}

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

			const attachSource = () => {
				if (source.getAttribute('src')) {
					return;
				}
				source.setAttribute('src', dataSrc);
				video.load();
				const playPromise = video.play();
				if (playPromise && typeof playPromise.catch === 'function') {
					playPromise.catch(() => {});
				}
			};

			if ('requestIdleCallback' in window) {
				window.requestIdleCallback(attachSource, { timeout: 2000 });
			} else {
				window.setTimeout(attachSource, 200);
			}
		});
	};

	if (document.readyState === 'complete') {
		loadVideos();
	} else {
		window.addEventListener('load', loadVideos, { once: true });
	}
})();
