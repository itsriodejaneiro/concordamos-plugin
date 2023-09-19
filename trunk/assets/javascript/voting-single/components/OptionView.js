const OptionView = ({ key, name, description, link, id }) => {
	return (
		<>
			<div className="option-voting">
				<div>
					<h3>{name}</h3>

					{ description ? (
						<p>{description}</p>
					) : null }

					{ link ? (
						<p><a className="voting-link" href={link} target="_blank" rel="noopener">{link}</a></p>
					) : null }
				</div>
			</div>
		</>
	);
}

export default OptionView;
