import { __, sprintf } from '@wordpress/i18n'
import { useState } from 'react'

export default function TranslationText ({ label, maxLength, name, onChange, original }) {
	const [currentLength, setCurrentLength] = useState(0)
	const [limitReached, setLimitReached] = useState(false)
	const [lastValue, setLastValue] = useState('')

	function handleChange (e) {
		const newValue = e.target.value
		const newLength = newValue.length

		if (maxLength && newLength <= maxLength) {
			setCurrentLength(newLength)
			setLastValue(newValue)
			onChange(e)
			setLimitReached(newLength === maxLength)
		} else if (newLength > maxLength) {
			e.target.value = lastValue
		} else if (!maxLength) {
			onChange(e)
		}
	}

	const warningClass = limitReached ? 'warning count-warning limit-reached' : 'warning count-warning'

	return (
		<div className="field field-text">
			<label>
				<span>{label}</span>
				<div className="translation-row">
					<input type="text" disabled={true} value={original} aria-label={__('Original text', 'concordamos')}/>
					<input type="text" name={name} aria-label={__('Translated text', 'concordamos')} onChange={handleChange}/>
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
