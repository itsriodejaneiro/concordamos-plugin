const OptionView = ({ key, name, description, link, id }) => {
	return (
		<>
			<div className="option-voting">
				<div>
					<h3>{name}</h3>

					{ description ? (
						link ? (
							<p><a className="voting-link" href={link} target="_blank" rel="noopener">{description}</a></p>
						) : (
							<p>{description}</p>
						)
					) : (
						link ? (
							<p><a className="voting-link" href={link} target="_blank" rel="noopener">{link}</a></p>
						): (
							null
						)
					) }
				</div>
			</div>
		</>
	);
}

export default OptionView;
