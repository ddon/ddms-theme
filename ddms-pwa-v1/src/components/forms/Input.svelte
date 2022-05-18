<script>
	export let name = '';
	export let label = '';
	export let error = false;

	export let type = 'text';

	export let requiredMessage = '';
	export let required = false;

	export let disabled = false;
</script>

<div class="field">
	{#if label}
		<label for={name} class:error>
			{label}
		</label>
	{/if}

	<input
		{type}
		id={name}
		{name}
		{required}
		{disabled}
		oninput="this.setCustomValidity('')"
		oninvalid="this.setCustomValidity('{requiredMessage}')"
	/>
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

	input[type='text'],
	input[type='number'] {
		box-sizing: border-box;
		display: block;

		width: 100%;
		height: 45px;

		padding: 12px 10px;

		background-color: var(--white);

		border: 1px solid var(--gray-300);
		border-radius: 4px;
	}

	input:disabled {
		background-color: var(--gray-200);
	}
</style>
