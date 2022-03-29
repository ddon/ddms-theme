<script>
	import { closeModal, closeAllModals, openModal, modals } from 'svelte-modals';
	import { fly, fade } from 'svelte/transition';
	import { onMount } from 'svelte';

	import api from './lib/api';

	export let isOpen;
	export let title;
	// export let body;
	export let dock_id;
	export let area_id;

	export let onJobAddSuccess;

	let companies = {};

	let addingJobErrors = {};

	let addingJobErrorClassesDefault = {
		title: '',
		company: '',
		person: '',
		pin: ''
	};

	let addingJobErrorClasses = addingJobErrorClassesDefault;

	async function fetchCompanies() {
		companies = await fetch(
			`https://ddms.greenoak.ee/wp-admin/admin-ajax.php?action=get_all_companies`
		).then((r) => {
			return r.json();
		});
	}

	onMount(fetchCompanies);

	const submitForm = (e) => {
		const formData = new FormData(e.target);

		const newJob = {
			job_status: formData.get('job_status'),
			dock: formData.get('dock'),
			dock_area: formData.get('dock_area'),
			title: formData.get('title'),
			description: formData.get('description'),
			company: formData.get('company'),
			// new_company: formData.get('new_company'),
			person: formData.get('person'),
			pin: formData.get('pin')
		};

		console.log('newJob:');
		console.log(newJob);

		api.addNewJob(newJob).then((res) => {
			if (res.ok) {
				console.log('### Modal: Job added. Closing modal form...');
				onJobAddSuccess();
			} else {
				console.log('#### RES:');
				console.log(res);

				addingJobErrorClasses = { ...addingJobErrorClassesDefault };
				addingJobErrors[res.field] = res.error;
				addingJobErrorClasses[res.field] = 'error';
			}
		});
	};

	// function getFieldErrorClass(f) {
	// 	if (addingJobErrors[f]) {
	// 		return "error";
	// 	} else {
	// 		return "no-error-class";
	// 	}
	// }

	// function getFieldError(f) {
	// 	if (addingJobErrors[f]) {
	// 		return addingJobErrors[f];
	// 	} else {
	// 		return "no-error";
	// 	}
	// }
</script>

{#if isOpen}
	<!-- on:introstart and on:outroend are required to transition 1 at a time between modals -->
	<div role="dialog" class="modal" in:fly={{ y: 50 }} out:fade>
		<div class="contents">
			<h2>{@html title}</h2>
			<div>
				<form id="add_new" on:submit|preventDefault={submitForm}>
					<input type="hidden" name="job_status" id="job_status" value="1" />
					<input type="hidden" name="dock" id="dock" value={dock_id} />
					<input type="hidden" name="dock_area" id="dock_area" value={area_id} />

					<label for="title" class={addingJobErrorClasses.title}
						>Job title / Название рабты
						<input
							type="text"
							name="title"
							id="title"
							required
							autofocus
							oninvalid="this.setCustomValidity('Please enter job title / Введите название работы')"
							oninput="this.setCustomValidity('')"
						/>
					</label>

					<label for="description">Description / Описание работ</label>
					<textarea name="description" id="description" rows="4" />

					<div class="company_fields form_row">
						<label for="company" class={addingJobErrorClasses.company}
							>Company / Фирма
							<select name="company" id="company" required>
								<option disabled selected value>select...</option>
								{#if companies.ok}
									{#each companies.data as c}
										<option value={c.id}>{c.name}</option>
									{/each}
								{/if}
							</select>
						</label>

						<label for="new_company"
							>Add Company / Добавить фирму
							<input disabled type="text" name="new_company" id="new_company" />
						</label>
					</div>

					<div class="form_row">
						<label for="person" class={addingJobErrorClasses.person}
							>Person / Исполнитель
							<input type="text" name="person" id="person" required />
						</label>
	
						<label for="pin" class={addingJobErrorClasses.pin}
							>PIN
							<input type="number" name="pin" id="pin" required />
						</label>
					</div>
				</form>
			</div>
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

	p {
		text-align: center;
		margin-top: 16px;
	}

	.actions {
		margin-top: 32px;
		display: flex;
		justify-content: space-between;
		gap: 8px;
		background-color: unset;
		padding: 0;
	}

	label.error {
		background-color: #f6482c;
	}

	form#add_new {
		display: flex;
		flex-direction: column;
	}

	form#add_new label {
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
		background-color: white;
		border-radius: 4px;
		box-sizing: border-box;
	}
	
	input:disabled {
		background-color: rgb(231, 231, 231);
	}

	input[type='submit']:hover {
		background-color: #45a049;
	}

	input[type='submit'] {
		width: 100%;
		background-color: var(--blrt-color-blue);
		color: white;
		padding: 14px 20px;
		margin: 8px 0;
		border: none;
		border-radius: 4px;
		cursor: pointer;
	}

	form#add_new {
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
	}
	button.close {
		background-color: white;
		color: var(--blrt-color-blue);
	}
</style>
