import { __, sprintf } from '@wordpress/i18n'

export default function TranslationText ({ label, maxLength, name, placeholder, source, type = 'text', value, onChange }) {
	const currentLength = value.length
	const limitReached = currentLength === maxLength

	function handleChange (e) {
		const newValue = e.target.value
		const newLength = newValue.length

		if (maxLength && newLength <= maxLength) {
			onChange(e)
		} else if (newLength > maxLength) {
			e.target.value = value
		} else if (!maxLength) {
			onChange(e)
		}
	}

	const Input = (type === 'textarea') ? 'textarea' : 'input'
	const warningClass = limitReached ? 'warning count-warning limit-reached' : 'warning count-warning'

	return (
		<div className="field field-text">
			<label>
				<span>{label}</span>
				<div className="translation-row">
					<Input type={type} disabled={true} value={source} aria-label={__('Source text', 'concordamos')}/>
					<Input type={type} name={name} placeholder={placeholder} aria-label={__('Translated text', 'concordamos')} onChange={handleChange}/>
				</div>
			</label>
			{maxLength && currentLength > maxLength * 0.8 && (
				<div className={warningClass}>
					{ limitReached
						? sprintf(__('You have reached the %s character limit', 'concordamos'), maxLength)
						: sprintf(__('You are approaching the %s character limit', 'concordamos'), maxLength)
					}
				</div>
			)}
		</div>
	)
}
