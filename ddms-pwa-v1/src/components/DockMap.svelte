<script>
	import { onMount } from 'svelte';
	import { Modals, closeModal, openModal, modals } from 'svelte-modals';
	import { debug } from 'svelte/internal';
	import { fade } from 'svelte/transition';

	import ModalAddJob from '$components/ModalAddJob.svelte';

	export let svgMap;
	export let loadDockData;

	let shapes;

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

	const fetchArea = async (areaId) => {
		console.log('a-id: ' + areaId);
		try {
			const response = await fetch(
				`https://ddms.greenoak.ee/wp-admin/admin-ajax.php?action=get_area_by_id&areaId=${areaId}`
			);
			const json = await response.json();
			console.log(json);
			return json;
		} catch (err) {
			console.error(err);
		}
	};

	const handleAreaClick = async (e) => {
		// console.log('Shapes:');
		console.log('handleAreaClick:');
		console.log(e.target.id);

		if (shapes.indexOf(e.target) >= 0) {
			const elementId = e.target.id;
			const areaId = elementId.replace('a-', '');

			const areaData = await fetchArea(areaId).then((res) => {
				return res.data;
			});

			if (areaData) {
				const title = areaData.name_en + '<br>' + areaData.name_ru;
				const content = 'hello!';
				console.log(areaData);
				handleOpenModal(title, content, areaData.dock, areaId);
			}

			// console.log(areaData);
		}

		// if (shapes) {
		// 	shapes.forEach((shape) => {
		// 		if (shape.className.baseVal !== 'active_area') {
		// 			shape.addEventListener('click', (e) => {
		// 				// alert('Area: ' + e.target.id);
		// 				console.log(e.target.id);
		// 			});
		// 		}
		// 	});
		// }
	};

	onMount(() => {
		getMapShapes();
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
</style>
