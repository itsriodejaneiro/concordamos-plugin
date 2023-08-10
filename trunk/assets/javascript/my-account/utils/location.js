export function buildUrl (endpoint, filters, page) {
	const url = new URL(endpoint, concordamos.rest_url)
	const params = url.searchParams

	params.set('page', page)
	if (filters.access) {
		params.set('access', filters.access)
	}
	if (filters.time) {
		params.set('time', filters.time)
	}

	return url
}
