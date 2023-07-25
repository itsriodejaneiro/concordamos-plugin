import { useState } from "react";
import Option from "./components/Option"
import OptionView from "./components/OptionView"

export function App({ initialData }) {

	const[viewMode, setViewMode] = useState(true)
	const handleViewChange = (e) => {
		e.preventDefault()
		setViewMode(!viewMode)
	}

	const { credits_voter, date_end, options } = initialData;
	const parseOptions = JSON.parse(options)

	const initialVotes = Object.keys(parseOptions).map(key => ({ id: key, count: 0 }));
	const [votes, setVotes] = useState(initialVotes);

	const formatedDateEnd = new Date(Number(date_end));

	const baseUrl = window.location.origin + '/wp-json/concordamos/v1/vote/'

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

		fetch(baseUrl, {
			headers: {
				'Content-Type': 'application/json',
				'X-WP-Nonce'  : concordamos.nonce
		  },
		  method: 'POST',
			body: JSON.stringify({
				"u_id"  : concordamos.u_id,
				"v_id"  : concordamos.v_id,
				"votes" : votes
			})
		})
		.then(response => response.json())
		.then(response => {
			if (response.status === 'error') {
				throw new Error(response.message);
			} else {
				// console.log(response);
				setVotes(initialVotes)
			}
		})
		.catch(error => console.error('Error:', error));
	}

	return (
		<>
			{ viewMode
				? <div className="content view-mode">
					<h2>Voting options</h2>
					<div className="options">
						{Object.keys(parseOptions).map(key => {
							const voteCount = votes.find(vote => vote.id === key)?.count || 0;
							return (
								<OptionView
									key={key}
									id={key}
									name={parseOptions[key].option_name}
									description={parseOptions[key].option_description}
									link={parseOptions[key].option_link}
								/>
							);
						})}
					</div>

					<div className="actions">
						<a href="/voting" className="back-link">Back</a>
						<button type="button" onClick={(e) => handleViewChange(e)}>Participate of the voting</button>
					</div>
				</div>

				: <div className="content voting-mode">
					<h2>Distribute your votes</h2>
					<p>You can use up to {credits_voter} credits to vote during this poll</p>
					<p>This poll ends on {formatedDateEnd.toLocaleDateString('pt-BR')}</p>

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

						<div className="actions">
							<span onClick={handleViewChange} className="back-link">Back to poll details</span>
							<button type="submit">Confirmar voto</button>
						</div>
					</form>
				</div>
			}
		</>
	)
}
