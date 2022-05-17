<script>
	export let name = '';
	export let label = '';
	export let error = false;
</script>

<div>
	<label for={name} class:error>
		{label}
	</label>

	<textarea id={name} {name} rows="4" />
</div>

<style>
	.error {
		color: var(--red-600);
	}

	label {
		font-weight: 600;
	}

	textarea {
		box-sizing: border-box;

		width: 100%;

		padding: 12px 20px;
		margin: 8px 0;

		display: block;

		background-color: white;

		border: 1px solid #ccc;
		border-radius: 4px;
	}
</style>
