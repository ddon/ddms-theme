<script>
	import { page } from '$app/stores';
	import Dock from '$components/Dock.svelte';

	console.log('$page.params', $page.params);
</script>

<svelte:head>
	<title>Dock 3</title>
</svelte:head>

<Dock dockID="9d0a" />
