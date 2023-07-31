import { useState } from 'react'

import SingleView from '../components/SingleView'
import SingleVoting from '../components/SingleVoting'

export default function Single ({ initialData }) {
	const parsedOptions = JSON.parse(initialData.options)

	const[viewMode, setViewMode] = useState(true)
	const handleViewChange = (event) => {
		event.preventDefault()
		setViewMode(!viewMode)
	}

	const initialVotes = Object.keys(parsedOptions).map(key => ({ id: key, count: 0 }))
	const [votes, setVotes] = useState(initialVotes)

	function handleVoteChange(id, change) {
		setVotes(prevVotes => {
			return prevVotes.map(vote => {
				if (vote.id === id) {
					return { ...vote, count: vote.count + change }
				}
				return vote
			})
		})
	}

	return viewMode ? (
		<SingleView
			handleViewChange={handleViewChange}
			initialData={initialData}
			votes={votes}
		/>
	) : (
		<SingleVoting
			handleViewChange={handleViewChange}
			handleVoteChange={handleVoteChange}
			initialData={initialData}
			votes={votes}
		/>
	)
}
