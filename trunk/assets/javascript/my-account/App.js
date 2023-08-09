import { __, sprintf } from '@wordpress/i18n'
import { useState } from 'react'

import CreatedVotings from './components/CreatedVotings'
import ParticipatedVotings from './components/ParticipatedVotings'
import Tabs from '../shared/components/Tabs'
import UserSettings from './components/UserSettings'
import { useFetch } from '../shared/hooks/fetch'

const tabs = [
	{ id: 'created', label: __('Votings created by me', 'concordamos') },
	{ id: 'participated', label: __('Votings I participated', 'concordamos') },
]

export function App ({ initialData }) {
	const [tab, setTab] = useState('created')

	const { data: user } = useFetch(new URL('my-account', concordamos.rest_url), {
		method: 'GET',
		headers: {
			'Content-Type': 'application/json',
			'X-WP-Nonce': concordamos.nonce,
		},
	})

	if (!user) {
		return null
	}

	return (
		<div className="my-account">
			<div className="my-account-header">
				<div className="my-account-header__name">{__('My account', 'concordamos')}</div>
				<h1>{sprintf(__('Hello, %s', 'concordamos'), user.name)}</h1>
			</div>
			<UserSettings user={user}/>
			<div className="my-account-panel">
				<div className="content panel">
					<Tabs tabs={tabs} value={tab} onChange={setTab}/>
					{(tab === 'created') ? (
						<CreatedVotings/>
					) : (tab === 'participated') ? (
						<ParticipatedVotings/>
					) : null}
				</div>
			</div>
		</div>
	)
}
