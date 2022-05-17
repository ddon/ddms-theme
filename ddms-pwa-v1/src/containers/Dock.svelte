<script lang="js">
	import { onMount } from 'svelte';
	import jQuery from 'jquery';

	import api from '$lib/api';
	import apiRelay from '$lib/apiRelay';

	import RequestLoader from '$components/RequestLoader.svelte';
	import DockHeader from '$components/DockHeader.svelte';
	import DockMap from '$components/DockMap.svelte';
	import Jobs from '$components/Jobs.svelte';

	/* ----- */

	export let dockID = '';

	const dockSlug = 'dock-' + dockID.replace(/\//g, '');

	let dock = {};
	let activeJobs = {};
	let jobs = [];
	let remoteSwitch = {};
	let svgMap = '';

	let loading = true;

	/* ----- */

	const getSwitchStatus = async () => {
		remoteSwitch = await apiRelay.getLedPanelStatus();

		if (remoteSwitch?.data) {
			console.log('LED Panel Status: ');
			console.log(remoteSwitch.data);
		}
	};

	const switchLedPanel = async (action = '') => {
		console.log('LED Button Clicked: ' + action + ' for dock: ' + dockID);
		await apiRelay.switchLedPanel(action);
		await getSwitchStatus();
	};

	const loadDockData = async () => {
		dock = await api.getDockBySlug({
			slug: dockSlug
		});

		activeJobs = await api.getActiveJobsByDockSlug({
			slug: dockSlug
		});

		loading = false;

		if (dock.ok) {
			svgMap = dock.data.svg_imagemap;
		}

		if (activeJobs.ok) {
			jobs = activeJobs.data || [];
		}
	};

	$: {
		if (remoteSwitch && remoteSwitch.data) {
			console.log('Switch STATUS: ');
			console.log(remoteSwitch.data.switch);
			if (activeJobs.data && activeJobs.data.length > 0) {
				if (remoteSwitch.data.switch === 'off') {
					switchLedPanel('on');
				}
			} else if (remoteSwitch.data.switch === 'on') {
				switchLedPanel('off');
			}
		}
	}

	onMount(() => {
		getSwitchStatus();
		loadDockData();
	});
</script>

<svelte:head>
	{#if dock.ok}
		<title>{dock.data.name || 'Dock'}</title>
	{:else}
		<title>Dock</title>
	{/if}
</svelte:head>

{#if remoteSwitch && remoteSwitch.data && remoteSwitch.data.switch}
	<!-- <pre>Switch: {JSON.stringify(remoteSwitch, undefined, 2)}</pre> -->
	<div class="switch_buttons">
		<p>Led Panel Status: {remoteSwitch.data.switch}</p>
	</div>
{/if}

{#if !dock.ok}
	<section class="loader">
		<RequestLoader />
		<h1>Loading Dock Data</h1>
	</section>
{:else}
	<DockHeader {dock} jobs={activeJobs} />

	<section class="dock">
		{#if svgMap}
			<DockMap dockId={dock.data.id} {svgMap} {jobs} {loadDockData} />
		{/if}

		<Jobs jobs={activeJobs} {loadDockData} />
	</section>
{/if}

<style>
	.loader {
		display: flex;
		flex-direction: column;
		justify-content: center;
		align-items: center;
		flex: 1;
	}

	.loader > h1 {
		text-align: center;
	}

	.dock {
		padding: 0 20px;
	}

	.switch_buttons {
		position: absolute;
	}

	@media only screen and (min-width: 1600px) {
		.dock {
			display: grid;
			grid-template-columns: 1fr 1fr;
			grid-gap: 20px;
			align-items: flex-start;
		}
	}
</style>
