<script>
	export let name = '';
	export let label = '';
	export let error = false;

	export let type = 'text';

	export let requiredMessage = '';
	export let required = false;

	export let disabled = false;
</script>

<div>
	<label for={name} class:error>
		{label}
	</label>

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
	.error {
		color: var(--red-600);
	}

	label {
		font-weight: 600;
	}

	input[type='text'],
	input[type='number'] {
		box-sizing: border-box;

		width: 100%;

		padding: 12px 20px;
		margin: 8px 0;

		display: block;

		background-color: white;

		border: 1px solid #ccc;
		border-radius: 4px;
	}

	input:disabled {
		background-color: rgb(231, 231, 231);
	}
</style>
