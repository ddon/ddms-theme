<script>
	export let jobs;

	export let getDockData;

	import { onMount } from 'svelte';
	import jQuery from 'jquery';
	import { Modals, closeModal, openModal, modals } from 'svelte-modals';
	import { fade } from 'svelte/transition';
	import ModalCloseJob from './ModalCloseJob.svelte';

	function handleOpenModal(jobData) {
		// console.log("jobId: ");
		// console.log(jobId);
		openModal(ModalCloseJob, {
			job: jobData,
			onJobCloseSuccess: () => {
				console.log('Jobs: Closing job...');
				closeModal();
				getDockData();
			}
		});
	}
</script>

{#if jobs.ok}
	<div class="jobs active_jobs">
		<h2>Active Jobs</h2>
		<table>
			<tr>
				<!-- <th>Id</th> -->
				<th>Created</th>
				<th>Title</th>
				<th>Description</th>
				<th>Person</th>
				<th>Company</th>
				<th>Area</th>
				<th>Action</th>
			</tr>

			{#each jobs.data as job (job.id)}
				{#if job}
					<tr>
						<!-- <td>{job.id}</td> -->
						<td>{job.date_created}</td>
						<td>{job.title}</td>
						<td>{job.description}</td>
						<td>{job.person}</td>
						<td>{job.company}</td>
						<td>{job.area_data.name_en}</td>
						<td><button class="close" on:click={handleOpenModal(job)}>Close</button></td>
					</tr>
				{/if}
			{:else}
				<tr>
					<td colspan="7"><p class="notice">no active jobs</p></td>
				</tr>
			{/each}
		</table>
	</div>
{:else}
	<h3>Loading Jobs...</h3>
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
	button.close {
		background-color: var(---red);
		border-color: var(---red);
	}
</style>
