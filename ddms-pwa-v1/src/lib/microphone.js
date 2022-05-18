const getStream = async () => {
	try {
		const steam = await navigator.mediaDevices.getUserMedia({
			video: false,
			audio: true
		});

		return steam;
	} catch (err) {
		console.error(err);
		return null;
	}
};

const record = async (callback) => {
	const stream = await getStream();

	if (!stream) {
		callback(null);
		return;
	}

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

		// const audioUrl = URL.createObjectURL(audioBlob);
		// const audio = new Audio(audioUrl);
		// audio.play();

		callback(audioBlob);
	});

	setTimeout(() => {
		mediaRecorder.stop();
	}, 7000);
};

class Recorder {
	stopCallback = null;
	mediaRecorder = null;
	audioChunks = [];

	constructor(stopCallback) {
		this.stopCallback = stopCallback;
	}

	async start() {
		const stream = await getStream();

		if (!stream) {
			return;
		}

		this.audioChunks = [];
		this.mediaRecorder = new MediaRecorder(stream);

		this.mediaRecorder.start();

		this.mediaRecorder.addEventListener('dataavailable', (evt) => {
			this.audioChunks.push(evt.data);
		});

		this.mediaRecorder.addEventListener('stop', () => {
			const audioBlob = new Blob(this.audioChunks || [], {
				type: 'audio/wav'
			});

			this.stopCallback(audioBlob);
		});
	}

	stop() {
		if (!this.mediaRecorder) {
			return null;
		}

		this.mediaRecorder.stop();
	}
}

export default {
	getStream,
	record,
	Recorder
};
