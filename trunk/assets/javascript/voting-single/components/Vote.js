const Vote = ({ id, count, onVoteChange }) => {
	return (
		<>
			<div className="option-voting-vote">
				<button type="button" className="button-down" onClick={() => onVoteChange(id, -1)}>-</button>
				<input type="text" value={count} name="vote" readOnly />
				<button type="button" className="button-up" onClick={() => onVoteChange(id, 1)}>+</button>
			</div>
		</>
	);
  }

  export default Vote
