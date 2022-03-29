<script lang="js">
	// import { page } from '$app/stores';
	import { onMount } from 'svelte';
	import jQuery from 'jquery';

	import api from './lib/api';

	import Jobs from './Jobs.svelte';
	import DockHeader from './DockHeader.svelte';
	import DockMap from './DockMap.svelte';

	export let dockID = '';

	console.log("DOCK ID: " + dockID);
	// const dockSlug = $page.url.pathname.replace(/\//g, '');
	const dockSlug = 'dock-' + dockID.replace(/\//g, '');

	let dock = {};
	let activeJobs = {};
	let remoteSwitch = {};
	
	let loading = true;
	
	async function getSwitchStatus() {

		remoteSwitch = await api.getLedPanelStatus();

		console.log("Getting switch data...");

		if (remoteSwitch.data) {
			console.log("LED Panel Status: ");
			console.log(remoteSwitch.data);
		}

	}

	async function switchLedPanel(action = '') {
		console.log("LED Button Clicked: " + action + " for dock: " + dockID);
		await api.switchLedPanel(action);
		await getSwitchStatus();
	}

	// async function getSwitchStatus() {

	// 	console.log("Getting switch data...")

	// 	remoteSwitch = await fetch('http://192.168.1.224:8081/zeroconf/info', {
	// 			method: "POST",
	// 			body: JSON.stringify(switchData),
	// 			headers: {"Content-type": "application/json"}
	// 	}).then((r) => {
	// 		// loading = false;
	// 		console.log(r.json());
	// 		return r.json();
	// 	}).catch(err => console.log(err));
	// }

	async function getDockData() {
		dock = await api.getDockBySlug({ slug: dockSlug });
		activeJobs = await api.getActiveJobsByDockSlug({ slug: dockSlug });
		loading = false;

		// activeJobs = await fetch(
		// 	`https://ddms.greenoak.ee/wp-admin/admin-ajax.php?action=get_active_jobs_by_dock_slug&slug=${dockSlug}`
		// ).then((r) => {
		// 	loading = false;
		// 	return r.json();
		// });
	}

	function renderAreaStatLabels (areaStats, positionOffset) {

		if (! areaStats ) {
			console.error("renderAreaStatLabels failed");
			return;
		}

		let areaPos;

		// creating and positioning number lable elements
		Object.entries(areaStats).forEach(([area_id, job_count])=> {
		
			areaPos = jQuery(`#a-${area_id}`).position();

			//creating
			jQuery(".dock-map").after(`<span class='a-${area_id}-area_stat stat a-${area_id}-mark'>${job_count}</span>`);

			//positioning
			jQuery(`.a-${area_id}-area_stat`).css({
				top : areaPos.top + positionOffset,
				left : areaPos.left + positionOffset
			});
		});
	}

	
	$: {

		let areaStatLabels = {};

		if (activeJobs.data && activeJobs.ok) {
			console.log("Active jobs data:");
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
			jQuery( window ).resize(function() {
				let activeAreas = Array.from( jQuery('.active_area') );

				activeAreas.forEach(a => {
					let aNewPos = jQuery(a).position();
					let aMark = a.className.baseVal.split(' ')[1];

					jQuery(".stat." + aMark).css({
						top : aNewPos.top + statPosOffset,
						left : aNewPos.left + statPosOffset
					});
				});
			});

			console.log(areaStatLabels);
		}

		if (remoteSwitch.data) {
			console.log("Switch STATAUS: ");
			console.log(remoteSwitch.data.switch);
			if (activeJobs.data && activeJobs.data.length > 0) {
				if (remoteSwitch.data.switch === 'off') {
					switchLedPanel("on");
				}
			} else if (remoteSwitch.data.switch === 'on') {
				switchLedPanel("off");
			}
		}
	}

	onMount(() => {
		getSwitchStatus();
		getDockData();
	});

	let svgMap = '';
	$: if (dock.ok) {
		svgMap = dock.data.svg_imagemap;
	}


</script>

<div class="dock">
	{#if  remoteSwitch.data && remoteSwitch.data.switch}
		<!-- <pre>Switch: {JSON.stringify(remoteSwitch, undefined, 2)}</pre> -->
		<div class="switch_buttons">
			<p>Led Panel Status: {remoteSwitch.data.switch}</p>
		</div>
	{/if}
	{#if dock.ok}
		<DockHeader {dock} jobs={activeJobs} />

		<section>
			{#if svgMap}
				<DockMap {svgMap} {getDockData} />
			{/if}

			<Jobs jobs={activeJobs} {getDockData} />
		</section>
	{:else}
		<section>
			<h1>Loading Dock Data...</h1>
		</section>
	{/if}
</div>

<style>
	h1 {
		text-align: center;
	}
	.dock {
		padding: 0 2%;
	}
	section {
		display: flex;
		flex-direction: column;
		justify-content: center;
		align-items: center;
		flex: 1;
	}

	:global(span[class*="-area_stat"]) {
         display: flex;
         justify-content: center;
         align-items: center;

         position: absolute;

         color: white;
         background-color: var(--blrt-color-red);
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
		section {
			display: grid;
			grid-template-columns: 50% 50%;
			gap: 20px;
			align-items: flex-start;
		}
	}
</style>
