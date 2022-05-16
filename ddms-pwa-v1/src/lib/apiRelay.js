import settings from '$settings';
import net from '$lib/net';

const RELAY_URL = `${settings.RELAY_DOMAIN_URL}/relay.php`;

const getLedPanelStatus = async () => {
	const res = await net.get(RELAY_URL, {
		cmd: 'info'
	});

	return res;
};

const switchLedPanel = async (cmd = '') => {
	const res = await net.get(RELAY_URL, {
		cmd
	});

	return res;
};

export default {
	getLedPanelStatus,
	switchLedPanel
};
