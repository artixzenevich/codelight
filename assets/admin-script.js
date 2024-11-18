document.addEventListener('DOMContentLoaded', function () {
	const linkTag = document.createElement('link');
	linkTag.id = 'codelight-theme-style';
	linkTag.rel = 'stylesheet';
	linkTag.href = codelightSettings.themePath + codelightSettings.currentTheme + '.min.css';
	document.head.appendChild(linkTag);

	// Function to initialize Highlight.js on the preview code block
	function initHighlightJS() {
		const codeBlock = document.querySelector('.preview-code');
		if (codeBlock) {
			hljs.highlightElement(codeBlock);
		}
	}

	// Trigger initialization once the CSS for the theme is fully loaded
	linkTag.onload = function () {
		if (typeof hljs !== 'undefined') {
			initHighlightJS();
		} else {
			console.error('Highlight.js is not loaded.');
		}
	};

	// Handle theme changes when the user selects a new theme
	const themeSelector = document.getElementById('codelight-theme-selector');
	themeSelector.addEventListener('change', function () {
		const selectedTheme = this.value;

		linkTag.href = codelightSettings.themePath + selectedTheme + '.min.css';

		// Reinitialize Highlight.js after the new theme is loaded
		linkTag.onload = function () {
			if (typeof hljs !== 'undefined') {
				initHighlightJS();
				console.error('Highlight.js is not loaded.');
			}
		};
	});
});