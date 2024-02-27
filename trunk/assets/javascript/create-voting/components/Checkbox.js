const Checkbox = ({label, name, checked, onChange}) => {
	return (
		<>
			<div className="field field-checkbox">
				<label>
					<input name={name} type="checkbox" onChange={onChange} checked={checked} />
					<span>{label}</span>
				</label>
			</div>
		</>
	)
}

export default Checkbox
