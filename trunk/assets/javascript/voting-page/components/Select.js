import { __, _x, sprintf } from '@wordpress/i18n'

function Select ({ label, name, onChange, options, value }) {
	return (
		<div className="field field-select">
			<label>
				<span>{label}</span>
				<select name={name} value={value} onChange={onChange}>
				{options.map((option) => (
					<option value={option.value}>{option.label}</option>
				))}
				</select>
			</label>
		</div>
	)
}

export default Select
