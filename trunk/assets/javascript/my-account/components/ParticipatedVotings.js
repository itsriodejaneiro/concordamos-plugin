import { __, _x } from '@wordpress/i18n'
import { useState } from 'react'
import Paginate from 'react-paginate'

import Filters from '../../shared/components/Filters'
import VotingCard from '../../voting-archive/components/VotingCard'
import { buildUrl } from '../utils/location'
import { useFetch } from '../../shared/hooks/fetch'

export default function ParticipatedVotings () {
	const [filters, setFilters] = useState({ access: '', time: '' })
	const [page, setPage] = useState(1)

	const { data } = useFetch(buildUrl('participated-votings/', filters, page))

	function onFiltersChange (value) {
		setFilters(value)
		setPage(1)
	}

	function onPageChange (event) {
		setPage(event.selected + 1)
	}

	return (
		<div className="my-account-tab">
			<h2 className="sr-only">{__('Votings I participated', 'concordamos')}</h2>
			<div className='my-account-filter-button'>
				<Filters filters={filters} onChange={onFiltersChange}/>
			</div>
			<div className="my-account-voting-grid">
				{ data?.posts?.length ? (
					data.posts.map((post) => <VotingCard key={post.ID} voting={post} />)
				) : (
					<h3>{__('No voting found.', 'concordamos')}</h3>
				) }
			</div>
			{(data?.num_pages && data.num_pages > 1) ? (
				<Paginate
					breakLabel="..."
					className="my-account-paginator"
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
