<script>
	export let form = '';
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

{#if form}
	<button
		{form}
		on:click={onButtonClick}
		type={buttonType}
		class="button"
		class:buttonRed={isRed}
		class:disabled
	>
		<slot />
	</button>
{:else}
	<button
		on:click={onButtonClick}
		type={buttonType}
		class="button"
		class:buttonRed={isRed}
		class:disabled
	>
		<slot />
	</button>
{/if}

<style>
	.button {
		height: 45px;

		padding: 5px 25px;

		background-color: var(--blue);

		border-radius: 5px;
		border: 1px solid var(--blue);

		color: white;
		font-size: 18px;
		text-decoration: none;

		cursor: pointer;

		transition: 0.5s background, color;
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

	.button:disabled {
		background-color: var(--gray-300);
		color: var(--gray-600);
		border-color: var(--gray-600);
	}
</style>
