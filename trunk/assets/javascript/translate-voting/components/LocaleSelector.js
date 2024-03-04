import { __ } from '@wordpress/i18n'

export default function LocaleSelector ({ label, name, template, value, onChange }) {
	const sourceLocale = concordamos.locales.find((locale) => locale.key === template.locale)
	const availableLocales = concordamos.locales.filter((locale) => locale.key !== template.locale)

	if (!value) {
		/** Mimic the shape of an event */
		onChange({ target: { value: availableLocales[0].key } })
	}

	return (
		<div className="field field-select">
			<label>
				<span>{label}</span>
				<div className="translation-row">
					<input type="text" disabled={true} value={sourceLocale.label} aria-label={__('Source language', 'concordamos')}/>
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
