import { __, _x } from '@wordpress/i18n'
import { useState } from 'react'
import { DebounceInput } from 'react-debounce-input'
import Paginate from 'react-paginate'

import Radio from './components/Radio'
import VotingCard from './components/VotingCard'
import { useFetch } from '../shared/hooks/fetch'

function buildUrl (query, filters, page) {
	const url = new URL('votings/', concordamos.rest_url)
	const params = url.searchParams

	params.set('page', page)
	params.set('type', 'public')
	if (query) {
		params.set('query', query)
	}
	if (filters.access) {
		params.set('access', filters.access)
	}
	if (filters.time) {
		params.set('time', filters.time)
	}

	return url
}

const votingAccessOptions = {
	'yes': __('Yes', 'concordamos'),
	'no': __('No', 'concordamos'),
	'': _x('All', 'accesses', 'concordamos'),
}

const votingTimeOptions = {
	'past': _x('Concluded', 'votings', 'concordamos'),
	'present': _x('Open', 'votings', 'concordamos'),
	'future': _x('Future', 'votings', 'concordamos'),
	'': _x('All', 'votings', 'concordamos'),
}

export function App() {
	const [query, setQuery] = useState('')
	const [filters, setFilters] = useState({ access: '', time: '' })
	const [page, setPage] = useState(1)

	const { data } = useFetch(buildUrl(query, filters, page), {
		method: 'GET',
		headers: {
			'Content-Type': 'application/json',
		}
	})

	function onQueryChange (event) {
		setQuery(event.target.value)
		setPage(1)
	}

	function onFilterChange (key) {
		return function (value) {
			setFilters((filters) => ({ ...filters, [key]: value }))
			setPage(1)
		}
	}

	function onPageChange (event) {
		setPage(event.selected + 1)
	}

	return (
		<div className="voting-archive">
			<div class="voting-archive-header">
				<h1>{__('Search for a voting', 'concordamos')}</h1>
				<div class="voting-archive-filters">
					<form className="voting-search-form">
						<DebounceInput type="search" debounceTimeout={500} placeholder="Buscar por..." value={query} onChange={onQueryChange}/>
						<button type="submit">
							<span className="sr-only">{__('Search', 'concordamos')}</span>
							<i className="icon"/>
						</button>
					</form>
					<details>
						<summary>{__('Filters', 'concordamos')}</summary>
						<div class="filter-label">{__('Voting status', 'concordamos')}</div>
						<Radio name="time" options={votingTimeOptions} value={filters.time} onChange={onFilterChange('time')}/>
						<div class="filter-label">{__('Does it require login?', 'concordamos')}</div>
						<Radio name="access" options={votingAccessOptions} value={filters.access} onChange={onFilterChange('access')}/>
					</details>
				</div>
			</div>
			<div className="voting-archive-grid">
			{(data?.posts ?? []).map((post) => (
				<VotingCard key={post.ID} voting={post}/>
			))}
			</div>
			{(data?.num_pages && data.num_pages > 1) ? (
				<Paginate
					breakLabel="..."
					className="voting-archive-paginator"
					forcePage={page - 1}
					nextLabel={_x('Next', 'page', 'concordamos')}
					pageCount={data?.num_pages ?? 0}
					previousLabel={_x('Previous', 'page', 'concordamos')}
					renderOnZeroPageCount={null}
					onPageChange={onPageChange}
				/>
			) : null}
		</div>
	)
}
