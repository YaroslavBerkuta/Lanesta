import React from 'react';
import classnames from 'classnames';
import {__} from "@wordpress/i18n";
import SUI from 'SUI';
import $ from 'jQuery';

export default class Modal extends React.Component {
	static defaultProps = {
		id: '',
		title: '',
		description: '',
		small: false,
		headerActions: false,
		focusAfterOpen: '',
		focusAfterClose: 'container',
		dialogClasses: [],
		disableCloseButton: false,
		enterDisabled: false,
		onEnter: false,
		onClose: () => false
	};

	constructor(props) {
		super(props);

		this.props = props;
	}

	componentDidMount() {
		SUI.openModal(
			this.props.id,
			this.props.focusAfterClose,
			this.props.focusAfterOpen ? this.props.focusAfterOpen : this.getTitleId(),
			false,
			false
		);
	}

	componentWillUnmount() {
		SUI.closeModal();
	}

	handleKeyDown(event) {
		const isTargetInput = $(event.target).is('.sui-modal.sui-active input');
		if (isTargetInput && event.keyCode === 13) {
			event.preventDefault();
			event.stopPropagation();

			if (!this.props.enterDisabled && this.props.onEnter) {
				this.props.onEnter(event);
			}
		}
	}

	render() {
		const headerActions = this.getHeaderActions();

		const dialogClasses = Object.assign({}, {
			'sui-modal-sm': this.props.small,
			'sui-modal-lg': !this.props.small
		}, this.props.dialogClasses);

		return <div className={classnames('sui-modal', dialogClasses)}
					onKeyDown={e => this.handleKeyDown(e)}>
			<div role="dialog"
				 id={this.props.id}
				 className={classnames('sui-modal-content', this.props.id + '-modal')}
				 aria-modal="true"
				 aria-labelledby={this.props.id + '-modal-title'}
				 aria-describedby={this.props.id + '-modal-description'}>

				<div className="sui-box" role="document">
					<div className={classnames('sui-box-header', {
						'sui-flatten sui-content-center sui-spacing-top--40': this.props.small
					})}>
						<h3 id={this.getTitleId()}
							className={classnames('sui-box-title', {
								'sui-lg': this.props.small
							})}>

							{this.props.title}
						</h3>

						{headerActions}
					</div>

					<div className={classnames('sui-box-body', {
						'sui-content-center': this.props.small
					})}>
						{this.props.description &&
						<p className="sui-description"
						   id={this.props.id + '-modal-description'}>
							{this.props.description}
						</p>}

						{this.props.children}
					</div>

					{this.props.footer && <div className="sui-box-footer">
						{this.props.footer}
					</div>}
				</div>
			</div>
		</div>;
	}

	getTitleId() {
		return this.props.id + '-modal-title';
	}

	getHeaderActions() {
		const closeButton = this.getCloseButton();
		if (this.props.small) {
			return closeButton;
		} else if (this.props.headerActions) {
			return this.props.headerActions;
		} else {
			return <div className="sui-actions-right">{closeButton}</div>
		}
	}

	getCloseButton() {
		return <button id={this.props.id + '-close-button'}
					   type="button"
					   onClick={() => this.props.onClose()}
					   disabled={this.props.disableCloseButton}
					   className={classnames("sui-button-icon", {
						   'sui-button-float--right': this.props.small
					   })}>

			<span className="sui-icon-close sui-md" aria-hidden="true"/>
			<span className="sui-screen-reader-text">
				{__('Close this dialog window', 'wds')}
			</span>
		</button>
	}
}
