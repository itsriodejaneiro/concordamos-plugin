const Radio = ({defaultValue, label, name, onChange, options, titleCssClass}) => {
	return (
		<>
			<div className="field field-radio inline">
				<label>
					<span className={titleCssClass ? titleCssClass : "label"}>{label}</span>
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
