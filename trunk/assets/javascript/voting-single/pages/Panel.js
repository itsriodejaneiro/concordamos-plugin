import { __ } from '@wordpress/i18n'
import { useState } from 'react'

import MyVotes from '../components/MyVotes'
import Tabs from '../components/Tabs'

export default function Panel ({ initialData }) {
	let tabs = [
		{ id: 'my-vote', label: __('My vote', 'concordamos') },
		{ id: 'results', label: __('Detailed results', 'concordamos') },
	]

	const [tab, setTab] = useState(tabs[0].id)

	return (
		<>
			<div className="content panel">
				<h2 className="sr-only">{__('User panel', 'concordamos')}</h2>
				<Tabs tabs={tabs} value={tab} onChange={setTab}/>
				{(tab === 'my-vote') ? (
					<MyVotes initialData={initialData}/>
				) : /* TODO */ null}
			</div>
		</>
	)
}
