import { useState } from "react"

const Number = ({label, name, onChange, placeholder}) => {
	return (
		<>
			<div className="field field-number">
				<label>
					<span>{label}</span>
					<input name={name} placeholder={placeholder} type="number" min="0" onChange={onChange} />
				</label>
			</div>
		</>
	)
}

export default Number
