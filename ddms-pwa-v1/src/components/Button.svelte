<script>
	export let buttonType = 'submit';
	export let disabled = false;
	export let onClick = null;

	export let isRed = false;

	const onButtonClick = (evt) => {
		if (onClick) {
			onClick();
		}
	};
</script>

<button
	on:click={onButtonClick}
	type={buttonType}
	class="button"
	class:buttonRed={isRed}
	class:disabled
>
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
		transition: 0.5s background, color;

		cursor: pointer;
	}

	.button:hover {
		color: var(--blue);
		background-color: white;
		border: 1px solid var(--blue);
	}

	.buttonRed {
		background-color: var(--red);
		border-color: var(--red);
	}

	.buttonRed:hover {
		background-color: var(--red-50);
		border-color: var(--red-50);
	}
</style>
