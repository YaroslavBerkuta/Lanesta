import React from 'react';

class ErrorBoundary extends React.Component {
	constructor(props) {
		super(props);
		this.state = {hasError: false};
	}

	static getDerivedStateFromError() {
		return {hasError: true};
	}

	componentDidCatch(error, errorInfo) {
		console.error(error);
		console.error(errorInfo);
	}

	render() {
		if (this.state.hasError) {
			return <div className="sui-notice sui-notice-error">
				<div className="sui-notice-content">
					<div className="sui-notice-message">
						<span className="sui-notice-icon sui-icon-warning-alert sui-md" aria-hidden="true"/>
						<p><strong>
							Something went wrong. Please contact <a target="_blank"
																	href="https://wpmudev.com/get-support/">support</a>.
						</strong></p>
					</div>
				</div>
			</div>;
		}

		return this.props.children;
	}
}

export default ErrorBoundary;
