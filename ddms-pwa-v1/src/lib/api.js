import settings from '$settings';
import net from '$lib/net';

const WP_ADMIN_URL = `${settings.DOMAIN_URL}/wp-admin/admin-ajax.php`;

const getDockBySlug = async (params = {}) => {
	const res = await net.get(WP_ADMIN_URL, {
		action: 'get_dock_by_slug',
		slug: params.slug
	});

	return res;
};

const getCompanies = async () => {
	return await net.get(WP_ADMIN_URL, {
		action: 'get_all_companies'
	});
};

const addNewJob = async (params = {}) => {
	const url = `${WP_ADMIN_URL}?action=add_new_job`;

	const res = await net.post(url, {
		...params
	});

	return res;
};

const closeJobByPin = async (params = {}) => {
	const url = `${WP_ADMIN_URL}?action=close_job_by_pin`;

	const res = await net.post(url, {
		...params
	});

	return res;
};

const getActiveJobsByDockSlug = async (params = {}) => {
	const res = await net.get(WP_ADMIN_URL, {
		action: 'get_active_jobs_by_dock_slug',
		slug: params.slug
	});

	return res;
};

const getMapAreaById = async (params = {}) => {
	const res = await net.get(WP_ADMIN_URL, {
		action: 'get_area_by_id',
		areaId: params.areaId
	});

	return res;
};

const postVoiceFile = async (params = {}) => {
	const url = `${WP_ADMIN_URL}?action=get_voice_transcription`;

	const res = await net.postForm(url, {
		dock: params.dock,
		audioFile: params.audioFile
	});

	return res;
};

export default {
	getDockBySlug,
	getCompanies,
	addNewJob,
	closeJobByPin,
	getActiveJobsByDockSlug,
	getMapAreaById,
	postVoiceFile
};
