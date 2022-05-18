<script>
	import { closeModal, closeAllModals, openModal, modals } from 'svelte-modals';
	import { fly, fade } from 'svelte/transition';
	import { onMount } from 'svelte';

	import api from '$lib/api';

	export let isOpen;
	export let onJobCloseSuccess;
	// export let getDockData;

	export let job;

	// async function closeJobByPin(jobId, jobPin) {
	// 	companies = await fetch(
	// 		`https://ddms.greenoak.ee/wp-admin/admin-ajax.php?action=close_job_by_pin&job_id=309&job_pin=4455`
	// 	).then((r) => {
	// 		return r.json();
	// 	});
	// }

	let cssClass = '';
	let errorMsg = '';
	let disabled = false;

	if (job) {
		console.log('job: ');
		console.log(job);
	}

	// onMount(fetchCompanies);

	const submitForm = (e) => {
		disabled = true;
		const formData = new FormData(e.target);
		// const jobId = formData.get('job_id');
		// const enteredPin = formData.get('pin');
		const params = {
			job_id: formData.get('job_id'),
			job_pin: formData.get('pin')
		};

		// if (job && enteredPin === job.pin) {
		// 	console.log('PIN IS CORRECT!!');

		// 	cssClass = '';
		// 	onJobCloseSuccess();
		// } else {
		// 	console.log('PIN IS WRONG');

		// 	cssClass = 'error';
		// }

		api.closeJobByPin(params).then((res) => {
			if (res.ok) {
				console.log(res);
				console.log('### Modal: Job closed. Closing modal form...');
				cssClass = 'success';

				setTimeout(function () {
					onJobCloseSuccess();
				}, 1000);
			} else {
				cssClass = 'error';
				console.log('#### RES:');
				console.log(res);
				console.log(res.error);

				if (res.error && res.error.indexOf('Wrong pin')) {
					errorMsg = 'Wrong PIN / Неправильный ПИН';
				}

				// addingJobErrorClasses = { ...addingJobErrorClassesDefault };
				// addingJobErrors[res.field] = res.error;
				// addingJobErrorClasses[res.field] = 'error';
			}
			// disabled = false;
			//reset error highlight after 4 sec
			setTimeout(function () {
				cssClass = '';
				errorMsg = '';
			}, 3000);
		});
	};
</script>

{#if isOpen}
	<!-- on:introstart and on:outroend are required to transition 1 at a time between modals -->
	<div role="dialog" class="modal" in:fly={{ y: 50 }} out:fade>
		<div class="contents">
			<h2>Close job / Закрыть работу<br />{job.title}</h2>
			<ul>
				<li><b>Job started / Начало работы:</b> {job.date_created}</li>
				<li><b>Person / Исполнитель:</b> {job.company}, {job.person}</li>
				{#if job.description}<li><b>Description / Описание:</b> {job.description}</li>{/if}
				<li><b>Area / Место:</b> {job.area_data.name_en}</li>
			</ul>
			<div>
				<form id="close_job" on:submit|preventDefault={submitForm}>
					<input type="hidden" name="job_id" id="job_id" value={job.id} />
					<label for="pin" class={cssClass}>
						<div class="field-title">
							Enter Job PIN {#if errorMsg} <span class={cssClass}>{errorMsg}</span> {/if}
						</div>
						<input
							type="number"
							name="pin"
							id="pin"
							required
							oninvalid="this.setCustomValidity('Please enter PIN number / Введите ПИН номер')"
							oninput="this.setCustomValidity('')"
						/>
					</label>
				</form>
			</div>
			<div class="actions">
				<button class="close" on:click={closeModal}>Cancel</button>
				<!-- <button class="ok" type="submit" on:click={onModalOk}>Ok</button> -->
				<button class="ok" form="close_job" type="submit">Ok</button>
			</div>
		</div>
	</div>
{/if}

<style>
	ul {
		list-style: none;
		padding: 0;
	}
	.modal {
		position: fixed;
		top: 0;
		bottom: 0;
		right: 0;
		left: 0;
		display: flex;
		justify-content: center;
		align-items: flex-start;

		/* allow click-through to backdrop */
		pointer-events: none;
	}

	.contents {
		min-width: 620px;
		border-radius: 6px;
		padding: 16px;
		background: white;
		display: flex;
		flex-direction: column;
		justify-content: space-between;
		pointer-events: auto;
	}

	h2 {
		text-align: center;
		font-size: 24px;
	}

	/*
	p {
		text-align: center;
		margin-top: 16px;
	}

	label.error {
		background-color: #f6482c;
		color: white;
		font-weight: bold;
	}

	label.success {
		background-color: #98f62c;
		font-weight: bold;
	}

	label,
	label span {
		transition: all 0.3s ease-in-out;
	}

	label .field-title {
		display: flex;
		justify-content: space-between;
	}

	form#close_job {
		display: flex;
		flex-direction: column;
	}

	form#close_job label {
		padding: 8px;
		border-radius: 4px;
	}

	input[type='text'],
	input[type='number'],
	input[type='text'],
	textarea,
	select {
		width: 100%;
		padding: 12px 20px;
		margin: 8px 0;
		display: inline-block;
		border: 1px solid #ccc;
		border-radius: 4px;
		box-sizing: border-box;
	}

	input[type='submit'] {
		width: 100%;
		background-color: var(--blue);
		color: white;
		padding: 14px 20px;
		margin: 8px 0;
		border: none;
		border-radius: 4px;
		cursor: pointer;
	}

	input[type='submit']:hover {
		background-color: #45a049;
	}

	form#close_job {
		border-radius: 5px;
		background-color: #f2f2f2;
		padding: 20px;
	}

	.company_fields {
		padding: 0;
		display: grid;
		grid-template-columns: 1fr 1fr;
		grid-gap: 10px;
	}
*/

	.actions {
		margin-top: 32px;
		display: flex;
		justify-content: space-between;
		gap: 8px;
		background-color: unset;
		padding: 0;
	}

	button {
		width: 50%;
		height: 30px;
	}

	button.close {
		background-color: white;
		color: var(--blue);
	}
</style>
