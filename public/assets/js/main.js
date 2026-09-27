// Lightweight UI scripts: loading screen, counters, small interactions
document.addEventListener('DOMContentLoaded', () => {
	// Loading screen progress
	const loading = document.getElementById('loading-screen');
	const loadingFill = document.querySelector('.loading-bar-fill');
	if (loadingFill) {
		loadingFill.style.width = '60%';
		setTimeout(() => { loadingFill.style.width = '100%'; }, 500);
	}
	if (loading) {
		setTimeout(() => {
			loading.style.opacity = '0';
			loading.style.pointerEvents = 'none';
			setTimeout(() => loading.remove(), 600);
		}, 900);
	}

	// Simple counter animation for elements with [data-counter]
	const counters = document.querySelectorAll('[data-counter]');
	counters.forEach(el => {
		const targetRaw = el.getAttribute('data-target') || '0';
		const suffix = el.getAttribute('data-suffix') || '';
		const target = parseFloat(targetRaw);
		if (isNaN(target)) return;
		const duration = 1200;
		const start = 0;
		const startTime = performance.now();
		function step(now) {
			const progress = Math.min((now - startTime) / duration, 1);
			const value = Math.floor(progress * (target - start) + start);
			el.textContent = value + suffix;
			if (progress < 1) requestAnimationFrame(step);
			else el.textContent = (Number.isInteger(target) ? target : target) + suffix;
		}
		requestAnimationFrame(step);
	});

	// Theme toggle (simple class on body)
	const themeBtn = document.querySelector('.theme-toggle');
	if (themeBtn) {
		themeBtn.addEventListener('click', () => {
			document.documentElement.classList.toggle('light-theme');
			document.documentElement.classList.toggle('dark-theme');
		});
	}
});
