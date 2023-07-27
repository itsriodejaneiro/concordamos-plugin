import { useEffect, useState } from 'react'

import Radio from './components/Radio'
import { useFetch } from '../shared/hooks/fetch'

const baseUrl = window.location.origin + '/wp-json/concordamos/v1/votings/'

function buildUrl (query, filters, page) {
	const url = new URL(baseUrl)
	const params = url.searchParams

	params.set('page', page)
	params.set('type', 'public')
	if (query) {
		params.set('query', query)
	}
	if (filters.access) {
		params.set('access', filters.access)
	}
	if (filters.status) {
		params.set('status', filters.status)
	}

	return url
}

const votingAccessOptions = {
	'yes': 'Sim',
	'no': 'Não',
	'': 'Tudo',
}

const votingStatusOptions = {
	'open': 'Abertas',
	'closed': 'Fechadas',
	'': 'Todas',
}

export function App() {
	const [query, setQuery] = useState('')
	const [filters, setFilters] = useState({ access: '', status: '' })
	const [page, setPage] = useState(1)

	useEffect(() => {
		setPage(1)
	}, [query, filters])

	const { data } = useFetch(buildUrl(query, filters, page), {
		method: 'GET',
		headers: {
			'Content-Type': 'application/json',
		}
	}, [query, filters, page])

	function setFilter (key) {
		return function (value) {
			setFilters((filters) => ({ ...filters, [key]: value }))
		}
	}

	return (
		<div className="voting-archive">
			<div class="voting-archive-header">
				<h1>Busque uma votação</h1>
				<form>
					<input type="search" placeholder="Buscar por..." value={query} onChange={setQuery}/>
					<button type="submit">
						<span>Pesquisar</span>
					</button>
				</form>
				<details>
					<summary>Filtros</summary>
					<div class="filter-label">Status da votação</div>
					<Radio name="status" options={votingStatusOptions} value={filters.status} onChange={setFilter('status')}/>
					<div class="filter-label">Requer login?</div>
					<Radio name="access" options={votingAccessOptions} value={filters.access} onChange={setFilter('access')}/>
				</details>
			</div>
			<pre>{JSON.stringify(data, null, 2)}</pre>
		</div>
	)
}
