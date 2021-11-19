export default class PaginationUtil {
	static getPage(collectionObject, pageNumber, perPage) {
		const page = {};

		Object.keys(collectionObject)
			.slice((pageNumber - 1) * perPage, pageNumber * perPage)
			.forEach((key) => {
				return page[key] = collectionObject[key];
			});

		return page;
	}

	static getPageCount(totalItemCount, perPage) {
		const pageCount = Math.ceil(totalItemCount / perPage);
		return pageCount < 1
			? 1
			: pageCount;
	}
}