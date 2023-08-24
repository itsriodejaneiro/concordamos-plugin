import { __ } from '@wordpress/i18n'
import { useMemo } from 'react'

import Grid from './Grid'
import Option from './Option'
import { useFetch } from '../../shared/hooks/fetch'

export default function MyVotes ({ initialData }) {
	const { credits_voter, options } = initialData
	const parsedOptions = JSON.parse(options)

	const { data: votes } = useFetch(`my-vote/?v_id=${concordamos.v_id}`, [])

	const usedCredits = useMemo(() => {
		if (!votes) {
			return 0
		}
		return votes.reduce((credits, vote) => credits + (vote.count ** 2), 0)
	}, [votes])

	return votes ? (
		<div className="wrapper">
			<div className="content voting-mode">
				<div className="votings-list">
					{Object.entries(parsedOptions).map(([key, option]) => {
						const voteCount = votes.find((vote) => vote.id === key)?.count ?? 0
						return (
							<Option
								key={key}
								id={key}
								count={voteCount}
								name={option.option_name}
								description={option.option_description}
								link={option.option_link}
								readonly={true}
							/>
						)
					})}
				</div>
			</div>
			<div className="sidebar">
				<div className="sidebar-content">
					<Grid squares={credits_voter} consumed={usedCredits}/>
					<span>{__('Distributed credits', 'concordamos')}</span>
					<span>{`${usedCredits} / ${credits_voter}`}</span>
				</div>
			</div>
		</div>
	) : null
}
