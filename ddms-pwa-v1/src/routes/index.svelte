<script>
	import { goto } from '$app/navigation';

	import settings from '$settings';
	import images from '$core/images';

	import Button from '$components/Button.svelte';

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
		<input type="text" id="slug" name="slug" />

		<Button>Go</Button>
	</form>
</section>

<style>
	section {
		height: 100vh;

		display: flex;
		flex-direction: column;
		justify-content: center;
		align-items: center;

		background-color: #e9e9e9;
	}

	img.logo {
		width: 100%;
		max-width: 100px;
	}

	input[type='text'] {
		padding: 8px;

		border: 2px solid #00000000;
		border-radius: 25px;
		border: 1px solid white;

		transition: 0.5s border;

		box-shadow: inset 0px -2px 4px #939393;
	}

	input[type='text']:hover {
		border-radius: 25px;
		/* border: 1px solid rgb(165, 165, 165); */
		padding: 8px;
	}

	input[type='text']:focus-visible,
	input[type='text']:focus {
		outline: 1px solid var(--blue);
	}
</style>
