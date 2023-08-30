import { useState } from "react"
import { __, _x, sprintf } from '@wordpress/i18n'

const Text = ({label, maxLength, name, onChange, placeholder}) => {
	const [currentLength, setCurrentLength] = useState(0)
	const [limitReached, setLimitReached] = useState(false)
	const [lastValue, setLastValue] = useState("")

	const handleChange = (e) => {
		const newValue = e.target.value
		const newLength = e.target.value.length

		if (maxLength && newLength <= maxLength) {
			setCurrentLength(newLength)
			setLastValue(newValue)
			onChange(e)

			if (newLength === maxLength) {
				setLimitReached(true)
			} else {
				setLimitReached(false)
			}
		} else if (newLength > maxLength) {
			e.target.value = lastValue
		} else if (!maxLength) {
			onChange(e)
		}
	}

	const warningClass = limitReached ? "warning count-warning limit-reached" : "warning count-warning"

	return (
		<>
			<div className="field field-text">
				<label>
					<span>{label}</span>
					<input name={name} placeholder={placeholder} type="text" onChange={handleChange} />
				</label>
				{maxLength && currentLength > maxLength * 0.8 && (
					<div className={warningClass}>
						{ limitReached
							? sprintf(__('Você atingiu o limite de %s caracteres', 'concordamos'), maxLength)
							: sprintf(__('Você está próximo do limite de %s caracteres', 'concordamos'), maxLength)
						}
					</div>
				)}
			</div>
		</>
	)
}

export default Text
