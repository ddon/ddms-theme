import settings from '$settings';
import net from '$lib/net';

const WP_ADMIN_URL = `${settings.DOMAIN_URL}/wp-admin/admin-ajax.php`;

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

const getDockBySlug = async (params = {}) => {
	const res = await net.get(WP_ADMIN_URL, {
		action: 'get_dock_by_slug',
		slug: params.slug
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

export default {
	addNewJob,
	getDockBySlug,
	closeJobByPin,
	getActiveJobsByDockSlug
};
