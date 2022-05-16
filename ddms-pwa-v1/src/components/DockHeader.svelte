<script>
	export let jobs;
	export let dock;

	const renderDockStatus = (status) => {
		return status > 0 ? 'online' : 'offline';
	};

	const countActiveJobs = (dockJobs) => {
		let count = '';

		if (dockJobs) {
			count = dockJobs.data.length;
		}

		return count;
	};
</script>

<header>
	{#if dock.ok}
		<h1>
			{dock.data.name}
			<!-- | {renderDockStatus(dock.data.connection)} -->
			{#if jobs.ok}
				| Active jobs: {countActiveJobs(jobs)}
			{/if}
		</h1>
	{:else}
		<h1>Loading Dock Data</h1>
	{/if}
</header>

<style>
	header {
		display: flex;
		flex-direction: column;
		justify-content: center;
		align-items: center;
		flex: 1;

		background-color: var(--gray-150);
		border-bottom: 1px solid var(--gray-500);
	}

	h1 {
		text-align: center;
	}
</style>
