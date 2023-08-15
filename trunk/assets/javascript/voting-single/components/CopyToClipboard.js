export default function CopyToClipboard ({ className = '', children, text }) {
	function copyToClipboard (e) {
		e.preventDefault()
		window.navigator.clipboard.writeText(text)
	}

	return (
		<button type="button" className={className} onClick={copyToClipboard}>{children}</button>
	)
}
