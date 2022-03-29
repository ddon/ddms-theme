import net from './net';


const DOMAIN_URL = 'https://ddms.greenoak.ee';
const WP_ADMIN_URL = `${DOMAIN_URL}/wp-admin/admin-ajax.php`;


const addNewJob = async (params = {}) => {
    const url = `${WP_ADMIN_URL}?action=add_new_job`;

    const res = await net.post(url, {
        ...params,
    });

    return res;
};

const closeJobByPin = async (params = {}) => {
    const url = `${WP_ADMIN_URL}?action=close_job_by_pin`;

    const res = await net.post(url, {
        ...params,
    });

    return res;
};

const getDockBySlug = async (params = {}) => {
    const res = await net.get(WP_ADMIN_URL, {
        action: 'get_dock_by_slug',
        slug: params.slug,
    });

    return res;
};

const getActiveJobsByDockSlug = async (params = {}) => {
    const res = await net.get(WP_ADMIN_URL, {
        action: 'get_active_jobs_by_dock_slug',
        slug: params.slug,
    });

    return res;
};

const getLedPanelStatus = async () => {
    const res = await net.get(`http://localhost:8080/relay.php?cmd=info`);

    return res;
};

const switchLedPanel = async (cmd = '') => {
    const res = await net.get(`http://localhost:8080/relay.php?cmd=${cmd}`);

    return res;
};

export default {
    addNewJob,
    getDockBySlug,
    closeJobByPin,
    getActiveJobsByDockSlug,
    getLedPanelStatus,
    switchLedPanel
};