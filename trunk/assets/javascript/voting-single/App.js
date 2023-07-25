import { useState } from "react";
import Option from "./components/Option"

export function App({ initialData }) {
	const { credits_voter, date_end, options } = initialData;
	const parseOptions = JSON.parse(options)

	const initialVotes = Object.keys(parseOptions).map(key => ({ id: key, count: 0 }));
	const [votes, setVotes] = useState(initialVotes);

	function handleVoteChange(id, change) {
		setVotes(prevVotes => {
			return prevVotes.map(vote => {
				if (vote.id === id) {
					return { ...vote, count: vote.count + change };
				}
				return vote;
			});
		});
	}

	const handleSubmit = (event) => {
		event.preventDefault();
	}

	return (
		<>
			<div className="content">
				<h2>Distribute your votes</h2>
				<p>You can use up to {credits_voter} credits to vote during this poll</p>
				<p>This poll ends on {date_end}</p>

				<form onSubmit={handleSubmit}>
					{Object.keys(parseOptions).map(key => {
						const voteCount = votes.find(vote => vote.id === key)?.count || 0;
						return (
							<Option
								key={key}
								id={key}
								count={voteCount}
								name={parseOptions[key].option_name}
								description={parseOptions[key].option_description}
								link={parseOptions[key].option_link}
								onVoteChange={handleVoteChange}
							/>
						);
					})}

					<button type="submit">Confirmar voto</button>
				</form>
			</div>
		</>
	)
}
