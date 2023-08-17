import { __ } from '@wordpress/i18n'

import Grid from './Grid'
import OptionView from './OptionView'
import { getPanelUrl } from '../../shared/utils/location'

export default function SingleView ({ handleViewChange, initialData }) {
	const { credits_voter, logged, options, voting_closed } = initialData

	const parsedOptions = JSON.parse(options)

	return (
		<div className="wrapper">
			<div className="content view-mode">
				<h2>{__('Voting options', 'concordamos')}</h2>
				<div className="options">
					{Object.entries(parsedOptions).map(([key, option]) => (
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
					<a href="/voting" className="back-link">{__('Back', 'concordamos')}</a>

					{voting_closed ? (
						logged ? (
							<a className="button primary" href={getPanelUrl(window.location.href)}>{__('See detailed results', 'concordamos')}</a>
						) : null
					) : (
						<button type="button" onClick={handleViewChange}>{__('Participate of the voting', 'concordamos')}</button>
					)}
				</div>
			</div>
			<div className="sidebar view-mode">
				<Grid squares={Number(credits_voter)} />
				<span>{__('Available credits', 'concordamos')}</span>
				<span>{`${credits_voter} / ${credits_voter}`}</span>
			</div>
		</div>
	)
}
