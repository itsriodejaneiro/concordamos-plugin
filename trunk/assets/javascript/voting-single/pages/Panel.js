import { __ } from '@wordpress/i18n'
import { useState } from 'react'

import MyVotes from '../components/MyVotes'
import Tabs from '../../shared/components/Tabs'
import VotingResults from '../components/VotingResults'
import VotingLinks from '../components/VotingLinks'

export default function Panel ({ initialData }) {
	const tabs = [
		{ id: 'my-vote', label: __('My vote', 'concordamos') }
	]

	if (concordamos.is_author == true) {
		tabs.unshift({ id: 'links', label: __('Voting links', 'concordamos') })
	} else if (concordamos.a_id !== '') {
		let currentURL = window.location.href;
		let match = currentURL.match(/\/a-([a-fA-F0-9]+)/);

		if (match && match[0] === "/" + concordamos.a_id) {
			tabs.unshift({ id: 'links', label: __('Voting links', 'concordamos') })
		}
	}

	if (initialData.results_end == 'no' || initialData.voting_closed == 'yes') {
		tabs.push({ id: 'results', label: __('Detailed results', 'concordamos') })
	}

	const [tab, setTab] = useState(tabs[0].id)

	const votingUrl = new URL(window.location.href);
	votingUrl.search = "";
	const backVotingUrl = votingUrl.toString();

	return (
		<>
			<div className="content panel">
				<h2 className="sr-only">{__('User panel', 'concordamos')}</h2>
				<Tabs tabs={tabs} value={tab} onChange={setTab}/>
				{(tab === 'links') ? (
					<VotingLinks initialData={initialData}/>
				) : (tab === 'my-vote') ? (
					<MyVotes initialData={initialData}/>
				) : (tab === 'results') ? (
					<VotingResults />
				) : null}

				<div className="actions">
					<a href={backVotingUrl} className="back-link">{__('Back to voting', 'concordamos')}</a>
				</div>
			</div>
		</>
	)
}
