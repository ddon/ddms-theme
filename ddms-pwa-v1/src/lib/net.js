import axios from 'axios';
import qs from 'qs';

const get = async (url, params) => {
	try {
		const res = await axios.get(url, {
			params
		});

		return res.data;
	} catch (err) {
		console.error(err);
		return null;
	}
};

const post = async (url, params = {}, headers = {}) => {
	try {
		const res = await axios.post(url, qs.stringify(params), {
			headers: headers
		});

		return res.data;
	} catch (err) {
		console.error(err);
		return null;
	}
};

const postJson = async (url, json = {}, headers = {}) => {
	try {
		const res = await axios.post(url, json, {
			headers: headers
		});

		return res.data;
	} catch (err) {
		console.error(err);
		return null;
	}
};

export default {
	get,
	post,
	postJson
};
