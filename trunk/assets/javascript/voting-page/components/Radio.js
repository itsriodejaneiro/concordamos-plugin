import { useState } from "react"

const Radio = ({defaultValue, label, name, options}) => {
	const [selectedOption, setSelectedOption] = useState(defaultValue);

	const handleChange = (event) => {
		setSelectedOption(event.target.value);
	};

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
										checked={selectedOption === value}
										onChange={handleChange}
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
