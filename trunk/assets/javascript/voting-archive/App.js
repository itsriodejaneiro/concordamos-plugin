import { useState } from 'react'

import { useFetch } from '../shared/hooks/fetch'

const baseUrl = window.location.origin + '/wp-json/concordamos/v1/votings/'
const userIsAdmin = concordamos.is_admin

function buildUrl (query, filters) {
	const votingStatus = (filters.open) ? (filters.closed ? '' : 'open') : (filters.closed ? 'closed' : '')
	const votingAccess = (filters.withoutLogin) ? (filters.withLogin ? '' : 'yes') : (filters.withLogin ? 'no' : '')
	return `${baseUrl}?query=${query}&type=public&status=${votingStatus}&access=${votingAccess}`
}

export function App() {
	const [query, setQuery] = useState('')
	const [filters, setFilters] = useState({ closed: true, open: true, withLogin: true, withoutLogin: true })

	const { data: results } = useFetch(buildUrl(query, filters), {
		method: 'GET',
		headers: {
			'Content-Type': 'application/json',
		}
	}, [query, filters])

	return (
		<pre>{JSON.stringify(results, null, 2)}</pre>
	)
}
