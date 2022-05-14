<div class="loader">
	<div class="loaderBar" />
	<div class="loaderBar" />
	<div class="loaderBar" />
	<div class="loaderBar" />
	<div class="loaderBar" />
	<div class="loaderBar" />
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
		display: grid;
		grid-auto-flow: column;
		justify-content: center;

		height: 60px;

		margin: 0;
		padding: 10px;

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

	.loaderBar:nth-child(4) {
		animation-delay: -0.9s;
	}

	.loaderBar:nth-child(5) {
		animation-delay: -0.8s;
	}

	.loaderBar:nth-child(6) {
		animation-delay: -0.7s;
	}

	.loaderBar:nth-child(7) {
		animation-delay: -0.6s;
	}

	.loaderBar:nth-child(8) {
		animation-delay: -0.5s;
	}

	.loaderBar:nth-child(9) {
		animation-delay: -0.4s;
	}
</style>
