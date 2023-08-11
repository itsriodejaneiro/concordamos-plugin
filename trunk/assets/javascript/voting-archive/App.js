import { __, _x } from '@wordpress/i18n'
import { useState } from 'react'
import { DebounceInput } from 'react-debounce-input'
import Paginate from 'react-paginate'

import Filters from '../shared/components/Filters'
import VotingCard from './components/VotingCard'
import { useFetch } from '../shared/hooks/fetch'

const initialQuery = new URLSearchParams(window.location.search).get('search') ?? ''

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

export function App () {
	const [query, setQuery] = useState(initialQuery)
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

	function onFiltersChange (value) {
		setFilters(value)
		setPage(1)
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
						<DebounceInput type="search" debounceTimeout={500} placeholder={__('Search by...', 'concordamos')} value={query} onChange={onQueryChange}/>
						<button type="button">
							<span className="sr-only">{__('Search', 'concordamos')}</span>
							<i className="icon"/>
						</button>
					</form>
					<Filters filters={filters} onChange={onFiltersChange}/>
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
