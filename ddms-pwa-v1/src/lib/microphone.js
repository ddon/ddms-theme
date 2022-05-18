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

export default {
	getStream,
	record
};
