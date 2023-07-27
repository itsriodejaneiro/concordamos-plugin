export default function Radio ({ name, onChange, options, value: currentValue }) {
	function innerOnChange (event) {
		onChange(event.target.value)
	}

	return (
		<div className="radio-options">
			{Object.entries(options).map(([value, label]) => (
				<label key={value}>
					<input
						type="radio"
						name={name}
						value={value}
						checked={value === currentValue}
						onChange={innerOnChange}
					/>
					<span>{label}</span>
				</label>
			))}
		</div>
	)
}
