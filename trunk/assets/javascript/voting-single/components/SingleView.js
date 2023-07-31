import Grid from './Grid'
import OptionView from './OptionView'

export default function SingleView ({ handleViewChange, initialData, votes }) {
	const { credits_voter, options } = initialData

	const parsedOptions = JSON.parse(options)

	return (
		<div className="wrapper">
			<div className="content view-mode">
				<h2>Voting options</h2>
				<div className="options">
					{Object.keys(parsedOptions).map(key => {
						const voteCount = votes.find(vote => vote.id === key)?.count || 0
						return (
							<OptionView
								key={key}
								id={key}
								name={parsedOptions[key].option_name}
								description={parsedOptions[key].option_description}
								link={parsedOptions[key].option_link}
							/>
						)
					})}
				</div>

				<div className="actions">
					<a href="/voting" className="back-link">Back</a>
					<button type="button" onClick={handleViewChange}>Participate of the voting</button>
				</div>
			</div>
			<div className="sidebar">
				<Grid count={credits_voter} />
				<span>Available credits</span>
				<span>{`${credits_voter} / ${credits_voter}`}</span>
			</div>
		</div>
	)
}
