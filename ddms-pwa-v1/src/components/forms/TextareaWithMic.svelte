<script>
	import microphone from '$lib/microphone.js';

	import IconMic from '$components/icons/Mic.svelte';
	import IconStopInRing from '$components/icons/StopInRing.svelte';

	import RequestLoaderSmall from '$components/RequestLoaderSmall.svelte';

	// TODO:
	import api from '$lib/api';

	/* ----- */

	export let name = '';
	export let label = '';
	export let value = '';
	export let error = false;

	let isRecording = false;
	let isLoading = false;
	let recorder = null;

	/* ----- */

	const onRecordedAudio = async (audio) => {
		if (!audio) {
			return;
		}

		isLoading = true;

		const res = await api.postVoiceFile({
			audioFile: audio
		});

		if (res.ok) {
			value += res.text || '';
		}

		isLoading = false;
	};

	const onClickMic = async () => {
		isRecording = true;

		recorder = new microphone.Recorder(onRecordedAudio);
		await recorder.start();
	};

	const onClickStop = () => {
		isRecording = false;

		recorder.stop();
		recorder = null;
	};
</script>

<div class="field">
	<label for={name} class:error>
		{label}
	</label>

	<div class="textareaWithControl">
		<textarea id={name} {name} rows="4">{value}</textarea>

		{#if isLoading}
			<div class="controls">
				<RequestLoaderSmall />
			</div>
		{:else if !isRecording}
			<div class="controls" on:click={onClickMic}>
				<IconMic />
			</div>
		{:else}
			<div class="controls" on:click={onClickStop}>
				<IconStopInRing />
			</div>
		{/if}
	</div>
</div>

<style>
	.field {
		margin: 0 0 25px 0;
	}

	.error {
		color: var(--red-600);
	}

	.textareaWithControl {
		display: flex;
	}

	.controls {
		width: 50px;

		display: grid;
		justify-content: right;
		align-items: center;

		cursor: pointer;
		outline: none;
	}

	label {
		font-weight: 600;
	}

	textarea {
		box-sizing: border-box;

		width: 100%;

		padding: 12px 10px;
		margin: 8px 0;

		background-color: var(--white);

		border: 1px solid var(--gray-300);
		border-radius: 4px;
	}
</style>
