import { __ } from '@wordpress/i18n'

import Grid from './Grid'
import OptionView from './OptionView'
import { getPanelUrl } from '../../shared/utils/location'

export default function SingleView ({ handleViewChange, initialData }) {
	const { credits_voter, logged, options, results_end, voting_closed } = initialData

	const canSeeResults = voting_closed === 'yes' || results_end === 'no'
	const canVote = voting_closed === 'no'

	return (
		<div className="wrapper">
			<div className="content view-mode">
				<h2 className="options-title-desktop">{__('Voting options', 'concordamos')}</h2>
				<div className="options">
					{Object.entries(options).map(([key, option]) => (
						<OptionView
							key={key}
							id={key}
							name={option.option_name}
							description={option.option_description}
							link={option.option_link}
						/>
					))}
				</div>

				<div className="actions">
					<a href={concordamos.archive_url} className="back-link">{__('Back', 'concordamos')}</a>

					{canVote ? (
						<button type="button primary" onClick={handleViewChange}>{__('Participate of the voting', 'concordamos')}</button>
					) : null}

					{canSeeResults ? (
						<a className="button primary" href={getPanelUrl(window.location.href)}>{__('See detailed results', 'concordamos')}</a>
					) : null}
				</div>
			</div>
			<div className="sidebar view-mode">
				<div className="sidebar-content">
					<h2 className="options-title-mobile">{__('Voting options', 'concordamos')}</h2>
					<Grid squares={Number(credits_voter)} />
					<span>{__('Available credits', 'concordamos')}</span>
					<span>{`${credits_voter} / ${credits_voter}`}</span>
				</div>
			</div>
		</div>
	)
}
