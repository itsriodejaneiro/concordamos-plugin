export function getPanelUrl (url) {
	if (url.includes('?')) {
		return `${url}&panel=1`
	} else {
		return `${url}?panel=1`
	}
}

export function navigateTo (url, replace = false) {
	window.location[replace ? 'replace' : 'assign'](url)
}
