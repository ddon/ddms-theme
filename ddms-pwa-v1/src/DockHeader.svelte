<script>
	export let jobs;
	export let dock;

	function renderDockStatus(status) {
		return status > 0 ? 'online' : 'offline';
	}

	function countActiveJobs(dockJobs) {
		let count = '';
		if (dockJobs) {
			count = dockJobs.data.length;
		}

		return count;
	}
</script>

<header>
	{#if dock.ok}
		<h1>
			{dock.data.name}
			<!-- | {renderDockStatus(dock.data.connection)} -->
			{#if jobs.ok}
				| active jobs: {countActiveJobs(jobs)}
			{/if}
		</h1>
	{:else}
		<h1>Loading Dock Data...</h1>
	{/if}
</header>

<style>
	h1 {
		text-align: center;
	}
	header {
		display: flex;
		flex-direction: column;
		justify-content: center;
		align-items: center;
		flex: 1;
	}
</style>
