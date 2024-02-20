import { __, _x, sprintf } from '@wordpress/i18n'
import { useMemo, useState } from 'react'

import Grid from './Grid'
import Modal, { useModal } from '../../shared/components/Modal'
import Option from './Option'
import { apiFetch } from '../../shared/hooks/fetch'
import { formatDate, formatTime } from '../../shared/utils/i18n'
import { getPanelUrl, navigateTo } from '../../shared/utils/location'

export default function SingleVoting ({ handleViewChange, initialData }) {
	const { credits_voter, date_end, options } = initialData
	const parsedOptions = JSON.parse(options)

	const initialVotes = Object.keys(parsedOptions).map((key) => ({ id: key, count: 0 }))
	const [votes, setVotes] = useState(initialVotes)
	const [hasVoted, setHasVoted] = useState(false)
	const [errorMessage, setErrorMessage] = useState('')

	const formattedDateEnd = new Date(Number(date_end))

	const [isSubmitting, setIsSubmitting] = useState(false)

	const confirmVoteModal = useModal(false)
	const creditsModal = useModal(false)

	function confirmVote (event) {
		event.preventDefault()
		confirmVoteModal.open()
	}

	const usedCredits = useMemo(() => {
		if (!votes) {
			return 0
		}
		return votes.reduce((credits, vote) => credits + (vote.count ** 2), 0)
	}, [votes])

	function handleVoteChange(id, change) {
		setVotes((prevVotes) => {
			return prevVotes.map(vote => {
				if (vote.id === id) {
					if (concordamos.negative_votes !== "yes" && change < 0) {
						if (vote.count <= 0) {
							return vote
						}
					}

					if ((usedCredits - (vote.count ** 2) + ((vote.count + change) ** 2)) <= Number(credits_voter)) {
						return { ...vote, count: vote.count + change }
					} else {
						creditsModal.open()
					}
				}
				return vote
			})
		})
	}

	function handleSubmit (event) {
		event.preventDefault()

		setIsSubmitting(true)

		apiFetch('POST', 'vote/', {
			'u_id'  : concordamos.u_id,
			'v_id'  : concordamos.v_id,
			'votes' : votes,
		})
		.then(response => {
			if (response.status === 'error') {
				setErrorMessage(response.message)
			}
			setHasVoted(true)
		})
		.catch(response => {
			setErrorMessage(response.message)
			setHasVoted(true)
		})
	}

	function handleFinish(event) {
		event.preventDefault()
		navigateTo(getPanelUrl(window.location.href))
	}

	const handleClose = (modal) => {
		modal.close()
		setIsSubmitting(false)
	}

	return (
		<div className="wrapper">
			<div className="content voting-mode">
				<h2>{__('Distribute your votes', 'concordamos')}</h2>
				<p>{sprintf(__('You can use up to %s credits to vote during this poll', 'concordamos'), credits_voter)}</p>
				<p className="end-date">{sprintf(__('This poll ends on %s, %s', 'concordamos'), formatDate(formattedDateEnd), formatTime(formattedDateEnd))}</p>

				<form onSubmit={confirmVote}>
					{Object.entries(parsedOptions).map(([key, option]) => {
						const voteCount = votes.find((vote) => vote.id === key)?.count ?? 0
						return (
							<Option
								key={key}
								id={key}
								count={voteCount}
								name={option.option_name}
								description={option.option_description}
								link={option.option_link}
								onVoteChange={handleVoteChange}
							/>
						)
					})}

					<div className="actions">
						<span onClick={handleViewChange} className="back-link">{__('Back to poll options', 'concordamos')}</span>
						<button type="submit">{__('Confirm vote', 'concordamos')}</button>
					</div>
				</form>

				<Modal controller={confirmVoteModal} danger={!!errorMessage}>
					{ hasVoted ? (
						errorMessage ? (
							<>
								<h2>{__('An error occurred', 'concordamos')}</h2>
								<p dangerouslySetInnerHTML={{ __html: errorMessage }}/>

								<div class="buttons">
									<button type="button" className="button primary" onClick={handleFinish}>{_x('Finish', 'verb', 'concordamos')}</button>
								</div>
							</>
						) : (
							<>
								<h2>{__('Success voting', 'concordamos')}</h2>
								<p>{__('Your vote has been successfully recorded', 'concordamos')}</p>

								<div class="buttons">
									<button type="button" className="button primary" onClick={handleFinish}>{_x('Finish', 'verb', 'concordamos')}</button>
								</div>
							</>
						)
					) : (
						<>
							<h2>{__('Vote confirmation', 'concordamos')}</h2>
							{ ((credits_voter - usedCredits) !== 0) ? (
								<p className="modal-text">{sprintf(__('You still have %s credits available.', 'concordamos'), `${credits_voter - usedCredits}`)}</p>
							) : null }
							<p className="modal-text" dangerouslySetInnerHTML={ { __html: sprintf(__("After confirming your vote, it can't be changed. You can access your voting option on <a href='%s'>voting infos</a>.", 'concordamos'), getPanelUrl(window.location.href)) } }/>

							<div class="buttons">
								<button type="button" className="button primary" onClick={handleSubmit} disabled={isSubmitting}>
									{isSubmitting ? __('Sending vote...', 'concordamos') : _x('Vote', 'verb', 'concordamos')}
								</button>
								<button type="button" className="button link" onClick={() => handleClose(confirmVoteModal)}>{__('Cancel', 'concordamos')}</button>
							</div>
						</>
					) }
				</Modal>

				<Modal controller={creditsModal}>
					{ ((credits_voter - usedCredits) <= 0) ? (
						<>
							<h2>{__('Vote confirmation', 'concordamos')}</h2>
							<p>{__('All your credits have been used', 'concordamos')}</p>

							<div class="buttons">
								<button type="button" className="button primary" onClick={() => handleClose(creditsModal)}>{__('Back', 'concordamos')}</button>
							</div>
						</>
					) : (
						<>
							<h2>{__('Check your credits', 'concordamos')}</h2>
							<p>{__('You still have credits available, redistribute your votes to use up all the credits or if you\'re satisfied, send your votes', 'concordamos')}</p>

							{ concordamos.faq_url ? (
								<p>{__('If you have any questions, please visit the', 'concordamos')} <a target="_blank" href={concordamos.faq_url}> {__('FAQ', 'concordamos')}</a></p>
							) : null }

							<div class="buttons">
								<button type="button" className="button primary" onClick={() => handleClose(creditsModal)}>{__('Back', 'concordamos')}</button>
							</div>
						</>
					) }
				</Modal>
			</div>
			<div className="sidebar voting-mode">
				<div className="sidebar-content">
					<h2>{__('Distribute your votes', 'concordamos')}</h2>
					<p>{sprintf(__('You can use up to %s credits to vote during this poll', 'concordamos'), credits_voter)}</p>
					<p className="end-date">{sprintf(__('This poll ends on %s, %s', 'concordamos'), formatDate(formattedDateEnd), formatTime(formattedDateEnd))}</p>
					<Grid squares={Number(credits_voter)} consumed={usedCredits}/>
					<span>{__('Available credits', 'concordamos')}</span>
					<span>{`${credits_voter - usedCredits} / ${credits_voter}`}</span>
				</div>
			</div>
		</div>
	)
}
