<script>
	import '../app.css';

	import { Modals, closeModal } from 'svelte-modals';
	import { fade } from 'svelte/transition';

	// import Header from '$lib/Header.svelte';
</script>

<!-- <Header /> -->

<main>
	<Modals>
		<div slot="backdrop" class="backdrop" transition:fade on:click={closeModal} />
	</Modals>
	<slot />
</main>

<!-- <footer>Copyright 2022</footer> -->
<style>
	main {
		padding-top: 30px;
		padding-bottom: 60px;
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
