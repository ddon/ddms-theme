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

export default {
	getStream
};
