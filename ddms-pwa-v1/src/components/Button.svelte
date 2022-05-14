<script>
	export let buttonType = 'submit';
	export let disabled = false;
</script>

<button type={buttonType} class="button" class:disabled>
	<slot />
</button>

<style>
	.button {
		padding: 5px 25px;
		margin: 10px 0 20px 0;

		display: inline-block;

		color: white;
		font-size: 18px;

		background-color: var(--blue);
		text-decoration: none;
		border-radius: 25px;
		border: 1px solid var(--blue);
		/* box-shadow: 2px 4px 10px #a9a9a9; */
		transition: 0.5s background, color;

		cursor: pointer;
	}

	.button:hover {
		color: var(--blue);
		background-color: white;
		border: 1px solid var(--blue);
	}
</style>
