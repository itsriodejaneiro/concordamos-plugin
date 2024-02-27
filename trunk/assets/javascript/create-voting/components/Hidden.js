const Hidden = ({ name, onChange }) => {
	return (
		<>
			<input type="hidden" name={name} onChange={onChange} />
		</>
	)
}

export default Hidden
