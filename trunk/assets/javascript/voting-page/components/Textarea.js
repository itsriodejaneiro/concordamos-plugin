import { useState } from "react"

const Textarea = ({label, name, onChange, placeholder}) => {
	return (
		<>
			<div className="field field-textarea">
				<label>
					<span>{label}</span>
					<textarea name={name} placeholder={placeholder} onChange={onChange} />
				</label>
			</div>
		</>
	)
}

export default Textarea
