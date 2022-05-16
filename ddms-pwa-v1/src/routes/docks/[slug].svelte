<script>
	import { page } from '$app/stores';
	import Dock from '$components/Dock.svelte';
</script>

<Dock dockID={$page.params.slug} />
