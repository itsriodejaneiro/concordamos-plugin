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
				<h2>Distribute your votes</h2>
				<p>You can use up to {credits_voter} credits to vote during this poll</p>
				<p>This poll ends on {formattedDateEnd.toLocaleDateString('pt-BR')}, {formattedDateEnd.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit', hour12: true })}</p>

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
						<span onClick={handleViewChange} className="back-link">Back to poll details</span>
						<button type="submit">Confirmar voto</button>
					</div>
				</form>

				<Modal open={modalOpen} onClose={closeModal}>
					<h2>Confirmação de voto</h2>
					<p>Após confirmar seu voto, ele não poderá ser alterado. Você pode acessar as suas opções de votos nas <a href="#">informações da votação</a>.</p>
					<div class="buttons">
						<button type="button" className="button primary" onClick={handleSubmit}>Votar</button>
						<button type="button" className="button link" onClick={closeModal}>Cancelar</button>
					</div>
				</Modal>
			</div>
			<div className="sidebar">
				<Grid squares={Number(credits_voter)} consumed={credits}/>
				<span>Available credits</span>
				<span>{`${credits} / ${credits_voter}`}</span>
			</div>
		</div>
	)
}
