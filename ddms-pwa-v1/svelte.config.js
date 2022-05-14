import path from 'path';

// import adapter from '@sveltejs/adapter-auto';
import adapter from '@sveltejs/adapter-static';

/** @type {import('@sveltejs/kit').Config} */
const config = {
	kit: {
		adapter: adapter(),

		// hydrate the <div id="svelte"> element in src/app.html
		// target: '#svelte

		vite: {
			resolve: {
				alias: {
					$settings: path.resolve('./src/settings'),
					$lib: path.resolve('./src/lib'),
					$routes: path.resolve('./src/router'),
					$core: path.resolve('./src/core'),
					$components: path.resolve('./src/components')
				}
			}
		}
	}
};

export default config;
