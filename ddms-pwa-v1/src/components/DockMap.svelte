<script>
	import { onMount, afterUpdate } from 'svelte';
	import { openModal, closeModal } from 'svelte-modals';
	import { debug } from 'svelte/internal';
	import { fade } from 'svelte/transition';

	import jQuery from 'jquery';

	import api from '$lib/api';
	import ModalAddJob from '$components/ModalAddJob.svelte';

	/* ----- */

	const statPosOffset = 4;

	export let svgMap = '';
	export let jobs = [];
	export let loadDockData = () => {};

	let shapes;

	/* ----- */

	const getMapShapes = () => {
		shapes = Array.from(document.querySelectorAll('g[id*="-areas"] path[id*="a-"]'));
	};

	const handleOpenModal = (title, content = '', dock_id, area_id) => {
		openModal(ModalAddJob, {
			title: title,
			body: content,
			dock_id: dock_id,
			area_id: area_id,
			onJobAddSuccess: () => {
				console.log('### Dock Map: Job Added Successfully ###');
				closeModal();
				loadDockData();
			}
		});
	};

	const handleAreaClick = async (evt) => {
		if (shapes.indexOf(evt.target) >= 0) {
			const elementId = evt.target.id;
			const areaId = elementId.replace('a-', '');

			const res = await api.getMapAreaById({ areaId });

			if (res && res.ok) {
				const areaData = res.data;
				const title = areaData.name_en + '<br>' + areaData.name_ru;
				const content = 'hello!';
				handleOpenModal(title, content, areaData.dock, areaId);
			}
		}

		//if (shapes) {
		//	shapes.forEach((shape) => {
		//		if (shape.className.baseVal !== 'active_area') {
		//			shape.addEventListener('click', (e) => {
		//				// alert('Area: ' + e.target.id);
		//				console.log(e.target.id);
		//			});
		//		}
		//	});
		//}
	};

	/* ----- */

	const mapClearArea = () => {
		jQuery('.active_area').removeClass('active_area');
		jQuery('span.stat').remove();
	};

	const mapResize = () => {
		jQuery(window).resize(() => {
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
	};

	const renderAreaStatLabels = (areaStats) => {
		if (!areaStats) {
			console.error('renderAreaStatLabels failed');
			return;
		}

		let areaPos;

		console.log(areaStats);
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
				top: areaPos.top + statPosOffset,
				left: areaPos.left + statPosOffset
			});
		});
	};

	const mapUpdate = () => {
		mapClearArea();

		let areaStatLabels = {};

		jobs.forEach((job) => {
			jQuery(`#a-${job.dock_area}`).addClass(`active_area a-${job.dock_area}-mark`);

			if (areaStatLabels[job.dock_area]) {
				areaStatLabels[job.dock_area] += 1;
			} else {
				areaStatLabels[job.dock_area] = 1;
			}
		});

		renderAreaStatLabels(areaStatLabels);
	};

	/* ----- */

	onMount(() => {
		getMapShapes();

		mapResize();
	});

	afterUpdate(() => {
		mapUpdate();
	});
</script>

{#if svgMap}
	<div class="dock-map" on:click|preventDefault={handleAreaClick}>
		{@html svgMap}
	</div>
{/if}

<style>
	.dock-map {
		width: 100%;
	}

	:global(g[id*='-areas'] path) {
		stroke-width: 1px;
		fill: rgba(255, 255, 255, 0.01);
	}

	:global(g[id*='-areas'] path.active_area) {
		stroke-width: 2px;
		fill: var(--red-50);
		stroke: var(--red) !important;
	}

	:global(g[id*='-areas'] path:hover) {
		stroke-width: 2px;
		fill: rgba(182, 255, 175, 0.5);
		stroke: #04c900 !important;
	}

	:global(g[id*='-areas']) {
		cursor: pointer;
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
</style>
