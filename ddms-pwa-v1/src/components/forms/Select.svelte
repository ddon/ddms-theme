<script>
	export let name = '';
	export let label = '';
	export let error = '';
	export let options = [];

	export let required = false;
	export let disabled = false;
</script>

<div class="field">
	<label for={name} class:error>
		{label}
	</label>

	<select id={name} {name} {required} {disabled}>
		<option disabled selected value>Select / Выбрать</option>

		{#if options}
			{#each options as option}
				<option value={option.value}>{option.name}</option>
			{/each}
		{/if}
	</select>
</div>

<style>
	.field {
		margin: 0 0 25px 0;
	}

	.error {
		color: var(--red-600);
	}

	label {
		display: block;
		margin: 0 0 5px 0;

		font-weight: 600;
	}

	select {
		box-sizing: border-box;
		display: block;

		width: 100%;
		height: 45px;

		padding: 12px 10px;

		background-color: var(--white);

		border: 1px solid var(--gray-300);
		border-radius: 4px;
	}

	select:disabled {
		background-color: var(--gray-200);
	}
</style>
