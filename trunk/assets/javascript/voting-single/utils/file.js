export function createFile (content, mimeType = 'text/plain') {
	const blob = new Blob([content], { type: mimeType })
	const url = URL.createObjectURL(blob)
	return url
}
