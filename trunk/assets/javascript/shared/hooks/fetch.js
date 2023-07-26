import { useEffect, useState } from 'react'

export function useFetch (url, options, dependencies) {
	const [data, setData] = useState(undefined)
	const [error, setError] = useState(undefined)
	const [loading, setLoading] = useState(false)

	useEffect(() => {
		let ignore = false

		async function fetchData () {
			setLoading(true)

			const res = await fetch(url, options)
			const json = await res.json()

			if (!ignore) {
				setLoading(false)
				if (json.status === 'error') {
					setError(json.message)
				} else {
					setData(json)
				}
			}
		}

		fetchData()

		return () => {
			ignore = true
		}
	}, dependencies)

	return { data, error, loading }
}
