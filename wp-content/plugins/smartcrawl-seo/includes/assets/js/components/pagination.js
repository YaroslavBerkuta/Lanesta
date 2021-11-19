import React from "react";
import {__, sprintf} from "@wordpress/i18n";
import range from "lodash-es/range";
import classnames from 'classnames';
import PaginationUtil from "./utils/pagination-util";

export default class Pagination extends React.Component {
	static defaultProps = {
		count: 0,
		perPage: 10,
		currentPage: 1,
		onClick: () => false
	};

	handleClick(e, pageNumber) {
		e.preventDefault();

		this.props.onClick(pageNumber);
	}

	getPageNumbers() {
		const pagesAtATime = 7; // Odd number so we can have equal buffer on both sides
		const pageCount = this.getPageCount();
		const rawPageNumbers = range(1, pageCount + 1);
		if (pageCount <= pagesAtATime) {
			return rawPageNumbers;
		}
		const currentPage = this.props.currentPage;
		const idealBuffer = (pagesAtATime - 1) / 2;
		let leftBuffer, rightBuffer;
		if (currentPage <= idealBuffer) { // Not enough space on the left of current page
			leftBuffer = currentPage - 1;
			rightBuffer = pagesAtATime - leftBuffer - 1;
		} else if ((pageCount - currentPage) <= idealBuffer) { // Not enough space on the right of current page
			rightBuffer = pageCount - currentPage;
			leftBuffer = pagesAtATime - rightBuffer - 1;
		} else { // Enough space on both sides
			leftBuffer = idealBuffer - 1; // -1 here because we want to show one '...' at a time for fixed width
			rightBuffer = idealBuffer;
		}

		let placeholder = -1;
		const pageNumbers = rawPageNumbers.map(pageNumber => {
			if (
				pageNumber < (currentPage - leftBuffer)
				|| pageNumber > (currentPage + rightBuffer)
			) {
				return placeholder;
			} else {
				placeholder--;
				return pageNumber;
			}
		});
		return Array.from(new Set(pageNumbers));
	}

	render() {
		const pageCount = this.getPageCount();
		const pageNumbers = this.getPageNumbers();

		return <div className="sui-pagination-wrap">
			<span className="sui-pagination-results">{sprintf(__('%s results', 'wds'), this.props.count)}</span>
			<ul className="sui-pagination">
				<li>
					<a href="#" role="button"
					   onClick={(e) => this.handleClick(e, 1)}
					   disabled={this.props.currentPage === 1}>
						<span className="sui-icon-arrow-skip-back" aria-hidden="true"/>
						<span className="sui-screen-reader-text">{__('Go to first page', 'wds')}</span>
					</a>
				</li>

				<li>
					<a href="#" role="button"
					   onClick={(e) => this.handleClick(e, this.props.currentPage - 1)}
					   disabled={this.props.currentPage === 1}>
						<span className="sui-icon-chevron-left" aria-hidden="true"/>
						<span className="sui-screen-reader-text">{__('Go to previous page', 'wds')}</span>
					</a>
				</li>

				{pageNumbers.map(
					(pageNumber) => {
						if (pageNumber < 0) {
							return <li key={pageNumber}>
								<a style={{pointerEvents: "none"}}>...</a>
							</li>;
						}

						return <li key={pageNumber}
								   className={classnames({'sui-active': pageNumber === this.props.currentPage})}>
							<a href="#" role="button"
							   onClick={(e) => this.handleClick(e, pageNumber)}>
								{pageNumber}
							</a>
						</li>;
					}
				)}

				<li>
					<a href="#" role="button"
					   onClick={(e) => this.handleClick(e, this.props.currentPage + 1)}
					   disabled={this.props.currentPage === pageCount}>
						<span className="sui-icon-chevron-right" aria-hidden="true"/>
						<span className="sui-screen-reader-text">{__('Go to next page', 'wds')}</span>
					</a>
				</li>

				<li>
					<a href="#" role="button"
					   onClick={(e) => this.handleClick(e, pageCount)}
					   disabled={this.props.currentPage === pageCount}>
						<span className="sui-icon-arrow-skip-forward" aria-hidden="true"/>
						<span className="sui-screen-reader-text">{__('Go to last page', 'wds')}</span>
					</a>
				</li>
			</ul>
		</div>;
	}

	getPageCount() {
		return PaginationUtil.getPageCount(
			this.props.count,
			this.props.perPage
		);
	}
}
