import { __ } from '@wordpress/i18n'

export function CopyToClipboard ({ text }) {
	function copyToClipboard (e) {
		e.preventDefault()
		window.navigator.clipboard.writeText(text)
	}

	return (
		<button type="button" onClick={copyToClipboard}>
			{__('Copy to clipboard', 'concordamos')}
		</button>
	)
}
