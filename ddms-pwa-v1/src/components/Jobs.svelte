<script>
	import { Modals, closeModal, openModal, modals } from 'svelte-modals';
	import { fade } from 'svelte/transition';

	import RequestLoader from '$components/RequestLoader.svelte';
	import Button from '$components/Button.svelte';
	import ModalCloseJob from '$components/ModalCloseJob.svelte';

	/* ----- */

	export let jobs;

	export let loadDockData;

	/* ----- */

	const onOpenModal = (jobData) => {
		openModal(ModalCloseJob, {
			job: jobData,
			onJobCloseSuccess: () => {
				closeModal();
				loadDockData();
			}
		});
	};
</script>

{#if jobs.ok}
	<div class="jobs active_jobs">
		<h2>Active Jobs</h2>
		<table>
			<tr>
				<th>Created</th>
				<th>Title</th>
				<th>Description</th>
				<th>Person</th>
				<th>Company</th>
				<th>Area</th>
				<th>Action</th>
			</tr>

			{#each jobs.data as job (job.id)}
				<tr>
					<td>{job.date_created}</td>
					<td>{job.title}</td>
					<td>{job.description}</td>
					<td>{job.person}</td>
					<td>{job.company}</td>
					<td>{job.area_data.name_en}</td>
					<td>
						<Button
							buttonType="button"
							class="close"
							onClick={() => {
								onOpenModal(job);
							}}
							isRed
						>
							Close
						</Button>
					</td>
				</tr>
			{:else}
				<tr>
					<td colspan="7">
						<p class="notice">No active jobs</p>
					</td>
				</tr>
			{/each}
		</table>
	</div>
{:else}
	<section class="loader">
		<RequestLoader />
		<h3>Loading Jobs</h3>
	</section>
{/if}

<style>
	h2 {
		text-align: center;
	}

	table {
		border-collapse: collapse;
		border-spacing: 0;
		width: 100%;
		border: 1px solid #ddd;
	}

	th,
	td {
		text-align: left;
		padding: 8px;
	}

	tr:nth-child(even) {
		background-color: #f2f2f2;
	}

	.jobs {
		overflow-x: auto;
		width: 100%;
	}

	.notice {
		text-align: center;
	}

	/*
	button.close {
		background-color: var(---red);
		border-color: var(---red);
	}
    */

	.loader {
		display: flex;
		justify-content: center;
		flex-direction: column;
		align-items: center;
	}
</style>
