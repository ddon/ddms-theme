<script>
	import { goto } from '$app/navigation';

	import settings from '$settings';
	import images from '$core/images';

	import Button from '$components/Button.svelte';
	import Input from '$components/Input.svelte';

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

	<form class="content" on:submit|preventDefault={handleOnSubmit}>
		<Input name="slug" />

		<Button>Go</Button>
	</form>
</section>

<style>
	section {
		height: 100vh;

		padding: 100px 10px 10px 10px;

		display: flex;
		flex-direction: column;
		align-items: center;

		background-color: var(--gray-150);
	}

	img.logo {
		width: 100%;
		max-width: 100px;
	}
</style>
