<script>
	export let name = '';
	export let label = '';
	export let error = false;
</script>

<div class="field">
	<label for={name} class:error>
		{label}
	</label>

	<textarea id={name} {name} rows="4" />
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

	textarea {
		box-sizing: border-box;
		display: block;

		width: 100%;

		padding: 12px 10px;

		background-color: var(--white);

		border: 1px solid var(--gray-300);
		border-radius: 4px;
	}
</style>
