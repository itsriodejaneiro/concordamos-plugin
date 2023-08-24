import { useState, useEffect } from 'react'

import SingleView from '../components/SingleView'
import SingleVoting from '../components/SingleVoting'

export default function Single ({ initialData }) {
	const[viewMode, setViewMode] = useState(true)
	const handleViewChange = (event) => {
		event.preventDefault()
		setViewMode(!viewMode)
	}

	useEffect(() => {
		window.scrollTo({top: 0, left: 0, behavior: 'smooth'});
	}, [viewMode]);

	return viewMode ? (
		<SingleView
			handleViewChange={handleViewChange}
			initialData={initialData}
		/>
	) : (
		<SingleVoting
			handleViewChange={handleViewChange}
			initialData={initialData}
		/>
	)
}
