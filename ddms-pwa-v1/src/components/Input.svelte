<script>
	export let name = '';
</script>

<input type="text" id={name} {name} />

<style>
	input[type='text'] {
		padding: 8px;

		border-radius: 5px;
		border: 1px solid white;

		transition: 0.5s border;

		box-shadow: inset 0px -2px 4px #939393;
	}

	input[type='text']:hover {
	}

	input[type='text']:focus-visible,
	input[type='text']:focus {
		outline: 1px solid var(--blue);
	}
</style>
