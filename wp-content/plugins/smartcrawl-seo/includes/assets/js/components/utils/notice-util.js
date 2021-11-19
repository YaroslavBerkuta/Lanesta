export default class NoticeUtil {
	static showSuccessNotice(id, message, dismissible = true) {
		return this.showNotice(id, message, 'success', dismissible);
	}

	static showErrorNotice(id, message, dismissible = true) {
		return this.showNotice(id, message, 'error', dismissible);
	}

	static showInfoNotice(id, message, dismissible = true) {
		return this.showNotice(id, message, 'info', dismissible);
	}

	static showWarningNotice(id, message, dismissible = true) {
		return this.showNotice(id, message, 'warning', dismissible);
	}

	static showNotice(id, message, type = 'success', dismissible = true) {
		const icons = {
			error: 'warning-alert',
			info: 'info',
			warning: 'warning-alert',
			success: 'check-tick'
		};

		SUI.closeNotice(id);
		SUI.openNotice(id, '<p>' + message + '</p>', {
			type: type,
			icon: icons[type],
			dismiss: {show: dismissible}
		});
	}
}