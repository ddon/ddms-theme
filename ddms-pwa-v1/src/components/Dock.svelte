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
	let remoteSwitch = {};
	let svgMap = '';

	let loading = true;

	/* ----- */

	const getSwitchStatus = async () => {
		remoteSwitch = await apiRelay.getLedPanelStatus();
		console.log('Getting switch data...');

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
	};

	const renderAreaStatLabels = (areaStats, positionOffset) => {
		if (!areaStats) {
			console.error('renderAreaStatLabels failed');
			return;
		}

		let areaPos;

		// creating and positioning number lable elements
		Object.entries(areaStats).forEach(([area_id, job_count]) => {
			areaPos = jQuery(`#a-${area_id}`).position();

			if (!areaPos) {
				return;
			}

			//creating
			jQuery('.dock-map').after(
				`<span class='a-${area_id}-area_stat stat a-${area_id}-mark'>${job_count}</span>`
			);

			//positioning
			jQuery(`.a-${area_id}-area_stat`).css({
				top: areaPos.top + positionOffset,
				left: areaPos.left + positionOffset
			});
		});
	};

	$: {
		let areaStatLabels = {};

		if (activeJobs.data && activeJobs.ok) {
			console.log('Active jobs data:');
			// console.log("Length: " + activeJobs.data.length);
			console.log(activeJobs.data);
			// clear old classes
			jQuery('.active_area').removeClass('active_area');
			// resetting area stat lable counters
			jQuery('span.stat').remove();

			activeJobs.data.forEach((job) => {
				// adding 'active_area' classes
				jQuery(`#a-${job.dock_area}`).addClass(`active_area a-${job.dock_area}-mark`);

				// collecting job count stats for areas
				if (areaStatLabels[job.dock_area]) {
					areaStatLabels[job.dock_area]++;
				} else {
					areaStatLabels[job.dock_area] = 1;
				}
			});

			let statPosOffset = 8;

			renderAreaStatLabels(areaStatLabels, statPosOffset);

			// re-rendering area's stats position on window resize
			jQuery(window).resize(function () {
				let activeAreas = Array.from(jQuery('.active_area'));

				activeAreas.forEach((a) => {
					let aNewPos = jQuery(a).position();
					let aMark = a.className.baseVal.split(' ')[1];

					jQuery('.stat.' + aMark).css({
						top: aNewPos.top + statPosOffset,
						left: aNewPos.left + statPosOffset
					});
				});
			});

			console.log(areaStatLabels);
		}

		if (remoteSwitch && remoteSwitch.data) {
			console.log('Switch STATAUS: ');
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
			<DockMap {svgMap} {loadDockData} />
		{/if}

		<Jobs jobs={activeJobs} {loadDockData} />
	</section>
{/if}

<style>
	h1 {
	}

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

	:global(span[class*='-area_stat']) {
		display: flex;
		justify-content: center;
		align-items: center;

		position: absolute;

		color: white;
		background-color: var(--red);
		border-radius: 50%;
		height: 25px;
		width: 25px;

		cursor: default;
		pointer-events: none;
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
