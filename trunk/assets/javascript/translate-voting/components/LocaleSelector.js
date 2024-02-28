import { __ } from '@wordpress/i18n'

export default function Select ({ label, name, value, onChange }) {
	const originalLocale = concordamos.locales.find((locale) => locale.key === concordamos.locale)
	const availableLocales = concordamos.locales.filter((locale) => locale.key !== concordamos.locale)

	return (
		<div className="field field-select">
			<label>
				<span>{label}</span>
				<div className="translation-row">
					<input type="text" disabled={true} value={originalLocale.label} aria-label={__('Original language', 'concordamos')}/>
					<select name={name} value={value} onChange={onChange} aria-label={__('New language', 'concordamos')}>
					{availableLocales.map((option) => (
						<option value={option.key}>{option.label}</option>
					))}
					</select>
				</div>
			</label>
		</div>
	)
}
