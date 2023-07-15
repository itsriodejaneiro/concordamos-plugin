import { useState } from "react"

const Text = ({label, name, onChange, placeholder}) => {
	return (
		<>
			<div className="field field-text">
				<label>
					<span>{label}</span>
					<input name={name} placeholder={placeholder} onChange={onChange} />
				</label>
			</div>
		</>
	)
}

export default Text
