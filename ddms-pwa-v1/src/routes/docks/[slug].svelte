<script>
	import { page } from '$app/stores';
	import Dock from '$containers/Dock.svelte';
</script>

<Dock dockID={$page.params.slug} />
