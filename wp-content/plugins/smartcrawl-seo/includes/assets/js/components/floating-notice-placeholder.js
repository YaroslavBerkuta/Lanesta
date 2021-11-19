import React from "react";

export default class FloatingNoticePlaceholder extends React.Component {
	static defaultProps = {
		id: ''
	};

	render() {
		return <div className="sui-floating-notices">
			<div role="alert"
				 id={this.props.id}
				 className="sui-notice"
				 aria-live="assertive">
			</div>
		</div>;
	}
}
