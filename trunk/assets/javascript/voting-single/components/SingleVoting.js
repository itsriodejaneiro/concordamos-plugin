import { __, _x, sprintf } from '@wordpress/i18n'
import { useState } from 'react'

import Grid from './Grid'
import Modal from '../../shared/components/Modal'
import Option from './Option'

const baseUrl = window.location.origin + '/wp-json/concordamos/v1/vote/'

export default function SingleVoting ({ credits, handleViewChange, handleVoteChange, initialData, votes }) {
	const { credits_voter, date_end, options } = initialData
	const parsedOptions = JSON.parse(options)

	const formattedDateEnd = new Date(Number(date_end))

	const [modalOpen, setModalOpen] = useState(false)

	function closeModal () {
		setModalOpen(false)
	}

	function openModal () {
		setModalOpen(true)
	}

	function confirmVote (event) {
		event.preventDefault()
		openModal()
	}

	function handleSubmit (event) {
		event.preventDefault()

		fetch(baseUrl, {
			headers: {
				'Content-Type': 'application/json',
				'X-WP-Nonce'  : concordamos.nonce
		  },
		  method: 'POST',
			body: JSON.stringify({
				'u_id'  : concordamos.u_id,
				'v_id'  : concordamos.v_id,
				'votes' : votes,
			})
		})
		.then(response => response.json())
		.then(response => {
			if (response.status === 'error') {
				throw new Error(response.message)
			} else {
				setVotes(initialVotes)
			}
		})
		.catch(error => console.error(error))
	}

	return (
		<div className="wrapper">
			<div className="content voting-mode">
				<h2>{__('Distribute your votes', 'concordamos')}</h2>
				<p>{sprintf(__('You can use up to %s credits to vote during this poll', 'concordamos'), credits_voter)}</p>
				<p>{sprintf(__('This poll ends on %s, %s', 'concordamos'), formattedDateEnd.toLocaleDateString('pt-BR'), formattedDateEnd.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit', hour12: true }))}</p>

				<form onSubmit={confirmVote}>
					{Object.keys(parsedOptions).map(key => {
						const voteCount = votes.find(vote => vote.id === key)?.count || 0
						return (
							<Option
								key={key}
								id={key}
								count={voteCount}
								name={parsedOptions[key].option_name}
								description={parsedOptions[key].option_description}
								link={parsedOptions[key].option_link}
								onVoteChange={handleVoteChange}
							/>
						)
					})}

					<div className="actions">
						<span onClick={handleViewChange} className="back-link">{__('Back to poll options', 'concordamos')}</span>
						<button type="submit">{__('Confirm vote', 'concordamos')}</button>
					</div>
				</form>

				<Modal open={modalOpen} onClose={closeModal}>
					<h2>{__('Vote confirmation', 'concordamos')}</h2>
					<p dangerouslySetInnerHTML={ { __html: sprintf(__("After confirming your vote, it can't be changed. You can access your voting option on <a href='%s'>voting infos</a>.", 'concordamos'), '#') } }></p>
					<div class="buttons">
						<button type="button" className="button primary" onClick={handleSubmit}>{_x('Vote', 'verb', 'concordamos')}</button>
						<button type="button" className="button link" onClick={closeModal}>{__('Cancel', 'concordamos')}</button>
					</div>
				</Modal>
			</div>
			<div className="sidebar">
				<Grid squares={Number(credits_voter)} consumed={credits}/>
				<span>{__('Available credits', 'concordamos')}</span>
				<span>{`${credits} / ${credits_voter}`}</span>
			</div>
		</div>
	)
}