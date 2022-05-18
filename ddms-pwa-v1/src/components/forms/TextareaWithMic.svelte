<script>
	import microphone from '$lib/microphone.js';
	import IconMic from '$components/icons/Mic.svelte';

	// TODO:
	import api from '$lib/api';

	/* ----- */

	export let name = '';
	export let label = '';
	export let value = '';
	export let error = false;

	/* ----- */

	const onClickMic = async () => {
		microphone.record(async (audio) => {
			const res = await api.postVoiceFile({
				audioFile: audio
			});

			if (res.ok) {
				value = res.text || '';
			}
		});
	};
</script>

<div>
	<label for={name} class:error>
		{label}
	</label>

	<div class="textareaWithControl">
		<textarea id={name} {name} rows="4">{value}</textarea>

		<div class="mic" on:click={onClickMic}>
			<IconMic />
		</div>
	</div>
</div>

<style>
	.error {
		color: var(--red-600);
	}

	.textareaWithControl {
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

	textarea {
		box-sizing: border-box;

		width: 100%;

		padding: 12px 20px;
		margin: 8px 0;

		display: block;

		background-color: white;

		border: 1px solid #ccc;
		border-radius: 4px;
	}
</style>
