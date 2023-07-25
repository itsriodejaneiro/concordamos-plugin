import Grid from "./Grid"
import Vote from "./Vote"

const Option = ({ key, name, description, link, id, count, onVoteChange }) => {
	return (
		<>
			<div className="option-voting">
				<div>
					<h3>{name}</h3>

					{ description
						? <p>{description}</p>
						: null
					}

					{ link
						? <a className="voting-link" href="{link}" target="_blank" rel="noopener">{link}</a>
						: null
					}
				</div>
				<div>
					<Grid count={count} />
				</div>
				<div>
					<Vote id={id} count={count} onVoteChange={onVoteChange} />
				</div>
			</div>
		</>
	);
}

export default Option;
