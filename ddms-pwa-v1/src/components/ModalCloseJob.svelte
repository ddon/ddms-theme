<script>
	import { closeModal, closeAllModals, openModal, modals } from 'svelte-modals';
	import { fly, fade } from 'svelte/transition';
	import { onMount } from 'svelte';

	import api from '$lib/api.js';
	import text from '$core/text.js';

	import Button from '$components/Button.svelte';

	import Input from '$components/forms/Input.svelte';

	/* ----- */

	export let isOpen;
	export let job;
	export let onJobCloseSuccess;

	let errors = {
		pin: ''
	};

	let disabled = false;

	/* ----- */

	const submitForm = async (evt) => {
		disabled = true;

		const formData = new FormData(evt.target);

		const res = await api.closeJobByPin({
			job_id: formData.get('job_id'),
			job_pin: formData.get('pin')
		});

		if (res.ok) {
			setTimeout(function () {
				onJobCloseSuccess();
			}, 1000);
		} else {
			if (res.error && res.error.indexOf('Wrong pin')) {
				errors.pin = 'Wrong PIN / Неправильный ПИН';
			}
		}

		disabled = false;

		setTimeout(function () {
			errors.pin = '';
		}, 3000);
	};
</script>

{#if isOpen}
	<div role="dialog" class="modal" in:fly={{ y: 50 }} out:fade>
		<div class="contents">
			<h2>
				{text.closeJobTitle}
				<div>{job.title}</div>
			</h2>

			<ul>
				<li>
					<strong>{text.jobStarted}:</strong>
					{job.date_created}
				</li>

				<li>
					<strong>{text.person}:</strong>
					{job.company}, {job.person}
				</li>

				{#if job.description}
					<li>
						<strong>{text.description}:</strong>
						{job.description}
					</li>
				{/if}

				<li>
					<strong>{text.area}:</strong>
					{job.area_data.name_en}
				</li>
			</ul>

			<div>
				<form id="closeJob" on:submit|preventDefault={submitForm}>
					<input type="hidden" name="job_id" id="job_id" value={job.id} />

					<Input
						name="pin"
						label="PIN"
						type="number"
						error={errors.pin}
						requiredMessage={text.invalidPIN}
						required
					/>

					{#if errors.pin}
						<div class="error">
							{errors.pin}
						</div>
					{/if}
				</form>
			</div>

			<div class="actions">
				<Button onClick={closeModal} isRed>{text.cancel}</Button>
				<Button form="closeJob" {disabled}>{text.closeJob}</Button>
			</div>
		</div>
	</div>
{/if}

<style>
	.error {
		color: var(--red-600);
		font-weight: 600;
	}

	.modal {
		position: fixed;
		top: 20px;
		bottom: 0;
		right: 0;
		left: 0;

		display: flex;
		justify-content: center;
		align-items: flex-start;

		pointer-events: none;
	}

	.contents {
		min-width: 620px;
		padding: 16px;

		border-radius: 6px;

		display: flex;
		flex-direction: column;
		justify-content: space-between;
		pointer-events: auto;

		background: var(--white);
	}

	ul {
		list-style: none;
		padding: 0;
	}

	h2 {
		margin: 0 0 20px 0;

		text-align: center;
		font-size: 24px;
	}

	form#closeJob {
		border-radius: 5px;
		background-color: #f2f2f2;
		padding: 20px;
	}

	.actions {
		margin-top: 32px;
		padding: 0;

		display: grid;
		grid-auto-flow: column;
		grid-auto-columns: 1fr;
		grid-column-gap: 8px;
	}
</style>
