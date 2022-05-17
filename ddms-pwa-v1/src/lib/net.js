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

const postForm = async (url, params = {}) => {
	try {
		const formData = new FormData();

		Object.keys(params).forEach((paramName) => {
			formData.append(paramName, params[paramName]);
		});

		const response = await axios.post(url, formData);
		return response.data;
	} catch (err) {
		console.error(err);
		return {};
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
	postForm,
	postJson
};
