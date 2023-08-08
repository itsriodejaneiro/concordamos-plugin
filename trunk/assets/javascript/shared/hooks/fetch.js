import { useEffect, useState } from 'react'

export function useFetch (url, options) {
	const [data, setData] = useState(undefined)
	const [error, setError] = useState(undefined)
	const [loading, setLoading] = useState(false)

	useEffect(() => {
		const abort = new AbortController()
		options.signal = abort.signal

		async function fetchData () {
			setLoading(true)

			try {
				const response = await fetch(url, options)
				const data = await response.json()

				setLoading(false)
				if (data.status === 'error') {
					setError(new Error(data.message))
				} else {
					setData(data)
				}
			} catch (err) {
				if (!err instanceof AbortController) {
					setLoading(false)
					setError(err)
					console.error(err)
				}
			}
		}

		fetchData()

		return () => {
			abort.abort()
		}
	}, [url.toString()])

	return { data, error, loading }
}
