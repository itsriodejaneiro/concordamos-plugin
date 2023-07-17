const Radio = ({defaultValue, label, name, onChange, options}) => {
	return (
		<>
			<div className="field field-radio">
				<label>
					<span>{label}</span>
					<div className="radio-options">
						{Object.entries(options).map(([value, label]) => (
							<div key={value}>
								<label>
									<input
										name={name}
										type="radio"
										value={value}
										checked={value === defaultValue}
										onChange={onChange}
									/>
									{label}
								</label>
							</div>
						))}
					</div>
				</label>
			</div>
		</>
	)
}

export default Radio
