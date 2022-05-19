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

	export let type = 'text';

	export let requiredMessage = '';
	export let required = false;

	export let disabled = false;

	let isRecording = false;
	let isLoading = false;
	let recorder = null;

	/* ----- */

	const onRecordedAudio = async (audio) => {
		if (!audio) {
			console.log('[microphone]: no audio recorded');
			return;
		}

		isLoading = true;

		console.log(`[microphone]: send audio to server`);

		const res = await api.postVoiceFile({
			audioFile: audio
		});

		console.log(`[microphone]: server response`, res);

		if (res.ok) {
			value += res.text || '';
		}

		isLoading = false;
	};

	const onClickMic = async () => {
		console.log(`[microphone]: start recording`);

		isRecording = true;

		recorder = new microphone.Recorder(onRecordedAudio);
		await recorder.start();
	};

	const onClickStop = () => {
		console.log(`[microphone]: stop recording`);

		isRecording = false;

		recorder.stop();
		recorder = null;
	};
</script>

<div class="field">
	<label for={name} class:error>
		{label}
	</label>

	<div class="inputWithControl">
		<input
			{type}
			id={name}
			{name}
			{value}
			{required}
			{disabled}
			oninput="this.setCustomValidity('')"
			oninvalid="this.setCustomValidity('{requiredMessage}')"
		/>

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

	.inputWithControl {
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
		display: block;
		margin: 0 0 5px 0;
		font-weight: 600;
	}

	input[type='text'],
	input[type='number'] {
		box-sizing: border-box;
		display: block;

		width: 100%;
		height: 45px;

		padding: 12px 10px;

		background-color: var(--white);

		border: 1px solid var(--gray-300);
		border-radius: 4px;
	}

	input:disabled {
		background-color: var(--gray-200);
	}
</style>
