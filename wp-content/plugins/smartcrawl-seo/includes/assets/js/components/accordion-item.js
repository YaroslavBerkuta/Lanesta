import React from "react";
import classnames from 'classnames';

export default class AccordionItem extends React.Component {
	static defaultProps = {
		className: '',
	};

	constructor(props) {
		super(props);

		this.state = {
			open: false
		};
	}

	toggle(e) {
		const className = e.target.className || '';
		const tagName = e.target.tagName || '';
		if (
			tagName === 'BUTTON'
			&& !className.includes('sui-accordion-open-indicator')
		) {
			return;
		}

		this.setState({
			open: !this.state.open
		});
	}

	render() {
		return <div className={classnames('sui-accordion-item', this.props.className, {
			'sui-accordion-item--open': this.state.open
		})}>
			<div className="sui-accordion-item-header"
				 onClick={(e) => this.toggle(e)}>
				{this.props.header}
			</div>

			{this.props.children &&
			<div className="sui-accordion-item-body">
				<div className="sui-box">
					<div className="sui-box-body">
						{this.props.children}
					</div>
				</div>
			</div>
			}
		</div>;
	}
}
