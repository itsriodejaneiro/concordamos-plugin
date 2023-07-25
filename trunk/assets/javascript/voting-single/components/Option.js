import Grid from "./Grid"
import Vote from "./Vote"

const Option = ({ key, name, description, link, id, count, onVoteChange }) => {
	return (
		<>
			<div className="option-voting">
				<div>
					<h3>{name}</h3>
					<p>{description}</p>
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
