import { __, sprintf } from '@wordpress/i18n'
import { useState } from 'react'

import CreatedVotings from './components/CreatedVotings'
import ParticipatedVotings from './components/ParticipatedVotings'
import Tabs from '../shared/components/Tabs'
import UserSettings from './components/UserSettings'
import { apiFetch, useFetch } from '../shared/hooks/fetch'
import { navigateTo } from '../shared/utils/location'

const tabs = [
	{ id: 'created', label: __('Votings created by me', 'concordamos') },
	{ id: 'participated', label: __('Votings I participated', 'concordamos') },
]

export function App ({ initialData }) {
	const [tab, setTab] = useState('created')

	const { data: user } = useFetch('my-account')

	if (!user) {
		return null
	}

	function handleSignOut (event) {
		apiFetch('POST', 'logout', {
			'user_id': concordamos.user_id,
		})
		.then(() => {
			navigateTo(concordamos.voting_archive_url)
		})
	}

	return (
		<div className="my-account">
			<div className="my-account-header">
				<div className="my-account-header__name">{__('My account', 'concordamos')}</div>

				<div className="my-account-header__wrapper">
					<div>
						<h1>{sprintf(__('Hello, %s', 'concordamos'), user.name)}</h1>
					</div>
					<button type="button" className="button edit" onClick={handleSignOut}>{__('Sign out', 'concordamos')}</button>
				</div>
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
