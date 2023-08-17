import { useEffect, useState } from 'react'

export function apiFetch (method, url, body, signal) {
	return fetch(new URL(url, concordamos.rest_url), {
		method,
		headers: {
			'Content-Type': 'application/json',
			'X-WP-Nonce': concordamos.nonce,
		},
		body: body ? JSON.stringify(body) : undefined,
		signal,
	})
	.then((response) => response.json())
	.catch((error) => console.error(error))
}

export function useFetch (url, defaultValue = undefined) {
	const [state, setState] = useState({ data: defaultValue, error: undefined, loading: false })

	useEffect(() => {
		const abort = new AbortController()

		async function fetchData () {
			setState((state) => ({ ...state, loading: true }))

			try {
				const data = await apiFetch('GET', url, undefined, abort.signal)

				if (data.status === 'error') {
					setState({ data: defaultValue, error: new Error(data.message), loading: false })
				} else {
					setState({ data, error: undefined, loading: false })
				}
			} catch (error) {
				if (error.name !== 'AbortError') {
					setState({ data: defaultValue, error, loading: false })
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
