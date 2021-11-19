import React from "react";
import ReactDOM from "react-dom";

const portalComponent = function (WrappedComponent, domNodeId) {
	return class extends React.Component {
		render() {
			const domNode = document.getElementById(domNodeId);
			if (!domNode) {
				return null;
			}

			return ReactDOM.createPortal(
				<WrappedComponent {...this.props}/>,
				domNode
			);
		}
	}
};
export default portalComponent;
