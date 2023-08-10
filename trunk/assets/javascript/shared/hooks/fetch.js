import { useEffect, useState } from 'react'

export function useFetch (url, options) {
	const [state, setState] = useState({ data: undefined, error: undefined, loading: false })

	useEffect(() => {
		const abort = new AbortController()
		options.signal = abort.signal

		async function fetchData () {
			setState((state) => ({ ...state, loading: true }))

			try {
				const response = await fetch(url, options)
				const data = await response.json()

				if (data.status === 'error') {
					setState({ data: undefined, error: new Error(data.message), loading: false })
				} else {
					setState({ data, error: undefined, loading: false })
				}
			} catch (error) {
				if (!error instanceof AbortController) {
					setState({ data: undefined, error, loading: false })
					console.error(error)
				}
			}
		}

		fetchData()

		return () => {
			abort.abort()
		}
	}, [url.toString()])

	return state
}
