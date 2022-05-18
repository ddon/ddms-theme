<div class="loader">
	<div class="loaderBar" />
	<div class="loaderBar" />
	<div class="loaderBar" />
</div>

<style>
	@keyframes loaderBarKeyframes {
		0%,
		40%,
		100% {
			transform: scaleY(0.4);
		}

		20% {
			transform: scaleY(1);
		}
	}

	.loader {
		height: 40px;
		width: 40px;

		margin: 0;
		padding: 0;

		display: grid;
		grid-auto-flow: column;
		justify-content: center;

		cursor: progress;
	}

	.loaderBar {
		height: 100%;
		width: 6px;

		margin: 2px;
		padding: 0;

		background-color: var(--gray-600);

		animation: loaderBarKeyframes 1.2s infinite ease-in-out;
	}

	.loaderBar:nth-child(2) {
		animation-delay: -1.1s;
	}

	.loaderBar:nth-child(3) {
		animation-delay: -1s;
	}
</style>
