import Grid from "./Grid"
import Vote from "./Vote"

const Option = ({ count, description, id, link, name, onVoteChange, readonly = false }) => {
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
						? <a className="voting-link" href={link} target="_blank" rel="noopener">{link}</a>
						: null
					}
				</div>
				<div>
					<Grid squares={count ** 2} />
				</div>
				<div>
					{readonly ? (
						<div className="option-voting__count">{count}</div>
					) : (
						<Vote id={id} count={count} onVoteChange={onVoteChange} />
					)}
				</div>
			</div>
		</>
	);
}

export default Option;
