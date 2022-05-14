<script>
	import { Modals, closeModal } from 'svelte-modals';
	import { fade } from 'svelte/transition';
</script>

<main>
	<Modals>
		<div slot="backdrop" class="backdrop" transition:fade on:click={closeModal} />
	</Modals>

	<slot />
</main>

<style global>
	:root {
		--gray-600: #4b5563;

		--red: #d31145;
		--red-50: #d3114550;

		--blue: #0b2265;

		--request-loader-bg-color: var(--gray-600);
	}

	:global(body) {
		margin: 0;
		padding: 0;
	}

	:global(body),
	:global(section),
	:global(h1),
	:global(h2),
	:global(h3) {
		font-family: 'Open Sans', sans-serif !important;
	}

	main {
		padding: 0;
		margin: 0;
	}

	.backdrop {
		position: fixed;
		top: 0;
		bottom: 0;
		right: 0;
		left: 0;
		background: rgba(0, 0, 0, 0.5);
	}

	:global(.modal) {
		z-index: 5;
	}
</style>
