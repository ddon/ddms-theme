<script>
	import microphone from '$lib/microphone.js';
	import IconMic from '$components/icons/Mic.svelte';

	/* ----- */

	export let name = '';
	export let label = '';
	export let error = false;

	export let type = 'text';

	export let requiredMessage = '';
	export let required = false;

	export let disabled = false;

	export let onAudio = () => {};

	/* ----- */

	const audioId = `input-with-mic-${name}`;

	/* ----- */

	const onClickMic = async () => {
		const stream = await microphone.getStream();

		const mediaRecorder = new MediaRecorder(stream);

		mediaRecorder.start();

		const audioChunks = [];

		mediaRecorder.addEventListener('dataavailable', (event) => {
			audioChunks.push(event.data);
		});

		mediaRecorder.addEventListener('stop', () => {
			const audioBlob = new Blob(audioChunks, {
				type: 'audio/wav'
			});

			const audioUrl = URL.createObjectURL(audioBlob);
			const audio = new Audio(audioUrl);

			audio.play();

			onAudio(audioBlob);
		});

		setTimeout(() => {
			mediaRecorder.stop();
		}, 10000);
	};

	// TODO: unmount
</script>

<div>
	<label for={name} class:error>
		{label}
	</label>

	<div class="inputWithControl">
		<input
			{type}
			id={name}
			{name}
			{required}
			{disabled}
			oninput="this.setCustomValidity('')"
			oninvalid="this.setCustomValidity('{requiredMessage}')"
		/>

		<div class="mic" on:click={onClickMic}>
			<IconMic />
		</div>
	</div>
</div>

<div>
	<audio id={audioId} />
</div>

<style>
	.error {
		color: var(--red-600);
	}

	.inputWithControl {
		display: flex;
	}

	.mic {
		width: 30px;

		display: grid;
		justify-content: right;
		align-items: center;

		cursor: pointer;
		outline: none;
	}

	label {
		font-weight: 600;
	}

	input[type='text'],
	input[type='number'] {
		box-sizing: border-box;

		width: 100%;

		padding: 12px 20px;
		margin: 8px 0;

		display: block;

		background-color: white;

		border: 1px solid #ccc;
		border-radius: 4px;
	}

	input:disabled {
		background-color: rgb(231, 231, 231);
	}
</style>
