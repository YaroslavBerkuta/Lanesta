import Config_Values from "../../es6/config-values";
import $ from 'jQuery';
import RequestUtil from "../utils/request-util";
import ajaxUrl from 'ajaxUrl';

export default class ConfigRequest {
	static sync() {
		return this.post('wds_sync_hub_configs');
	}

	static applyConfig(configId) {
		return this.post('wds_apply_config', {config_id: configId});
	}

	static deleteConfig(configId) {
		return this.post('wds_delete_config', {config_id: configId});
	}

	static updateConfig(configId, configName, configDescription) {
		return this.post('wds_update_config', {
			'config_id': configId,
			'name': configName,
			'description': configDescription,
		});
	}

	static createConfig(configName, configDescription) {
		return this.post('wds_create_new_config', {
			'name': configName,
			'description': configDescription,
		});
	}

	static uploadConfig(file) {
		const form_data = new FormData();

		form_data.append('file', file);
		form_data.append('action', 'wds_upload_config');
		form_data.append('_wds_nonce', Config_Values.get('nonce', 'config'));

		return new Promise((resolve, reject) => {
			$.ajax({
				url: ajaxUrl,
				cache: false,
				contentType: false,
				processData: false,
				type: 'post',
				data: form_data,
			}).done(function (response) {
				if (response.success) {
					resolve(
						(response || {}).data
					);
				} else {
					reject();
				}
			}).fail(function () {
				reject();
			});
		});
	}

	static post(action, data) {
		const nonce = Config_Values.get('nonce', 'config');
		return RequestUtil.post(action, nonce, data);
	}
}
