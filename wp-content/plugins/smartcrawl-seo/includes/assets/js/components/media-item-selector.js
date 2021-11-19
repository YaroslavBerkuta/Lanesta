import React from 'react';
import {__} from '@wordpress/i18n';
import wp from 'wp';
import classnames from 'classnames';

export default class MediaItemSelector extends React.Component {
	constructor(props) {
		super(props);

		this.props = props;
		this.state = {mediaItem: false};
	}

	componentDidMount() {
		if (!this.props.value) {
			this.setState({mediaItem: false});
			return;
		}

		const mediaItem = wp.media.attachment(this.props.value);
		if (mediaItem.get && mediaItem.get('url')) {
			this.setState({mediaItem: mediaItem});
		} else {
			mediaItem.fetch().then(() => {
				this.setState({mediaItem: mediaItem});
			});
		}
	}

	removeFile(e) {
		e.preventDefault();

		this.setState({mediaItem: false});
		this.props.onChange('');
	}

	openMediaLibrary(e) {
		e.preventDefault();

		const mediaLibrary = new wp.media({
			multiple: false,
			library: {type: 'image'}
		});
		mediaLibrary.on('select', () => {
			const selection = mediaLibrary.state().get('selection');
			if (!selection.length) {
				return false;
			}

			const mediaItem = selection.shift();
			this.props.onChange(mediaItem.get('id'));
			this.setState({mediaItem: mediaItem});
		});
		mediaLibrary.open();
	}

	render() {
		const mediaItem = this.state.mediaItem;
		const classNames = classnames({
			'sui-upload': true,
			'sui-has_file': mediaItem
		});
		const backgroundUrl = mediaItem ? mediaItem.get('url') : '';
		const fileName = mediaItem ? mediaItem.get('filename') : '';

		return <div className={classNames}>
			<div className="sui-upload-image" aria-hidden="true">
				<div className="sui-image-mask"/>
				<div role="button"
					 className="sui-image-preview"
					 style={{backgroundImage: "url('" + backgroundUrl + "')"}}>
				</div>
			</div>

			<button onClick={e => this.openMediaLibrary(e)} className="sui-upload-button">
				<span className="sui-icon-upload-cloud"
					  aria-hidden="true"/> {__('Upload file', 'wds')}
			</button>

			<div className="sui-upload-file">
				<span>{fileName}</span>

				<button onClick={e => this.removeFile(e)} aria-label={__('Remove file', 'wds')}>
					<span className="sui-icon-close" aria-hidden="true"/>
				</button>
			</div>
		</div>
	}
}
