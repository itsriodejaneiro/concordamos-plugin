import { __ } from '@wordpress/i18n'
import { useState } from 'react'

import VotingCard from '../voting-archive/components/VotingCard'
import { useFetch } from '../shared/hooks/fetch'
import { navigateTo } from '../shared/utils/location'

export function App () {
	const [query, setQuery] = useState('')

	const { data } = useFetch(new URL('votings/?type=public&per_page=3', concordamos.rest_url), {
		method: 'GET',
		headers: {
			'Content-Type': 'application/json',
		},
	})

	function onInputChange (event) {
		setQuery(event.target.value)
	}

	function handleSubmit (event) {
		event.preventDefault()
		navigateTo(`/voting/?search=${query}`)
	}

	return (
		<div className="votings-block">
			<form className="voting-search-form" onSubmit={handleSubmit}>
				<input type="search" placeholder={__('Search by...', 'concordamos')} aria-label={__('Search votings', 'concordamos')} value={query} onChange={onInputChange}/>
				<button type="submit">
					<span className="sr-only">{__('Search', 'concordamos')}</span>
					<i className="icon"/>
				</button>
			</form>
			<div className="votings-block__cards">
			{(data?.posts ?? []).map((post) => (
				<VotingCard key={post.ID} voting={post}/>
			))}
			</div>
		</div>
	)
}
