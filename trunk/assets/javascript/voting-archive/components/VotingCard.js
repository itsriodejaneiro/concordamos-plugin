import { __, _x, sprintf } from '@wordpress/i18n'
import classNames from 'classnames'

import Image from './Image'
import { getPanelUrl } from '../../shared/utils/location'

const dateFormatter = new Intl.DateTimeFormat('pt-br', { dateStyle: 'short' })

function formatDate (timestamp) {
	return dateFormatter.format(timestamp)
}

function getVotingStatus (voting) {
	switch (voting.time) {
		case 'past':
			return _x('Concluded', 'voting', 'concordamos')
		case 'present':
			return _x('Open', 'voting', 'concordamos')
		case 'future':
			return _x('Scheduled', 'voting', 'concordamos')
	}
}

function getVotingDate (voting) {
	switch (voting.time) {
		case 'past':
			return sprintf(_x('Since %s', 'date', 'concordamos'), formatDate(voting.meta.date_end))
		case 'present':
			return sprintf(_x('Until %s', 'date', 'concordamos'), formatDate(voting.meta.date_end))
		case 'future':
			return sprintf(_x('From %s', 'date', 'concordamos'), formatDate(voting.meta.date_start))
	}
}

export default function VotingCard ({ panel = false, voting }) {
	const isOpen = voting.time === 'present'
	const requiresLogin = voting.meta.voting_access === 'yes'
	return (
		<a href={panel ? getPanelUrl(voting.permalink) : voting.permalink} className={classNames('voting-card', voting.time)}>
			<article >
				<header>
					<span>{getVotingStatus(voting)}</span>
					<span>{getVotingDate(voting)}</span>
				</header>
				<main>
					<h2>{voting.title}</h2>
					<p>{voting.content}</p>
					{ panel ? null : (
						<p className="voting-card__staus"><Image src={requiresLogin ? 'private.svg' : 'public.svg'}/> {requiresLogin ? (isOpen ? __('Login required', 'concordamos') : __('Login required to see results', 'concordamos')) : __('No login', 'concordamos')}</p>
					)}
					<ul className="voting-card__tags">
					{voting.tags.map((tag) => (
						<li key={tag.ID}>#{tag.name}</li>
					))}
					</ul>
				</main>
			</article>
		</a>
	)
}
