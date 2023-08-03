import { __ } from '@wordpress/i18n'
import { useMemo } from 'react'

import Grid from './Grid'
import Option from './Option'
import { useFetch } from '../../shared/hooks/fetch'

const baseUrl = `${window.location.origin}/wp-json/concordamos/v1/my-vote/?v_id=${concordamos.v_id}`

export default function MyVotes ({ initialData }) {
	const { credits_voter, options } = initialData
	const parsedOptions = JSON.parse(options)

	const { data: votes } = useFetch(baseUrl, {
		method: 'GET',
		headers: {
			'Content-Type': 'application/json',
			'X-WP-Nonce': concordamos.nonce,
		},
	}, [])

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
				<Grid squares={credits_voter}/>
				<span>{__('Distributed credits', 'concordamos')}</span>
				<span>{`${usedCredits} / ${credits_voter}`}</span>
			</div>
		</div>
	) : null
}
