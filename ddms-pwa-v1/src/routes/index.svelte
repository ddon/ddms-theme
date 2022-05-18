<script>
	import { goto } from '$app/navigation';

	import settings from '$settings';
	import images from '$core/images';

	import Button from '$components/Button.svelte';
	import Input from '$components/forms/Input.svelte';

	const handleOnSubmit = (evt) => {
		const formData = new FormData(evt.target);

		const data = {};

		for (let field of formData) {
			const [key, value] = field;
			data[key] = value;
		}

		if (data.slug) {
			goto(`/docks/${data.slug}`);
		}
	};
</script>

<svelte:head>
	<title>BLRT Tallinn Shipyard DDMS</title>
</svelte:head>

<section>
	<img class="logo" src={images.logos.blrt512} alt="BLRT Logo" />

	<h1>BLRT Tallinn Shipyard DDMS</h1>

	<form on:submit|preventDefault={handleOnSubmit}>
		<div class="formRow">
			<Input name="slug" />

			<Button>Go</Button>
		</div>
	</form>
</section>

<style>
	section {
		height: calc(100vh - 110px);

		padding: 100px 10px 10px 10px;

		display: flex;
		flex-direction: column;
		align-items: center;

		background-color: var(--gray-150);
	}

	.logo {
		width: 100%;
		max-width: 100px;
	}

	form {
		width: 500px;
	}

	.formRow {
		display: grid;
		grid-auto-flow: column;
		grid-column-gap: 15px;
	}
</style>
