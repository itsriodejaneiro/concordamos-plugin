const Vote = ({ id, count, onVoteChange }) => {
	return (
		<>
			<div className="option-voting-vote">
				<button className="button-down" onClick={() => onVoteChange(id, -1)}>-</button>
				<input type="text" value={count} name="vote" readOnly />
				<button className="button-up" onClick={() => onVoteChange(id, 1)}>+</button>
			</div>
		</>
	);
  }

  export default Vote
