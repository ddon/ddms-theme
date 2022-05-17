<script>
	export let name = '';
	export let label = '';
	export let error = '';
	export let options = [];

	export let required = false;
	export let disabled = false;
</script>

<div>
	<label for={name} class:error>
		{label}
	</label>

	<select id={name} {name} {required} {disabled}>
		<option disabled selected value>Select...</option>

		{#if options}
			{#each options as option}
				<option value={option.value}>{option.name}</option>
			{/each}
		{/if}
	</select>
</div>

<style>
	.error {
		color: var(--red-600);
	}

	label {
		font-weight: 600;
	}

	select {
		box-sizing: border-box;

		width: 100%;

		padding: 12px 20px;
		margin: 8px 0;

		display: block;

		background-color: white;

		border: 1px solid #ccc;
		border-radius: 4px;
	}

	select:disabled {
		background-color: rgb(231, 231, 231);
	}
</style>
