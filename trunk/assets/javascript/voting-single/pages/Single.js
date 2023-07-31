import { useMemo, useState } from 'react'

import SingleView from '../components/SingleView'
import SingleVoting from '../components/SingleVoting'

export default function Single ({ initialData }) {
	const { credits_voter, options } = initialData
	const parsedOptions = JSON.parse(options)

	const[viewMode, setViewMode] = useState(true)
	const handleViewChange = (event) => {
		event.preventDefault()
		setViewMode(!viewMode)
	}

	const initialVotes = Object.keys(parsedOptions).map(key => ({ id: key, count: 0 }))
	const [votes, setVotes] = useState(initialVotes)

	const usedCredits = useMemo(() => {
		return votes.reduce((credits, vote) => credits + (vote.count ** 2), 0)
	}, [votes])

	function handleVoteChange(id, change) {
		setVotes((prevVotes) => {
			return prevVotes.map(vote => {
				if (vote.id === id) {
					if ((usedCredits - (vote.count ** 2) + ((vote.count + change) ** 2)) <= (credits_voter ** 2)) {
						return { ...vote, count: vote.count + change }
					}
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
