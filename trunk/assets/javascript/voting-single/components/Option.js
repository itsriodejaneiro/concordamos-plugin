import Grid from "./Grid"
import Vote from "./Vote"

const Option = ({ count, description, id, link, name, onVoteChange, readonly = false }) => {
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
