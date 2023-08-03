import { __ } from '@wordpress/i18n'
import { useState } from 'react'

import Tabs from '../components/Tabs'

export default function Panel () {
	let tabs = {
		'my-vote': __('My vote', 'concordamos'),
		'results': __('Detailed results', 'concordamos'),
	}

	const [tab, setTab] = useState(Object.keys(tabs)[0])

	return (
		<>
			<div className="content panel">
				<h2>{__('User panel', 'concordamos')}</h2>
				<Tabs tab={tab} tabs={tabs} onChange={setTab}/>
			</div>
		</>
	)
}
