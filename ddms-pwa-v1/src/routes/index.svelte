<script>
	import { goto } from '$app/navigation';

	let blrtLogo = '/icons/BLRT-logo-512.png';

	function handleOnSubmit(e) {
		const formData = new FormData(e.target);

		const data = {};
		for (let field of formData) {
			const [key, value] = field;
			data[key] = value;
		}
		// console.log("page slug:");
		// console.log(data.slug);

		if (data.slug) {
			// window.open(`https://ddms.greenoak.ee/${data.slug}`, '_self');
			goto(`/dock-${data.slug}`);
		}
	}
</script>

<section>
	<img class="logo" src={blrtLogo} alt="BLRT Logo" />
	<h1>BLRT Tallinn Shipyard DDMS</h1>

	<form class="content" on:submit|preventDefault={handleOnSubmit}>
		<input type="text" id="slug" name="slug" />

		<button type="submit"> Go </button>
	</form>
</section>

<style>
	section {
		display: flex;
		flex-direction: column;
		justify-content: center;
		align-items: center;

		height: 100vh;

		background-color: #e9e9e9;
	}

	img.logo {
		width: 100%;
		max-width: 100px;
	}

	input[type='text'] {
		border-radius: 25px;
		border: 1px solid white;
		padding: 8px;
		transition: 0.5s border;

		border: 2px solid #00000000;
		box-shadow: inset 0px -2px 4px #939393;
	}

	input[type='text']:hover {
		border-radius: 25px;
		/* border: 1px solid rgb(165, 165, 165); */
		padding: 8px;
	}

	input[type='text']:focus-visible,
	input[type='text']:focus {
		outline: 1px solid var(--blrt-color-blue);
	}
</style>
