<script>
	import { closeModal, closeAllModals, openModal, modals } from 'svelte-modals';
	import { fly, fade } from 'svelte/transition';
	import { onMount } from 'svelte';

	import api from '$lib/api';

	import Input from '$components/forms/Input.svelte';
	import InputWithMic from '$components/forms/InputWithMic.svelte';
	import Textarea from '$components/forms/Textarea.svelte';
	import Select from '$components/forms/Select.svelte';

	/* ----- */

	export let isOpen;
	export let title;
	export let dock_id;
	export let area_id;

	export let onJobAddSuccess;

	let companies = {};
	let companiesOptions = [];

	let addingJobErrors = {};

	let addingJobErrorClassesDefault = {
		title: '',
		company: '',
		person: '',
		pin: ''
	};

	let addingJobErrorClasses = addingJobErrorClassesDefault;

	/* ----- */

	const getCompanyOptions = (cs) => {
		return cs.map((c) => ({
			value: c.id,
			name: c.name
		}));
	};

	/* ----- */

	const loadCompanies = async () => {
		companies = await api.getCompanies();

		if (companies.ok) {
			companiesOptions = getCompanyOptions(companies.data || []);
		}
	};

	const onAudio = async (audio) => {
		const res = await api.postVoiceFile({
			dock: dock_id,
			audioFile: audio
		});

		console.log(res);
		if (res.ok) {
			// TODO: set input field value res.text
		}
	};

	const submitForm = async (evt) => {
		const formData = new FormData(evt.target);

		const newJob = {
			job_status: formData.get('job_status'),
			dock: formData.get('dock'),
			dock_area: formData.get('dock_area'),

			title: formData.get('title'),
			description: formData.get('description'),
			company: formData.get('company'),
			person: formData.get('person'),
			pin: formData.get('pin')
		};

		const res = await api.addNewJob(newJob);

		if (res.ok) {
			onJobAddSuccess();
		} else {
			addingJobErrorClasses = {
				...addingJobErrorClassesDefault
			};

			addingJobErrors[res.field] = res.error;
			addingJobErrorClasses[res.field] = true;
		}
	};

	/* ----- */

	onMount(() => {
		loadCompanies();
	});
</script>

{#if isOpen}
	<div role="dialog" class="modal" in:fly={{ y: 50 }} out:fade>
		<div class="contents">
			<h2>{@html title}</h2>

			<form id="add_new" on:submit|preventDefault={submitForm}>
				<input type="hidden" name="job_status" id="job_status" value="1" />

				<input type="hidden" name="dock" id="dock" value={dock_id} />

				<input type="hidden" name="dock_area" id="dock_area" value={area_id} />

				<InputWithMic
					name="title"
					label="Job title / Название работы"
					error={addingJobErrorClasses.title}
					requiredMessage="Please enter job title / Введите название работы"
					required
					{onAudio}
				/>

				<Textarea name="description" label="Description / Описание работ" />

				<div class="company_fields form_row">
					<Select
						name="company"
						label="Company / Фирма"
						options={companiesOptions}
						error={addingJobErrorClasses.company}
					/>

					<Input name="new_company" label="Add Company / Добавить фирму" disabled />
				</div>

				<div class="form_row">
					<Input
						name="person"
						label="Person / Исполнитель"
						error={addingJobErrorClasses.person}
						required
					/>

					<Input name="pin" label="PIN" type="number" error={addingJobErrorClasses.pin} required />
				</div>
			</form>

			<div class="actions">
				<button class="close" on:click={closeModal}>Close</button>
				<!-- <button class="ok" type="submit" on:click={onModalOk}>Ok</button> -->
				<button class="ok" form="add_new" type="submit">Add</button>
			</div>
		</div>
	</div>
{/if}

<style>
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
		min-width: 800px;
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
*/
	.actions {
		margin-top: 32px;
		display: flex;
		justify-content: space-between;
		gap: 8px;
		background-color: unset;
		padding: 0;
	}

	form#add_new {
		display: flex;
		flex-direction: column;

		border-radius: 5px;
		background-color: #f2f2f2;
		padding: 20px;
	}

	.form_row {
		padding: 0;
		display: grid;
		grid-template-columns: 1fr 1fr;
		grid-gap: 10px;
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
