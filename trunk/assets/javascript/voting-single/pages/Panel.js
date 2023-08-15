import { __ } from '@wordpress/i18n'
import { useState } from 'react'

import MyVotes from '../components/MyVotes'
import Tabs from '../../shared/components/Tabs'
import VotingLinks from '../components/VotingLinks'

export default function Panel ({ initialData }) {
	const tabs = [
		{ id: 'my-vote', label: __('My vote', 'concordamos') },
		{ id: 'results', label: __('Detailed results', 'concordamos') },
	]

	if (concordamos.is_author == true) {
		tabs.unshift({ id: 'links', label: __('Voting links', 'concordamos') })
	}

	const [tab, setTab] = useState(tabs[0].id)

	return (
		<>
			<div className="content panel">
				<h2 className="sr-only">{__('User panel', 'concordamos')}</h2>
				<Tabs tabs={tabs} value={tab} onChange={setTab}/>
				{(tab === 'links') ? (
					<VotingLinks initialData={initialData}/>
				) : (tab === 'my-vote') ? (
					<MyVotes initialData={initialData}/>
				) : /* TODO */ null}
			</div>
		</>
	)
}
